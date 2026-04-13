<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'consumer_id',
        'producer_id',
        'question',
        'answer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function consumer()
    {
        return $this->belongsTo(User::class, 'consumer_id');
    }

    public function producer()
    {
        return $this->belongsTo(User::class, 'producer_id');
    }
}
