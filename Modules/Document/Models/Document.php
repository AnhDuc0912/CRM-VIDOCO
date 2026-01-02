<?php

namespace Modules\Document\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Collection;
use Modules\Document\Models\DocumentStructure;
use Modules\Employee\Models\Employee;

class Document extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'structures'      => 'array',
        'recipients'      => 'array',
        'sender'          => 'array',
        'to_internals'    => 'array',
        'followers'    => 'array',
        'receivers'       => 'array',
        'issue_date'      => 'date',
        'effective_date'  => 'date',
        'expiration_date' => 'date',
    ];

    public function structure($type)
    {
        $rawStructures = $this->structures;

        if ($rawStructures instanceof Collection) {
            $ids = $rawStructures->pluck('id')->toArray();
        } elseif (is_array($rawStructures)) {
            $ids = $rawStructures;
        } elseif (is_string($rawStructures)) {
            $decoded = json_decode($rawStructures, true);
            $ids = is_array($decoded) ? $decoded : explode(',', $rawStructures);
        } else {
            $ids = [];
        }

        $ids = array_map('intval', $ids);

        if (empty($ids)) {
            return null;
        }

        return DocumentStructure::whereIn('id', $ids)
            ->get()
            ->first(fn($item) => strtolower($item->type) === strtolower($type));
    }




    public function getStructuresAttribute($value)
    {

        $ids = is_array($value)
            ? $value
            : (is_string($value) ? json_decode($value, true) : []);

        $ids = array_map('intval', $ids);

        return DocumentStructure::whereIn('id', $ids)->get();
    }


    public function fromEmployee()
    {
        return $this->belongsTo(Employee::class, 'from_unit');
    }

    public function approver()
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

     public function files()
    {
        return $this->hasMany(DocumentFile::class, 'document_id', 'id');
    }
}
