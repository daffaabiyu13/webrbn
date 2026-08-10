<?php

namespace App\Http\Requests\Admin;

use App\Support\UploadLimit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $projectId = $this->route('project')?->id;
        $limit = UploadLimit::forProducts();

        return [
            'title' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('projects', 'slug')->ignore($projectId)],
            'short_description' => ['nullable', 'string', 'max:1000'],
            'short_description_en' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:' . $limit->maxKb()],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['integer'],
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
            'images.*.uploaded' => "Salah satu gambar gagal diunggah — kemungkinan lebih besar dari batas server (max {$human}).",
            'images.*.max' => "Salah satu gambar terlalu besar. Maksimal {$human}.",
            'images.*.image' => 'Salah satu file bukan gambar yang valid.',
            'images.*.mimes' => 'Salah satu gambar harus JPG/PNG/WebP.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_published' => $this->boolean('is_published'),
        ]);
    }
}
