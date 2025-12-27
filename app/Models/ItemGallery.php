<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ItemGallery extends Model
{
    /** @use HasFactory<\Database\Factories\ItemGalleryFactory> */
    use HasFactory;

    protected $fillable = [
        'item_id',
        'image_path',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * URL accessor for a gallery image. Falls back to a default item image.
     * Usage: $gallery->url
     */
    public function getUrlAttribute()
    {
        // Use public/images/items/ storage convention
        if ($this->image_path) {
            $path = public_path('images/items/' . ltrim($this->image_path, '/'));
            if (file_exists($path)) {
                return asset('images/items/' . ltrim($this->image_path, '/'));
            }
        }

        if (file_exists(public_path('images/items/item_placeholder.jpg'))) {
            return asset('images/items/item_placeholder.jpg');
        }

        return asset('images/items/item_placeholder.png');
    }
}
