<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class ImageOptimizer
{
    protected const MAX_WIDTH = 1920;

    protected const QUALITY = 78;

    protected const OPTIMIZABLE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

    /**
     * Resizes/compresses the image in place and returns the relative path
     * it ends up at. Opaque PNGs are converted to JPEG: GD's PNG encoder
     * ignores the quality setting entirely, so a lossless PNG photo can
     * never shrink via compression alone — only JPEG/WebP re-encoding
     * actually reduces photographic file size. The path changes (and the
     * caller must persist that) whenever this conversion happens.
     */
    public static function optimize(string $disk, ?string $relativePath): ?string
    {
        if (! $relativePath || ! Storage::disk($disk)->exists($relativePath)) {
            return $relativePath;
        }

        $extension = strtolower(pathinfo($relativePath, PATHINFO_EXTENSION));

        if (! in_array($extension, self::OPTIMIZABLE_EXTENSIONS, true)) {
            return $relativePath;
        }

        $fullPath = Storage::disk($disk)->path($relativePath);

        $manager = new ImageManager(['driver' => 'gd']);
        $image = $manager->make($fullPath);

        if ($image->width() > self::MAX_WIDTH) {
            $image->resize(self::MAX_WIDTH, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        if ($extension === 'png' && ! self::hasTransparency($image)) {
            $newRelativePath = preg_replace('/\.png$/i', '.jpg', $relativePath);
            $newFullPath = Storage::disk($disk)->path($newRelativePath);

            $image->save($newFullPath, self::QUALITY, 'jpg');
            Storage::disk($disk)->delete($relativePath);

            return $newRelativePath;
        }

        $image->save($fullPath, self::QUALITY);

        return $relativePath;
    }

    /**
     * Samples a grid of pixels rather than every pixel, since this only
     * needs to catch real transparency (icons/logos), not scan exhaustively.
     */
    protected static function hasTransparency($image): bool
    {
        $core = $image->getCore();
        $width = imagesx($core);
        $height = imagesy($core);

        $stepX = max(1, intdiv($width, 40));
        $stepY = max(1, intdiv($height, 40));

        for ($y = 0; $y < $height; $y += $stepY) {
            for ($x = 0; $x < $width; $x += $stepX) {
                $rgba = imagecolorat($core, $x, $y);
                $alpha = ($rgba >> 24) & 0x7F;

                if ($alpha > 0) {
                    return true;
                }
            }
        }

        return false;
    }
}
