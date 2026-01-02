<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Employee\Models\Employee;
use Modules\User\Models\User;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'url',
        'is_read',
    ];

    public function user()
    {
        return $this->belongsTo(Employee::class);
    }
}
