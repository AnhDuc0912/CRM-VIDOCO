<?php

namespace Modules\Category\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Category\Database\Factories\CategoryFileFactory;

class CategoryFile extends Model
{
    use HasFactory;

    protected $table = 'category_files';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['category_id', 'file_path', 'extension'];

    // protected static function newFactory(): CategoryFileFactory
    // {
    //     // return CategoryFileFactory::new();
    // }
}
