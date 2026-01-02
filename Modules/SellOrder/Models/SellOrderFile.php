<?php

namespace Modules\SellOrder\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SellOrderFile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'sell_order_id',
        'path',
        'name',
        'extension',
        'size',
    ];
}
