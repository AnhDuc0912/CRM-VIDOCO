<?php

namespace Modules\Customer\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Modules\Core\Repositories\Contracts\BaseRepositoryInterface;
use Modules\Customer\Models\Customer;

interface CustomerRepositoryInterface extends BaseRepositoryInterface
{
    public function getAllCustomers(): Collection;
    public function getCustomerById(int $id): Customer;
}
