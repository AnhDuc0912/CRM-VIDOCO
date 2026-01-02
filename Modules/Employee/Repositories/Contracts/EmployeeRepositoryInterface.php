<?php

namespace Modules\Employee\Repositories\Contracts;

use Modules\Employee\Models\Employee;
use Illuminate\Database\Eloquent\Collection;
use Modules\Core\Repositories\Contracts\BaseRepositoryInterface;

interface EmployeeRepositoryInterface extends BaseRepositoryInterface
{
    public function getAllEmployees(): Collection;
    public function getEmployeeById($id): Employee;
    public function updatePassword($userId, $data): bool;
    public function getLastEmployee(): Employee;
    public function getAllManagers(): Collection;
}
