<?php

namespace Modules\Work\Models;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Customer\Models\Customer;
use Modules\Employee\Models\Employee;
use Modules\Project\Models\Project;
use Carbon\Carbon;

class Work extends Model
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

    public function from_user()
    {
        return $this->belongsTo(Employee::class, 'from_user');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function children()
    {
        return $this->hasMany(Work::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Work::class, 'parent_id');
    }

    public function getUserEmployeesAttribute()
    {
        $ids = json_decode($this->to_user ?? '[]', true);
        return Employee::whereIn('id', $ids)->get();
    }

    public function getFollowEmployeesAttribute()
    {
        $ids = json_decode($this->follow_id ?? '[]', true);
        return Employee::whereIn('id', $ids)->get();
    }

    public function reports()
    {
        return $this->hasMany(WorkReport::class, 'work_id', 'id');
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
