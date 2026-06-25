<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Command extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'article_id',
        'quantity',
        'color',
        'customer_first_name',
        'customer_last_name',
        'address',
        'city',
        'email',
        'phone_number',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
