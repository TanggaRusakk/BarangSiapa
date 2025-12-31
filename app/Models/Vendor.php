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
    ];

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
