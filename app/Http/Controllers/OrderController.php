<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['orderItems','user'])->latest()->take(50)->get();
        return response()->json($orders);
    }
}
