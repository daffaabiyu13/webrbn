<?php

namespace App\Http\Requests\Admin;

use App\Support\UploadLimit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $articleId = $this->route('article')?->id;
        $limit = UploadLimit::forProducts();

        return [
            'title' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('articles', 'slug')->ignore($articleId)],
            'short_description' => ['nullable', 'string', 'max:1000'],
            'short_description_en' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'url' => ['nullable', 'url', 'max:2048'],
            'year' => ['nullable', 'integer', 'between:1900,2100'],
            'position' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['sometimes', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:' . $limit->maxKb()],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['integer'],
        ];
    }

    public function messages(): array
    {
        $limit = UploadLimit::forProducts();
        $human = $limit->human();

        return [
            'images.*.uploaded' => "Salah satu gambar gagal diunggah — kemungkinan lebih besar dari batas server (max {$human}).",
            'images.*.max' => "Salah satu gambar terlalu besar. Maksimal {$human}.",
            'images.*.image' => 'Salah satu file bukan gambar yang valid.',
            'images.*.mimes' => 'Salah satu gambar harus JPG/PNG/WebP.',
            'url.url' => 'URL harus lengkap, misalnya https://contoh.com/artikel.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_published' => $this->boolean('is_published'),
        ]);
    }
}
