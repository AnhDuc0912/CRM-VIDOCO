<?php

namespace Modules\Order\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\Customer\Models\CustomerNotificationHistories;

class ZNSService
{
    protected string $endpoint;
    protected string $from;

    public function __construct()
    {
        $this->endpoint = config('services.zns.endpoint');
        $this->from     = config('services.zns.from');
    }

    public function send(string $phone, string $templateId, array $params = []): bool
    {
        $phone = preg_replace('/^0/', '84', $phone);

        $payload = [
            'client_req_id' => (string) Str::uuid(),
            'from'          => (string) $this->from, 
            'to'            => $phone,
            'template_id'   => (string) $templateId,
            'template_data' => $params,
        ];

        $auth = base64_encode(
            config('services.zns.app_id') . ':' . config('services.zns.app_secret')
        );

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $auth,
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ])->post($this->endpoint, $payload);

        Log::info('ZNS RESPONSE', [
            'payload'  => $payload,
            'http'     => $response->status(),
            'response' => $response->json(),
        ]);

        return $response->ok()
            && ($response->json('status') ?? 0) == 1;
    }
}
