<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Item;
use App\Models\Vendor;
use App\Models\Category;
use App\Models\Review;

class UserController extends Controller
{
    // User dashboard (role: user)
    public function dashboard(Request $request)
    {
        if (auth()->user()->role !== 'user') abort(403);

        $user = auth()->user();
        $recentOrders = Order::where('user_id', $user->id)->with('orderItems.item')->latest()->take(5)->get();
        $ordersCount = Order::where('user_id', $user->id)->count();

        // Recent viewed items placeholder
        $recentProducts = Item::latest()->take(5)->get();

        return view('dashboard.user.index', compact('recentOrders', 'ordersCount', 'recentProducts'));
    }

    // Admin dashboard (role: admin)
    public function adminDashboard(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);

        $totalUsers = User::count();
        $activeVendors = Vendor::where('is_active', 1)->count();
        $totalProducts = Item::count();
        $totalCategories = Category::count();

        $revenueThisMonth = Order::whereIn('order_status', ['paid','completed'])
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('order_total_amount');

        $recentCategories = Category::latest()->take(6)->get();
        $recentOrders = Order::with('user')->latest()->take(5)->get();
        $recentUsers = User::latest()->take(5)->get();

        return view('dashboard', compact('totalUsers','activeVendors','totalProducts','totalCategories','revenueThisMonth','recentCategories','recentOrders','recentUsers'));
    }
}
