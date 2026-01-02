<?php

namespace Modules\Position\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $table = 'positions';
    protected $fillable = [
        'name',
        'description',
    ];

}
