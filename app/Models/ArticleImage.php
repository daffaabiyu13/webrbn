<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleImage extends Model
{
    protected $fillable = [
        'article_id',
        'path',
        'position',
    ];

    protected $casts = [
        'position' => 'integer',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function url(): string
    {
        return str_starts_with($this->path, 'http')
            ? $this->path
            : asset('storage/' . $this->path);
    }
}
