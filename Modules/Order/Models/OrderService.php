<?php

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Category\Models\CategoryService;
use Modules\Category\Models\CategoryServiceProduct;
use Modules\Order\Models\Order;

class OrderService extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'order_id',
        'service_id',
        'product_id',
        'quantity',
        'discount_amount',
        'total_price',
        'status',
        'auto_email',
        'start_date',
        'end_date',
        'notes',
        'domain',
    ];

    protected $casts = [
        'end_date' => 'datetime',
    ];


    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo(CategoryService::class, 'service_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(CategoryServiceProduct::class, 'product_id', 'id');
    }
}
