<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Payment; 
use App\Models\Order;

class MidtransNotification extends Controller
{
    public function handle(Request $request)
    {
        // 1. Ambil payload JSON mentah
        $payload = $request->getContent();
        $notification = json_decode($payload, true);

        if (!is_array($notification)) {
            Log::warning('Midtrans: invalid JSON payload', ['payload' => $payload]);
            return response()->json(['message' => 'Invalid JSON'], 400);
        }

        // Ambil field penting (aman jika beberapa field tidak tersedia)
        $midtransOrderId = $notification['order_id'] ?? null;
        $statusCode = $notification['status_code'] ?? null;
        $grossAmount = $notification['gross_amount'] ?? null;
        $signatureKey = $notification['signature_key'] ?? null;
        $transactionStatus = $notification['transaction_status'] ?? null;
        $paymentMethod = $notification['payment_type'] ?? null; // Metode pembayaran dari Midtrans

        if (!$midtransOrderId || !$statusCode || !$grossAmount || !$signatureKey) {
            Log::warning('Midtrans: missing required fields', $notification);
            return response()->json(['message' => 'Missing required fields'], 400);
        }

        // 2. Verifikasi signature key secara aman
        $serverKey = config('midtrans.server_key') ?? env('MIDTRANS_SERVER_KEY');
        $computed = hash('sha512', $midtransOrderId . $statusCode . $grossAmount . $serverKey);

        if (!hash_equals($computed, $signatureKey)) {
            Log::warning('Midtrans: invalid signature', [
                'order_id' => $midtransOrderId,
                'computed' => $computed,
                'received' => $signatureKey,
            ]);
            return response()->json(['message' => 'Invalid signature key'], 403);
        }

        // 3. Update atau buat payment baru berdasarkan midtrans_order_id
        DB::beginTransaction();
        try {
            // Cek apakah payment sudah ada
            $existingPayment = Payment::where('midtrans_order_id', $midtransOrderId)->first();
            
            if ($existingPayment) {
                // Jika sudah ada, UPDATE
                $existingPayment->payment_status = $transactionStatus;
                $existingPayment->payment_method = $paymentMethod;

                // Ensure user_id is set from order if missing
                if (empty($existingPayment->user_id) && $existingPayment->order_id) {
                    $ord = Order::find($existingPayment->order_id);
                    if ($ord) $existingPayment->user_id = $ord->user_id;
                }

                if (in_array($transactionStatus, ['settlement', 'capture'])) {
                    $existingPayment->paid_at = now();
                }

                $existingPayment->save();
                $payment = $existingPayment;
            } else {
                // Jika belum ada, ekstrak order_id dari midtrans_order_id
                // Format: ORDER-{order_id}-{timestamp}
                $parts = explode('-', $midtransOrderId);
                $orderId = isset($parts[1]) ? (int)$parts[1] : null;
                
                if (!$orderId) {
                    throw new \Exception("Cannot extract order_id from midtrans_order_id: {$midtransOrderId}");
                }
                
                // CREATE payment baru dengan semua field required
                $order = Order::find($orderId);
                $paymentData = [
                    'order_id' => $orderId,
                    'midtrans_order_id' => $midtransOrderId,
                    'payment_method' => $paymentMethod,
                    'payment_type' => 'full', // Default ke 'full', sesuaikan jika perlu
                    'payment_total_amount' => (int)$grossAmount,
                    'payment_status' => $transactionStatus,
                    'paid_at' => in_array($transactionStatus, ['settlement', 'capture']) ? now() : null,
                ];

                // Attach user_id when possible
                if ($order) {
                    $paymentData['user_id'] = $order->user_id;
                }

                $payment = Payment::create($paymentData);
            }

            // 4. Update order status dan stock jika payment successful
            if (in_array($transactionStatus, ['settlement', 'capture'])) {
                $order = Order::find($payment->order_id);
                if ($order) {
                    $order->order_status = 'processing';
                    $order->save();

                    // Reduce item stock - use order_item_quantity if available
                    foreach ($order->orderItems as $orderItem) {
                        $item = $orderItem->item;
                        if ($item) {
                            $qty = $orderItem->order_item_quantity ?? $orderItem->quantity ?? 1;
                            $item->item_stock = max(0, $item->item_stock - $qty);
                            if ($item->item_stock <= 0) {
                                $item->item_status = 'unavailable';
                            }
                            $item->save();
                        }
                    }
                }
            }
            
            // If payment failed or cancelled
            if (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
                $order = Order::find($payment->order_id);
                if ($order) {
                    $order->order_status = 'cancelled';
                    $order->save();
                }
            }

            DB::commit();

            Log::info('Midtrans notification processed', [
                'midtrans_order_id' => $midtransOrderId,
                'status' => $payment->payment_status,
                'transaction_status' => $transactionStatus,
            ]);

            // 5. Balas Midtrans dengan 200 OK
            return response()->json(['message' => 'Notification processed successfully'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Midtrans notification error', [
                'error' => $e->getMessage(),
                'midtrans_order_id' => $midtransOrderId,
            ]);
            return response()->json(['message' => 'Error processing notification'], 500);
        }
    }
}
