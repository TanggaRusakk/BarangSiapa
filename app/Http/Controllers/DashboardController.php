<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Ad;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Core datasets
        $recentProducts = Item::orderBy('created_at', 'desc')->take(6)->get();
        $lastViewed = null;
        if (session('last_viewed_item')) {
            $lastViewed = Item::find(session('last_viewed_item'));
        }

        // Admin metrics
        $totalUsers = User::count();
        $activeVendors = Vendor::count();
        $totalProducts = Item::count();
        $revenueThisMonth = Payment::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->where('payment_status', 'settlement')
            ->sum('payment_total_amount');
        $recentUsers = User::orderBy('created_at', 'desc')->take(6)->get();
        $recentOrders = Order::with(['user', 'orderItems.item.vendor'])->orderBy('created_at', 'desc')->take(6)->get();
        $recentAds = Ad::with(['item.vendor', 'payment'])->orderBy('created_at', 'desc')->take(6)->get();

        // Vendor specific
        $vendorProductsCount = 0;
        $vendorOrdersCount = 0;
        $vendorRevenue = 0;
        $vendorRating = 0.0;
        $vendorRecentProducts = collect();
        $vendorRecentOrders = collect();
        $vendorRecentAds = collect();
        
        if ($user->vendor) {
            $vendor = $user->vendor;
            
            // Total products count (all status)
            $vendorProductsCount = $vendor->items()->count();
            
            // Total PAID orders count for vendor's items
            $vendorOrdersCount = Order::whereIn('order_status', ['paid', 'completed'])
                ->whereHas('orderItems.item', function($q) use ($vendor) {
                    $q->where('vendor_id', $vendor->id);
                })->count();
            
            // Total revenue from paid orders
            $vendorRevenue = Payment::where('payment_status', 'settlement')
                ->whereHas('order.orderItems.item', function($q) use ($vendor) {
                    $q->where('vendor_id', $vendor->id);
                })->sum('payment_total_amount');
            
            // Average store rating from all vendor's product reviews
            $vendorRating = Review::whereHas('item', function($q) use ($vendor) {
                $q->where('vendor_id', $vendor->id);
            })->avg('review_rating') ?? 0.0;
            $vendorRating = round($vendorRating, 1);
            
            // Recent products
            $vendorRecentProducts = $vendor->items()
                ->with('galleries')
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();
            
            // Recent orders for vendor
            $vendorRecentOrders = Order::with(['user', 'orderItems.item'])
                ->whereHas('orderItems.item', function($q) use ($vendor) {
                    $q->where('vendor_id', $vendor->id);
                })
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();
            
            // Recent ads for vendor
            $vendorRecentAds = Ad::with(['item', 'payment'])
                ->whereHas('item', function($q) use ($vendor) {
                    $q->where('vendor_id', $vendor->id);
                })
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();
        }

        // Member specific - recent orders and rentals
        $userOrders = Order::with(['orderItems.item'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        
        $userRentals = Order::with(['orderItems.item'])
            ->where('user_id', $user->id)
            ->whereHas('orderItems.item', function($q) {
                $q->whereIn('item_type', ['rent']);
            })
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        
        // Member stats
        $activeOrdersCount = Order::where('user_id', $user->id)
            ->whereIn('order_status', ['pending', 'processing', 'paid'])
            ->count();
        
        $activeRentalsCount = Order::where('user_id', $user->id)
            ->whereIn('order_status', ['pending', 'processing', 'paid'])
            ->whereHas('orderItems.item', function($q) {
                $q->whereIn('item_type', ['rent']);
            })
            ->count();
        
        $totalSpent = Payment::where('user_id', $user->id)
            ->where('payment_status', 'settlement')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', '>=', now()->subDays(30))
            ->sum('payment_total_amount');
        
        $reviewsGiven = Review::where('user_id', $user->id)->count();

        return view('dashboard', compact(
            'recentProducts', 'lastViewed', 'totalUsers', 'activeVendors', 'totalProducts', 
            'revenueThisMonth', 'recentUsers', 'recentOrders', 'recentAds', 
            'vendorProductsCount', 'vendorOrdersCount', 'vendorRevenue', 'vendorRating',
            'vendorRecentProducts', 'vendorRecentOrders', 'vendorRecentAds',
            'userOrders', 'userRentals', 'activeOrdersCount', 'activeRentalsCount', 
            'totalSpent', 'reviewsGiven'
        ));
    }

    public function recordView(Request $request)
    {
        $id = $request->input('id');
        if ($id) {
            session(['last_viewed_item' => $id]);
        }
        return response()->json(['status' => 'ok']);
    }
}
