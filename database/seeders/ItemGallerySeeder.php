<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemGallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = \App\Models\Item::all();
        foreach ($items as $item) {
            // ensure at least one gallery image per item
            \App\Models\ItemGallery::updateOrCreate(
                ['item_id' => $item->id],
                ['image_path' => 'images/item/default-image.png']
            );
        }
    }
}
