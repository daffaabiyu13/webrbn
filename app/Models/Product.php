<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'product_category_id',
        'name',
        'slug',
        'short_description',
        'full_description',
        'image',
        'specifications',
        'features',
        'is_featured',
    ];

    protected $casts = [
        'specifications' => 'array',
        'features' => 'array',
        'is_featured' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function imageUrl(): string
    {
        if ($this->image) {
            return str_starts_with($this->image, 'http')
                ? $this->image
                : asset('storage/' . $this->image);
        }

        return $this->placeholderImage();
    }

    protected function placeholderImage(): string
    {
        $label = trim($this->name);
        if (mb_strlen($label) > 22) {
            $label = mb_substr($label, 0, 20) . '..';
        }
        $label = htmlspecialchars($label, ENT_QUOTES | ENT_XML1, 'UTF-8');

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 400" preserveAspectRatio="xMidYMid slice">'
            . '<defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1">'
            . '<stop offset="0%" stop-color="#2D6A27"/>'
            . '<stop offset="100%" stop-color="#1e4d1b"/>'
            . '</linearGradient></defs>'
            . '<rect width="600" height="400" fill="url(#g)"/>'
            . '<circle cx="500" cy="80" r="140" fill="#6DBE45" fill-opacity="0.18"/>'
            . '<circle cx="90" cy="340" r="110" fill="#6DBE45" fill-opacity="0.12"/>'
            . '<text x="300" y="205" font-family="Helvetica,Arial,sans-serif" font-size="46" font-weight="700" fill="#ffffff" text-anchor="middle">' . $label . '</text>'
            . '<text x="300" y="245" font-family="Helvetica,Arial,sans-serif" font-size="16" font-weight="500" fill="#ffffff" fill-opacity="0.7" letter-spacing="2" text-anchor="middle">PT. RADIKA BINTANG NUSANTARA</text>'
            . '</svg>';

        return 'data:image/svg+xml;charset=UTF-8,' . rawurlencode($svg);
    }
}
