<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::first();
        $item = \App\Models\Item::first();
        if (! $user || ! $item) return;

        $order = \App\Models\Order::updateOrCreate(
            ['user_id' => $user->id, 'order_type' => 'jual'],
            ['order_status' => 'pending', 'total_amount' => $item->item_price]
        );

        // create order item
        \App\Models\OrderItem::updateOrCreate(
            ['order_id' => $order->id, 'item_id' => $item->id],
            ['order_item_quantity' => 1, 'order_item_price' => $item->item_price, 'order_item_subtotal' => $item->item_price]
        );
    }
}
