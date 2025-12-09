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
        // Use public/images/item/ storage convention
        if ($this->image_path) {
            return asset('images/item/' . ltrim($this->image_path, '/'));
        }

        return asset('images/item/default-image.png');
    }
}
