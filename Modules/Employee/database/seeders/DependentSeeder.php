<?php

namespace Modules\Employee\database\seeders;

use Modules\Core\Enums\GenderEnum;
use Modules\Core\Enums\RelationshipEnum;
use Modules\Employee\Models\EmployeeDependent;
use Illuminate\Database\Seeder;

class DependentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmployeeDependent::create([
            'name' => 'Nguyen Van A',
            'relationship' => RelationshipEnum::CHILD,
            'phone' => '0909090909',
            'address' => 'Hanoi',
            'birthday' => '1990-01-01',
            'gender' => GenderEnum::MALE,
            'job' => 'Học sinh',
            'employee_id' => 1,
        ]);
        EmployeeDependent::create([
            'name' => 'Nguyen Van B',
            'relationship' => RelationshipEnum::CHILD,
            'phone' => '0909090909',
            'address' => 'Hanoi',
            'birthday' => '1990-01-01',
            'gender' => GenderEnum::MALE,
            'job' => 'Học sinh',
            'employee_id' => 1,
        ]);
        EmployeeDependent::create([
            'name' => 'Nguyen Van C',
            'relationship' => RelationshipEnum::CHILD,
            'phone' => '0909090909',
            'address' => 'Hanoi',
            'birthday' => '1990-01-01',
            'gender' => GenderEnum::MALE,
            'job' => 'Học sinh',
            'employee_id' => 1,
        ]);

        EmployeeDependent::create([
            'name' => 'Nguyen Van A',
            'relationship' => RelationshipEnum::CHILD,
            'phone' => '0909090909',
            'address' => 'Hanoi',
            'birthday' => '1990-01-01',
            'gender' => GenderEnum::MALE,
            'job' => 'Học sinh',
            'employee_id' => 3,
        ]);
        EmployeeDependent::create([
            'name' => 'Nguyen Van B',
            'relationship' => RelationshipEnum::CHILD,
            'phone' => '0909090909',
            'address' => 'Hanoi',
            'birthday' => '1990-01-01',
            'gender' => GenderEnum::MALE,
            'job' => 'Học sinh',
            'employee_id' => 3,
        ]);
        EmployeeDependent::create([
            'name' => 'Nguyen Van C',
            'relationship' => RelationshipEnum::CHILD,
            'phone' => '0909090909',
            'address' => 'Hanoi',
            'birthday' => '1990-01-01',
            'gender' => GenderEnum::FEMALE,
            'job' => 'Học sinh',
            'employee_id' => 3,
        ]);
    }
}
