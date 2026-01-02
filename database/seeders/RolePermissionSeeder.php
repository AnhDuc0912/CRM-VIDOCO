<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\database\seeders\PermissionSeeder;
use Modules\Core\database\seeders\RoleSeeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
        ]);
    }
}
