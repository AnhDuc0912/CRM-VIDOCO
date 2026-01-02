<?php

namespace Modules\SellContract\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\SellContract\Database\Factories\SellContractServiceFactory;

class SellContractService extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'sell_contract_id',
        'price',
        'quantity',
        'total',
        'category_id',
        'service_id',
        'product_id',
    ];
}
