<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'stripe_subscription_id',
        'stripe_price_id',
        'status',
        'starts_at',
        'ends_at',
        'canceled_at',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'canceled_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' &&
               $this->ends_at &&
               $this->ends_at->isFuture();
    }

    public function cancel(): void
    {
        $this->update([
            'status' => 'canceled',
            'canceled_at' => now(),
        ]);
    }

    public function getPriceAttribute(): float
    {
        return match($this->type) {
            'verified' => 2.00,
            'purple' => 5.00,
            default => 0.00,
        };
    }
}
