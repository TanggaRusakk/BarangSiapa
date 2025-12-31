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
     * Usage: $gallery->url or $gallery->image_url
     */
    public function getUrlAttribute()
    {
        if ($this->image_path) {
            // Try products folder (new standard path)
            $pathProducts = public_path('images/products/' . ltrim($this->image_path, '/'));
            if (file_exists($pathProducts)) {
                return asset('images/products/' . ltrim($this->image_path, '/'));
            }
            // Try items folder (legacy)
            $pathItems = public_path('images/items/' . ltrim($this->image_path, '/'));
            if (file_exists($pathItems)) {
                return asset('images/items/' . ltrim($this->image_path, '/'));
            }
            // Try item folder (singular - old legacy)
            $pathItem = public_path('images/item/' . ltrim($this->image_path, '/'));
            if (file_exists($pathItem)) {
                return asset('images/item/' . ltrim($this->image_path, '/'));
            }
        }

        if (file_exists(public_path('images/products/product_placeholder.jpg'))) {
            return asset('images/products/product_placeholder.jpg');
        }

        return asset('images/items/item_placeholder.jpg');
    }

    /**
     * Alias for url attribute for backwards compatibility
     */
    public function getImageUrlAttribute()
    {
        return $this->url;
    }
}
