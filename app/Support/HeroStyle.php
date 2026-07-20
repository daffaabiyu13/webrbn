<?php

namespace App\Support;

use App\Models\Setting;

class HeroStyle
{
    public static function forPage(string $page): array
    {
        $background = Setting::get("hero_background_{$page}");
        $backgroundUrl = null;
        if ($background) {
            $backgroundUrl = str_starts_with($background, 'http')
                ? $background
                : asset('storage/' . $background);
        }

        $color = self::normalizeHex(Setting::get("hero_overlay_color_{$page}", '#2D6A27'));
        $opacity = self::clampOpacity((int) Setting::get("hero_overlay_opacity_{$page}", 80));

        return [
            'background' => $backgroundUrl,
            'overlay_color' => $color,
            'overlay_opacity' => $opacity,
            'overlay_rgba' => self::rgba($color, $opacity),
        ];
    }

    public static function rgba(string $hex, int $opacity): string
    {
        $hex = self::normalizeHex($hex);
        $r = hexdec(substr($hex, 1, 2));
        $g = hexdec(substr($hex, 3, 2));
        $b = hexdec(substr($hex, 5, 2));
        $a = self::clampOpacity($opacity) / 100;

        return sprintf('rgba(%d, %d, %d, %s)', $r, $g, $b, rtrim(rtrim(number_format($a, 2, '.', ''), '0'), '.'));
    }

    public static function normalizeHex(?string $hex): string
    {
        $hex = trim((string) $hex);
        if (! preg_match('/^#[0-9a-fA-F]{6}$/', $hex)) {
            return '#2D6A27';
        }

        return strtoupper($hex);
    }

    public static function clampOpacity(int $opacity): int
    {
        return max(0, min(100, $opacity));
    }
}
