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
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:' . $limit->maxKb()],
            'url' => ['nullable', 'url', 'max:2048'],
            'client' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'project_size' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer', 'between:1900,2100'],
            'position' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        $limit = UploadLimit::forProducts();
        $human = $limit->human();

        return [
            'image.uploaded' => "Gambar gagal diunggah — kemungkinan lebih besar dari batas server (max {$human}).",
            'image.max' => "Ukuran gambar terlalu besar. Maksimal {$human}.",
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
