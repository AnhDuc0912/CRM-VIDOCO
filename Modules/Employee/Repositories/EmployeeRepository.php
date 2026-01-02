<?php

namespace Modules\Employee\Repositories;

use Modules\Core\Enums\RoleEnum;
use Modules\Employee\Models\Employee;
use Modules\Employee\Repositories\Contracts\EmployeeRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;
use Modules\Core\Repositories\BaseRepository;

class EmployeeRepository extends BaseRepository implements EmployeeRepositoryInterface
{
    /**
     * Specify Model class name
     */
    protected function getModelClass(): string
    {
        return Employee::class;
    }

    /**
     * Get all employees
     *
     * @return Collection
     */
    public function getAllEmployees(): Collection
    {
        return $this->model->with([
            'department',
            'user',
            'contracts',
            'salary',
            'dependents',
            'bankAccount',
        ])->get();
    }

    /**
     * Get employee by id
     *
     * @param int $id
     * @return Employee
     */
    public function getEmployeeById($id): Employee
    {
        return $this->model->with([
            'department',
            'user',
            'dependents',
            'bankAccount',
            'contracts',
            'salary',
        ])->findOrFail($id);
    }

    /**
     * Update password for the specified resource.
     *
     * @param int $userId
     * @param array $data
     * @return bool
     */
    public function updatePassword($userId, $data): bool
    {
        $employee = $this->getEmployeeById($userId);
        $updated = $employee->user->update([
            'password' => Hash::make($data['new_password']),
        ]);

        return $updated;
    }

    /**
     * Get last employee
     *
     * @return Employee
     */
    public function getLastEmployee(): Employee
    {
        return $this->model->orderBy('code', 'desc')->first();
    }

    /**
     * Get all managers
     *
     * @return Collection
     */
    public function getAllManagers(): Collection
    {
        return $this->model
            ->whereHas('user', function ($query) {
                $query->whereHas('roles', function ($roleQuery) {
                    $roleQuery->whereIn('name', [
                        RoleEnum::CEO,
                        RoleEnum::HR,
                        RoleEnum::IT,
                        RoleEnum::BUSINESS,
                        RoleEnum::ACCOUNTANT
                    ]);
                });
            })
            ->get();
    }
}
