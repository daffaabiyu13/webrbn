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
        return route('placeholder', ['name' => $this->name]);
    }
}
