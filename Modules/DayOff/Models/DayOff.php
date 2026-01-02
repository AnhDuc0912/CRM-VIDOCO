<?php

namespace Modules\DayOff\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\User\Models\User;
use Modules\Employee\Models\Employee;

class DayOff extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'session',
        'type',
        'reason_type',
        'note',
        'status',
        'mode',
        'approved_by',
        'time',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i',
    ];

    // Label hiển thị cho dropdown, table, v.v.
    public static $typeLabels = [
        'ke_hoach' => 'Kế hoạch',
        'lam_viec_o_nha' => 'Làm việc ở nhà',
        'ngoai_le' => 'Ngoại lệ',
    ];

    public static $reasonLabels = [
        'tac_duong' => 'Tắc đường',
        'nghi_om' => 'Nghỉ ốm',
        'viec_khan_cap' => 'Việc khẩn cấp',
        'khac' => 'Khác',
    ];

    public static $modeLabels = [
        'den_muon' => 'Đến muộn',
        've_som' => 'Về sớm',
        'ra_ngoai' => 'Ra ngoài',
    ];

    public static $statusLabels = [
        'pending' => 'Chờ duyệt',
        'approved' => 'Đã duyệt',
        'rejected' => 'Từ chối',
    ];

    // Accessors cho hiển thị dễ hiểu
    public function getTypeLabelAttribute()
    {
        return self::$typeLabels[$this->type] ?? ucfirst($this->type);
    }

    public function getReasonLabelAttribute()
    {
        return self::$reasonLabels[$this->reason_type] ?? ucfirst($this->reason_type);
    }

    public function getModeLabelAttribute()
    {
        return self::$modeLabels[$this->mode] ?? ucfirst($this->mode);
    }

    public function getStatusLabelAttribute()
    {
        return self::$statusLabels[$this->status] ?? ucfirst($this->status);
    }

    // Quan hệ
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employee()
    {
        return $this->hasOneThrough(Employee::class, User::class, 'id', 'user_id', 'user_id', 'id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Helper
    public function isPending()
    {
        return $this->status === 'pending';
    }
}
