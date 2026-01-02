<?php

namespace Modules\Category\Services;

use Modules\Core\Enums\TemplateCodeEnum;
use Modules\Category\Repositories\Contracts\CategoryServiceRepositoryInterface;

class CategoryServiceService
{
    public function __construct(
        protected CategoryServiceRepositoryInterface $categoryServiceRepository,
    ) {}

    /**
     * Get all services
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getServices(array $data)
    {
        return $this->categoryServiceRepository->getAllServices($data);
    }

    /**
     * Create a new service
     *
     * @param array $data
     *
     * @return void
     */
    public function createService(array $data)
    {
        $latestService = $this->categoryServiceRepository->latestService(['id']);
        $data['code'] = TemplateCodeEnum::CATEGORY_SERVICE . str_pad($latestService->id + 1, 5, '0', STR_PAD_LEFT);

        return $this->categoryServiceRepository->createService($data);
    }

    /**
     * Get service by id
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getServiceById($id)
    {
        return $this->categoryServiceRepository->getServiceById($id);
    }

    /**
     * Update service by id
     *
     * @param int $id
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function updateService($id, array $data)
    {
        return $this->categoryServiceRepository->updateService($id, $data);
    }
}
