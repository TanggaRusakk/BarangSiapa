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
            $path = public_path('images/vendor/' . ltrim($this->logo_path, '/'));
            if (file_exists($path)) {
                return asset('images/vendor/' . ltrim($this->logo_path, '/'));
            }
        }

        if (file_exists(public_path('images/vendor/vendor_placeholder.jpg'))) {
            return asset('images/vendor/vendor_placeholder.jpg');
        }

        return asset('images/vendor/vendor_placeholder.png');
    }
}
