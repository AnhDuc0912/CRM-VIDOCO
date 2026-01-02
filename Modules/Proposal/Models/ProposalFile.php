<?php

namespace Modules\Proposal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Proposal\Database\Factories\ProposalFactory;

class ProposalFile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'path',
        'name',
        'extension',
        'size',
        'proposal_id',
    ];

    // protected static function newFactory(): ProposalFactory
    // {
    //     // return ProposalFactory::new();
    // }
}
