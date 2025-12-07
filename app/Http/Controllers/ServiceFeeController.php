<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceFee;

class ServiceFeeController extends Controller
{
    public function index(Request $request)
    {
        $fees = ServiceFee::latest()->take(50)->get();
        return response()->json($fees);
    }
}
