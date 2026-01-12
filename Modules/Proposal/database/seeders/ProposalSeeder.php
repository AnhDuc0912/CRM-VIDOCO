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
            'note' => 'Đề xuất dự án website',
            'expired_at' => now()->addDays(30),
            'created_by' => 1,
            'customer_id' => 1,
        ]);

        Proposal::create([
            'code' => TemplateCodeEnum::PROPOSAL . '00002',
            'status' => ProposalStatusEnum::NEGOTIATION,
            'amount' => 2000000,
            'note' => 'Đề xuất dự án mobile app',
            'expired_at' => now()->addDays(45),
            'created_by' => 1,
            'customer_id' => 1,
        ]);

        Proposal::create([
            'code' => TemplateCodeEnum::PROPOSAL . '00003',
            'status' => ProposalStatusEnum::APPROVED,
            'amount' => 3000000,
            'note' => 'Đề xuất dự án CRM',
            'expired_at' => now()->addDays(60),
            'created_by' => 1,
            'customer_id' => 1,
        ]);
    }
}
