<?php

namespace Modules\Category\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Category\Models\CategoryService;
use Modules\Category\Enums\PaymentPeriodEnum;
use Modules\Category\Enums\PaymentTypeEnum;
use Modules\Core\Enums\TemplateCodeEnum;

class CategoryServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CategoryService::create([
            'code' => TemplateCodeEnum::CATEGORY_SERVICE . '00001',
            'name' => 'Dịch vụ gia hạn',
            'category_id' => 1,
            'payment_type' => PaymentTypeEnum::RENEWABLE,
            'vat' => 10,
        ]);

        CategoryService::create([
            'code' => TemplateCodeEnum::CATEGORY_SERVICE . '00003',
            'name' => 'Dịch vụ không gia hạn',
            'category_id' => 1,
            'payment_type' => PaymentTypeEnum::NON_RENEWABLE,
            'vat' => 10,
        ]);
    }
}
