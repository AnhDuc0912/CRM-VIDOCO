<?php

namespace Modules\Work\Http\Controllers;

use App\Helpers\FileHelper;
use Modules\Core\Http\Controllers\Controller;
use Modules\Work\Services\WorkService;
use Modules\Core\Enums\PermissionEnum;
use Modules\Work\Http\Requests\StoreWorkRequest;
use Modules\Work\Http\Requests\RenewWorkRequest;
use Illuminate\Support\Facades\Log;
use App\Mail\WorkCreatedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\Category\Models\Category;
use Modules\Category\Services\CategoryService;
use Modules\Core\Enums\TemplateCodeEnum;
use Modules\Employee\Models\Employee;
use Modules\Employee\Services\EmployeeService;
use Modules\Log\Helpers\Logger;
use Modules\Project\Models\Project;
use Modules\Project\Models\ProjectFile;
use Modules\ProjectCategory\Models\ProjectCategory;
use Modules\Work\Models\Work;
use Modules\Work\Models\WorkFile;
use Modules\Work\Models\WorkReport;

class WorkController extends Controller
{

    protected $categoryService;
    protected $employeeService;

    public function __construct(
        private WorkService $workService,
        EmployeeService  $employeeService,
        CategoryService $categoryService,
    ) {
        $this->categoryService = $categoryService;
        $this->employeeService = $employeeService;
    }

    /**
     * Display active work
     */
    public function index(Request $request)
    {
        can(PermissionEnum::WORK_VIEW);
        set_breadcrumbs([
            [
                'title' => 'Danh sách công việc',
                'url' => null,
            ],
        ]);

        $query = Work::whereNull('parent_id')->with(['children', 'project']);

        $userId = Auth::user()->employee_id;

        $dateRange = request('date_range');

        if ($request->has('category_id')) {
            $query->where('group_id', $request->category_id);
        }

        if ($dateRange == 'today') {
            $query->whereDate('start_date', today());
        } elseif ($dateRange == 'this_week') {
            $query->whereBetween('start_date', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($dateRange == 'this_month') {
            $query->whereMonth('start_date', now()->month)
                ->whereYear('start_date', now()->year);
        } elseif ($dateRange == 'last_month') {
            $query->whereMonth('start_date', now()->subMonth()->month)
                ->whereYear('start_date', now()->subMonth()->year);
        }


        if ($request->filter == 'my') {
            $query->whereJsonContains('to_user', "$userId");
        } elseif ($request->filter == 'assign') {
            $query->where('from_user', "$userId");
        } elseif ($request->filter == 'follow') {
            $query->whereJsonContains('follow_id', "$userId");
        }

        $allWorks = (clone $query)->select('status')->get()->groupBy('status')->map->count();

        if ($request->has('status')) {
            $query->where('status', $request->status);
            if ($request->status >= 3) {
                $query->where('progress', 100);
            }
        }


        $works = $query->orderBy('id', 'desc')->paginate(10);

        return view('work::index', compact('works', 'allWorks'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        can(PermissionEnum::WORK_CREATE);
        set_breadcrumbs([
            [
                'title' => 'Thêm mới Công Việc',
                'url' => null,
            ],
        ]);

        $customers = $this->workService->getCustomersForCreate();
        $users = Employee::whereHas('user', function ($query) {
            $query->where('status', 'active');
        })->get();
        $projects = $this->workService->getProjectsForCreate();
        $selectedProject = null;
        $groups = null;
        if ($request->has('project_id')) {
            $selectedProject = Project::find($request->project_id);
            $groups = ProjectCategory::where('project_id', $request->project_id)->select('id', 'name')->get();
        }

        return view('work::create', compact('users', 'customers', 'groups', 'projects', 'selectedProject'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        can(PermissionEnum::WORK_CREATE);
        $request->validate([
            'work_name'   => 'required|string|max:255',
            'project_id'  => 'nullable|integer|exists:projects,id',
            'group_id'    => 'nullable|integer|exists:project_categories,id',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        $progress = 1;
        if ($request->status >= 3) {
            $progress = 100;
        }

        $work = Work::create([
            'work_name'  => $request->work_name,
            'project_id' => $request->project_id ?? null,
            'group_id'   => $request->group_id ?? null,
            'start_date' => $request->start_date ?? null,
            'end_date'   => $request->end_date ?? null,
            'from_user'  => Auth::id(),
            'to_user'    => $request->to_user_id ? json_encode($request->to_user_id) : null,
            'priority'   => $request->priority ?? 1,
            'progress'   => $progress,
            'follow_id'  => $request->follow_id ? json_encode($request->follow_id) : null,
            'status'     => $request->status ?? 1,
            'description' => $request->description ?? null,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);
        if ($request->project_id) {
            $project = $work->project;
            if ($project->progress_calculate != 1) {
                $avgProgress = $project->works()->avg('progress');
                $project->progress = round($avgProgress, 2);
                $project->save();
                Logger::record('project', 'update_status', $project, 'Tạo một công việc');
            }
        }


        if ($request->has('children')) {
            foreach ($request->children as $child) {
                Work::create([
                    'work_name'  => $child['work_name'],
                    'project_id' => $work->project_id,
                    'group_id'   => $work->group_id ?? null,
                    'start_date' => $child['start_date'] ?? null,
                    'end_date'   => $child['end_date'] ?? null,
                    'from_user'  => Auth::id(),
                    'to_user'    => isset($child['to_user_id']) ? json_encode($child['to_user_id']) : null,
                    'follow_id'          => isset($child['follow_id']) ? json_encode($child['follow_id']) : null,
                    'priority'   => $child['priority'] ?? 1,
                    'progress'   => $child['progress'] ?? 0,
                    'status'     => $child['status'] ?? 1,
                    'description' => $child['description'] ?? null,
                    'parent_id'  => $work->id,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            }
        }

        $toUsers = json_decode($work->to_user ?? '[]', true);



        return redirect()->route('work.index')
            ->with('success', 'Công việc đã được tạo thành công');
    }


    public function edit($id)
    {
        can(PermissionEnum::WORK_UPDATE);

        set_breadcrumbs([
            [
                'title' => 'Chỉnh sửa Công Việc',
                'url'   => null,
            ],
        ]);
        $work = Work::findorFail($id);
        $customers = $this->workService->getCustomersForCreate();
        $users = Employee::whereHas('user', function ($query) {
            $query->where('status', 'active');
        })->get();;
        $groups = ProjectCategory::where('project_id', $work->project_id)->select('id', 'name')->get();
        $projects = $this->workService->getProjectsForCreate();

        return view('work::edit', compact('work', 'users', 'customers', 'groups', 'projects'));
    }

    public function indexReport($id)
    {
        can(PermissionEnum::WORK_UPDATE);

        set_breadcrumbs([
            [
                'title' => 'Danh sách Báo Cáo Công Việc',
                'url'   => null,
            ],
        ]);

        $work = Work::with(['reports.user.employee', 'reports.files'])->findOrFail($id);

        $customers = $this->workService->getCustomersForCreate();
        $users = Employee::all();
        $groups = ProjectCategory::where('project_id', $work->project_id)->select('id', 'name')->get();
        $projects = $this->workService->getProjectsForCreate();

        $reports = $work->reports()->latest()->get();

        return view('work::report.index', compact('work', 'users', 'customers', 'groups', 'projects', 'reports'));
    }

    public function projectReports($project_id)
    {
        can(PermissionEnum::WORK_UPDATE);

        set_breadcrumbs([
            ['title' => 'Danh sách Báo Cáo Dự Án', 'url' => null],
        ]);

        $works = Work::with(['reports.user.employee', 'reports.files'])
            ->where('project_id', $project_id)
            ->get();

        $reports = $works->pluck('reports')->flatten()->sortByDesc('report_date');

        $project = Project::findOrFail($project_id);

        return view('project::report.index', compact('project', 'reports'));
    }


    public function createReport($id)
    {
        can(PermissionEnum::WORK_UPDATE);

        $work = Work::findOrFail($id);
        $projects = $this->workService->getProjectsForCreate();

        return view('work::report.create', compact('work', 'projects'));
    }



    public function storeReport(Request $request, $id)
    {
        can(PermissionEnum::WORK_UPDATE);

        $work = Work::findOrFail($id);

        $report = new WorkReport();
        $report->work_id = $work->id;
        $report->report_date = $request->report_date
            ? \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->report_date)
            : now();
        $report->content = $request->content ?? null;
        $report->user_id = Auth::user()->employee_id;
        $report->to_user_id = $work->project->manager_id;
        $report->save();

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('reports', 'public');
                $report->files()->create([
                    'file_path' => $path,
                    'extension' => $file->getClientOriginalExtension(),
                ]);
            }
        }

        return redirect()->route('work.report.index', $work->id)
            ->with('success', 'Thêm báo cáo công việc thành công.');
    }

    public function showReport($work_id, $report_id)
    {
        $work = Work::findOrFail($work_id);
        $report = $work->reports()->with('files', 'user.employee')->findOrFail($report_id);
        $projects = $this->workService->getProjectsForCreate();
        return view('work::report.show', compact('work', 'report', 'projects'));
    }

    public function editReport($work_id, $report_id)
    {
        $work = Work::findOrFail($work_id);
        $report = $work->reports()->with('files', 'user.employee')->findOrFail($report_id);
        $projects = $this->workService->getProjectsForCreate();
        return view('work::report.edit', compact('work', 'report', 'projects'));
    }

    public function updateReport(Request $request, $id)
    {
        can(PermissionEnum::WORK_UPDATE);

        $report = WorkReport::findOrFail($id);
        $report->content = $request->content ?? $report->content;
        $report->save();

        if ($request->has('delete_files')) {
            foreach ($request->delete_files as $fileId) {
                $file = $report->files()->find($fileId);
                if ($file) {
                    if (Storage::disk('public')->exists($file->file_path)) {
                        Storage::disk('public')->delete($file->file_path);
                    }
                    $file->delete();
                }
            }
        }

        if ($request->has('receiver_status')) {
            $report->receiver_status = $request->receiver_status;
            $report->save();
        }


        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('reports', 'public');
                $report->files()->create([
                    'file_path' => $path,
                    'extension' => $file->getClientOriginalExtension(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Cập nhật báo cáo công việc thành công.');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        can(PermissionEnum::WORK_UPDATE);

        $work = Work::findOrFail($id);

        $completeDate = $work->complete_date;
        $progress = $work->progress ?? 0;

        if ($request->status >= 3) {
            $progress = 100;
            $project = $work->project;
            if ($project->progress_calculate != 1) {
                $avgProgress = $project->works()->avg('progress');
                $project->progress = round($avgProgress, 2);
                $project->save();
            }
        }

        if ($request->status == 6) {
            if (!$completeDate) {
                $completeDate = now();
            }
        }

        $work->update([
            'work_name'      => $request->work_name,
            'project_id'     => $request->project_id,
            'group_id'       => $request->group_id ?? null,
            'start_date'     => $request->start_date ?? null,
            'end_date'       => $request->end_date ?? null,
            'from_user'      => Auth::id(),
            'to_user'        => $request->to_user_id ? json_encode($request->to_user_id) : null,
            'priority'       => $request->priority ?? 1,
            'progress'       => $progress,
            'follow_id'      => $request->follow_id ? json_encode($request->follow_id) : null,
            'status'         => $request->status ?? 1,
            'complete_date'  => $completeDate,
            'description'    => $request->description ?? null,
            'updated_by'     => Auth::id(),
        ]);

        $childIds = collect($request->children ?? [])->pluck('id')->filter()->toArray();

        $work->children()->whereNotIn('id', $childIds)->delete();

        if ($request->has('children')) {
            foreach ($request->children as $child) {
                if (!empty($child['id'])) {
                    $c = Work::find($child['id']);
                    if ($c) {
                        $completeDateC = $c->complete_date;
                        $progressC = $c->progress ?? 0;

                        if ($request->status >= 3) {
                            $progressC = 100;
                        }

                        if ($request->status == 6) {
                            if (!$completeDateC) {
                                $completeDateC = now();
                            }
                        }

                        $c->update([
                            'work_name'   => $child['work_name'],
                            'project_id'  => $work->project_id,
                            'group_id'    => $work->group_id ?? null,
                            'start_date'  => $child['start_date'] ?? null,
                            'end_date'    => $child['end_date'] ?? null,
                            'from_user'   => Auth::id(),
                            'to_user'     => isset($child['to_user_id']) ? json_encode($child['to_user_id']) : null,
                            'follow_id'   => isset($child['follow_id']) ? json_encode($child['follow_id']) : null,
                            'progress'       => $progressC,
                            'priority'    => $child['priority'] ?? 1,
                            'progress'    => $child['progress'] ?? 0,
                            'status'      => $child['status'] ?? 1,
                            'complete_date'  => $completeDateC,
                            'description' => $child['description'] ?? null,
                            'updated_by'  => Auth::id(),
                        ]);
                    }
                } else {
                    Work::create([
                        'work_name'   => $child['work_name'],
                        'project_id'  => $work->project_id,
                        'group_id'    => $work->group_id ?? null,
                        'start_date'  => $child['start_date'] ?? null,
                        'end_date'    => $child['end_date'] ?? null,
                        'from_user'   => Auth::id(),
                        'to_user'     => isset($child['to_user_id']) ? json_encode($child['to_user_id']) : null,
                        'follow_id'   => isset($child['follow_id']) ? json_encode($child['follow_id']) : null,
                        'priority'    => $child['priority'] ?? 1,
                        'progress'    => $child['progress'] ?? 0,
                        'status'      => $child['status'] ?? 1,
                        'description' => $child['description'] ?? null,
                        'parent_id'   => $work->id,
                        'created_by'  => Auth::id(),
                        'updated_by'  => Auth::id(),
                    ]);
                }
            }
        }

        return redirect()->route('work.index')
            ->with('success', 'Công việc đã được cập nhật thành công');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        can(PermissionEnum::WORK_SHOW);

        set_breadcrumbs([
            [
                'title' => 'Chi tiết Công Việc',
                'url'   => null,
            ],
        ]);
        $work = Work::findorFail($id);
        $customers = $this->workService->getCustomersForCreate();
        $users = Employee::whereHas('user', function ($query) {
            $query->where('status', 'active');
        })->get();
        $groups = ProjectCategory::where('project_id', $work->project_id)->select('id', 'name')->get();
        $projects = $this->workService->getProjectsForCreate();

        return view('work::show', compact('work', 'users', 'customers', 'groups', 'projects'));
    }

    public function updateProgress(Request $request)
    {
        $work = Work::findOrFail($request->id);

        if ($request->progress == 1) {
            $work->status = 1;
        } else if ($request->progress == 100) {
            $work->status = 3;
        } else {
            $work->status = 2;
        }
        $work->progress = $request->progress;
        $work->save();
        if ($work->project) {
            $project = $work->project;
            if ($project->progress_calculate != 1) {
                $avgProgress = $project->works()->avg('progress');
                $project->progress = round($avgProgress, 2);
                $project->save();
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function getGroups($project_id)
    {
        $groups = ProjectCategory::where('project_id', $project_id)
            ->select('id', 'name')
            ->get();

        return response()->json($groups);
    }

    public function followReport(Request $request)
    {
        can(PermissionEnum::WORK_UPDATE);

        set_breadcrumbs([
            ['title' => 'Theo dõi báo cáo', 'url' => null],
        ]);

        $userId = Auth::user()->employee_id;

        $reportsToMe = WorkReport::where('to_user_id', $userId)->latest()->get();


        $reportsFromMe = WorkReport::where('user_id', $userId)->latest()->get();

        $statusFilter = $request->status;

        if ($statusFilter) {
            $reportsToMe = $reportsToMe->where('receiver_status', $statusFilter);
            $reportsFromMe = $reportsFromMe->where('receiver_status', $statusFilter);
        }

        return view('work::report.follow', compact('reportsToMe', 'reportsFromMe'));
    }
}
