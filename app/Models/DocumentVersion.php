<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentVersion extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'version_number',
        'change_date',
        'file_url',
        'comment',
        'document_id',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'change_date' => 'datetime',
        ];
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}
