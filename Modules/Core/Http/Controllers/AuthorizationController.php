<?php

namespace Modules\Core\Http\Controllers;

use Modules\Core\Enums\PermissionEnum;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\UpdatePermissionRequest;
use Modules\Core\Services\AuthorizationService;
use Modules\Department\Models\Department;
use Modules\Employee\Models\Employee;
use Modules\Employee\Services\EmployeeService;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthorizationController extends Controller
{
    public function __construct(
        private AuthorizationService $authorizationService,
        private EmployeeService $employeeService,
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        can(PermissionEnum::AUTHORIZATION_VIEW);
        set_breadcrumbs([
            ['title' => 'Phân quyền', 'url' => null]
        ]);

        $ceoEmails = ['tamthoishowhet@gmail.com'];

        $roles = Role::all();
        $permissions = Permission::all();
        $employees = Employee::with('department')->whereHas('user', function ($query) use ($ceoEmails) {
            $query->whereNotIn('email', $ceoEmails);
        })->get();
        $departments = Department::withCount('employees')->get();

        $resourceLabelMap = PermissionEnum::RESOURCE_LABEL_MAP;
        $actionLabelMap = PermissionEnum::ACTION_LABEL_MAP;

        $grouped = [];

        foreach ($permissions as $permission) {
            [$resource, $action] = explode('.', $permission->name);
            $resourceLabel = $resourceLabelMap[$resource] ?? ucfirst($resource);
            $actionLabel = $actionLabelMap[$action] ?? ucfirst($action);
            $grouped[$resourceLabel][$permission->name] = $actionLabel;
        }

        $salesPersons = $this->employeeService->getEmployeesByPosition('Kinh Doanh');

        return view('core::authorization.index', compact('roles', 'permissions', 'employees', 'departments', 'grouped', 'salesPersons'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePermissionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function updatePermissionsEmployee(UpdatePermissionRequest $request)
    {
        can(PermissionEnum::AUTHORIZATION_UPDATE);
        $data = $request->validated();

        $this->authorizationService->updatePermissionsEmployee($data);

        return redirect()->route('authorization')->with('success', 'Cập nhật quyền thành công');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePermissionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function updatePermissionsDepartment(UpdatePermissionRequest $request)
    {
        can(PermissionEnum::AUTHORIZATION_UPDATE);
        $data = $request->validated();

        $this->authorizationService->updatePermissionsDepartment($data);

        return redirect()->route('authorization')->with('success', 'Cập nhật quyền thành công');
    }
}
