<?php

namespace Modules\SellContract\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\SellContract\Database\Factories\SellContractFileFactory;

class SellContractFile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'sell_contract_id',
        'path',
        'name',
        'extension',
        'size',
    ];
}
