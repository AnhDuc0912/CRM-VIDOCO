<?php

namespace Modules\Employee\database\seeders;

use Modules\Employee\Models\EmployeeAllowance;
use Illuminate\Database\Seeder;

class AllowanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmployeeAllowance::create([
            'employee_id' => 1,
            'name' => 'Phụ cấp trách nhiệm',
            'amount' => 1000000,
            'note' => 'Phụ cấp trách nhiệm',
        ]);
        EmployeeAllowance::create([
            'employee_id' => 1,
            'name' => 'Phụ cấp nhà ở',
            'amount' => 1000000,
            'note' => 'Phụ cấp nhà ở',
        ]);
        EmployeeAllowance::create([
            'employee_id' => 1,
            'name' => 'Phụ cấp thâm niên',
            'amount' => 1000000,
            'note' => 'Phụ cấp thâm niên',
        ]);
        EmployeeAllowance::create([
            'employee_id' => 1,
            'name' => 'Phụ cấp thu hút',
            'amount' => 1000000,
            'note' => 'Phụ cấp thu hút',
        ]);
    }
}
