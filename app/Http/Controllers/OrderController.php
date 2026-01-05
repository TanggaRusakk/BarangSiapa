<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Item;
use App\Models\OrderItem;
use App\Models\RentalInfo;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        // Validate: Only users can create orders - admin and vendor cannot
        $userRole = Auth::user()->role ?? 'user';
        if (in_array($userRole, ['admin', 'vendor'])) {
            return redirect()->route('items.show', $itemId)->with('error', 'Admin dan Vendor tidak dapat membuat pesanan.');
        }

        $item = Item::with(['vendor', 'galleries'])->findOrFail($itemId);
        
        // Validate: Check if item is available
        if ($item->item_status !== 'available' || $item->item_stock <= 0) {
            return redirect()->route('items.show', $itemId)->with('error', 'Item tidak tersedia untuk dipesan.');
        }

        return view('checkout', compact('item'));
    }

    // Create order from checkout
    public function store(Request $request)
    {
        // Validate: Only users can create orders
        $userRole = Auth::user()->role ?? 'user';
        if (in_array($userRole, ['admin', 'vendor'])) {
            return back()->with('error', 'Admin dan Vendor tidak dapat membuat pesanan.');
        }

        // Validate request
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'rental_start_date' => 'nullable|date',
            'rental_end_date' => 'nullable|date|after:rental_start_date',
            'payment_option' => 'nullable|in:dp,full',
        ]);

        $item = Item::findOrFail($request->item_id);

        // Validate: Check stock
        if ($item->item_stock < $request->quantity) {
            return back()->with('error', 'Stok tidak mencukupi!');
        }

        // Validate: Check if item is available
        if ($item->item_status !== 'available') {
            return back()->with('error', 'Item tidak tersedia untuk dipesan.');
        }

        // Check if item type is rent and dates are provided
        $isRent = $item->item_type === 'rent';
        if ($isRent && (!$request->rental_start_date || !$request->rental_end_date)) {
            return back()->with('error', 'Tanggal rental harus diisi untuk item rent!');
        }

        DB::beginTransaction();
        try {
            // Calculate rental units if applicable
            $rentalUnits = 1;
            $durationDays = 0;
            
            if ($isRent) {
                // Use service for calculation helper
                $rentalUnits = $this->orderService->calculateRentalUnits(
                    $item,
                    $request->rental_start_date,
                    $request->rental_end_date
                );
                
                $startDate = new \DateTime($request->rental_start_date);
                $endDate = new \DateTime($request->rental_end_date);
                $durationDays = max(1, $startDate->diff($endDate)->days);
            }
            
            // Calculate totals using service
            $totals = $this->orderService->calculateOrderTotals($item, $request->quantity, $rentalUnits);
            
            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $totals['total_amount'],
                'order_type' => $isRent ? 'rent' : 'buy',
                'order_status' => 'pending',
            ]);

            // Create order item
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $item->id,
                'order_item_quantity' => $request->quantity,
                'order_item_price' => $totals['item_price_per_unit'],
                'order_item_subtotal' => $totals['item_total'],
            ]);

            // Create rental info if applicable
            if ($isRent) {
                RentalInfo::create([
                    'order_id' => $order->id,
                    'start_date' => $request->rental_start_date,
                    'end_date' => $request->rental_end_date,
                    'duration_days' => $durationDays,
                ]);
            }

            DB::commit();

            // Persist chosen payment option in session
            // Buy items always use full payment, rent items can choose dp or full
            $paymentOption = $isRent ? $request->input('payment_option', 'dp') : 'full';
            session(['order_payment_option_' . $order->id => $paymentOption]);

            // Redirect to payment
            return redirect()->route('payment.create', ['order' => $order->id, 'payment_option' => $paymentOption]);

        } catch (\Exception $e) {
            DB::rollBack();
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
