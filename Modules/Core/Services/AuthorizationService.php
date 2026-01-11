<?php

namespace Modules\Core\Services;

use Modules\Department\Models\Department;
use Modules\Department\Repositories\DepartmentRepository;
use Modules\Employee\Services\EmployeeService;
use Spatie\Permission\Models\Role;

class AuthorizationService
{
    public function __construct(
        private EmployeeService $employeeService,
        private DepartmentRepository $departmentRepository,
    ) {}

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePermissionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function updatePermissionsEmployee($data)
    {
        if (!empty($data['employee_id'])) {
            $employee = $this->employeeService->getEmployeeById($data['employee_id']);
            $employee->user?->syncPermissions($data['permissions']);
        }

        if (!empty($data['department_id'])) {
            $department = Department::find($data['department_id']);
            $department->employees?->user->each(function ($employee) use ($data) {
                $employee->user?->syncPermissions($data['permissions']);
            });
        }
        

        if (!empty($data['role_id'])) {
            $role = Role::find($data['role_id']);
            $role->each(function ($user) use ($data) {
                $user->syncPermissions($data['permissions']);
            });
        }

        return $employee;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePermissionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function updatePermissionsDepartment($data)
    {
        $department = $this->departmentRepository->findOrFail($data['department_id']);
        $department->employees->each(function ($employee) use ($data) {
            $employee->user?->syncPermissions($data['permissions']);
        });
    }
}
