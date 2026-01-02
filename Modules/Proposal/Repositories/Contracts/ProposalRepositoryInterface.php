<?php

namespace Modules\Proposal\Repositories\Contracts;

use Modules\Core\Repositories\Contracts\BaseRepositoryInterface;

interface ProposalRepositoryInterface extends BaseRepositoryInterface
{
    public function getProposals();
    public function getProposalById($id);
}
