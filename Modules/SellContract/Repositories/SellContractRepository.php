<?php

namespace Modules\SellContract\Repositories;

use Modules\Core\Repositories\BaseRepository;
use Modules\SellContract\Models\SellContract;
use Modules\SellContract\Repositories\Contracts\SellContractRepositoryInterface;

class SellContractRepository extends BaseRepository implements SellContractRepositoryInterface
{
    public function getModelClass(): string
    {
        return SellContract::class;
    }

    public function getSellContracts()
    {
        return $this->query->with('files', 'customer', 'customer.personInCharge', 'services')->get();
    }

        public function getSellContractById($id)
    {
        return $this->query->with('files', 'customer', 'customer.personInCharge', 'services')->where('id', $id)->first();
    }
}
