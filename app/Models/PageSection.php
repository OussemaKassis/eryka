<?php

namespace App\Models;

use App\Models\Concerns\OptimizesUploadedImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    use HasFactory, OptimizesUploadedImage;

    protected $fillable = ['page_key', 'title', 'body', 'image_path', 'video_path', 'sort_order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
