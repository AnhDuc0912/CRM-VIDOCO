<?php

namespace Modules\Project\Repositories\Contracts;

use Modules\Core\Repositories\Contracts\BaseRepositoryInterface;

interface ProjectRepositoryInterface extends BaseRepositoryInterface
{
    public function getActiveProject($customerId = null);

     public function getAllProject();

    public function getExpiringProject($customerId = null);

    public function getExpiredProject($customerId = null);

    public function createProject(array $data);

    public function findProjectService(int $projectServiceId);

    public function updateProjectService(int $projectServiceId, array $data);
}
