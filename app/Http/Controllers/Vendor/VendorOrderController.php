<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorOrderController extends Controller
{
    public function list()
    {
        if (auth()->user()->role !== 'vendor') abort(403);
        
        $vendor = auth()->user()->vendor;
        if (!$vendor) {
            return back()->with('error', 'Vendor profile not found');
        }

        $orders = Order::whereHas('orderItems.item', function ($q) use ($vendor) {
                $q->where('vendor_id', $vendor->id);
            })
            ->with(['orderItems.item', 'user'])
            ->latest()
            ->paginate(20);

        return view('vendor.orders-list', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        if (auth()->user()->role !== 'vendor') abort(403);
        
        $vendor = auth()->user()->vendor;
        if (!$vendor) {
            return back()->with('error', 'Vendor profile not found');
        }

        // Check if order contains items from this vendor
        $hasVendorItem = $order->orderItems()->whereHas('item', function ($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id);
        })->exists();

        if (!$hasVendorItem) {
            abort(403);
        }

        $request->validate([
            'order_status' => 'required|string',
        ]);

        $order->update(['order_status' => $request->order_status]);

        return redirect()->route('vendor.orders.list')->with('success', 'Order status updated');
    }
}
