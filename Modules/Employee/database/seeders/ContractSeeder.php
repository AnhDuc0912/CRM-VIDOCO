<?php

namespace Modules\Employee\database\seeders;

use Modules\Employee\Models\EmployeeContract;
use Illuminate\Database\Seeder;

class ContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmployeeContract::create([
            'employee_id' => 1,
            'contract_type' => 1,
            'start_date' => '2020-01-01',
            'end_date' => '2025-01-01',
            'status' => '1',
            'note' => 'note',
        ]);
        EmployeeContract::create([
            'employee_id' => 2,
            'contract_type' => 1,
            'start_date' => '2020-01-01',
            'end_date' => '2025-01-01',
            'status' => '1',
            'note' => 'note',
        ]);
        EmployeeContract::create([
            'employee_id' => 3,
            'contract_type' => 1,
            'start_date' => '2020-01-01',
            'end_date' => '2025-01-01',
            'status' => '1',
            'note' => 'note',
        ]);
    }
}
