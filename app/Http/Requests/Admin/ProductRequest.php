<?php

namespace App\Http\Requests\Admin;

use App\Support\UploadLimit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $productId = $this->route('product')?->id;

        return [
            'product_category_id' => ['required', 'exists:product_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($productId)],
            'short_description' => ['required', 'string', 'max:500'],
            'full_description' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:' . UploadLimit::forProducts()->maxKb()],
            'specifications' => ['nullable', 'string'],
            'features' => ['nullable', 'string'],
            'is_featured' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        $limit = UploadLimit::forProducts();
        $human = $limit->human();

        return [
            'image.uploaded' => "Gambar gagal diunggah — kemungkinan besar file lebih besar dari batas server (max {$human}, upload_max_filesize={$limit->phpUpload()}, post_max_size={$limit->phpPost()}). Kompres gambarnya atau naikkan limit di php.ini.",
            'image.max' => "Ukuran gambar terlalu besar. Maksimal {$human}.",
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_featured' => $this->boolean('is_featured'),
        ]);
    }

    public function specificationsArray(): ?array
    {
        $raw = trim((string) $this->input('specifications'));
        if ($raw === '') {
            return null;
        }

        $result = [];
        foreach (preg_split('/\r\n|\r|\n/', $raw) as $line) {
            $line = trim($line);
            if ($line === '' || ! str_contains($line, ':')) {
                continue;
            }
            [$key, $value] = array_map('trim', explode(':', $line, 2));
            if ($key !== '') {
                $result[$key] = $value;
            }
        }

        return $result ?: null;
    }

    public function featuresArray(): ?array
    {
        $raw = trim((string) $this->input('features'));
        if ($raw === '') {
            return null;
        }

        $features = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $raw))));

        return $features ?: null;
    }
}
