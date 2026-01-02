<?php

namespace Modules\ProjectCategory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Employee\Models\Employee;
use Modules\Work\Models\Work;

class ProjectCategory extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = ['id'];

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

     public function works()
    {
        return $this->hasMany(Work::class, 'group_id');
    }


}
