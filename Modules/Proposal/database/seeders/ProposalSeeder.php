<?php

namespace Modules\Proposal\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Enums\TemplateCodeEnum;
use Modules\Proposal\Enums\ProposalStatusEnum;
use Modules\Proposal\Models\Proposal;

class ProposalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Proposal::create([
            'code' => TemplateCodeEnum::PROPOSAL . '00001',
            'status' => ProposalStatusEnum::NEW,
            'amount' => 1000000,
            'expired_at' => now()->addDays(30),
            'created_by' => 1,
            'customer_id' => 1,
        ]);
    }
}
