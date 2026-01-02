<?php

namespace Modules\Customer\database\seeders;

use Modules\Customer\Models\CustomerBankAccount;
use Illuminate\Database\Seeder;

class CustomerBankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CustomerBankAccount::create([
            'name' => 'Vietcombank',
            'account_number' => '1234567890',
            'account_name' => 'Nguyễn Văn A',
            'branch' => 'Hanoi',
            'customer_id' => 1,
        ]);
    }
}
