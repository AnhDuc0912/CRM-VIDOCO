<?php

namespace Modules\TimeKeeping\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Employee\Models\Employee;
use Carbon\Carbon;

class Timekeeping extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'employee_id',
        'check_in',
        'ip_check_in',
        'device_check_in',
        'check_out',
        'ip_check_out',
        'device_check_out'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function getLateAttribute()
    {
        if (!$this->check_in) return null;

        $checkin = \Carbon\Carbon::parse($this->check_in);
        $limit = $checkin->copy()->setTime(8, 35, 0);

        if ($checkin->lessThanOrEqualTo($limit)) {
            return null;
        }

        return $checkin->diff($limit)->format('%H:%I:%S');
    }

    public function getEarlyLeaveAttribute()
    {
        if (!$this->check_out) return null;

        $checkout = \Carbon\Carbon::parse($this->check_out);
        $limit = $checkout->copy()->setTime(17, 30, 0);

        if ($checkout->greaterThanOrEqualTo($limit)) {
            return null;
        }

        return $limit->diff($checkout)->format('%H:%I:%S');
    }
}
