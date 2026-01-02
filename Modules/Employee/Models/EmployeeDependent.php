<?php

namespace Modules\Employee\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeDependent extends Model
{
    protected $table = 'employee_dependents';
    protected $fillable = [
        'name',
        'relationship',
        'phone',
        'address',
        'birthday',
        'gender',
    ];

    /**
     * Get the employee that owns the dependent.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
