<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
        'order_type',
        'order_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderServiceFees() {
        return $this->hasMany(OrderServiceFee::class);
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasOne(Payment::class);
    }

    public function rentalInfos() {
        return $this->hasOne(RentalInfo::class);
    }

    /**
     * Get order total amount - uses total_amount column or calculates from orderItems
     */
    public function getOrderTotalAmountAttribute()
    {
        // Try to get from total_amount column first
        $value = $this->attributes['total_amount'] ?? null;
        
        if ($value !== null) {
            return $value;
        }
        
        // Otherwise calculate from order items
        return $this->orderItems->sum(function ($item) {
            $qty = $item->order_item_quantity ?? $item->quantity ?? 1;
            $price = $item->order_item_price ?? $item->price ?? 0;
            return $qty * $price;
        });
    }

    /**
     * Get calculated total from order items
     */
    public function getCalculatedTotalAttribute()
    {
        return $this->orderItems->sum(function ($item) {
            $qty = $item->order_item_quantity ?? $item->quantity ?? 1;
            $price = $item->order_item_price ?? $item->price ?? 0;
            return $qty * $price;
        });
    }

    protected static function booted()
    {
        // When order_status changes into a successful state, increment vendor totals.
        static::updated(function (self $order) {
            if (! $order->isDirty('order_status')) {
                return;
            }

            $old = $order->getOriginal('order_status');
            $new = $order->order_status;
            $successful = ['paid', 'completed'];

            // Only act when transitioning from non-successful -> successful
            if (in_array($new, $successful) && ! in_array($old, $successful)) {
                $perVendor = [];
                foreach ($order->orderItems as $oi) {
                    $vendor = $oi->item->vendor ?? null;
                    if (! $vendor) continue;
                    $vid = $vendor->id;
                    $lineTotal = ($oi->order_item_price ?? 0) * ($oi->order_item_quantity ?? 1);
                    if (! isset($perVendor[$vid])) {
                        $perVendor[$vid] = 0;
                    }
                    $perVendor[$vid] += $lineTotal;
                }

                foreach ($perVendor as $vid => $amount) {
                    $vendor = \App\Models\Vendor::find($vid);
                    if (! $vendor) continue;
                    $vendor->addTotals((int) $amount, 1);
                }
            }
        });
    }
}
