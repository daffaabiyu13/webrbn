<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title',
        'title_en',
        'slug',
        'category',
        'category_en',
        'client',
        'location',
        'year',
        'summary',
        'summary_en',
        'description',
        'description_en',
        'image',
        'is_featured',
        'position',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'year' => 'integer',
        'position' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function translated(string $field): mixed
    {
        if (app()->getLocale() === 'en') {
            $en = $this->{$field . '_en'} ?? null;
            if ($en !== null && $en !== '') {
                return $en;
            }
        }

        return $this->{$field};
    }

    public function imageUrl(): string
    {
        if (! $this->image) {
            return route('placeholder', ['name' => $this->title]);
        }
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        return asset('storage/' . $this->image);
    }

    /**
     * Render description as HTML — mirrors Product::formattedDescription so
     * admins can paste plain text with blank-line paragraph breaks.
     */
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
