<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'detail',
        'category_id',
        'image',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function commands()
    {
        return $this->hasMany(Command::class);
    }

    public function images()
    {
        return $this->hasMany(ArticleImage::class)->orderBy('sort_order');
    }

    public function getMainImageAttribute()
    {
        return $this->images->first()->image_path ?? $this->image;
    }
}
