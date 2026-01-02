<?php

namespace Modules\Document\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Employee\Models\Employee;
use Modules\document\Models\Document;

class DocumentFile extends Model
{
    use HasFactory;

    protected $table = 'document_files';

    protected $fillable = [
        'file_path',
        'name',
        'extension',
        'size',
        'document_id',
        'user_id',
    ];

    /**
     * Liên kết với document
     */
    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function uploader()
    {
        return $this->belongsTo(Employee::class, 'user_id');
    }
}
