<?php

namespace Modules\Core\database\seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Modules\Core\Enums\PermissionEnum;
use Modules\Core\Enums\RoleEnum;
use Modules\User\Models\User;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa cache permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Permission::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $permissions = [
            // Dashboard
            PermissionEnum::DASHBOARD_VIEW,

            // Authorization
            PermissionEnum::AUTHORIZATION_VIEW,
            PermissionEnum::AUTHORIZATION_UPDATE,

            // Employee
            ...PermissionEnum::getEmployeePermissions(),

            // Category
            ...PermissionEnum::getCategoryPermissions(),

            //Service
            ...PermissionEnum::getServicePermissions(),

            // Customer
            ...PermissionEnum::getCustomerPermissions(),

            // Order
            ...PermissionEnum::getOrderPermissions(),

            // Proposal
            ...PermissionEnum::getProposalPermissions(),

            // Sell Contract
            ...PermissionEnum::getSellContractPermissions(),

            // Sell Order
            ...PermissionEnum::getSellOrderPermissions(),

            // Project
            ...PermissionEnum::getProjectPermissions(),

            // Work
            ...PermissionEnum::getWorkPermissions(),

            // Comment
            ...PermissionEnum::getCommentPermissions(),

            // Day Off
            ...PermissionEnum::getDayOffPermissions(),
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $ceo = User::where('email', 'vinh.lethuc@vidoco.vn')->first();
        $ceo->syncPermissions(Permission::all());
        $ceo->assignRole(RoleEnum::CEO);

        $employee = User::where('email', 'nhi.lengocyen@vidoco.vn')->first();
        $employee->syncPermissions(PermissionEnum::getCustomerPermissions());
        $employee->assignRole(RoleEnum::ACCOUNTANT);

        $employee = User::where('email', 'ketoan@vidoco.vn')->first();
        $employee->syncPermissions(Permission::all());
        $employee->assignRole(RoleEnum::ACCOUNTANT);
    }
}
