<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ebook extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'file_path',
        'file_size',
        'format',
        'sample_content',
        'drm_watermark_enabled',
    ];

    protected function casts(): array
    {
        return [
            'sample_content' => 'array',
            'drm_watermark_enabled' => 'boolean',
        ];
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
