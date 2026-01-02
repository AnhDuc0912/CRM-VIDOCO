<?php

namespace Modules\Category\Repositories\Contracts;

use Modules\Core\Repositories\Contracts\BaseRepositoryInterface;

interface CategoryFileRepositoryInterface extends BaseRepositoryInterface
{
    public function findCategoryFile($fileId);
    public function storeCategoryFile(array $data);
    public function deleteFile($fileId);
}
