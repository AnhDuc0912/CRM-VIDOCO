<?php

namespace Modules\SellOrder\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SellOrderService extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'sell_order_id',
        'price',
        'quantity',
        'total',
        'category_id',
        'service_id',
        'product_id',
    ];
}
