<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    use HasFactory;

    protected $fillable = ['platform', 'url', 'sort_order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public const PLATFORM_ICONS = [
        'facebook' => 'fa-brands fa-facebook-f',
        'instagram' => 'fa-brands fa-instagram',
        'twitter' => 'fa-brands fa-twitter',
        'pinterest' => 'fa-brands fa-pinterest-p',
        'youtube' => 'fa-brands fa-youtube',
        'linkedin' => 'fa-brands fa-linkedin-in',
        'tiktok' => 'fa-brands fa-tiktok',
        'whatsapp' => 'fa-brands fa-whatsapp',
    ];

    public function getIconClassAttribute(): string
    {
        return self::PLATFORM_ICONS[$this->platform] ?? 'fa-solid fa-link';
    }
}
