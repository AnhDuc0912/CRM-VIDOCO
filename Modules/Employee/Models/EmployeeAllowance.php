<?php

namespace Modules\Employee\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeAllowance extends Model
{
    protected $table = 'employee_allowances';
    protected $fillable = [
        'employee_id',
        'name',
        'amount',
        'note',
    ];
}
