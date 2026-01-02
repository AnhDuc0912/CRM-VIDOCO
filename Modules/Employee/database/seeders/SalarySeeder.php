<?php

namespace Modules\Employee\database\seeders;

use Modules\Employee\Models\EmployeeSalary;
use Illuminate\Database\Seeder;

class SalarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmployeeSalary::create([
            'employee_id' => 1,
            'base_salary' => 'Bậc 8/2',
            'basic_salary' => 10000000,
            'insurance_salary' => 10000000,
        ]);
        EmployeeSalary::create([
            'employee_id' => 2,
            'base_salary' => 'Bậc 8/2',
            'basic_salary' => 10000000,
            'insurance_salary' => 10000000,
        ]);
        EmployeeSalary::create([
            'employee_id' => 3,
            'base_salary' => 'Bậc 8/2',
            'basic_salary' => 10000000,
            'insurance_salary' => 10000000,
        ]);
    }
}
