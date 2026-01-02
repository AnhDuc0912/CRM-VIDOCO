<?php

namespace Modules\Employee\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSalary extends Model
{
    protected $fillable = [
        'employee_id',
        'base_salary',
        'basic_salary',
        'insurance_salary',
    ];
}
