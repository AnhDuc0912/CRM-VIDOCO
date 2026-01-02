<?php

namespace Modules\Department\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Modules\Core\Repositories\Contracts\BaseRepositoryInterface;

interface DepartmentRepositoryInterface extends BaseRepositoryInterface
{
    public function getAllDepartments(): Collection;
}
