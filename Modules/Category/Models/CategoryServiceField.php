<?php

namespace Modules\Category\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\CreatedUpdatedBy;

class CategoryServiceField extends Model
{
    use HasFactory, CreatedUpdatedBy;

    protected $table = 'service_fields';

    protected $fillable = [
        'code',
        'name',
        'created_by',
        'updated_by',
    ];

    public function services()
    {
        return $this->hasMany(CategoryService::class, 'service_field_id', 'id');
    }
}
