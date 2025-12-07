<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemCategory;

class ItemCategoryController extends Controller
{
    public function index(Request $request)
    {
        $ics = ItemCategory::with(['item','category'])->latest()->take(200)->get();
        return response()->json($ics);
    }
}
