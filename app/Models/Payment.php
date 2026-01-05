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
        // Only show if this is a DP payment that has been paid
        if ($this->payment_type !== 'dp' || !in_array($this->payment_status, ['settlement', 'capture', 'success'])) {
            return false;
        }

        // Check if payment_dp_paid flag is set (means already fully paid)
        if ($this->payment_dp_paid == 1 || $this->payment_dp_paid === true) {
            return false;
        }

        // Check if there's already a successful remaining payment
        $hasRemainingPayment = Payment::where('order_id', $this->order_id)
            ->where('payment_type', 'remaining')
            ->whereIn('payment_status', ['settlement', 'capture', 'success'])
            ->exists();

        return !$hasRemainingPayment;
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
