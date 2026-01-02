<?php

namespace Modules\Document\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Employee\Models\Employee;

class Notification extends Model
{
    protected $guarded = [];

    public function fromUser()
    {
        return $this->belongsTo(Employee::class, 'from_user');
    }

    public function toUser()
    {
        return $this->belongsTo(Employee::class, 'to_user');
    }

    public function isRead()
    {
        return !is_null($this->read_at);
    }
}

