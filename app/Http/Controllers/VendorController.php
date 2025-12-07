<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $vendors = Vendor::with('user')->latest()->take(50)->get();
        return response()->json($vendors);
    }
}
