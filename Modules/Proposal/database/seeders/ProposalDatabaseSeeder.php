<?php

namespace Modules\Proposal\database\seeders;

use Illuminate\Database\Seeder;

class ProposalDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            ProposalSeeder::class,
        ]);
    }
}
