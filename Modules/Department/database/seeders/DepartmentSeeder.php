<?php

namespace Modules\Department\database\seeders;

use Modules\Department\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::create([
            'name' => 'Ban quản trị',
            'description' => 'Ban quản trị',
        ]);
        Department::create([
            'name' => 'Phòng Marketing',
            'description' => 'Phòng Marketing',
        ]);
        Department::create([
            'name' => 'Phòng Đào tạo',
            'description' => 'Phòng Đào tạo',
        ]);
        Department::create([
            'name' => 'Phòng Lập trình',
            'description' => 'Phòng Lập trình',
        ]);
        Department::create([
            'name' => 'Phòng Kế toán - Tài chính',
            'description' => 'Phòng Kế toán - Tài chính',
        ]);
        Department::create([
            'name' => 'Phòng Hành chính',
            'description' => 'Phòng Hành chính',
        ]);
        Department::create([
            'name' => 'Phòng Nhân sự',
            'description' => 'Phòng Nhân sự',
        ]);
        Department::create([
            'name' => 'Phòng kinh doanh',
            'description' => 'Phòng kinh doanh',
        ]);
    }
}
