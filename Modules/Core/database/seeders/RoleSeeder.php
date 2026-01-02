<?php

namespace Modules\Core\database\seeders;

use Modules\Core\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa cache roles
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = [
            RoleEnum::IT,
            RoleEnum::CEO,
            RoleEnum::HR,
            RoleEnum::BUSINESS,
            RoleEnum::ACCOUNTANT,
            RoleEnum::EMPLOYEE,
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }
    }
}
