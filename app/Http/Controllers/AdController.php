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
        // Popular items (top 5 most ordered)
        $popularItems = Item::where('item_status', 'available')
            ->with(['itemGalleries', 'vendor.user'])
            ->withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(5)
            ->get();

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

        // items: for product grid (use paginator so views can call ->links())
        $items = Item::where('item_status', 'available')
            ->with('itemGalleries')
            ->latest()
            ->paginate(12);

        $categories = Category::all();

        return view('home', compact('trending', 'items', 'categories', 'popularItems'));
    }
}
