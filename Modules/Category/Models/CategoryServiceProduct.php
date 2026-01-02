<?php

namespace Modules\Category\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryServiceProduct extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'payment_period',
        'package_period',
        'price',
        'category_service_id',
        'created_by',
        'updated_by',
    ];

    protected $table = 'category_service_products';

    /**
     * Get the service that owns this product.
     */
    public function service()
    {
        return $this->belongsTo(CategoryService::class, 'category_service_id');
    }
}
