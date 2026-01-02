<?php

namespace Modules\SellContract\Repositories\Contracts;

use Modules\Core\Repositories\Contracts\BaseRepositoryInterface;

interface SellContractRepositoryInterface extends BaseRepositoryInterface
{
    public function getSellContracts();
    public function getSellContractById($id);
}
