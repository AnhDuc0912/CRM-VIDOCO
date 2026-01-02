<?php

namespace Modules\Comment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Employee\Models\Employee;
use Modules\Project\Models\Project;
use Modules\User\Models\User;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'parent_id',
        'commentable_id',
        'commentable_type',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(Employee::class,'user_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class,'commentable_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('user', 'replies');
    }

    public function commentable()
    {
        return $this->morphTo();
    }
}



