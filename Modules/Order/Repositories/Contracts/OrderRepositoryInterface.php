<?php

namespace Modules\Order\Repositories\Contracts;

use Modules\Core\Repositories\Contracts\BaseRepositoryInterface;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    public function getActiveOrders($customerId = null);

    public function getExpiringOrders($customerId = null);

    public function getExpiredOrders($customerId = null);

    public function createOrder(array $data);

    public function findOrderService(int $orderServiceId);

    public function updateOrderService(int $orderServiceId, array $data);
}
