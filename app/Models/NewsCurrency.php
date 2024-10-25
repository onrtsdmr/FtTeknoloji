<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsCurrency extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_id', 'currency_code', 'currency_title', 'currency_slug', 'currency_url'
    ];

    public function news()
    {
        return $this->belongsTo(News::class);
    }
}
