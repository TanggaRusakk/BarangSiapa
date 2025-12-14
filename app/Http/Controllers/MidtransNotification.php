<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Payment; 

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

        // 3. Cari order di database
        $payment = Payment::where('midtrans_order_id', $midtransOrderId)->first();

        if (!$payment) {
            // Jika tidak ditemukan, buat record baru berdasarkan payload Midtrans
            $payment = new Payment();
            $payment->midtrans_transaction_id = $midtransOrderId;
            $payment->payment_total_amount = (int) $grossAmount;
            $payment->payment_status = strtoupper($transactionStatus ?? 'pending');
            $payment->paid_at = ($payment->payment_status === 'settlement' || $payment->payment_status === 'capture') ? now() : null;
            $payment->save();

            Log::info('Midtrans: order created from notification', ['order_id' => $midtransOrderId]);
        }

        // 4. Update order dengan data dari Midtrans
        // Map beberapa field yang umum; simpan transaksi id, amount, status, payment_date jika relevan
        $payment->midtrans_transaction_id = $midtransOrderId ?? $payment->midtrans_transaction_id;
        $payment->payment_total_amount = (int) $grossAmount;
        $payment->payment_status = ($transactionStatus ?? $payment->payment_status);
        // Jika ingin menyimpan full notification JSON, tambahkan kolom di migration dan simpan:
        // $order->raw_notification = json_encode($notification);

        $payment->save();

        Log::info('Midtrans notification processed', [
            'order_id' => $midtransOrderId,
            'status' => $payment->payment_status,
            'transaction_id' => $payment->midtrans_transaction_id,
        ]);

        // 5. Balas Midtrans dengan 200 OK
        return response()->json(['message' => 'Notification processed successfully'], 200);
    }
}
