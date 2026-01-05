<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Item;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
    public function index(Request $request)
    {
        $orders = Order::with(['orderItems','user'])->latest()->take(50)->get();
        return response()->json($orders);
    }

    // Show checkout page
    public function checkout($itemId)
    {
        // Only users can create orders - admin and vendor cannot
        $userRole = Auth::user()->role ?? 'user';
        if (!$this->orderService->canUserCreateOrder($userRole)) {
            return redirect()->route('items.show', $itemId)->with('error', 'Admin dan Vendor tidak dapat membuat pesanan.');
        }

        $item = Item::with(['vendor', 'galleries'])->findOrFail($itemId);
        
        // Check if item is available
        $availability = $this->orderService->isItemAvailable($item, 1);
        if (!$availability['valid']) {
            return redirect()->route('items.show', $itemId)->with('error', $availability['message']);
        }

        return view('checkout', compact('item'));
    }

    // Create order from checkout
    public function store(Request $request)
    {
        // Only users can create orders - admin and vendor cannot
        $userRole = Auth::user()->role ?? 'user';
        if (!$this->orderService->canUserCreateOrder($userRole)) {
            return back()->with('error', 'Admin dan Vendor tidak dapat membuat pesanan.');
        }

        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'rental_start_date' => 'nullable|date',
            'rental_end_date' => 'nullable|date|after:rental_start_date',
            'payment_option' => 'nullable|in:dp,full',
        ]);

        $item = Item::findOrFail($request->item_id);

        // Check stock availability
        $availability = $this->orderService->isItemAvailable($item, $request->quantity);
        if (!$availability['valid']) {
            return back()->with('error', $availability['message']);
        }

        try {
            // Create order using service
            $order = $this->orderService->createOrder([
                'item_id' => $request->item_id,
                'quantity' => $request->quantity,
                'rental_start_date' => $request->rental_start_date,
                'rental_end_date' => $request->rental_end_date,
            ]);

            // Persist chosen payment option in session
            $isRent = $item->item_type === 'rent';
            // Buy items always use full payment, rent items can choose dp or full
            $paymentOption = $isRent ? $request->input('payment_option', 'dp') : 'full';
            session(['order_payment_option_' . $order->id => $paymentOption]);

            // Redirect to payment
            return redirect()->route('payment.create', ['order' => $order->id, 'payment_option' => $paymentOption]);

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Show my orders
    public function myOrders(Request $request)
    {
        $query = Order::with(['orderItems.item.galleries', 'payments'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        $status = $request->query('status');
        if ($status) {
            $query->where('order_status', $status);
        }

        $orders = $query->paginate(10)->appends($request->only('status'));

        return view('orders.my-orders', compact('orders'));
    }

    // Show order detail
    public function show($id)
    {
        $order = Order::with(['orderItems.item.galleries', 'payments', 'rentalInfos'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('orders.show', compact('order'));
    }
}
