<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor as VendorModel;
use Illuminate\Http\Request;

class VendorDashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'vendor') abort(403);
        
        $vendor = auth()->user()->vendor;
        if (!$vendor) {
            return redirect()->route('dashboard')->with('error', 'Vendor profile not found');
        }

        $vendor->load(['items' => fn($q) => $q->latest()->take(5)]);

        // Calculate stats
        $totalProducts = $vendor->items()->count();
        $totalRevenue = $vendor->total_revenue ?? 0;
        $totalOrders = $vendor->total_orders ?? 0;
        $averageRating = $vendor->average_rating ?? 0;

        return view('vendor.dashboard', compact('vendor', 'totalProducts', 'totalRevenue', 'totalOrders', 'averageRating'));
    }
}
