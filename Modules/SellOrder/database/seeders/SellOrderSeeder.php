<?php

namespace Modules\SellOrder\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Enums\TemplateCodeEnum;
use Modules\SellOrder\Enums\SellOrderStatusEnum;
use Modules\SellOrder\Models\SellOrder;

class SellOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SellOrder::create([
            'code' => TemplateCodeEnum::SELL_ORDER . '00001',
            'status' => SellOrderStatusEnum::CREATED,
            'amount' => 1000000,
            'expired_at' => now()->addDays(30),
            'proposal_id' => 1,
            'created_by' => 1,
            'customer_id' => 1,
        ]);
    }
}
