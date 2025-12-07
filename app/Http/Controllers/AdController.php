<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class AdController extends Controller
{
    /**
     * Show the application landing page using Ads and Items.
     */
    public function index(Request $request)
    {
        // If there are active ads, show their related items as trending
        $ads = Ad::where('status', 'active')
            ->with('item.itemGalleries')
            ->latest()
            ->take(6)
            ->get();

        $trending = $ads->map(function ($ad) {
            return $ad->item;
        })->filter();

        // fallback to latest available items if not enough ads
        if ($trending->count() < 6) {
            $more = Item::where('item_status', 'available')
                ->with('itemGalleries')
                ->latest()
                ->take(6 - $trending->count())
                ->get();
            $trending = $trending->merge($more);
        }

        // items: for product grid
        $items = Item::where('item_status', 'available')
            ->with('itemGalleries')
            ->latest()
            ->take(12)
            ->get();

        $categories = Category::all();

        return view('welcome', compact('trending', 'items', 'categories'));
    }
}
