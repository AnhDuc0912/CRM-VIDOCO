<?php

namespace Modules\Project\Http\Controllers;

use App\Helpers\FileHelper;
use Modules\Core\Http\Controllers\Controller;
use Modules\Project\Services\ProjectService;
use Modules\Core\Enums\PermissionEnum;
use Modules\Project\Http\Requests\StoreProjectRequest;
use Modules\Project\Http\Requests\RenewProjectRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Category\Models\Category;
use Modules\Category\Services\CategoryService;
use Modules\Core\Enums\TemplateCodeEnum;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProjectCreatedMail;
use Modules\User\Models\User;
use Illuminate\Support\Facades\Auth;
use Modules\Employee\Models\Employee;
use Modules\Employee\Services\EmployeeService;
use Modules\Log\Helpers\Logger;
use Modules\Project\Models\Project;
use Modules\Project\Models\ProjectFile;
use Modules\ProjectCategory\Models\ProjectCategory;
use Modules\Work\Models\Work;

class ProjectController extends Controller
{

    protected $categoryService;
    protected $employeeService;

    public function __construct(
        private ProjectService $projectService,
        EmployeeService  $employeeService,
        CategoryService $categoryService,
    ) {
        $this->categoryService = $categoryService;
        $this->employeeService = $employeeService;
    }

    /**
     * Display active project
     */
    public function index(Request $request)
    {
        can(PermissionEnum::PROJECT_VIEW);
        set_breadcrumbs([
            [
                'title' => 'Danh sách dự án',
                'url' => null,
            ],
        ]);

        $userId =  Auth::user()->employee_id;

        $allProjects = Project::where(function ($q) use ($userId) {
            $q->where('manager_id', $userId)
                ->orWhereJsonContains('follow_id', "$userId")
                ->orWhereJsonContains('assignees', "$userId");
        })->select('status')->get()->groupBy('status')->map->count();

        $query = Project::where(function ($q) use ($userId) {
            $q->where('manager_id', $userId)
                ->orWhereJsonContains('follow_id', "$userId")
                ->orWhereJsonContains('assignees', "$userId");
        });


        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $projects = $query->get();

        return view('project::index', compact('projects', 'allProjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        can(PermissionEnum::PROJECT_CREATE);
        set_breadcrumbs([
            [
                'title' => 'Thêm mới dự án',
                'url' => null,
            ],
        ]);

        $customers = $this->projectService->getCustomersForCreate();
        $users = Employee::whereHas('user', function ($query) {
            $query->where('status', 'active');
        })->get();
        $groups = $this->categoryService->getCategories();

        return view('project::create', compact('users', 'customers', 'groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        can(PermissionEnum::PROJECT_CREATE);

        $request->validate([
            'project_code'        => 'required|string|max:100|unique:projects,project_code',
            'project_name'        => 'required|string|max:255',
            'start_date'          => 'nullable|date',
            'end_date'            => 'nullable|date|after_or_equal:start_date',
        ]);

        $project = Project::create([
            'project_code'       => $request['project_code'],
            'project_name'       => $request['project_name'],
            'group'              => $request['group'] ?? null,
            'start_date'         => $request['start_date'] ?? null,
            'end_date'           => $request['end_date'] ?? null,
            'customer_id'        => $request['customer_id'] ?? null,
            'manager_id'         => $request['manager_id'] ?? null,
            'assignees'          => json_encode($request['assignees']) ?? null,
            'follow_id'          => json_encode($request['follow_id']) ?? null,
            'description'        => $request['description'] ?? null,
            'budget_min'         => str_replace(',', '', $request['budget_min']) ?? null,
            'budget_max'         => str_replace(',', '', $request['budget_max']) ?? null,
            'zalo_group'         => $request['zalo_group'] ?? null,
            'auto_email'         => $request['auto_email'] ?? 1,
            'progress_calculate' => $request['progress_calculate'] ?? null,
            'level'              => $request['level'] ?? null,
            'status'             => $request['status'] ?? null,
        ]);

        if (!empty($request['files'])) {
            foreach ($request['files'] as $file) {
                $path = 'project/' . str_replace('/', '-',  $project->project_code);
                $file = FileHelper::uploadFile($file, $path);
                ProjectFile::create([
                    'project_id' => $project->id,
                    'user_id' => Auth::user()->employee_id,
                    'file_path' => $file['path'],
                    'extension' => $file['extension'],
                ]);
                Logger::record('project', 'update_status', $project, 'Thêm một File tài liệu');
            }
        }

        if ($request->has('categories')) {
            foreach ($request->categories as $cat) {
                ProjectCategory::create([
                    'project_id' => $project->id,
                    'order' => $cat['order'],
                    'name' => $cat['name'],
                    'progress_calculate' => $cat['progress_calculate'],
                    'created_at' => now(),
                ]);
            }
        }

        Logger::record('project', 'update_status', $project, 'Tạo dự án');


        $emails = [];

        if (!empty($request['assignees'])) {
            $assignees = Employee::whereIn('id', $request['assignees'])->pluck('email_work')->toArray();
            $emails = array_merge($emails, $assignees);
        }

        if (!empty($request['manager_id'])) {
            $managerEmail = Employee::where('id', $request['manager_id'])->value('email_work');
            if ($managerEmail) $emails[] = $managerEmail;
        }

        if (!empty($request['customer_id'])) {
            $customerEmail = Employee::where('id', $request['customer_id'])->value('email_work');
            if ($customerEmail) $emails[] = $customerEmail;
        }

        $emails = array_unique($emails);

        foreach ($emails as $email) {
            Mail::to($email)->send(new ProjectCreatedMail($project));
        }


        return redirect()->route('project.index')
            ->with('success', 'Dự án đã được tạo thành công');
    }

    public function edit($id)
    {
        can(PermissionEnum::PROJECT_UPDATE);
        $user = Auth::user()->employee_id;
        set_breadcrumbs([
            [
                'title' => 'Chỉnh sửa dự án',
                'url'   => null,
            ],
        ]);
        $project = Project::findorFail($id);

        if ($user !== $project->manager_id && $user !== $project->created_by && $user !== 1) {
            return abort(403);
        }

        $customers = $this->projectService->getCustomersForCreate();
        $users     = Employee::whereHas('user', function ($query) {
            $query->where('status', 'active');
        })->get();
        $groups    = $this->categoryService->getCategories();
        return view('project::edit', compact('project', 'users', 'customers', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        can(PermissionEnum::PROJECT_UPDATE);
        $request->validate([
            'project_name'        => 'required|string|max:255',
            'start_date'          => 'nullable|date',
            'end_date'            => 'nullable|date|after_or_equal:start_date',
        ]);
        $project = Project::findorFail($id);
        $project->update([
            'project_name'       => $request['project_name'],
            'group'              => $request['group'] ?? null,
            'start_date'         => $request['start_date'] ?? null,
            'end_date'           => $request['end_date'] ?? null,
            'customer_id'        => $request['customer_id'] ?? null,
            'manager_id'         => $request['manager_id'] ?? null,
            'assignees'          => json_encode($request['assignees']) ?? null,
            'follow_id'          => json_encode($request['follow_id']) ?? null,
            'description'        => $request['description'] ?? null,
            'budget_min'         => str_replace(',', '', $request['budget_min']) ?? null,
            'budget_max'         => str_replace(',', '', $request['budget_max']) ?? null,
            'zalo_group'         => $request['zalo_group'] ?? null,
            'auto_email'         => $request['auto_email'] ?? 1,
            'progress_calculate' => $request['progress_calculate'] ?? null,
            'level'              => $request['level'] ?? null,
            'status'             => $request['status'] ?? null,
        ]);

        if ($request->status >= 3) {
            $project->progress = 100;
            $project->save();
        }

        if ($request->status == 6) {
            if (!$project->complete_date) {
                $project->complete_date = now();
            }
        }

        if ($request->has('delete_files')) {
            foreach ($request->delete_files as $fileId) {
                $file = ProjectFile::find($fileId);
                if ($file) {
                    Storage::delete($file->file_path);
                    $file->delete();
                }
            }
        }

        if (!empty($request['files'])) {
            foreach ($request['files'] as $file) {
                $path = 'project/' . str_replace('/', '-',  $project->project_code);
                $file = FileHelper::uploadFile($file, $path);
                ProjectFile::create([
                    'project_id' => $project->id,
                    'user_id' => Auth::user()->employee_id,
                    'file_path' => $file['path'],
                    'extension' => $file['extension'],
                ]);
                Logger::record('project', 'update_status', $project, 'Thêm một File tài liệu');
            }
        }

        $project->categories()->delete();
        if ($request->has('categories')) {
            foreach ($request->categories as $category) {
                $project->categories()->create([
                    'order' => $category['order'],
                    'name' => $category['name'],
                    'manager_id' => $category['manager_id'],
                    'progress_calculate' => $category['progress_calculate'],
                ]);
            }
        }

        Logger::record('project', 'update_status', $project, 'chỉnh sửa dự án');

        return redirect()->route('project.index')
            ->with('success', 'Dự án đã được cập nhật thành công');
    }



    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        can(PermissionEnum::PROJECT_SHOW);

        set_breadcrumbs([
            [
                'title' => 'Chi tiết dự án',
                'url'   => null,
            ],
        ]);
        $project = Project::findorFail($id);
        $customers = $this->projectService->getCustomersForCreate();
        $users     = Employee::whereHas('user', function ($query) {
            $query->where('status', 'active');
        })->get();
        $groups    = $this->categoryService->getCategories();
        $works = Work::with('children')->whereNull('parent_id')->where('project_id', $id)->get();

        return view('project::show', compact('project', 'users', 'customers', 'groups', 'works'));
    }

    public function updateStatus(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        $project->status = $request->status;
        if ($request->status >= 3) {
            $project->progress = 100;
        }

        if ($request->status == 6) {
            if (!$project->complete_date) {
                $project->complete_date = now();
            }
        }
        $project->save();


        return response()->json(['success' => true, 'message' => 'Cập nhật trạng thái thành công!']);
    }

    public function addMember(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $existingMembers = json_decode($project->assignees, true) ?? [];
        if (!in_array($request->employee_id, $existingMembers)) {
            $existingMembers[] = $request->employee_id;
            $project->assignees = json_encode($existingMembers);
            $project->save();
        }

        return response()->json(['success' => true, 'message' => 'Thêm thành viên thành công!']);
    }

    public function updateProgress(Request $request)
    {
        $project = Project::findOrFail($request->id);
        $project->progress = $request->progress;
        if ($request->progress == 1) {
            $project->status = 1;
        } else if ($request->progress == 100) {
            $project->status = 3;
        } else {
            $project->status = 2;
        }

        $project->save();

        return response()->json(['success' => true]);
    }
}
