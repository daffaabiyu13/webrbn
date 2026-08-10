<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    protected $fillable = [
        'title',
        'title_en',
        'slug',
        'short_description',
        'short_description_en',
        'description',
        'description_en',
        'url',
        'year',
        'position',
        'is_published',
    ];

    protected $casts = [
        'year' => 'integer',
        'position' => 'integer',
        'is_published' => 'boolean',
    ];

    public function translated(string $field): mixed
    {
        if (app()->getLocale() === 'en') {
            $enField = $field . '_en';
            $en = $this->{$enField} ?? null;
            if ($en !== null && $en !== '') {
                return $en;
            }
        }

        return $this->{$field};
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function images(): HasMany
    {
        return $this->hasMany(ArticleImage::class)->orderBy('position')->orderBy('id');
    }

    public function primaryImage(): ?ArticleImage
    {
        return $this->images->first();
    }

    public function imageUrl(): string
    {
        $primary = $this->primaryImage();
        if ($primary) {
            return $primary->url();
        }

        return route('placeholder', ['name' => $this->title]);
    }

    public function formattedDescription(): string
    {
        $text = (string) ($this->translated('description') ?? '');
        if (trim($text) === '') {
            return '';
        }

        if (preg_match('/<(p|div|ul|ol|h[1-6]|section|article|br)\b/i', $text)) {
            return $text;
        }

        $paragraphs = preg_split('/\r\n{2,}|\r{2,}|\n{2,}/', trim($text));

        return collect($paragraphs)
            ->map(fn ($p) => '<p>' . nl2br(e(trim($p))) . '</p>')
            ->implode('');
    }
}
