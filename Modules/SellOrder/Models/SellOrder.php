<?php

namespace Modules\SellOrder\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\SellOrder\Models\SellOrderService;
use Modules\SellOrder\Models\SellOrderFile;
use Modules\Proposal\Models\Proposal;
use Modules\Customer\Models\Customer;

class SellOrder extends Model
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
        'proposal_id',
        'source_type',
        'source_id',
        'created_by',
        'customer_id',
    ];


    public function services()
    {
        return $this->hasMany(SellOrderService::class);
    }

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function files()
    {
        return $this->hasMany(SellOrderFile::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
