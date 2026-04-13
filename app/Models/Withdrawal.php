<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdrawal extends Model
{
    protected $fillable = ['producer_id', 'amount', 'status', 'phone', 'notes', 'admin_note', 'processed_at'];

    protected $casts = ['processed_at' => 'datetime', 'amount' => 'decimal:2'];

    public function producer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'producer_id');
    }

    public function statusBadge(): string
    {
        return match ($this->status) {
            'approved' => 'badge-green',
            'rejected' => 'badge-red',
            default    => 'badge-yellow',
        };
    }
}
