<?php

namespace App\Support;

class UploadLimit
{
    public static function forProducts(): self
    {
        return new self();
    }

    public static function forHeroBackground(): self
    {
        return new self();
    }

    public function maxKb(): int
    {
        $upload = $this->iniToBytes(ini_get('upload_max_filesize') ?: '2M');
        $post = $this->iniToBytes(ini_get('post_max_size') ?: '8M');

        return (int) floor(min($upload, $post) / 1024);
    }

    public function human(): string
    {
        $kb = $this->maxKb();

        return $kb >= 1024
            ? rtrim(rtrim(number_format($kb / 1024, 1), '0'), '.') . ' MB'
            : $kb . ' KB';
    }

    public function phpUpload(): string
    {
        return ini_get('upload_max_filesize') ?: '2M';
    }

    public function phpPost(): string
    {
        return ini_get('post_max_size') ?: '8M';
    }

    private function iniToBytes(string $value): int
    {
        $value = trim($value);
        if ($value === '') {
            return PHP_INT_MAX;
        }
        $unit = strtolower(substr($value, -1));
        $num = (int) $value;

        return match ($unit) {
            'g' => $num * 1024 * 1024 * 1024,
            'm' => $num * 1024 * 1024,
            'k' => $num * 1024,
            default => $num,
        };
    }
}
