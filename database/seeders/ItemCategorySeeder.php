<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = \App\Models\Category::all();
        $items = \App\Models\Item::all();
        if ($categories->isEmpty() || $items->isEmpty()) return;

        // Attach first category to each item
        foreach ($items as $item) {
            \App\Models\ItemCategory::updateOrCreate(
                ['item_id' => $item->id, 'category_id' => $categories->first()->id],
                []
            );
        }
    }
}
