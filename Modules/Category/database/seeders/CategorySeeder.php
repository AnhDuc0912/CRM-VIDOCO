<?php

namespace Modules\Category\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Category\Models\Category;
use Modules\Category\Enums\CategoryStatusEnum;
use Modules\Core\Enums\TemplateCodeEnum;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'code' => TemplateCodeEnum::CATEGORY . '00001',
            'name' => 'Tên miền quốc tế	',
            'status' => CategoryStatusEnum::ACTIVE,
            'created_by' => 1,
            'updated_by' => 1,
            'approved_by' => 1,
            'approved_at' => now(),
        ]);

        Category::create([
            'code' => TemplateCodeEnum::CATEGORY . '00002',
            'name' => 'Tên miền VN',
            'status' => CategoryStatusEnum::ACTIVE,
            'created_by' => 1,
            'updated_by' => 1,
            'approved_by' => 1,
            'approved_at' => now(),
        ]);

        Category::create([
            'code' => TemplateCodeEnum::CATEGORY . '00003',
            'name' => 'Hosting',
            'status' => CategoryStatusEnum::ACTIVE,
            'created_by' => 1,
            'updated_by' => 1,
            'approved_by' => 1,
            'approved_at' => now(),
        ]);
    }
}
