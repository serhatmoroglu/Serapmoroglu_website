<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/** Yüklenen görselleri public/uploads/YYYY/MM altına kaydeder (gerekirse küçültür). */
class Uploads
{
    public const MAX_WIDTH = 1800;

    public static function image(UploadedFile $file): string
    {
        $dir = 'uploads/'.date('Y/m');
        @mkdir(public_path($dir), 0775, true);
        $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension());
        $name = Str::random(12).'.'.$ext;
        $dest = public_path("$dir/$name");
        $file->move(public_path($dir), $name);
        self::shrink($dest, $ext);
        return "$dir/$name";
    }

    public static function video(UploadedFile $file): string
    {
        $dir = 'uploads/'.date('Y/m');
        @mkdir(public_path($dir), 0775, true);
        $name = Str::random(12).'.'.strtolower($file->getClientOriginalExtension() ?: 'mp4');
        $file->move(public_path($dir), $name);
        return "$dir/$name";
    }

    private static function shrink(string $path, string $ext): void
    {
        if (! function_exists('imagecreatefromjpeg') || ! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            return;
        }
        [$w, $h] = @getimagesize($path) ?: [0, 0];
        if ($w <= self::MAX_WIDTH || $w === 0) {
            return;
        }
        $src = match ($ext) {
            'jpg', 'jpeg' => @imagecreatefromjpeg($path),
            'png' => @imagecreatefrompng($path),
            'webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
        };
        if (! $src) {
            return;
        }
        $nh = (int) round($h * self::MAX_WIDTH / $w);
        $dst = imagecreatetruecolor(self::MAX_WIDTH, $nh);
        if ($ext !== 'jpg' && $ext !== 'jpeg') {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
        }
        imagecopyresampled($dst, $src, 0, 0, 0, 0, self::MAX_WIDTH, $nh, $w, $h);
        match ($ext) {
            'jpg', 'jpeg' => imagejpeg($dst, $path, 85),
            'png' => imagepng($dst, $path, 7),
            'webp' => imagewebp($dst, $path, 85),
        };
    }

    /** Sadece panelin yüklediği (uploads/) dosyalar silinebilir. */
    public static function forget(?string $path): void
    {
        if ($path && str_starts_with($path, 'uploads/') && ! str_contains($path, '..') && ! str_starts_with($path, 'uploads/eski/')) {
            @unlink(public_path($path));
        }
    }
}
