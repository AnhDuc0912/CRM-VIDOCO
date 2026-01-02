<?php

namespace Modules\Order\Repositories;

use Modules\Order\Repositories\Contracts\OrderServiceRepositoryInterface;
use Modules\Order\Models\OrderService;

class OrderServiceRepository implements OrderServiceRepositoryInterface
{
    /**
     * Find order service by ID
     */
    public function findById($id)
    {
        return OrderService::find($id);
    }

    /**
     * Create new order service
     */
    public function create(array $data)
    {
        return OrderService::create($data);
    }

    /**
     * Update order service
     */
    public function update($id, array $data)
    {
        $orderService = $this->findById($id);
        if ($orderService) {
            $orderService->update($data);
            return $orderService;
        }
        return null;
    }

    /**
     * Delete order service
     */
    public function delete($id)
    {
        $orderService = $this->findById($id);
        if ($orderService) {
            return $orderService->delete();
        }
        return false;
    }
}