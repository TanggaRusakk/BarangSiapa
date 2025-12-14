<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
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

    public function payment(Request $request)
    {
        $transaction_details = [
            'order_id'    => 'ORDER-' . time(),
            'gross_amount' => 10000, // Gunakan angka uji coba (test amount)
        ];
        
        $customer_details = [
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'email'      => 'johndoe@example.com',
            'phone'      => '081234567890',
        ];

        $params = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            // ... detail lain seperti item_details bisa ditambahkan
        ];

        try {
            // 3. Dapatkan Snap Token
            $snapToken = Snap::getSnapToken($params);
            
            return view('payment', compact('snapToken', 'transaction_details'));

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
