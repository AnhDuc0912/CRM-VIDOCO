<?php

namespace Modules\Work\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Core\Enums\TemplateCodeEnum;
use Modules\Project\Repositories\Contracts\ProjectRepositoryInterface;
use Modules\Work\Repositories\Contracts\WorkRepositoryInterface;
use Modules\Work\Repositories\Contracts\CategoryRepositoryInterface;
use Modules\Work\Repositories\Contracts\CustomerRepositoryInterface;
use Modules\Work\Repositories\Contracts\CategoryServiceProductRepositoryInterface;

class WorkService 
{
    public function __construct(
        private WorkRepositoryInterface $workRepository,
        private CustomerRepositoryInterface $customerRepository,
        private ProjectRepositoryInterface $projectRepository,
        private CategoryRepositoryInterface $categoryRepository,
        private CategoryServiceProductRepositoryInterface $categoryServiceProductRepository,
    ) {}

    /**
     * Get active work
     */
    public function getActiveWork($customerId = null)
    {
        return $this->workRepository->getActiveWork($customerId);
    }

    /**
     * Get expiring work
     */
    public function getExpiringWork($customerId = null)
    {
        return $this->workRepository->getExpiringWork($customerId);
    }

    /**
     * Get expired work
     */
    public function getExpiredWork($customerId = null)
    {
        return $this->workRepository->getExpiredWork($customerId);
    }

    /**
     * Get work by ID
     */
    public function getWorkById($workId, $workServiceId = null)
    {
        $with = [
            'customer',
            'workServices' => function ($query) use ($workServiceId) {
                if ($workServiceId) {
                    $query->where('id', $workServiceId);
                }
                $query->with(['service', 'service.category', 'product']);
            }
        ];
        return $this->workRepository->with($with)->find($workId);
    }

    /**
     * Get work service by ID
     */
    public function getWorkServiceById($id)
    {
        return $this->workServiceRepository->findById($id);
    }

    /**
     * Get categories for create form
     */
    public function getCategoriesForCreate()
    {
        return $this->categoryRepository->getActiveCategories();
    }

    /**
     * Get services with products
     */
    public function getServicesWithProducts()
    {
        return $this->categoryRepository->getActiveCategories()->load(['services.products', 'services.category']);
    }

    /**
     * Get customers for create form
     */
    public function getCustomersForCreate()
    {
        return $this->customerRepository->getAllCustomers();
    }

     public function getProjectsForCreate()
    {
        return $this->projectRepository->getAllProject();
    }

    /**
     * Create new work
     */
    public function createWork(array $data)
    {
        try {
            $work = $this->workRepository->create([
                'work_code'       => $data['work_code'],
                'work_name'       => $data['work_name'],
                'group'              => $data['group'] ?? null,
                'start_date'         => $data['start_date'] ?? null,
                'end_date'           => $data['end_date'] ?? null,
                'customer_id'        => $data['customer_id'] ?? null,
                'manager_id'         => $data['manager_id'] ?? null,
                'assignees'          => $data['assignees'] ?? null, 
                'follow_id'          => $data['follow_id'] ?? null, 
                'description'        => $data['description'] ?? null,
                'attachments'        => $data['attachments'] ?? null, 
                'budget_min'         => $data['budget_min'] ?? null,
                'budget_max'         => $data['budget_max'] ?? null,
                'zalo_group'         => $data['zalo_group'] ?? null,
                'auto_email'         => $data['auto_email'] ?? 1,
                'progress_calculate' => $data['progress_calculate'] ?? null,
                'level'              => $data['level'] ?? null,
                'status'             => $data['status'] ?? null,
            ]);

            DB::commit();
            return $work;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating work: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Renew work services
     */
    public function renewWorkServices(array $data)
    {
        try {
            DB::beginTransaction();

            // Create new work
            $work = $this->workRepository->createWork([
                'code' => $data['code'],
                'customer_id' => $data['customer_id'],
                'notes' => $data['services'][0]['notes'] ?? '',
            ]);

            if (!empty($data['services'])) {
                foreach ($data['services'] as $serviceData) {
                    $this->workServiceRepository->create([
                        'work_id' => $work->id,
                        'service_id' => $serviceData['service_id'],
                        'product_id' => $serviceData['product_id'],
                        'domain' => $serviceData['domain'] ?? '',
                        'notes' => $serviceData['notes'] ?? '',
                        'start_date' => now(),
                        'end_date' => $serviceData['end_date'],
                        'total_price' => $serviceData['price'] ?? 0,
                    ]);
                }
            }

            DB::commit();
            return $work;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error renewing work services: ' . $e->getMessage());
            throw $e;
        }
    }
}
