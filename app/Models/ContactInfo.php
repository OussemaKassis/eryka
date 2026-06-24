<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'label', 'value', 'sort_order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public const TYPE_ICONS = [
        'email' => 'fa-solid fa-envelope',
        'phone' => 'fa-solid fa-phone',
        'address' => 'fa-solid fa-location-dot',
    ];

    public function getIconClassAttribute(): string
    {
        return self::TYPE_ICONS[$this->type] ?? 'fa-solid fa-circle-info';
    }
}
