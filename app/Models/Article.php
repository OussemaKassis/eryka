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
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    protected $with = ['images'];

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = ucfirst($value);
    }

    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = $value === null ? null : ucfirst($value);
    }

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

    public function getInStockAttribute(): bool
    {
        return $this->quantity > 0;
    }
}
