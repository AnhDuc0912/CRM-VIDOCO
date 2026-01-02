<?php

namespace Modules\Proposal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Proposal\Database\Factories\ProposalServiceFactory;

class ProposalService extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'proposal_id',
        'category_id',
        'service_id',
        'product_id',
        'name',
        'price',
        'quantity',
        'total'
    ];
}
