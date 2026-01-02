<?php

namespace Modules\Employee\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Enums\PermissionEnum;
use Modules\Department\Models\Department;
use Modules\Department\Services\DepartmentService;
use Modules\Employee\Http\Requests\SetupPasswordRequest;
use Modules\Employee\Http\Requests\StoreEmployeeRequest;
use Modules\Employee\Http\Requests\UpdateEmployeeRequest;
use Modules\Employee\Http\Requests\UpdatePasswordPersonalRequest;
use Modules\Employee\Models\Employee;
use Modules\Employee\Services\EmployeeService;
use Modules\Level\Models\Level;
use Modules\Position\Models\Position;

class EmployeeController extends Controller
{
    protected EmployeeService $employeeService;
    protected DepartmentService $departmentService;

    public function __construct(EmployeeService $employeeService, DepartmentService $departmentService)
    {
        $this->employeeService = $employeeService;
        $this->departmentService = $departmentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        can(PermissionEnum::EMPLOYEE_VIEW);
        set_breadcrumbs([
            ['title' => 'Quản lý nhân sự', 'url' => null]
        ]);

        $user = Auth::user();

        $employees = $this->employeeService->getAllEmployees();

        return view('employee::index', compact('employees'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function info($id)
    {
        can(PermissionEnum::EMPLOYEE_SHOW);

        $employee = $this->employeeService->getEmployeeById($id);

        set_breadcrumbs([
            ['title' => 'Quản lý nhân sự', 'url' => route('employees.index')],
            ['title' => 'Hồ sơ nhân sự', 'url' => null]
        ]);

        return view('employee::info', compact('employee'));
    }

    /**
     * Update password for the specified resource.
     *
     * @param UpdatePasswordPersonalRequest $request
     * @param int $userId
     *
     * @return View
     */
    public function updatePassword(UpdatePasswordPersonalRequest $request, $userId)
    {
        $data = $request->validated();
        $updated = $this->employeeService->updatePassword($userId, $data);

        if ($updated) {
            return redirect()->route('employees.info', $userId)->with('success', 'Mật khẩu đã được cập nhật thành công');
        }

        return redirect()->route('employees.info', $userId)->with('error', 'Mật khẩu đã được cập nhật thành công');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        can(PermissionEnum::EMPLOYEE_UPDATE);

        set_breadcrumbs([
            ['title' => 'Quản lý nhân sự', 'url' => route('employees.index')],
            ['title' => 'Cập nhật thông tin nhân sự', 'url' => null]
        ]);

        $code = $this->employeeService->getNextEmployeeCode();
        $employee = $this->employeeService->getEmployeeById($id);
        $departments = Department::get();
        $managers = $this->employeeService->getAllManagers();
        $levels = Level::all();
        $positions = Position::all();
        return view('employee::edit', compact('employee', 'code', 'departments', 'managers', 'levels', 'positions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        can(PermissionEnum::EMPLOYEE_CREATE);

        set_breadcrumbs([
            ['title' => 'Quản lý nhân sự', 'url' => route('employees.index')],
            ['title' => 'Thêm nhân sự', 'url' => null]
        ]);

        $code = $this->employeeService->getNextEmployeeCode();
        $departments = Department::get();
        $managers = $this->employeeService->getAllManagers();
        $levels = Level::all();
        $positions = Position::all();
        return view('employee::create', compact('code', 'departments', 'managers', 'levels', 'positions'));
    }

    public function search(Request $request)
    {
        return Employee::select('id', 'full_name', 'username')
            ->where('full_name', 'like', "%{$request->q}%")
            ->get();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param StoreEmployeeRequest $request
     *
     * @return View
     */
    public function store(StoreEmployeeRequest $request)
    {
        $employee = $this->employeeService->storeEmployee($request->validated());

        return redirect()->route('employees.edit', $employee->id)->with('success', 'Thêm nhân sự thành công!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateEmployeeRequest $request
     * @param int $id
     *
     * @return View
     */
    public function update(UpdateEmployeeRequest $request, $id)
    {
        $this->employeeService->updateEmployee($id, $request->validated());

        return redirect()->route('employees.edit', $id)->with('success', 'Cập nhật nhân sự thành công!');
    }

    /**
     * Get employee permissions.
     *
     * @param int $employeeId
     * @return JsonResponse
     */
    public function getEmployeePermissions($employeeId)
    {
        $employee = $this->employeeService->getEmployeeById($employeeId);

        if ($employee->user) {
            $permissions = $employee->user?->getDirectPermissions()?->pluck('name');
        } else {
            $permissions = [];
        }

        return response()->json(['permissions' => $permissions]);
    }

    /**
     * Update employee status.
     *
     * @param int $employeeId
     * @return View
     */
    public function updateStatus(Request $request)
    {
        can(PermissionEnum::EMPLOYEE_UPDATE_STATUS);

        $employeeId = $request->employeeId;
        $status = $request->status;

        $this->employeeService->updateStatus($employeeId, $status);

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công');
    }

    /**
     * Delete the specified resource in storage.
     *
     * @param int $id
     * @return View
     */
    public function delete($id)
    {
        $this->employeeService->deleteEmployee($id);

        return redirect()->back()->with('success', 'Xóa nhân sự thành công');
    }

    /**
     * Send password setup email to employee
     *
     * @param int $employeeId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendPasswordSetupEmail($employeeId)
    {
        can(PermissionEnum::EMPLOYEE_CREATE);

        $success = $this->employeeService->sendPasswordSetupEmail($employeeId);

        if ($success) {
            return redirect()->back()->with('success', 'Email tạo mật khẩu đã được gửi thành công');
        }

        return redirect()->back()->with('error', 'Không thể gửi email tạo mật khẩu. Vui lòng kiểm tra lại thông tin nhân viên hoặc liên hệ admin.');
    }

    /**
     * Show password setup form
     *
     * @param string $token
     * @return \Illuminate\View\View
     */
    public function showPasswordSetupForm($token)
    {
        $employee = $this->employeeService->getEmployeeBySetupToken($token);

        if (!$employee) {
            abort(404, 'Link không hợp lệ hoặc đã hết hạn');
        }

        return view('employee::setup-password', compact('employee', 'token'));
    }

    /**
     * Setup password for employee
     *
     * @param SetupPasswordRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setupPassword(SetupPasswordRequest $request)
    {
        $data = $request->validated();

        $success = $this->employeeService->setupPassword($data['token'], $data['password']);

        if ($success) {
            return redirect()->route('login')->with('success', 'Mật khẩu đã được tạo thành công. Bạn có thể đăng nhập ngay bây giờ.');
        }

        return redirect()->back()->with('error', 'Không thể tạo mật khẩu. Vui lòng thử lại.');
    }

    /**
     * Remove file from employee
     *
     * @param int $employeeId
     * @param int $fileId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeFile($employeeId, $fileId)
    {
        $this->employeeService->removeFile($employeeId, $fileId);

        return redirect()->back()->with('success', 'Xóa file thành công');
    }

    /**
     * Download files from employee
     *
     * @param int $employeeId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function downloadOtherFiles($employeeId)
    {
        return $this->employeeService->downloadFiles($employeeId);
    }

}
