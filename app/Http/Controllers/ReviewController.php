<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::with(['user','item'])->latest()->take(100)->get();
        return response()->json($reviews);
    }
}
