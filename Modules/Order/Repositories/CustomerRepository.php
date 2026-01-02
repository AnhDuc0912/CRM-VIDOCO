<?php

namespace Modules\Order\Repositories;

use Modules\Customer\Models\Customer;
use Modules\Core\Repositories\BaseRepository;
use Modules\Order\Repositories\Contracts\CustomerRepositoryInterface;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
    public function getModelClass(): String
    {
        return Customer::class;
    }

    /**
     * Get all customers
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllCustomers()
    {
        return $this->model->all();
    }
}
