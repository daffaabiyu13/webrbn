<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title',
        'title_en',
        'slug',
        'short_description',
        'short_description_en',
        'description',
        'description_en',
        'image',
        'client',
        'location',
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

    public function imageUrl(): string
    {
        if ($this->image) {
            return str_starts_with($this->image, 'http')
                ? $this->image
                : asset('storage/' . $this->image);
        }

        return route('placeholder', ['name' => $this->title]);
    }
}
