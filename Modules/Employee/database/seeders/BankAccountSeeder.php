<?php

namespace Modules\Employee\database\seeders;

use Modules\Employee\Models\EmployeeBankAccount;
use Illuminate\Database\Seeder;

class BankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmployeeBankAccount::create([
            'bank_name' => 'Vietcombank',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Nguyen Van A',
            'bank_branch' => 'Hanoi',
            'bank_account_type' => 'Checking',
            'employee_id' => 1,
        ]);
        EmployeeBankAccount::create([
            'bank_name' => 'Vietcombank',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Nguyen Van B',
            'bank_branch' => 'Hanoi',
            'bank_account_type' => 'Checking',
            'employee_id' => 2,
        ]);
        EmployeeBankAccount::create([
            'bank_name' => 'Vietcombank',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Nguyen Van C',
            'bank_branch' => 'Hanoi',
            'bank_account_type' => 'Checking',
            'employee_id' => 3,
        ]);
    }
}
