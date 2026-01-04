<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Item;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
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
        if (in_array($userRole, ['admin', 'vendor'])) {
            return redirect()->route('items.show', $itemId)->with('error', 'Admin dan Vendor tidak dapat membuat pesanan.');
        }

        $item = Item::with(['vendor', 'galleries'])->findOrFail($itemId);
        
        // Check if item is available
        if ($item->item_status !== 'available' || $item->item_stock <= 0) {
            return redirect()->route('items.show', $itemId)->with('error', 'Item tidak tersedia untuk dipesan.');
        }

        return view('checkout', compact('item'));
    }

    // Create order from checkout
    public function store(Request $request)
    {
        // Only users can create orders - admin and vendor cannot
        $userRole = Auth::user()->role ?? 'user';
        if (in_array($userRole, ['admin', 'vendor'])) {
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

        // Check stock
        if ($item->item_stock < $request->quantity) {
            return back()->with('error', 'Stok tidak mencukupi!');
        }

        // Check if item type is rent and dates are provided
        $isRent = in_array($item->item_type, ['sewa', 'rent']);
        if ($isRent && (!$request->rental_start_date || !$request->rental_end_date)) {
            return back()->with('error', 'Tanggal rental harus diisi untuk item sewa!');
        }

        DB::beginTransaction();
        try {
            // Calculate rental units if applicable
            $rentalUnits = 1;
            $durationDays = 0;
            
            if ($isRent) {
                $startDate = new \DateTime($request->rental_start_date);
                $endDate = new \DateTime($request->rental_end_date);
                $durationDays = max(1, $startDate->diff($endDate)->days); // At least 1 day
                
                // Calculate how many rental periods are needed
                $rentalDurationValue = $item->rental_duration_value ?? 1;
                $rentalDurationUnit = $item->rental_duration_unit ?? 'day';
                
                if ($rentalDurationUnit === 'day') {
                    $rentalUnits = ceil($durationDays / $rentalDurationValue);
                } elseif ($rentalDurationUnit === 'week') {
                    $rentalUnits = ceil($durationDays / ($rentalDurationValue * 7));
                } elseif ($rentalDurationUnit === 'month') {
                    $rentalUnits = ceil($durationDays / ($rentalDurationValue * 30));
                }
                
                $rentalUnits = max(1, $rentalUnits); // At least 1 unit
            }
            
            // Calculate total
            $itemPricePerUnit = $item->item_price * $rentalUnits; // Price adjusted for rental duration
            $itemTotal = $itemPricePerUnit * $request->quantity;
            $serviceFee = $itemTotal * 0.05; // 5% service fee
            $totalAmount = $itemTotal + $serviceFee;

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'order_type' => $isRent ? 'sewa' : 'jual',
                'order_status' => 'pending',
            ]);

            // Create order item
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $item->id,
                'order_item_quantity' => $request->quantity,
                'order_item_price' => $itemPricePerUnit, // Price per unit including rental duration
                'order_item_subtotal' => $itemTotal,
            ]);

            // If it's a rental, create rental info
            if ($isRent) {
                \App\Models\RentalInfo::create([
                    'order_id' => $order->id,
                    'start_date' => $request->rental_start_date,
                    'end_date' => $request->rental_end_date,
                    'duration_days' => $durationDays,
                ]);
            }

            DB::commit();

            // Persist chosen payment option in session so PaymentController will honor it
            $paymentOption = $request->input('payment_option');
            if (!$paymentOption) {
                $paymentOption = $isRent ? 'dp' : 'full';
            }
            session(['order_payment_option_' . $order->id => $paymentOption]);

            // Redirect to payment (include chosen payment option as query param to ensure persistence)
            return redirect()->route('payment.create', ['order' => $order->id, 'payment_option' => $paymentOption]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Show my orders
    public function myOrders()
    {
        $orders = Order::with(['orderItems.item.galleries', 'payments'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

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
