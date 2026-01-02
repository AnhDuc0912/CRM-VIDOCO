<?php

namespace Modules\Log\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Employee\Models\Employee;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'module',
        'action',
        'description',
        'target_id',
        'target_type',
    ];

    public function user()
    {
        return $this->belongsTo(Employee::class);
    }

    public function target()
    {
        return $this->morphTo();
    }
}
