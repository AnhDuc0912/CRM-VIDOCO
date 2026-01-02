<?php

namespace Modules\Category\Repositories;

use Modules\Category\Models\CategoryFile;
use Modules\Category\Repositories\Contracts\CategoryFileRepositoryInterface;
use Modules\Core\Repositories\BaseRepository;

class CategoryFileRepository extends BaseRepository implements CategoryFileRepositoryInterface
{
    public function getModelClass(): String
    {
        return CategoryFile::class;
    }

    public function storeCategoryFile(array $data)
    {
        return $this->model->create($data);
    }

    public function deleteFile($fileId)
    {
        return $this->model->where('id', $fileId)->delete();
    }

    public function findCategoryFile($fileId)
    {
        return $this->model->find($fileId);
    }
}
