<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    // Status yang dianggap final dan tidak boleh diubah lagi
    private const FINAL_PAYMENT_STATUSES = ['settlement', 'capture', 'success'];
    private const SUCCESSFUL_STATUSES = ['capture', 'settlement'];
    private const FAILED_STATUSES = ['deny', 'expire', 'cancel'];

    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
        
        // Initialize Midtrans config
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

    public function create(Request $request, $orderId)
    {
        $order = Order::with(['orderItems.item', 'user', 'rentalInfos'])->findOrFail($orderId);

        // Validate: Prevent unauthorized access
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Validate: Prevent duplicate payment for already paid orders
        $existingPayment = Payment::where('order_id', $order->id)->first();
        if ($existingPayment && in_array($existingPayment->payment_status, self::FINAL_PAYMENT_STATUSES)) {
            return redirect()->route('orders.show', $order->id)
                ->with('error', 'Order ini sudah dibayar!');
        }

        // Generate unique Midtrans order ID
        $midtransOrderId = 'ORDER-' . $order->id . '-' . time();

        // Get order total
        $orderTotal = $order->total_amount ?? $order->order_total_amount ?? $order->calculated_total;
        $isRental = $order->order_type === 'rent';

        // Determine payment type and amount
        $requestOption = $request->query('payment_option', null);
        $sessionKey = 'order_payment_option_' . $order->id;
        $sessionOption = session($sessionKey, null);

        // For buy orders, always use full payment
        if (!$isRental) {
            $paymentType = 'full';
            $paymentAmount = $orderTotal;
        } else {
            // For rental orders, determine based on user choice (default to dp)
            $chosenOption = $requestOption ?? $sessionOption ?? ($existingPayment->payment_type ?? null) ?? 'dp';
            $paymentType = $chosenOption;
            $paymentAmount = ($paymentType === 'dp') ? round($orderTotal * 0.30) : $orderTotal;
        }

        Log::info('PaymentController.create: payment details', [
            'order_id' => $order->id,
            'payment_type' => $paymentType,
            'payment_amount' => $paymentAmount,
        ]);

        // Update existing payment if needed
        if ($existingPayment) {
            if ($existingPayment->payment_type !== $paymentType || 
                (int)$existingPayment->payment_total_amount !== (int)$paymentAmount) {
                try {
                    $existingPayment->update([
                        'payment_type' => $paymentType,
                        'payment_total_amount' => $paymentAmount,
                        'payment_status' => 'pending',
                    ]);
                } catch (\Exception $e) {
                    Log::warning('Unable to sync existing payment', ['payment_id' => $existingPayment->id, 'error' => $e->getMessage()]);
                }
            }
        }

        // Build transaction details
        $transactionDetails = [
            'order_id' => $midtransOrderId,
            'gross_amount' => (int) $paymentAmount,
        ];

        // Build item details
        $itemDetails = [];
        foreach ($order->orderItems as $orderItem) {
            $price = (int) ($orderItem->order_item_price ?? $orderItem->price ?? 0);
            $quantity = (int) ($orderItem->order_item_quantity ?? $orderItem->quantity ?? 1);
            
            // Adjust price for DP
            if ($paymentType === 'dp') {
                $price = round($price * 0.30);
            }
            
            $itemDetails[] = [
                'id' => $orderItem->item->id ?? 'ITEM-' . $orderItem->id,
                'price' => $price,
                'quantity' => $quantity,
                'name' => substr($orderItem->item->item_name ?? 'Item', 0, 50),
            ];
        }

        // Add service fee if needed
        $itemTotal = collect($itemDetails)->sum(fn($item) => $item['price'] * $item['quantity']);
        if ($itemTotal < $paymentAmount) {
            $itemDetails[] = [
                'id' => 'SERVICE_FEE',
                'price' => (int) ($paymentAmount - $itemTotal),
                'quantity' => 1,
                'name' => $isRental ? 'DP Service Fee' : 'Service Fee',
            ];
        }

        // Build customer details
        $customerDetails = [
            'first_name' => $order->user->name ?? 'Customer',
            'email' => $order->user->email ?? 'customer@example.com',
            'phone' => $order->user->phone ?? '081234567890',
        ];

        // Prepare Midtrans params
        $params = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
            'callbacks' => [
                'finish' => route('payment.success'),
                'unfinish' => route('payment.pending'),
                'error' => route('payment.error'),
            ],
        ];

        try {
            // Generate snap token
            $snapToken = Snap::getSnapToken($params);

            // Create or update payment record
            $payment = Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'user_id' => $order->user_id,
                    'midtrans_order_id' => $midtransOrderId,
                    'payment_method' => 'midtrans',
                    'payment_type' => $paymentType,
                    'payment_total_amount' => $paymentAmount,
                    'payment_status' => 'pending',
                ]
            );

            return view('payment', compact('snapToken', 'order', 'payment'));

        } catch (\Exception $e) {
            Log::error('Failed to create Midtrans payment', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            
            return redirect()->route('orders.my-orders')
                ->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
        }
    }

    // WHY: UI callbacks are READ-ONLY - webhook is single source of truth for DB updates
    public function success(Request $request)
    {
        Log::info('Payment success redirect hit', ['query' => $request->query()]);
        $midtransOrderId = $request->query('order_id');
        
        if ($midtransOrderId) {
            $payment = Payment::where('midtrans_order_id', $midtransOrderId)->first();
            
            if ($payment) {
                Log::info('Matched payment on success redirect', ['payment_id' => $payment->id, 'payment_type' => $payment->payment_type]);
                // If this is an ad payment, create Ad from session (webhook will activate later)
                if ($payment->payment_type === 'ad') {
                    $pendingAd = session('pending_ad');
                    Log::info('Pending ad from session', ['pending_ad_exists' => $pendingAd ? true : false]);
                    
                    if ($pendingAd && $pendingAd['payment_id'] === $payment->id) {
                        // Check if Ad already exists (prevent duplicate)
                        $existingAd = \App\Models\Ad::where('payment_id', $payment->id)->first();
                        
                        if (!$existingAd) {
                            \App\Models\Ad::create([
                                'item_id' => $pendingAd['item_id'],
                                'start_date' => $pendingAd['start_date'],
                                'end_date' => $pendingAd['end_date'],
                                'price' => $pendingAd['price'],
                                'ad_image' => $pendingAd['ad_image'] ?? 'ad_placeholder.jpg',
                                'status' => 'pending', // Webhook will activate
                                'payment_id' => $payment->id,
                            ]);
                        }
                        
                        session()->forget('pending_ad');
                    }
                    
                    return redirect()->route('vendor.ads.index')
                        ->with('success', 'Pembayaran iklan diterima! Iklan akan aktif setelah verifikasi sistem.');
                }

                if ($payment->order_id) {
                    $order = Order::find($payment->order_id);
                    // WHY: Verify user owns this order before showing success page
                    if ($order && $order->user_id === Auth::id()) {
                        return redirect()->route('orders.show', $order->id)
                            ->with('info', 'Pembayaran sedang diverifikasi. Status akan diupdate otomatis.');
                    }
                }
            }
        }

        return redirect()->route('orders.my-orders')
            ->with('info', 'Pembayaran sedang diverifikasi oleh sistem.');
    }

    public function pending(Request $request)
    {
        $midtransOrderId = $request->query('order_id');
        if ($midtransOrderId) {
            $payment = Payment::where('midtrans_order_id', $midtransOrderId)->first();
            if ($payment && $payment->payment_type === 'ad') {
                return redirect()->route('vendor.ads.index')
                    ->with('info', 'Pembayaran iklan tertunda. Mohon selesaikan pembayaran Anda.');
            }
        }

        return redirect()->route('orders.my-orders')
            ->with('info', 'Pembayaran tertunda. Mohon selesaikan pembayaran Anda.');
    }

    public function error(Request $request)
    {
        $midtransOrderId = $request->query('order_id');
        if ($midtransOrderId) {
            $payment = Payment::where('midtrans_order_id', $midtransOrderId)->first();
            if ($payment && $payment->payment_type === 'ad') {
                return redirect()->route('vendor.ads.index')
                    ->with('error', 'Pembayaran iklan gagal atau dibatalkan. Silakan coba lagi.');
            }
        }

        return redirect()->route('orders.my-orders')
            ->with('error', 'Pembayaran gagal! Silakan coba lagi.');
    }

    /**
     * Webhook Handler - SINGLE SOURCE OF TRUTH untuk update payment & order status
     * 
     * WHY: Webhook dipanggil oleh Midtrans server, bukan user, sehingga lebih aman
     * WHY: Signature verification otomatis via Midtrans\Notification mencegah fake requests
     */
    public function webhook(Request $request)
    {
        try {
            // WHY: Midtrans\Notification automatically verifies signature_key
            // Akan throw exception jika signature invalid (protection dari fake webhook)
            $notification = new \Midtrans\Notification();
            
            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? null;
            $paymentType = $notification->payment_type;
            $grossAmount = $notification->gross_amount;
            
            Log::info('Midtrans webhook received', [
                'order_id' => $orderId,
                'status' => $transactionStatus,
            ]);

            // WHY: Defensive - pastikan payment dan order benar-benar ada
            $payment = Payment::where('midtrans_order_id', $orderId)->first();
            if (!$payment) {
                Log::error('Payment not found in webhook', ['order_id' => $orderId]);
                return response()->json(['message' => 'Payment not found'], 404);
            }

            // If this payment is linked to an order, load it; otherwise handle ad payments
            $order = null;
            if ($payment->order_id) {
                $order = Order::find($payment->order_id);
                if (!$order) {
                    Log::error('Order not found in webhook', ['payment_id' => $payment->id]);
                    return response()->json(['message' => 'Order not found'], 404);
                }
            }

            // Validate: Check amount
            if ((int)$grossAmount !== (int)$payment->payment_total_amount) {
                Log::error('Amount mismatch in webhook', [
                    'expected' => $payment->payment_total_amount,
                    'received' => $grossAmount,
                    'order_id' => $orderId,
                ]);
                return response()->json(['message' => 'Amount mismatch'], 400);
            }

            // Validate: IDEMPOTENCY - prevent double processing
            if (in_array($payment->payment_status, self::FINAL_PAYMENT_STATUSES)) {
                Log::info('Webhook ignored - payment already final', [
                    'order_id' => $orderId,
                    'current_status' => $payment->payment_status,
                ]);
                return response()->json(['message' => 'Payment already processed'], 200);
            }

            // Process transaction status - if payment_type is 'ad' handle Ad creation/activation
            if ($payment->payment_type === 'ad') {
                $this->processAdPaymentWebhook($payment, $transactionStatus, $fraudStatus, $paymentType, $orderId);
            } else {
                $this->processTransactionStatus(
                    $payment,
                    $order,
                    $transactionStatus,
                    $fraudStatus,
                    $paymentType,
                    $orderId
                );
            }

            return response()->json(['message' => 'OK'], 200);

        } catch (\Exception $e) {
            // WHY: Log error tapi tetap return 200 agar Midtrans tidak retry terus-menerus
            Log::error('Midtrans webhook processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json(['message' => 'Error processed'], 200);
        }
    }

    /**
     * Process transaction status dari Midtrans webhook
     * 
     * WHY: Extracted method untuk reduce complexity dan improve testability
     */
    private function processTransactionStatus(
        Payment $payment,
        Order $order,
        string $transactionStatus,
        ?string $fraudStatus,
        string $paymentType,
        string $orderId
    ): void {
        switch ($transactionStatus) {
            case 'capture':
                // Credit card perlu fraud check sebelum approve
                if ($fraudStatus === 'accept') {
                    $this->markPaymentAsSettled($payment, $order, $paymentType);
                } elseif ($fraudStatus === 'challenge') {
                    $payment->update(['payment_status' => 'challenge']);
                    Log::warning('Payment challenged by fraud detection', ['order_id' => $orderId]);
                }
                break;

            case 'settlement':
                // Bank transfer, e-wallet langsung settlement tanpa fraud check
                $this->markPaymentAsSettled($payment, $order, $paymentType);
                break;

            case 'pending':
                // Update payment method tapi jangan ubah order status
                $payment->update([
                    'payment_status' => 'pending',
                    'payment_method' => $paymentType,
                ]);
                break;

            case 'deny':
            case 'expire':
            case 'cancel':
                // Failed payment - cancel order
                $this->markPaymentAsFailed($payment, $order, $transactionStatus);
                break;

            default:
                Log::warning('Unhandled transaction status', [
                    'status' => $transactionStatus,
                    'order_id' => $orderId,
                ]);
        }
    }

    /**
     * Mark payment as settled and update order status
     */
    private function markPaymentAsSettled(
        Payment $payment,
        Order $order,
        string $paymentType
    ): void {
        DB::transaction(function () use ($payment, $order, $paymentType) {
            $payment->update([
                'payment_status' => 'settlement',
                'payment_method' => $paymentType,
                'paid_at' => now(),
            ]);

            $order->update(['order_status' => 'paid']);
        });

        Log::info('Payment successfully settled', [
            'order_id' => $order->id,
            'payment_method' => $paymentType,
        ]);
    }

    /**
     * Mark payment as failed and cancel order
     */
    private function markPaymentAsFailed(
        Payment $payment,
        Order $order,
        string $status
    ): void {
        $payment->update(['payment_status' => $status]);
        $order->update(['order_status' => 'cancelled']);
        
        Log::info('Payment failed and order cancelled', [
            'order_id' => $order->id,
            'reason' => $status,
        ]);
    }

    /**
     * Handle ad payment webhook processing (create/activate Ad)
     */
    private function processAdPaymentWebhook(
        Payment $payment,
        string $transactionStatus,
        ?string $fraudStatus,
        string $paymentType,
        string $orderId
    ): void {
        switch ($transactionStatus) {
            case 'capture':
            case 'settlement':
                // Mark payment as settled
                $payment->update([
                    'payment_status' => 'settlement',
                    'payment_method' => $paymentType,
                    'paid_at' => now(),
                ]);

                // Find Ad by payment_id and activate it (Ad should be created by success callback)
                $existingAd = \App\Models\Ad::where('payment_id', $payment->id)->first();
                if ($existingAd) {
                    $existingAd->update(['status' => 'active']);
                    Log::info('Ad activated via webhook', ['ad_id' => $existingAd->id, 'payment_id' => $payment->id]);
                } else {
                    Log::warning('Ad payment settled but no Ad record found', ['payment_id' => $payment->id]);
                }

                Log::info('Ad payment settled', ['order_id' => $orderId, 'payment_id' => $payment->id]);
                break;

            case 'pending':
                $payment->update(['payment_status' => 'pending', 'payment_method' => $paymentType]);
                break;

            case 'deny':
            case 'expire':
            case 'cancel':
                $payment->update(['payment_status' => $transactionStatus]);
                Log::info('Ad payment failed', ['payment_id' => $payment->id, 'reason' => $transactionStatus]);
                break;

            default:
                Log::warning('Unhandled ad transaction status', ['status' => $transactionStatus, 'order_id' => $orderId]);
        }
    }
}
