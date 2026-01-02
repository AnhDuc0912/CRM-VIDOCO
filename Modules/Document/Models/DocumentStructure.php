<?php

namespace Modules\Document\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentStructure extends Model
{
    protected $fillable = ['name', 'type', 'parent_id'];

     public const TYPE_LABELS = [
        'storage'       => 'Kho lưu trữ',
        'content_group' => 'Nhóm nội dung',
        'folder'        => 'Thư mục',
        'book'          => 'Sổ văn bản',
    ];


    public function documents()
    {
        return $this->belongsToMany(
            Document::class,
            'document_structures'
        )->withPivot('type')->withTimestamps();
    }

    public function getTypeLabelAttribute()
    {
        return self::TYPE_LABELS[$this->type] ?? $this->type;
    }
}
