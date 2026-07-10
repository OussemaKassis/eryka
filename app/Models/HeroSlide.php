<?php

namespace App\Models;

use App\Models\Concerns\OptimizesUploadedImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    use HasFactory, OptimizesUploadedImage;

    protected $fillable = ['page_key', 'image_path', 'sort_order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
