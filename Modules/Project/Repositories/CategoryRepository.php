<?php

namespace Modules\Project\Repositories;

use Modules\Category\Models\Category;
use Modules\Category\Enums\ServiceStatusEnum;
use Modules\Core\Repositories\BaseRepository;
use Modules\Category\Enums\CategoryStatusEnum;
use Modules\Project\Repositories\Contracts\CategoryRepositoryInterface;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function getModelClass(): String
    {
        return Category::class;
    }

    /**
     * Get active categories with services
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveCategories()
    {
        return $this->model->where('status', CategoryStatusEnum::ACTIVE)
            ->with([
                'services' => function($query) {
                    $query->where('status', ServiceStatusEnum::ACTIVE);
                },
                'services.products',
            ])
            ->get();
    }
}
