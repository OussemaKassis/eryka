<?php

namespace App\Models\Concerns;

use App\Support\ImageOptimizer;

trait OptimizesUploadedImage
{
    public static function bootOptimizesUploadedImage(): void
    {
        static::created(function ($model) {
            self::optimizeAndPersist($model);
        });

        static::updated(function ($model) {
            if ($model->wasChanged('image_path')) {
                self::optimizeAndPersist($model);
            }
        });
    }

    protected static function optimizeAndPersist($model): void
    {
        if (! $model->image_path) {
            return;
        }

        $newPath = ImageOptimizer::optimize('public', $model->image_path);

        if ($newPath !== $model->image_path) {
            $model->updateQuietly(['image_path' => $newPath]);
        }
    }
}
