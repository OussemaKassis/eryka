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
        return $this->effective_quantity > 0;
    }

    /**
     * The real available stock: when colors have their own quantity set,
     * the sum of those colors' quantities is used and the "Quantité en
     * stock" field is ignored; otherwise that field is the reference.
     */
    public function getEffectiveQuantityAttribute(): int
    {
        $colorImages = $this->images->whereNotNull('color');

        if ($colorImages->isNotEmpty()) {
            return (int) $colorImages->sum('quantity');
        }

        return (int) $this->quantity;
    }

    /**
     * Stock for a specific color (or the article's effective stock when no
     * color is given / the article has no color variants).
     */
    public function quantityForColor(?string $color): int
    {
        $colorImages = $this->images->whereNotNull('color');

        if ($colorImages->isEmpty()) {
            return (int) $this->quantity;
        }

        if ($color === null) {
            return (int) $colorImages->sum('quantity');
        }

        $image = $colorImages->firstWhere('color', $color);

        return $image ? (int) $image->quantity : 0;
    }
}
