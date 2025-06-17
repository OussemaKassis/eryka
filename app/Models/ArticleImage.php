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

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
