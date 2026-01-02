<?php

namespace Modules\Category\Repositories\Contracts;

use Modules\Core\Repositories\Contracts\BaseRepositoryInterface;

interface CategoryServiceRepositoryInterface extends BaseRepositoryInterface
{
    public function getAllServices(array $data);

    public function createService(array $data);

    public function latestService(array $columns = ['*']);

    public function getServiceById(int $id, array $columns = ['*']);

    public function updateService(int $id, array $data);
}
