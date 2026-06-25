<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageHero extends Model
{
    use HasFactory;

    protected $fillable = ['page_key', 'title', 'subtitle', 'image_path'];

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value === null ? null : ucfirst($value);
    }

    public function setSubtitleAttribute($value)
    {
        $this->attributes['subtitle'] = $value === null ? null : ucfirst($value);
    }

    public function slides()
    {
        return $this->hasMany(HeroSlide::class, 'page_key', 'page_key')->where('is_active', true)->orderBy('sort_order');
    }
}
