<?php

namespace Modules\Work\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Employee\Models\Employee;
use Modules\Project\Models\ProjectFile;
use Modules\User\Models\User;

class WorkReport extends Model
{
    protected $fillable = ['work_id', 'user_id', 'content', 'report_date'];

    protected $casts = [
        'report_date' => 'datetime',
    ];

    const STATUS_LABELS = [
        1 => 'Chờ đọc',
        2 => 'Đã đọc',
        3 => 'Khen ngợi',
    ];

    public function work()
    {
        return $this->belongsTo(Work::class);
    }

    public function user()
    {
        return $this->belongsTo(Employee::class, 'user_id');
    }

     public function to_user()
    {
        return $this->belongsTo(Employee::class, 'to_user_id');
    }

    public function files()
    {
        return $this->hasMany(ProjectFile::class, 'work_report_id', 'id');
    }
}
