<?php

namespace Modules\Employee\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeBenefit extends Model
{
    protected $table = 'employee_benefits';
    protected $fillable = [
        'employee_id',
        'name',
        'amount',
        'note',
    ];
}
