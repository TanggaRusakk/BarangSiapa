<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /** @use HasFactory<\Database\Factories\ItemFactory> */
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'item_name',
        'item_description',
        'item_type',
        'item_price',
        'item_status',
        'rental_duration_unit',
        'rental_duration_value',
        'item_stock',
    ];

    public function vendor() {
        return $this->belongsTo(Vendor::class);
    }

    public function itemCategories() {
        return $this->hasMany(ItemCategory::class);
    }

    public function categories() {
        return $this->belongsToMany(Category::class, 'item_categories', 'item_id', 'category_id');
    }

    public function galleries() {
        return $this->hasMany(ItemGallery::class);
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }

    public function ad() {
        return $this->hasMany(Ad::class);
    }

    public function itemGalleries() {
        return $this->hasMany(ItemGallery::class);
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }

    /**
     * First image URL for the item. Returns the first related gallery image's
     * URL accessor or a default image when none exist.
     * Usage: $item->first_image_url
     */
    public function getFirstImageUrlAttribute()
    {
        $first = $this->itemGalleries()->first();
        if ($first && $first->image_path) {
            // Try products folder (new standard path)
            $pathProducts = public_path('images/products/' . ltrim($first->image_path, '/'));
            if (file_exists($pathProducts)) {
                return asset('images/products/' . ltrim($first->image_path, '/'));
            }
            // Try items folder (legacy)
            $pathItems = public_path('images/items/' . ltrim($first->image_path, '/'));
            if (file_exists($pathItems)) {
                return asset('images/items/' . ltrim($first->image_path, '/'));
            }
            // Try item folder (singular - old legacy)
            $pathItem = public_path('images/item/' . ltrim($first->image_path, '/'));
            if (file_exists($pathItem)) {
                return asset('images/item/' . ltrim($first->image_path, '/'));
            }
        }

        // Prefer products placeholder
        if (file_exists(public_path('images/products/product_placeholder.jpg'))) {
            return asset('images/products/product_placeholder.jpg');
        }

        return asset('images/items/item_placeholder.jpg');
    }
}
