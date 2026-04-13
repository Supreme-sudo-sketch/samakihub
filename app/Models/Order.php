<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'consumer_id',
        'delivery_agent_id',
        'status',
        'payment_status',
        'total_amount',
        'delivery_fee',
        'delivery_address',
        'delivery_notes',
        'mpesa_phone',
        'mpesa_receipt_number',
        'checkout_request_id',
        'merchant_request_id',
        'pesapal_phone',
        'pesapal_transaction_id',
    ];

    // Status flow (pending_payment is pre-payment; not shown in step tracker)
    const STEPS = ['confirmed', 'preparing', 'in_transit', 'delivered'];

    const STATUS_LABELS = [
        'pending_payment' => 'Awaiting Payment',
        'confirmed'       => 'Confirmed',
        'preparing'       => 'Preparing',
        'in_transit'      => 'In Transit',
        'delivered'       => 'Delivered',
        'cancelled'       => 'Cancelled',
    ];

    const NEXT_STATUS = [
        'confirmed'  => 'preparing',
        'preparing'  => 'in_transit',
        'in_transit' => 'delivered',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst($this->status);
    }

    public function getStepIndexAttribute(): int
    {
        return array_search($this->status, self::STEPS) ?: 0;
    }

    // Relationships
    public function consumer()
    {
        return $this->belongsTo(User::class, 'consumer_id');
    }

    public function deliveryAgent()
    {
        return $this->belongsTo(User::class, 'delivery_agent_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function locations()
    {
        return $this->hasMany(DeliveryLocation::class);
    }

    public function latestLocation()
    {
        return $this->hasOne(DeliveryLocation::class)->latestOfMany();
    }
}
