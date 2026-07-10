<?php

namespace App\Models;

use App\Models\Concerns\OptimizesUploadedImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleImage extends Model
{
    use HasFactory, OptimizesUploadedImage;

    protected $fillable = [
        'article_id',
        'image_path',
        'color',
        'quantity',
        'sort_order'
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'quantity' => 'integer',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
    
    public function getImageUrlAttribute()
    {
        if (!$this->image_path) {
            return null;
        }
        
        // For WAMP, we need to use the direct path
        $path = str_replace('public/', '', $this->image_path);
        return '/storage/' . $path;
    }
}
