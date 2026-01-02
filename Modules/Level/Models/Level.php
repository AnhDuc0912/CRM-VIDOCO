<?php

namespace Modules\Level\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Position\Models\Position;

class Level extends Model
{
    protected $table = 'levels';
    protected $fillable = [
        'name',
        'description',
    ];




}
