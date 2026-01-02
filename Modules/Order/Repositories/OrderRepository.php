<?php

namespace Modules\Order\Repositories;

use Modules\Order\Models\Order;
use Modules\Order\Repositories\Contracts\OrderRepositoryInterface;
use Modules\Core\Repositories\BaseRepository;
use Carbon\Carbon;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function getModelClass(): String
    {
        return Order::class;
    }

    /**
     * Get active orders
     *
     * @param int|null $customerId
     * @return \Illuminate\Database\Eloquent\Collection|null
     */
    public function getActiveOrders($customerId = null)
    {
        $query = $this->model->with([
            'customer',
            'orderServices.service',
            'orderServices.service.category',
        ]);

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        return $query->whereHas('orderServices', function ($q) {
            $q->where('status', true)
                ->whereDate('end_date', '>', Carbon::now()->toDateString());
        })->get();
    }

    /**
     * Get expiring orders
     *
     * @param int|null $customerId
     * @return \Illuminate\Database\Eloquent\Collection|null
     */
    public function getExpiringOrders($customerId = null)
    {
        $now = Carbon::now()->toDateString();
        $expiredDate = Carbon::now()->addDays(7)->toDateString();
        $query = $this->model->with([
            'customer',
            'orderServices.service',
            'orderServices.service.category',
        ]);

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        return $query->whereHas('orderServices', function ($q) use ($now, $expiredDate) {
            $q->where('status', true)
                ->whereDate('end_date', '>', $now)
                ->whereDate('end_date', '<=', $expiredDate);
        })->get();
    }

    /**
     * Get expired orders
     *
     * @param int|null $customerId
     * @return \Illuminate\Database\Eloquent\Collection|null
     */
    public function getExpiredOrders($customerId = null)
    {
        $now = Carbon::now()->toDateString();
        $query = $this->model->with([
            'customer',
            'orderServices.service',
            'orderServices.service.category',
        ]);

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        return $query->whereHas('orderServices', function ($q) use ($now) {
            $q->where(function ($subQ) use ($now) {
                $subQ->where('status', false)
                    ->orWhereDate('end_date', '<', $now);
            });
        })->get();
    }

    public function createOrder(array $data)
    {
        return $this->model->create($data);
    }

    public function findOrderService(int $orderServiceId)
    {
        return $this->model->orderServices()->where('id', $orderServiceId)->first();
    }

    public function updateOrderService(int $orderServiceId, array $data)
    {
        $orderService = $this->findOrderService($orderServiceId);
        if ($orderService) {
            $orderService->update($data);
            return $orderService;
        }
        return false;
    }
}
