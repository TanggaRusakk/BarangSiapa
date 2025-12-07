<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Item;

class ItemController extends Controller
{
    /**
     * Return a JSON list of items (basic API for UI)
     */
    public function index(Request $request)
    {
        $items = Item::with(['itemGalleries','vendor'])->latest()->take(50)->get();
        return response()->json($items);
    }
}
