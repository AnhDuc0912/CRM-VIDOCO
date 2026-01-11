<?php

namespace Modules\Customer\database\seeders;

use Modules\Customer\Models\CustomerSource;
use Illuminate\Database\Seeder;

class CustomerSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sources = [
            ['name' => 'Sale tự kiếm', 'description' => 'Khách hàng được tìm kiếm bởi sale', 'is_active' => 1],
            ['name' => 'Kênh Marketing', 'description' => 'Khách hàng từ kênh marketing', 'is_active' => 1],
            ['name' => 'CTV 1', 'description' => 'Khách hàng từ cộng tác viên 1', 'is_active' => 1],
            ['name' => 'CTV 2', 'description' => 'Khách hàng từ cộng tác viên 2', 'is_active' => 1],
            ['name' => 'Facebook', 'description' => 'Khách hàng từ Facebook', 'is_active' => 1],
            ['name' => 'Website', 'description' => 'Khách hàng từ Website', 'is_active' => 1],
            ['name' => 'Zalo', 'description' => 'Khách hàng từ Zalo', 'is_active' => 1],
            ['name' => 'Workshop', 'description' => 'Khách hàng từ workshop/sự kiện', 'is_active' => 1],
            ['name' => 'Khách liên hệ chủ động', 'description' => 'Khách hàng tự liên hệ', 'is_active' => 1],
            ['name' => 'Giới thiệu', 'description' => 'Khách hàng được giới thiệu', 'is_active' => 1],
        ];

        foreach ($sources as $source) {
            CustomerSource::firstOrCreate(
                ['name' => $source['name']],
                $source
            );
        }
    }
}
