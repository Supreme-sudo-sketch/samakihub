<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'category',
        'freshness',
        'price_per_kg',
        'stock_kg',
        'is_available',
        'catch_date',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'price_per_kg' => 'decimal:2',
            'stock_kg'     => 'decimal:2',
            'is_available' => 'boolean',
            'catch_date'   => 'date',
        ];
    }

    const FRESHNESS_LABELS = [
        'super_fresh' => 'Super Fresh',
        'fresh'       => 'Fresh',
        'smoked'      => 'Smoked',
        'dried'       => 'Dried',
    ];

    public function getFreshnessLabelAttribute(): string
    {
        return self::FRESHNESS_LABELS[$this->freshness] ?? ucfirst($this->freshness);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/'.$this->image) : null;
    }

    // Relationships
    public function producer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->latest();
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function getAvgRatingAttribute(): float
    {
        if (isset($this->attributes['reviews_avg_rating'])) {
            return round($this->attributes['reviews_avg_rating'] ?? 0, 1);
        }

        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function getReviewCountAttribute(): int
    {
        if (isset($this->attributes['reviews_count'])) {
            return (int) $this->attributes['reviews_count'];
        }

        return $this->reviews()->count();
    }
}
