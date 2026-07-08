<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = ['footer_tagline', 'shipping_fee'];

    protected $casts = [
        'shipping_fee' => 'float',
    ];

    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }
}
