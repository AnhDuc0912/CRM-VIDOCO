<?php

namespace Modules\Customer\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Core\Repositories\BaseRepository;
use Modules\Customer\Models\Customer;
use Modules\Customer\Repositories\Contracts\CustomerRepositoryInterface;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
    public function getModelClass(): string
    {
        return Customer::class;
    }

    public function getAllCustomers(): Collection
    {
        return $this->model->with(['services'])->get();
    }

    public function getCustomerById(int $id): Customer
    {
        return $this->model->with(['bankAccounts', 'behaviors', 'files', 'personInCharge'])->findOrFail($id);
    }
}
