<?php

namespace Modules\Customer\Repositories;

use Modules\Core\Repositories\BaseRepository;
use Modules\Customer\Models\CustomerBehaviors;
use Modules\Customer\Repositories\Contracts\CustomerBehaviorRepositoryInterface;

class CustomerBehaviorRepository extends BaseRepository implements CustomerBehaviorRepositoryInterface
{
    public function getModelClass(): string
    {
        return CustomerBehaviors::class;
    }
}
