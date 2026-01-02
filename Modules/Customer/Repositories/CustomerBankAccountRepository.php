<?php

namespace Modules\Customer\Repositories;

use Modules\Core\Repositories\BaseRepository;
use Modules\Customer\Models\CustomerBankAccount;
use Modules\Customer\Repositories\Contracts\CustomerBankAccountRepositoryInterface;

class CustomerBankAccountRepository extends BaseRepository implements CustomerBankAccountRepositoryInterface
{
    public function getModelClass(): string
    {
        return CustomerBankAccount::class;
    }
}
