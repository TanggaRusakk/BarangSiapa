<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use App\Models\Payment;
use App\Models\Message;
use App\Models\Ad;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function orders()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $orders = Order::with('user')->latest()->paginate(25);
        return view('admin.orders', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $order->load(['user', 'orderItems.item', 'payments']);
        return view('admin.orders-show', compact('order'));
    }

    public function updateOrder(Request $request, Order $order)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $request->validate([
            'order_status' => 'required|string',
        ]);
        
        $order->update(['order_status' => $request->order_status]);
        return redirect()->route('admin.orders')->with('success', 'Order updated');
    }

    public function reviews()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $reviews = Review::with(['user', 'item'])->latest()->paginate(25);
        return view('admin.reviews', compact('reviews'));
    }

    public function destroyReview(Review $review)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $review->delete();
        return redirect()->route('admin.reviews')->with('success', 'Review deleted');
    }

    public function payments()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $payments = Payment::with('order.user')->latest()->paginate(25);
        return view('admin.payments', compact('payments'));
    }

    public function showPayment(Payment $payment)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $payment->load('order.orderItems.item', 'user');
        return view('admin.payments-show', compact('payment'));
    }

    public function messages()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $messages = Message::with(['chat', 'sender'])->latest()->paginate(50);
        return view('admin.messages', compact('messages'));
    }

    public function ads()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $ads = Ad::with('item')->latest()->paginate(25);
        return view('admin.ads', compact('ads'));
    }

    public function categories()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $categories = Category::all();
        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $data = $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name',
        ]);
        
        Category::create($data);
        return redirect()->route('admin.categories')->with('success', 'Category created');
    }

    public function editCategory(Category $category)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        return view('admin.categories-edit', compact('category'));
    }

    public function updateCategory(Request $request, Category $category)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $data = $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name,' . $category->id,
        ]);
        
        $category->update($data);
        return redirect()->route('admin.categories')->with('success', 'Category updated');
    }

    public function destroyCategory(Category $category)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $category->delete();
        return redirect()->route('admin.categories')->with('success', 'Category deleted');
    }
}
