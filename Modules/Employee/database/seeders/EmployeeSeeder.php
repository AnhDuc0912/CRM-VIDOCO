<?php

namespace Modules\Employee\database\seeders;

use Modules\Employee\Models\Employee;
use Illuminate\Database\Seeder;
use Modules\Core\Enums\TemplateCodeEnum;
use Modules\Employee\Enums\JobLevelEnum;
use Modules\Employee\Enums\JobPositionEnum;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::create([
            'code' => TemplateCodeEnum::EMPLOYEE . '00001',
            'qr_code' => TemplateCodeEnum::EMPLOYEE . '00001',
            'first_name' => 'Le Thuc',
            'last_name' => 'Vinh',
            'full_name' => 'Le Thuc Vinh',
            'citizen_id_number' => '1234567890',
            'citizen_id_created_date' => '1990-01-01',
            'citizen_id_created_place' => 'Hanoi',
            'email_work' => 'vinh.lethuc@vidoco.vn',
            'email_personal' => 'vinh.lethuc@vidoco.vn',
            'phone' => '0909090909',
            'permanent_address' => '123 Đường ABC, Quận XYZ, TP. HCM',
            'current_address' => '123 Đường ABC, Quận XYZ, TP. HCM',
            'gender' => 'male',
            'birthday' => '1990-01-01',
            'level' => JobLevelEnum::LEADER,
            'current_position' => JobPositionEnum::CEO,
            'last_position' => JobPositionEnum::COO,
            'manager_id' => 2,
            'department_id' => 1,
        ]);
        Employee::create([
            'code' => TemplateCodeEnum::EMPLOYEE . '00002',
            'qr_code' => TemplateCodeEnum::EMPLOYEE . '00002',
            'first_name' => 'Lê Ngọc Yến',
            'last_name' => 'Nhi',
            'full_name' => 'Lê Ngọc Yến Nhi',
            'citizen_id_number' => '1234567890',
            'citizen_id_created_date' => '1990-01-01',
            'citizen_id_created_place' => 'Hanoi',
            'email_work' => 'nhi.lengocyen@vidoco.vn',
            'email_personal' => 'nhi.lengocyen@vidoco.vn',
            'phone' => '0909090909',
            'permanent_address' => '123 Đường ABC, Quận XYZ, TP. HCM',
            'current_address' => '123 Đường ABC, Quận XYZ, TP. HCM',
            'gender' => 'male',
            'birthday' => '1990-01-01',
            'level' => JobLevelEnum::STAFF,
            'current_position' => JobPositionEnum::COPYWRITER,
            'last_position' => JobPositionEnum::COPYWRITER,
            'manager_id' => 1,
            'department_id' => 2,
        ]);

        Employee::create([
            'code' => TemplateCodeEnum::EMPLOYEE . '00003',
            'qr_code' => TemplateCodeEnum::EMPLOYEE . '00003',
            'first_name' => 'Ke',
            'last_name' => 'Toan',
            'full_name' => 'Ke Toan',
            'citizen_id_number' => '1234567890',
            'citizen_id_created_date' => '1990-01-01',
            'citizen_id_created_place' => 'Hanoi',
            'email_work' => 'ketoan@vidoco.vn',
            'email_personal' => 'ketoan@vidoco.vn',
            'phone' => '0909090909',
            'permanent_address' => '123 Đường ABC, Quận XYZ, TP. HCM',
            'current_address' => '123 Đường ABC, Quận XYZ, TP. HCM',
            'gender' => 'male',
            'birthday' => '1990-01-01',
            'level' => JobLevelEnum::STAFF,
            'current_position' => JobPositionEnum::COPYWRITER,
            'last_position' => JobPositionEnum::COPYWRITER,
            'manager_id' => 1,
            'department_id' => 3,
        ]);
    }
}
