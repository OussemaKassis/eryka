<?php

namespace App\Models\Concerns;

use App\Support\ImageOptimizer;

trait OptimizesUploadedImage
{
    public static function bootOptimizesUploadedImage(): void
    {
        static::created(function ($model) {
            if ($model->image_path) {
                ImageOptimizer::optimize('public', $model->image_path);
            }
        });

        static::updated(function ($model) {
            if ($model->wasChanged('image_path') && $model->image_path) {
                ImageOptimizer::optimize('public', $model->image_path);
            }
        });
    }
}
