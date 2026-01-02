<?php

namespace Modules\Project\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Core\Enums\TemplateCodeEnum;
use Modules\Project\Repositories\Contracts\ProjectRepositoryInterface;
use Modules\Project\Repositories\Contracts\CategoryRepositoryInterface;
use Modules\Project\Repositories\Contracts\CustomerRepositoryInterface;
use Modules\Project\Repositories\Contracts\CategoryServiceProductRepositoryInterface;

class ProjectService
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
        private CustomerRepositoryInterface $customerRepository,
        private CategoryRepositoryInterface $categoryRepository,
        private CategoryServiceProductRepositoryInterface $categoryServiceProductRepository,
    ) {}

    /**
     * Get active project
     */
    public function getActiveProject($customerId = null)
    {
        return $this->projectRepository->getActiveProject($customerId);
    }

    /**
     * Get expiring project
     */
    public function getExpiringProject($customerId = null)
    {
        return $this->projectRepository->getExpiringProject($customerId);
    }

    /**
     * Get expired project
     */
    public function getExpiredProject($customerId = null)
    {
        return $this->projectRepository->getExpiredProject($customerId);
    }

    /**
     * Get project by ID
     */
    public function getProjectById($projectId, $projectServiceId = null)
    {
        $with = [
            'customer',
            'projectServices' => function ($query) use ($projectServiceId) {
                if ($projectServiceId) {
                    $query->where('id', $projectServiceId);
                }
                $query->with(['service', 'service.category', 'product']);
            }
        ];
        return $this->projectRepository->with($with)->find($projectId);
    }

    /**
     * Get project service by ID
     */
    public function getProjectServiceById($id)
    {
        return $this->projectServiceRepository->findById($id);
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

    /**
     * Create new project
     */
    public function createProject(array $data)
    {
        try {
            $project = $this->projectRepository->create([
                'project_code'       => $data['project_code'],
                'project_name'       => $data['project_name'],
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
            return $project;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating project: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Renew project services
     */
    public function renewProjectServices(array $data)
    {
        try {
            DB::beginTransaction();

            // Create new project
            $project = $this->projectRepository->createProject([
                'code' => $data['code'],
                'customer_id' => $data['customer_id'],
                'notes' => $data['services'][0]['notes'] ?? '',
            ]);

            if (!empty($data['services'])) {
                foreach ($data['services'] as $serviceData) {
                    $this->projectServiceRepository->create([
                        'project_id' => $project->id,
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
            return $project;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error renewing project services: ' . $e->getMessage());
            throw $e;
        }
    }
}
