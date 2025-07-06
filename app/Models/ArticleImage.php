<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'image_path',
        'sort_order'
    ];

    protected $casts = [
        'sort_order' => 'integer',
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
