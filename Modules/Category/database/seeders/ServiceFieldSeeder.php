<?php

namespace Modules\Category\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ServiceFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('service_fields')->insert([
            [
                'code' => 'MARKETING',
                'name' => 'Marketing',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'WEBSITE',
                'name' => 'Website',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
