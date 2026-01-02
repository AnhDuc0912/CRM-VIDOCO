<?php

namespace Modules\Employee\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeFile extends Model
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
        'employee_id',
        'type',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
