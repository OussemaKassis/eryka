<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Command extends Model
{
    use HasFactory;

    public const SHIPPING_FEE = 7.00;

    protected $fillable = [
        'group_id',
        'article_id',
        'quantity',
        'shipping_fee',
        'color',
        'customer_first_name',
        'customer_last_name',
        'address',
        'city',
        'email',
        'phone_number',
    ];

    protected $casts = [
        'shipping_fee' => 'float',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
