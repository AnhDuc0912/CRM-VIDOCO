<?php

namespace Modules\Project\Models;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Comment\Models\Comment;
use Modules\Customer\Models\Customer;
use Modules\Employee\Models\Employee;
use Modules\ProjectCategory\Models\ProjectCategory;
use Modules\Work\Models\Work;
use Carbon\Carbon;
use Modules\Log\Models\Log;

class Project extends Model
{
    use HasFactory, CreatedUpdatedBy;
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'complete_date' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = ['id'];

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function files()
    {
        return $this->hasMany(ProjectFile::class, 'project_id', 'id');
    }

    public function works()
    {
        return $this->hasMany(Work::class);
    }

    public function categories()
    {
        return $this->hasMany(ProjectCategory::class);
    }

    public function getAssigneeEmployeesAttribute()
    {
        $ids = json_decode($this->assignees ?? '[]', true);
        return Employee::whereIn('id', $ids)->get();
    }

    public function getFollowEmployeesAttribute()
    {
        $ids = json_decode($this->follow_id ?? '[]', true);
        return Employee::whereIn('id', $ids)->get();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'commentable_id', 'id')->where('commentable_type', 'project');
    }

    public function logs()
    {
        return $this->morphMany(Log::class, 'target');
    }


    public function getStatusBadgeAttribute()
    {
        $status = $this->statuses[$this->status] ?? [
            'label' => '',
            'color' => '',
        ];

        $completeDate = $this->complete_date ? Carbon::parse($this->complete_date) : null;
        $endDate = $this->end_date ? Carbon::parse($this->end_date) : null;
        $progress = $this->progress ?? 0;
        $today = Carbon::today();

        if ($progress == 100) {
            if (!$completeDate) {
                return ['label' => '', 'color' => ''];
            } elseif ($endDate && $completeDate->lte($endDate)) {
                return ['label' => 'Hoàn thành đúng tiến độ', 'color' => 'success'];
            } else {
                return ['label' => 'Hoàn thành trễ tiến độ', 'color' => 'danger'];
            }
        }

        if ($endDate && $today->gt($endDate)) {
            return ['label' => 'Trễ tiến độ', 'color' => 'danger'];
        }

        return $status;
    }
}
