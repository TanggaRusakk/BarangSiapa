<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderItem;

class OrderItemController extends Controller
{
    public function index(Request $request)
    {
        $items = OrderItem::with(['order','item'])->latest()->take(200)->get();
        return response()->json($items);
    }
}
