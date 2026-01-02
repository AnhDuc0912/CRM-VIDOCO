<?php

namespace Modules\Order\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Order\Models\Order;
use Modules\Core\Enums\TemplateCodeEnum;

class OrderSeeder extends Seeder
{
    public function run()
    {
        Order::create([
            'code' => TemplateCodeEnum::ORDER . '00001',
            'notes' => 'Lời nhắn',
            'customer_id' => 1,
            'created_by' => 1,
        ]);
    }
}
