<?php

namespace App\Models;

use App\Models\Concerns\OptimizesUploadedImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsItem extends Model
{
    use HasFactory, OptimizesUploadedImage;

    protected $fillable = ['title', 'description', 'image_path', 'link_url', 'sort_order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
