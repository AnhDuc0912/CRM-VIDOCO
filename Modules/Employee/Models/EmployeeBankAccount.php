<?php

namespace Modules\Employee\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeBankAccount extends Model
{
    protected $table = 'employee_bank_accounts';
    protected $fillable = [
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'bank_branch',
        'bank_account_type',
        'employee_id',
    ];

    /**
     * Get the employee that owns the bank account.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
