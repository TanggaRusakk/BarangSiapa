<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendor = \App\Models\Vendor::first();
        if (! $vendor) {
            // no vendor present — nothing to seed here
            return;
        }

        $itemsData = [
            ['item_name'=>'Wireless Mouse','item_description'=>'Ergonomic design, long battery life','item_type'=>'jual','item_price'=>49000,'item_status'=>'available','rental_duration_unit'=>null,'rental_duration_value'=>null,'item_stock'=>12],
            ['item_name'=>'Drone with Camera','item_description'=>'Perfect for aerial photography','item_type'=>'sewa','item_price'=>35000,'item_status'=>'available','rental_duration_unit'=>'day','rental_duration_value'=>1,'item_stock'=>4],
            ['item_name'=>'Designer Backpack','item_description'=>'Stylish and spacious','item_type'=>'jual','item_price'=>89000,'item_status'=>'available','rental_duration_unit'=>null,'rental_duration_value'=>null,'item_stock'=>6],
            ['item_name'=>'Power Drill Set','item_description'=>'Complete tool kit for DIY projects','item_type'=>'sewa','item_price'=>12000,'item_status'=>'available','rental_duration_unit'=>'day','rental_duration_value'=>1,'item_stock'=>3],
            ['item_name'=>'Yoga Mat Pro','item_description'=>'Non-slip, eco-friendly material','item_type'=>'jual','item_price'=>39000,'item_status'=>'available','rental_duration_unit'=>null,'rental_duration_value'=>null,'item_stock'=>20],
            ['item_name'=>'Party Speaker','item_description'=>'Bluetooth, powerful bass','item_type'=>'sewa','item_price'=>20000,'item_status'=>'available','rental_duration_unit'=>'day','rental_duration_value'=>1,'item_stock'=>5],
        ];

        foreach ($itemsData as $data) {
            \App\Models\Item::updateOrCreate(
                ['item_name' => $data['item_name']],
                array_merge($data, ['vendor_id' => $vendor->id])
            );
        }
    }
}
