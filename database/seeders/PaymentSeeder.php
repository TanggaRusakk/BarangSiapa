<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $order = \App\Models\Order::first();
        if (! $order) return;

        \App\Models\Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'midtrans_order_id' => 'ORDER-TEST',
                'payment_method' => 'qris',
                'payment_type' => 'full',
                'payment_total_amount' => $order->total_amount,
                'payment_status' => 'settlement',
                'paid_at' => now(),
            ]
        );
    }
}
