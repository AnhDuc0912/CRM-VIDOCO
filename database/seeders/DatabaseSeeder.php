<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Category\database\seeders\CategoryDatabaseSeeder;
use Modules\Core\database\seeders\PermissionSeeder;
use Modules\Core\database\seeders\RoleSeeder;
use Modules\Customer\database\seeders\CustomerDatabaseSeeder;
use Modules\Department\database\seeders\DepartmentSeeder;
use Modules\Employee\database\seeders\EmployeeSeeder;
use Modules\Employee\database\seeders\BankAccountSeeder;
use Modules\Employee\database\seeders\ContractSeeder;
use Modules\Employee\database\seeders\SalarySeeder;
use Modules\Employee\database\seeders\AllowanceSeeder;
use Modules\Employee\database\seeders\BenefitSeeder;
use Modules\User\database\seeders\UserSeeder;
use Modules\Employee\database\seeders\DependentSeeder;
use Modules\Order\database\seeders\OrderDatabaseSeeder;
use Modules\Proposal\database\seeders\ProposalDatabaseSeeder;
use Modules\SellContract\database\seeders\SellContractSeeder;
use Modules\SellOrder\database\seeders\SellOrderSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User
        $this->call([
            UserSeeder::class,
        ]);

        // Role and Permission
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
        ]);

        // User
        $this->call([
            DepartmentSeeder::class,
            EmployeeSeeder::class,
            DependentSeeder::class,
            BankAccountSeeder::class,
            ContractSeeder::class,
            SalarySeeder::class,
            AllowanceSeeder::class,
            BenefitSeeder::class,
        ]);

        // // Category
        // $this->call([
        //     CategoryDatabaseSeeder::class,
        // ]);

        // // Customer
        // $this->call([
        //     CustomerDatabaseSeeder::class,
        // ]);

        // // Order
        // $this->call([
        //     OrderDatabaseSeeder::class,
        // ]);

        // // Proposal
        // $this->call([
        //     ProposalDatabaseSeeder::class,
        // ]);

        // // Sell Contract
        // $this->call([
        //     SellContractSeeder::class,
        // ]);

        // // Sell Order
        // $this->call([
        //     SellOrderSeeder::class,
        // ]);
    }
}
