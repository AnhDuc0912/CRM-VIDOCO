<?php

namespace Modules\Category\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\CreatedUpdatedBy;
use Modules\Order\Models\OrderService;

class CategoryService extends Model
{
    use HasFactory, CreatedUpdatedBy;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'code',
        'name',
        'category_id',
        'service_field_id',
        'description',
        'payment_type',
        'status',
        'vat',
        'created_by',
        'updated_by',
    ];

    protected $table = 'category_services';

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function products()
    {
        return $this->hasMany(CategoryServiceProduct::class);
    }

    public function orderServices()
    {
        return $this->hasMany(OrderService::class, 'service_id', 'id');
    }

    public function serviceField()
    {
        return $this->belongsTo(CategoryServiceField::class, 'service_field_id', 'id');
    }
}
