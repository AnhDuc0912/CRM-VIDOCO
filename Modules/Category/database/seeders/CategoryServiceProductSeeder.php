<?php

namespace Modules\Category\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Category\Enums\PaymentPeriodEnum;
use Modules\Category\Models\CategoryServiceProduct;

class CategoryServiceProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CategoryServiceProduct::create([
            'payment_period' => PaymentPeriodEnum::YEAR,
            'package_period' => 1,
            'price' => 100000,
            'category_service_id' => 1,
        ]);

        CategoryServiceProduct::create([
            'payment_period' => PaymentPeriodEnum::YEAR,
            'package_period' => 2,
            'price' => 200000,
            'category_service_id' => 1,
        ]);

        CategoryServiceProduct::create([
            'payment_period' => PaymentPeriodEnum::YEAR,
            'package_period' => 3,
            'price' => 300000,
            'category_service_id' => 1,
        ]);
    }
}
