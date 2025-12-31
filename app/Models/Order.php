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
}
