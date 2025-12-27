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
        $paymentType = $notification['payment_type'] ?? null;

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

        // 3. Cari payment di database
        $payment = Payment::where('midtrans_order_id', $midtransOrderId)->first();

        if (!$payment) {
            Log::warning('Midtrans: payment not found', ['midtrans_order_id' => $midtransOrderId]);
            return response()->json(['message' => 'Payment not found'], 404);
        }

        DB::beginTransaction();
        try {
            // 4. Update payment status
            $payment->payment_status = $transactionStatus;
            $payment->payment_method = $paymentType;
            
            // If payment is successful, update paid_at and order status
            if (in_array($transactionStatus, ['settlement', 'capture'])) {
                $payment->paid_at = now();
                
                // Update order status
                $order = Order::find($payment->order_id);
                if ($order) {
                    $order->order_status = 'processing';
                    $order->save();
                    
                    // Reduce item stock
                    foreach ($order->orderItems as $orderItem) {
                        $item = $orderItem->item;
                        if ($item) {
                            $item->item_stock -= $orderItem->quantity;
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

            $payment->save();
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
