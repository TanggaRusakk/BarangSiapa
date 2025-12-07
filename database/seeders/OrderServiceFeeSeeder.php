<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderServiceFeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // If orders and service fees exist, attach a service fee to the first order
        $order = \App\Models\Order::first();
        $fee = \App\Models\ServiceFee::first();
        if (! $order || ! $fee) return;

        \App\Models\OrderServiceFee::updateOrCreate(
            ['order_id' => $order->id, 'service_fee_id' => $fee->id],
            []
        );
    }
}
