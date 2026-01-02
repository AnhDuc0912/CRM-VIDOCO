<?php

namespace Modules\Project\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Employee\Models\Employee;
use Modules\Project\Models\Project;
use Modules\Work\Models\Work;

class ProjectFile extends Model
{
    use HasFactory;

    protected $table = 'project_files';

    protected $fillable = [
        'file_path',
        'name',
        'extension',
        'size',
        'project_id',
        'work_report_id',
        'user_id',
    ];

    /**
     * Liên kết với Project
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
     public function work()
    {
        return $this->belongsTo(Work::class, 'work_id');
    }

    public function uploader()
    {
        return $this->belongsTo(Employee::class, 'user_id');
    }
}
