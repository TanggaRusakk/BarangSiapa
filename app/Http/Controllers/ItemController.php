<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Item;
use App\Models\Category;

class ItemController extends Controller
{
    /**
     * Display items listing page with search and category filter
     */
    public function index(Request $request)
    {
        $query = Item::with(['galleries', 'vendor']);

        // Search by item name
        if ($request->has('search') && $request->search) {
            $query->where('item_name', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        // Filter by vendor
        if ($request->has('vendor') && $request->vendor) {
            $query->where('vendor_id', $request->vendor);
        }

        $items = $query->latest()->paginate(12);
        $categories = Category::orderBy('category_name')->get();

        return view('items', compact('items', 'categories'));
    }

    /**
     * Display item detail page
     */
    public function show(Item $item)
    {
        $item->load(['galleries', 'vendor.items', 'reviews.user']);
        
        return view('itemdetail', compact('item'));
    }
}

