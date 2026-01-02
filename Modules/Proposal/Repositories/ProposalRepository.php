<?php

namespace Modules\Proposal\Repositories;

use Modules\Core\Repositories\BaseRepository;
use Modules\Proposal\Models\Proposal;
use Modules\Proposal\Repositories\Contracts\ProposalRepositoryInterface;

class ProposalRepository extends BaseRepository implements ProposalRepositoryInterface
{
    public function getModelClass(): string
    {
        return Proposal::class;
    }

    public function getProposals()
    {
        return $this->query->with('files', 'customer')->get();
    }

    public function getProposalById($id)
    {
        return $this->query->with('files', 'customer', 'customer.personInCharge', 'services')->where('id', $id)->first();
    }
}
