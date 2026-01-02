<?php

namespace Modules\Order\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Core\Enums\TemplateCodeEnum;
use Modules\Order\Repositories\Contracts\OrderRepositoryInterface;
use Modules\Order\Repositories\Contracts\CategoryRepositoryInterface;
use Modules\Order\Repositories\Contracts\CustomerRepositoryInterface;
use Modules\Order\Repositories\Contracts\OrderServiceRepositoryInterface;
use Modules\Order\Repositories\Contracts\CategoryServiceProductRepositoryInterface;

class OrderService
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private OrderServiceRepositoryInterface $orderServiceRepository,
        private CustomerRepositoryInterface $customerRepository,
        private CategoryRepositoryInterface $categoryRepository,
        private CategoryServiceProductRepositoryInterface $categoryServiceProductRepository,
    ) {}

    /**
     * Get active orders
     */
    public function getActiveOrders($customerId = null)
    {
        return $this->orderRepository->getActiveOrders($customerId);
    }

    /**
     * Get expiring orders
     */
    public function getExpiringOrders($customerId = null)
    {
        return $this->orderRepository->getExpiringOrders($customerId);
    }

    /**
     * Get expired orders
     */
    public function getExpiredOrders($customerId = null)
    {
        return $this->orderRepository->getExpiredOrders($customerId);
    }

    /**
     * Get order by ID
     */
    public function getOrderById($orderId, $orderServiceId = null)
    {
        $with = [
            'customer',
            'orderServices' => function ($query) use ($orderServiceId) {
                if ($orderServiceId) {
                    $query->where('id', $orderServiceId);
                }
                $query->with(['service', 'service.category', 'product']);
            }
        ];
        return $this->orderRepository->with($with)->find($orderId);
    }

    /**
     * Get order service by ID
     */
    public function getOrderServiceById($id)
    {
        return $this->orderServiceRepository->findById($id);
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
     * Create new order
     */
    public function createOrder(array $data)
    {
        $data['code'] = generate_code(TemplateCodeEnum::ORDER, 'orders');
        try {
            DB::beginTransaction();

            // Create order
            $order = $this->orderRepository->createOrder($data);

            $price = 0;
            $product = $this->categoryServiceProductRepository->findById($data['product_id']);
            if ($product) {
                $price = $product->price;
            }

            $this->orderServiceRepository->create([
                'order_id' => $order->id,
                'service_id' => $data['service_id'],
                'product_id' => $data['product_id'],
                'domain' => $data['domain'] ?? '',
                'notes' => $data['notes'] ?? '',
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'total_price' => $price,
            ]);

            DB::commit();
            return $order;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating order: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Renew order services
     */
    public function renewOrderServices(array $data)
    {
        try {
            DB::beginTransaction();

            // Create new order
            $order = $this->orderRepository->createOrder([
                'code' => $data['code'],
                'customer_id' => $data['customer_id'],
                'notes' => $data['services'][0]['notes'] ?? '',
            ]);

            if (!empty($data['services'])) {
                foreach ($data['services'] as $serviceData) {
                    $this->orderServiceRepository->create([
                        'order_id' => $order->id,
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
            return $order;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error renewing order services: ' . $e->getMessage());
            throw $e;
        }
    }
}
