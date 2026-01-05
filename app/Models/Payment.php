<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'midtrans_order_id',
        'payment_method',
        'payment_type',
        'payment_total_amount',
        'payment_dp_amount',
        'payment_dp_paid',
        'payment_status',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if this is a DP payment that has been paid and needs remaining 70% payment
     */
    public function needsRemainingPayment(): bool
    {
        return $this->payment_type === 'dp' 
            && in_array($this->payment_status, ['settlement', 'capture', 'success'])
            && !$this->payment_dp_paid;
    }

    /**
     * Calculate remaining amount to be paid (70% of order total)
     */
    public function getRemainingAmount(): float
    {
        if (!$this->needsRemainingPayment() || !$this->order) {
            return 0;
        }

        $orderTotal = $this->order->total_amount ?? $this->order->order_total_amount ?? 0;
        return round($orderTotal * 0.70);
    }
}
