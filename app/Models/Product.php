<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'product_category_id',
        'name',
        'name_en',
        'slug',
        'short_description',
        'short_description_en',
        'full_description',
        'full_description_en',
        'specifications',
        'specifications_en',
        'features',
        'features_en',
        'is_featured',
    ];

    protected $casts = [
        'specifications' => 'array',
        'specifications_en' => 'array',
        'features' => 'array',
        'features_en' => 'array',
        'is_featured' => 'boolean',
    ];

    public function translated(string $field): mixed
    {
        if (app()->getLocale() === 'en') {
            $enField = $field . '_en';
            $en = $this->{$enField} ?? null;
            if ($en !== null && $en !== '' && $en !== []) {
                return $en;
            }
        }

        return $this->{$field};
    }

    /**
     * Render full_description as HTML — accepts both plain text (Enter for
     * new paragraphs, double Enter for paragraph breaks) and legacy HTML
     * content that already contains <p>/<strong>/etc.
     */
    public function formattedDescription(): string
    {
        $text = (string) ($this->translated('full_description') ?? '');
        if (trim($text) === '') {
            return '';
        }

        // Already contains block-level HTML — trust it as-is.
        if (preg_match('/<(p|div|ul|ol|h[1-6]|section|article|br)\b/i', $text)) {
            return $text;
        }

        // Plain text: split on blank line(s) → paragraphs, single line break → <br>.
        $paragraphs = preg_split('/\r\n{2,}|\r{2,}|\n{2,}/', trim($text));

        return collect($paragraphs)
            ->map(fn ($p) => '<p>' . nl2br(e(trim($p))) . '</p>')
            ->implode('');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('position')->orderBy('id');
    }

    public function primaryImage(): ?ProductImage
    {
        return $this->images->first();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function imageUrl(): string
    {
        $primary = $this->primaryImage();
        if ($primary) {
            return $primary->url();
        }

        return $this->placeholderImage();
    }

    protected function placeholderImage(): string
    {
        return route('placeholder', ['name' => $this->name]);
    }
}
