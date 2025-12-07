<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderServiceFee;

class OrderServiceFeeController extends Controller
{
    /**
     * Return a JSON list of order service fees with relations.
     */
    public function index(Request $request)
    {
        $list = OrderServiceFee::with(['order','serviceFee'])->latest()->take(200)->get();
        return response()->json($list);
    }
}
