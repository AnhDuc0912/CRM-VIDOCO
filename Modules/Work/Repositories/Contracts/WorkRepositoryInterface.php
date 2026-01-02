<?php

namespace Modules\Work\Repositories\Contracts;

use Modules\Core\Repositories\Contracts\BaseRepositoryInterface;

interface WorkRepositoryInterface extends BaseRepositoryInterface
{
    public function getActiveWork($customerId = null);

    public function getExpiringWork($customerId = null);

    public function getExpiredWork($customerId = null);

    public function createWork(array $data);

    public function findWorkService(int $projectServiceId);

    public function updateWorkService(int $projectServiceId, array $data);
}
