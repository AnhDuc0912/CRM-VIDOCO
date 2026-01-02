<?php

namespace Modules\Category\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Employee\Models\Employee;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'status',
        'created_by',
        'updated_by',
        'approved_by',
        'approved_at',
        'file_path',
        'code',
    ];

    protected $casts = [
        'status' => 'integer',
        'approved_at' => 'datetime',
    ];

    protected $table = 'categories';

    public function services()
    {
        return $this->hasMany(CategoryService::class);
    }

    /**
     * Get the employee who created this category
     */
    public function creator()
    {
        return $this->belongsTo(Employee::class, 'created_by', 'id');
    }

    /**
     * Get the employee who updated this category
     */
    public function updater()
    {
        return $this->belongsTo(Employee::class, 'updated_by', 'id');
    }

    public function files()
    {
        return $this->hasMany(CategoryFile::class);
    }
}
