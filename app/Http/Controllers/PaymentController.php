<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function index(Request $request)
    {
        $payments = Payment::with(['order','user'])->latest()->take(50)->get();
        return response()->json($payments);
    }

    // Create payment from order
    public function create($orderId)
    {
        $order = Order::with(['orderItems.item', 'user'])->findOrFail($orderId);

        // Check if user owns this order
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Check if payment already exists
        $existingPayment = Payment::where('order_id', $order->id)->first();
        if ($existingPayment && in_array($existingPayment->payment_status, ['settlement', 'capture', 'success'])) {
            return redirect()->route('orders.show', $order->id)->with('error', 'Order ini sudah dibayar!');
        }

        // Generate unique order id for Midtrans
        $midtransOrderId = 'ORDER-' . $order->id . '-' . time();

        // Prepare transaction details
        $orderTotal = $order->total_amount ?? $order->order_total_amount ?? $order->calculated_total;
        
        $transactionDetails = [
            'order_id' => $midtransOrderId,
            'gross_amount' => (int) $orderTotal,
        ];

        // Prepare item details
        $itemDetails = [];
        foreach ($order->orderItems as $orderItem) {
            $itemDetails[] = [
                'id' => $orderItem->item->id,
                'price' => (int) $orderItem->price,
                'quantity' => $orderItem->quantity,
                'name' => substr($orderItem->item->item_name, 0, 50),
            ];
        }

        // Add service fee if any
        $itemTotal = collect($itemDetails)->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });
        if ($itemTotal < $orderTotal) {
            $itemDetails[] = [
                'id' => 'SERVICE_FEE',
                'price' => (int) ($orderTotal - $itemTotal),
                'quantity' => 1,
                'name' => 'Service Fee',
            ];
        }

        // Customer details
        $customerDetails = [
            'first_name' => $order->user->name ?? 'Customer',
            'email' => $order->user->email ?? 'customer@example.com',
            'phone' => $order->user->phone ?? '081234567890',
        ];

        // Build params
        $params = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
        ];

        try {
            // Get Snap Token from Midtrans
            $snapToken = Snap::getSnapToken($params);

            // Create or update payment record
            $payment = Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'user_id' => $order->user_id,
                    'midtrans_order_id' => $midtransOrderId,
                    'payment_method' => 'midtrans',
                    'payment_type' => 'full',
                    'payment_total_amount' => $orderTotal,
                    'payment_status' => 'pending',
                ]
            );

            return view('payment', compact('snapToken', 'order', 'payment'));

        } catch (\Exception $e) {
            return redirect()->route('orders.my-orders')->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
        }
    }

    // Handle payment success callback
    public function success(Request $request)
    {
        $orderId = $request->query('order_id');
        
        if ($orderId) {
            // Extract order id from midtrans order id (ORDER-{id}-{timestamp})
            $parts = explode('-', $orderId);
            if (count($parts) >= 2) {
                $orderIdNum = $parts[1];
                $order = Order::find($orderIdNum);
                
                if ($order) {
                    return redirect()->route('orders.show', $order->id)->with('success', 'Pembayaran berhasil!');
                }
            }
        }

        return redirect()->route('orders.my-orders')->with('success', 'Pembayaran sedang diproses!');
    }

    // Handle payment pending callback
    public function pending(Request $request)
    {
        return redirect()->route('orders.my-orders')->with('info', 'Pembayaran tertunda. Mohon selesaikan pembayaran Anda.');
    }

    // Handle payment error callback
    public function error(Request $request)
    {
        return redirect()->route('orders.my-orders')->with('error', 'Pembayaran gagal! Silakan coba lagi.');
    }
}
