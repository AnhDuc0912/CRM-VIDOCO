<?php

namespace Modules\Project\Repositories;

use Modules\Category\Models\CategoryServiceProduct;
use Modules\Core\Repositories\BaseRepository;
use Modules\Project\Repositories\Contracts\CategoryServiceProductRepositoryInterface;

class CategoryServiceProductRepository extends BaseRepository implements CategoryServiceProductRepositoryInterface
{
    public function getModelClass(): String
    {
        return CategoryServiceProduct::class;
    }

    /**
     * Find a category service product by id
     *
     * @param int $id
     * @return \Modules\Category\Models\CategoryServiceProduct
     */
    public function findById($id)
    {
        return $this->model->find($id);
    }
}
