<?php

namespace Modules\Department\Repositories;

use Modules\Department\Models\Department;
use Modules\Department\Repositories\Contracts\DepartmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Modules\Core\Repositories\BaseRepository;

class DepartmentRepository extends BaseRepository implements DepartmentRepositoryInterface
{
    /**
     * Specify Model class name
     */
    protected function getModelClass(): string
    {
        return Department::class;
    }

    public function getAllDepartments(): Collection
    {
        return $this->model->get();
    }
}
