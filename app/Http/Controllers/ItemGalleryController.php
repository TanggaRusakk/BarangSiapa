<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemGallery;

class ItemGalleryController extends Controller
{
    public function index(Request $request)
    {
        $galleries = ItemGallery::latest()->take(100)->get();
        return response()->json($galleries);
    }
}
