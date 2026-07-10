<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class ImageOptimizer
{
    protected const MAX_WIDTH = 1920;

    protected const QUALITY = 78;

    protected const OPTIMIZABLE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

    public static function optimize(string $disk, ?string $relativePath): void
    {
        if (! $relativePath || ! Storage::disk($disk)->exists($relativePath)) {
            return;
        }

        $extension = strtolower(pathinfo($relativePath, PATHINFO_EXTENSION));

        if (! in_array($extension, self::OPTIMIZABLE_EXTENSIONS, true)) {
            return;
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

        $image->save($fullPath, self::QUALITY);
    }
}
