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
        'slug',
        'short_description',
        'full_description',
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
