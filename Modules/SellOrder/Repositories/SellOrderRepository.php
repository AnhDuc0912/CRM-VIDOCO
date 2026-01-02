<?php

namespace Modules\SellOrder\Repositories;

use Modules\Core\Repositories\BaseRepository;
use Modules\SellOrder\Models\SellOrder;
use Modules\SellOrder\Repositories\Contracts\SellOrderRepositoryInterface;

class SellOrderRepository extends BaseRepository implements SellOrderRepositoryInterface
{
    public function getModelClass(): string
    {
        return SellOrder::class;
    }

    public function getSellOrders()
    {
        return $this->query->with('files', 'customer', 'customer.personInCharge', 'services')->get();
    }

    public function getSellOrderById($id)
    {
        return $this->query->with('files', 'customer', 'customer.personInCharge', 'services')->where('id', $id)->first();
    }
}
