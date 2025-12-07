<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::first();
        $item = \App\Models\Item::first();
        if (! $user || ! $item) {
            return;
        }

        \App\Models\Review::updateOrCreate(
            ['user_id' => $user->id, 'item_id' => $item->id],
            ['rating' => 5, 'comment' => 'Great product!', 'created_at' => now(), 'updated_at' => now()]
        );
    }
}
