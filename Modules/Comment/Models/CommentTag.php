<?php

namespace Modules\Comment\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Employee\Models\Employee;
use Modules\User\Models\User;

class CommentTag extends Model
{
    protected $fillable = [
        'comment_id',
        'tagged_user_id',
    ];

    public function user()
    {
        return $this->belongsTo(Employee::class, 'tagged_user_id');
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}
