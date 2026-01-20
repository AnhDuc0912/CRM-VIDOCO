<?php

namespace Modules\Category\Repositories;

use Modules\Category\Models\Category;
use Modules\Category\Repositories\Contracts\CategoryRepositoryInterface;
use Modules\Core\Repositories\BaseRepository;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function getModelClass(): String
    {
        return Category::class;
    }

    /**
     * Get all categories
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllCategories($params = [])
    {
        $query = $this->model->with('services', 'services.products', 'creator', 'files', 'serviceField');
        if (!empty($params['category_service'])) {
            $query->whereHas('services', function($q) use ($params) {
                $q->where('id', $params['category_service']);
            });
        }
        return $query->get();
    }

    /**
     * Create a new category
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createCategory(array $data)
    {
        return $this->model->create($data);
    }
}
