<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VendorDashboardController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'vendor') abort(403);
        $vendor = auth()->user()->vendor;

        // Recent products (for display)
        $recentProducts = $vendor ? $vendor->items()->orderBy('created_at', 'desc')->take(5)->get() : collect();

        // Orders related to this vendor (orders that contain items belonging to vendor)
        if ($vendor) {
            $vendorItems = $vendor->items()->pluck('id');

            $allOrders = \App\Models\Order::with(['user','orderItems.item'])->get();
            $vendorOrders = $allOrders->filter(function($order) use ($vendorItems) {
                return $order->orderItems->pluck('item_id')->intersect($vendorItems)->isNotEmpty();
            });

            $ordersCount = $vendorOrders->whereIn('order_status', ['paid', 'completed'])->count();

            $recentOrders = $vendorOrders
                ->whereIn('order_status', ['paid', 'completed'])
                ->sortByDesc('created_at')
                ->take(5);

            // Total Sales
            $successfulOrders = $vendorOrders->whereIn('order_status', ['paid', 'completed']);
            $revenue = 0;
            foreach ($successfulOrders as $order) {
                foreach ($order->orderItems as $item) {
                    if ($vendorItems->contains($item->item_id)) {
                        $revenue += ($item->order_item_price ?? 0) * ($item->order_item_quantity ?? 1);
                    }
                }
            }

            $productsCount = $vendor->items()->count();

            // Store Rating
            $storeRating = \App\Models\Review::whereHas('item', function ($q) use ($vendor) {
                $q->where('vendor_id', $vendor->id ?? 0);
            })->avg('rating') ?? 0;
            $storeRating = round($storeRating, 1);

            // Recent Ads (paid and active)
            $finalPaymentStatuses = ['settlement', 'capture', 'success'];
            $recentAds = \App\Models\Ad::with('item')
                ->whereHas('item', function($q) use ($vendor) {
                    $q->where('vendor_id', $vendor->id);
                })
                ->where('status', 'active')
                ->whereHas('payment', function($q) use ($finalPaymentStatuses) {
                    $q->whereIn('payment_status', $finalPaymentStatuses);
                })
                ->where(function($q) {
                    $q->whereNull('start_date')->orWhere('start_date', '<=', now());
                })
                ->where(function($q) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                })
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        } else {
            $ordersCount = 0;
            $recentOrders = collect();
            $revenue = 0;
            $productsCount = 0;
            $storeRating = 0;
            $recentAds = collect();
        }

        return view('vendor.dashboard', compact('recentProducts', 'ordersCount', 'recentOrders', 'revenue', 'productsCount', 'storeRating', 'recentAds'));
    }
}
