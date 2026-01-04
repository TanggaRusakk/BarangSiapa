<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    /** @use HasFactory<\Database\Factories\VendorFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vendor_name',
        'location',
        'description',
        'logo_path',
        'total_revenue',
        'total_orders',
        'rating',
    ];

    /**
     * Recalculate and persist store rating (average of reviews across all vendor items)
     */
    public function recalcRating()
    {
        $avg = \App\Models\Review::whereHas('item', function ($q) {
            $q->where('vendor_id', $this->id ?? 0);
        })->avg('rating') ?? 0;

        $this->update(['rating' => round($avg, 1)]);
    }

    /**
     * Increment totals for this vendor by provided amount and order count.
     * Uses atomic DB increment to reduce race conditions.
     */
    public function addTotals(int $amount, int $orders = 1)
    {
        $this->increment('total_revenue', $amount);
        if ($orders !== 0) {
            $this->increment('total_orders', $orders);
        }
        $this->refresh();
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function items() {
        return $this->hasMany(Item::class);
    }

    public function getLogoUrlAttribute()
    {
        if ($this->logo_path) {
            // Try vendors folder
            $pathVendors = public_path('images/vendors/' . ltrim($this->logo_path, '/'));
            if (file_exists($pathVendors)) {
                return asset('images/vendors/' . ltrim($this->logo_path, '/'));
            }
            // Try vendor folder (singular - legacy)
            $path = public_path('images/vendor/' . ltrim($this->logo_path, '/'));
            if (file_exists($path)) {
                return asset('images/vendor/' . ltrim($this->logo_path, '/'));
            }
        }

        // Use products placeholder for vendor logo
        return asset('images/products/product_placeholder.jpg');
    }
}
