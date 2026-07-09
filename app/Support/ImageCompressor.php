<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageCompressor
{
    public function __construct(
        private int $maxWidth = 1600,
        private int $maxHeight = 1600,
        private int $quality = 82,
    ) {
    }

    public static function forProducts(): self
    {
        return new self(1600, 1600, 82);
    }

    public static function forHeroBackground(): self
    {
        return new self(2400, 1600, 82);
    }

    public function storeCompressed(UploadedFile $file, string $disk, string $directory): string
    {
        if (! $this->gdAvailable()) {
            return $file->store($directory, $disk);
        }

        $image = $this->readImage($file->getRealPath(), $file->getMimeType());
        if (! $image) {
            return $file->store($directory, $disk);
        }

        $srcW = imagesx($image);
        $srcH = imagesy($image);
        [$dstW, $dstH] = $this->fitDimensions($srcW, $srcH);

        if ($dstW !== $srcW || $dstH !== $srcH) {
            $resized = imagecreatetruecolor($dstW, $dstH);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $dstW, $dstH, $srcW, $srcH);
            imagedestroy($image);
            $image = $resized;
        }

        $tmpPath = tempnam(sys_get_temp_dir(), 'rbn_img_') . '.jpg';
        if (! imagejpeg($image, $tmpPath, $this->quality)) {
            imagedestroy($image);
            @unlink($tmpPath);
            return $file->store($directory, $disk);
        }
        imagedestroy($image);

        $filename = Str::random(40) . '.jpg';
        $relativePath = trim($directory, '/') . '/' . $filename;
        Storage::disk($disk)->put($relativePath, file_get_contents($tmpPath));
        @unlink($tmpPath);

        return $relativePath;
    }

    private function readImage(string $path, ?string $mime)
    {
        return match ($mime) {
            'image/jpeg', 'image/jpg' => function_exists('imagecreatefromjpeg') ? @imagecreatefromjpeg($path) : null,
            'image/png' => function_exists('imagecreatefrompng') ? $this->readPngFlattened($path) : null,
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : null,
            'image/gif' => function_exists('imagecreatefromgif') ? @imagecreatefromgif($path) : null,
            default => null,
        };
    }

    private function readPngFlattened(string $path)
    {
        $img = @imagecreatefrompng($path);
        if (! $img) {
            return null;
        }

        $w = imagesx($img);
        $h = imagesy($img);
        $bg = imagecreatetruecolor($w, $h);
        $white = imagecolorallocate($bg, 255, 255, 255);
        imagefilledrectangle($bg, 0, 0, $w, $h, $white);
        imagecopy($bg, $img, 0, 0, 0, 0, $w, $h);
        imagedestroy($img);

        return $bg;
    }

    private function fitDimensions(int $w, int $h): array
    {
        if ($w <= $this->maxWidth && $h <= $this->maxHeight) {
            return [$w, $h];
        }

        $ratio = min($this->maxWidth / $w, $this->maxHeight / $h);

        return [(int) round($w * $ratio), (int) round($h * $ratio)];
    }

    private function gdAvailable(): bool
    {
        if (! extension_loaded('gd')) {
            return false;
        }

        foreach ([
            'imagejpeg',
            'imagecreatetruecolor',
            'imagecopyresampled',
            'imagesx',
            'imagesy',
            'imagedestroy',
            'imagecolorallocate',
            'imagefilledrectangle',
            'imagecopy',
        ] as $fn) {
            if (! function_exists($fn)) {
                return false;
            }
        }

        return true;
    }
}
