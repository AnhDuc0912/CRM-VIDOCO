<?php

namespace Modules\Customer\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerNotificationHistories extends Model
{
    protected $fillable = [
        'channel',
        'provider',
        'to',
        'template_id',
        'payload',
        'status',
        'error_code',
        'error_message',
        'response',
        'sent_at',
    ];

    protected $casts = [
        'payload'  => 'array',
        'response' => 'array',
        'sent_at'  => 'datetime',
    ];
}
