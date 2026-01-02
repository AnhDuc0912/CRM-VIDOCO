<?php

namespace Modules\User\database\seeders;

use Illuminate\Database\Seeder;
use Modules\User\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Vinh Le Thuc',
                'email' => 'vinh.lethuc@vidoco.vn',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'employee_id' => 1,
            ],
            [
                'name' => 'Lê Ngọc Yến Nhi',
                'email' => 'nhi.lengocyen@vidoco.vn',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'employee_id' => 2,
            ],
            [
                'name' => 'Ke Toan',
                'email' => 'ketoan@vidoco.vn',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'employee_id' => 3,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
