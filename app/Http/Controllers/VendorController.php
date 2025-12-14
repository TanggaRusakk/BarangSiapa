<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Category;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $vendors = Vendor::with('user')->latest()->take(50)->get();
        return response()->json($vendors);
    }

    /**
     * Display vendor detail page with items, filters and sort.
     */
    public function show(Request $request, Vendor $vendor)
    {
        $vendor->load('user');

        $query = $vendor->items()->with(['galleries', 'categories']);

        if ($request->filled('search')) {
            $query->where('item_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $category = $request->category;
            $query->whereHas('categories', function ($q) use ($category) {
                $q->where('categories.id', $category);
            });
        }

        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('item_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('item_price', 'desc');
                break;
            case 'name':
                $query->orderBy('item_name', 'asc');
                break;
            default:
                $query->latest();
        }

        $items = $query->paginate(12)->withQueryString();

        $categories = Category::orderBy('category_name')->get();

        return view('vendor', compact('vendor', 'items', 'categories'));
    }
}
