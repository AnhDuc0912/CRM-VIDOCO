<?php

namespace Modules\Employee\database\seeders;

use Modules\Employee\Models\EmployeeBenefit;
use Illuminate\Database\Seeder;

class BenefitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmployeeBenefit::create([
            'employee_id' => 1,
            'name' => 'Đồng phục',
            'amount' => 1000000,
            'note' => 'Đồng phục',
        ]);
        EmployeeBenefit::create([
            'employee_id' => 1,
            'name' => 'Bảo hiểm xã hội',
            'amount' => 1000000,
            'note' => 'Bảo hiểm xã hội',
        ]);
        EmployeeBenefit::create([
            'employee_id' => 1,
            'name' => 'Bảo hiểm y tế',
            'amount' => 1000000,
            'note' => 'Bảo hiểm y tế',
        ]);
        EmployeeBenefit::create([
            'employee_id' => 1,
            'name' => 'Bảo hiểm thất nghiệp',
            'amount' => 1000000,
            'note' => 'Bảo hiểm thất nghiệp',
        ]);
    }
}
