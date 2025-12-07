<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = \App\Models\Item::take(6)->get();
        $now = \Carbon\Carbon::now();
        foreach ($items->take(3) as $i => $it) {
            \App\Models\Ad::updateOrCreate(
                ['item_id' => $it->id],
                [
                    'start_date' => $now->copy()->subDays($i),
                    'end_date' => $now->copy()->addDays(7),
                    'price' => intval($it->item_price * 0.2),
                    'status' => 'active'
                ]
            );
        }
    }
}
