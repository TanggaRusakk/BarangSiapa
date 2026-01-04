<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    /** @use HasFactory<\Database\Factories\ReviewFactory> */
    use HasFactory;

    protected $fillable = [
        'item_id',
        'user_id',
        'rating',
        'comment',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function item() {
        return $this->belongsTo(Item::class);
    }

    protected static function booted()
    {
        // After save (created or updated) recalc vendor rating
        static::saved(function (self $review) {
            try {
                if ($review->item && $review->item->vendor) {
                    $review->item->vendor->recalcRating();
                }
            } catch (\Exception $e) {
                // avoid breaking save flow
                \Log::warning('Failed to recalc vendor rating on review saved: ' . $e->getMessage());
            }
        });

        // After delete recalc vendor rating
        static::deleted(function (self $review) {
            try {
                if ($review->item && $review->item->vendor) {
                    $review->item->vendor->recalcRating();
                }
            } catch (\Exception $e) {
                \Log::warning('Failed to recalc vendor rating on review deleted: ' . $e->getMessage());
            }
        });
    }
}
