<?php

namespace Modules\Proposal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Customer\Models\Customer;

// use Modules\Proposal\Database\Factories\ProposalFactory;

class Proposal extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'code',
        'status',
        'amount',
        'note',
        'expired_at',
        'created_by',
        'customer_id',
    ];

    public function files()
    {
        return $this->hasMany(ProposalFile::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function services()
    {
        return $this->hasMany(ProposalService::class);
    }
}
