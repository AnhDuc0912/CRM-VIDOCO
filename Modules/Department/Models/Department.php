<?php

namespace Modules\Department\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Employee\Models\Employee;

class Department extends Model
{
    protected $table = 'departments';
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the employees for the department.
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
