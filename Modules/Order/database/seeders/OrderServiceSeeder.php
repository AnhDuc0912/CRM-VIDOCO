<?php

namespace Modules\Order\database\seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Modules\Order\Models\OrderService;

class OrderServiceSeeder extends Seeder
{
    public function run()
    {
        OrderService::create([
            'order_id' => 1,
            'service_id' => 2,
            'product_id' => 1,
            'domain' => 'google.com',
            'quantity' => 1,
            'discount_amount' => 0,
            'total_price' => 100000,
            'status' => 1,
            'auto_email' => 1,
            'start_date' => '2021-01-01',
            'end_date' => '2021-01-01',
            'notes' => 'Lời nhắn',
        ]);
        OrderService::create([
            'order_id' => 1,
            'service_id' => 1,
            'product_id' => 2,
            'domain' => 'google.com',
            'quantity' => 1,
            'discount_amount' => 0,
            'total_price' => 100000,
            'status' => 1,
            'auto_email' => 1,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addMonths(30),
            'notes' => 'Lời nhắn',
        ]);
    }
}
