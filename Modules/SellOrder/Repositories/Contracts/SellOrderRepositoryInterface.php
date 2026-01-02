<?php

namespace Modules\SellOrder\Repositories\Contracts;

use Modules\Core\Repositories\Contracts\BaseRepositoryInterface;

interface SellOrderRepositoryInterface extends BaseRepositoryInterface
{
    public function getSellOrders();
    public function getSellOrderById($id);
}
