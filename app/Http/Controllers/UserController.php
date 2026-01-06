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

        // Metrics (computed in controller only)
        $activeOrders = Order::where('user_id', $user->id)
            ->where('order_status', 'paid')
            ->count();

        $totalSpent = Order::where('user_id', $user->id)
            ->where('order_status', 'paid')
            ->sum('order_total_amount');

        $reviewsGiven = \App\Models\Review::where('user_id', $user->id)->count();

        // Conversations count (chats where user participates)
        $messagesCount = \App\Models\Chat::where('user_id', $user->id)
            ->orWhere('vendor_user_id', $user->id)
            ->count();

        $recentOrders = Order::where('user_id', $user->id)->with('orderItems.item')->latest()->take(5)->get();
        $recentProducts = Item::latest()->take(5)->get();

        return view('dashboard.user.index', compact('recentOrders', 'recentProducts', 'activeOrders', 'totalSpent', 'reviewsGiven', 'messagesCount'));
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

    // Generic dashboard route that redirects to role-specific dashboard
    public function redirectDashboard(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $role = auth()->user()->role;
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'vendor':
                return redirect()->route('vendor.dashboard');
            case 'user':
            default:
                return redirect()->route('user.dashboard');
        }
    }
}
