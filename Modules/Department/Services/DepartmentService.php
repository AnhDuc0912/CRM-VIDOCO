<?php

namespace Modules\Department\Services;

use Modules\Department\Repositories\Contracts\DepartmentRepositoryInterface;

class DepartmentService
{
    protected DepartmentRepositoryInterface $departmentRepository;

    public function __construct(DepartmentRepositoryInterface $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    /**
     * Get all departments
     *
     * @return Collection
     */
    public function getAllDepartments()
    {
        return $this->departmentRepository->getAllDepartments();
    }

    public function getDepartmentByIdAjax($id)
    {
        return $this->departmentRepository->findOrFail($id);
    }
}
