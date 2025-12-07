<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RentalInfo;

class RentalInfoController extends Controller
{
    public function index(Request $request)
    {
        $rental = RentalInfo::latest()->take(50)->get();
        return response()->json($rental);
    }
}
