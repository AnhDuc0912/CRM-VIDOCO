<?php

namespace Modules\Category\Repositories\Contracts;

use Modules\Core\Repositories\Contracts\BaseRepositoryInterface;

interface CategoryRepositoryInterface extends BaseRepositoryInterface
{
    public function getAllCategories($params = []);

    public function createCategory(array $data);
}
