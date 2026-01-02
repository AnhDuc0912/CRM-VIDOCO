<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Modules\Order\Models\OrderService;
use Modules\Order\Services\ZNSService;

class NotifyExpiringOrders extends Command
{
    protected $signature = 'zns:notify-expiring-orders';
    protected $description = 'Send ZNS notification for expiring services';

    public function handle(ZNSService $zns)
{
    $from = Carbon::now()->addDays(1)->startOfDay();
    $to   = Carbon::now()->addDays(15)->endOfDay();

    $services = OrderService::get();

    foreach ($services as $service) {

        $customer = $service->order?->customer;

        if (!$customer || !$customer->zalo) {
            continue;
        }

        $phone = preg_replace('/\D/', '', $customer->zalo);

        if (!preg_match('/^(0|\+84|84)\d{9}$/', $phone)) {
            continue;
        }

        $success = $zns->send(
            phone: $phone,
            templateId: '397939',
            params: [
                'customer_name' => "$customer->company_name",
                'customer_id'  => "$customer->id",
            ]
        );

        if (!$success) {
            \Log::warning('ZNS SEND FAILED', [
                'order_service_id' => $service->id,
                'phone' => $phone,
            ]);
        }
    }

    $this->info('ZNS notification job completed.');
}

}