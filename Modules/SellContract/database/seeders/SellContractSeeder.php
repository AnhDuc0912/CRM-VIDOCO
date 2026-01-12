<?php

namespace Modules\SellContract\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Enums\TemplateCodeEnum;
use Modules\SellContract\Enums\SellContractStatusEnum;
use Modules\SellContract\Models\SellContract;

class SellContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SellContract::create([
            'code' => TemplateCodeEnum::SELL_CONTRACT . '00001',
            'status' => SellContractStatusEnum::NEW,
            'amount' => 3000000,
            'expired_at' => now()->addDays(60),
            'proposal_id' => 3,
            'created_by' => 1,
            'customer_id' => 1,
        ]);
    
        SellContract::create([
            'code' => TemplateCodeEnum::SELL_CONTRACT . '00002',
            'status' => SellContractStatusEnum::NEW,
            'amount' => 5000000,
            'expired_at' => now()->addDays(90),
            'proposal_id' => 3,
            'created_by' => 1,
            'customer_id' => 1,
        ]);
    }
}
