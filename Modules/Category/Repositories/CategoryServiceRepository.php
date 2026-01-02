<?php

namespace Modules\Category\Repositories;

use Modules\Category\Models\CategoryService;
use Modules\Core\Repositories\BaseRepository;
use Modules\Category\Repositories\Contracts\CategoryServiceRepositoryInterface;

class CategoryServiceRepository extends BaseRepository implements CategoryServiceRepositoryInterface
{
    public function getModelClass(): String
    {
        return CategoryService::class;
    }

    public function getAllServices(array $data)
    {
        $query = $this->model->with('products', 'category');

        if (!empty($data['category_id'])) {
            $query->where('category_id', $data['category_id']);
        }

        return $query->get();
    }

    /**
     * Create a new service with products
     *
     * @param array $data
     *
     * @return void
     */
    public function createService(array $data)
    {
        $products = $data['products'] ?? [];
        unset($data['products']);
        $service = $this->model->create($data);

        if (!empty($products)) {
            foreach ($products as $productData) {
                $service->products()->create([
                    'payment_period' => $productData['payment_period'],
                    'package_period' => $productData['package_period'],
                    'price' => $productData['price'],
                ]);
            }
        }
    }

    /**
     * Get the latest service
     *
     * @param array $columns
     *
     * @return CategoryService
     */
    public function latestService(array $columns = ['*'])
    {
        return $this->model->latest()->first($columns) ?? new CategoryService();
    }

    public function getServiceById(int $id, array $columns = ['*'])
    {
        return $this->model->with(['products', 'category'])->find($id, $columns);
    }

    public function updateService(int $id, array $data)
    {
        $service = $this->model->find($id);
        if (!$service) {
            throw new \Exception('Không tìm thấy dịch vụ');
        }
        $products = $data['products'] ?? [];
        unset($data['products']);

        $service->update($data);
        $service->products()->delete();

        if (!empty($products)) {
            foreach ($products as $productData) {
                $service->products()->create([
                    'payment_period' => $productData['payment_period'],
                    'package_period' => $productData['package_period'],
                    'price' => $productData['price'],
                ]);
            }
        }
        return $service->fresh(['products', 'category']);
    }
}
