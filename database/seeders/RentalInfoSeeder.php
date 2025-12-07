<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RentalInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $order = \App\Models\Order::where('order_type','sewa')->first();
        if (! $order) return;

        $start = now();
        $end = $start->copy()->addDays(3);

        \App\Models\RentalInfo::updateOrCreate(
            ['order_id' => $order->id],
            ['start_date' => $start, 'end_date' => $end, 'duration_days' => 3, 'late_fee' => 0]
        );
    }
}
