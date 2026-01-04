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
            // Sound Systems
            ['item_name'=>'JBL VTX A12 Line Array System','item_description'=>'Professional line array speaker system with 3000W power, perfect for large concerts and outdoor festivals','item_type'=>'rent','item_price'=>1500000,'item_status'=>'available','rental_duration_unit'=>'day','rental_duration_value'=>1,'item_stock'=>8],
            ['item_name'=>'Yamaha QL5 Digital Mixer','item_description'=>'32-channel digital mixing console with premium preamps and Dante networking','item_type'=>'buy','item_price'=>75000000,'item_status'=>'available','rental_duration_unit'=>null,'rental_duration_value'=>null,'item_stock'=>3],
            ['item_name'=>'Shure ULXD4Q Wireless System','item_description'=>'4-channel digital wireless receiver with exceptional RF performance','item_type'=>'rent','item_price'=>450000,'item_status'=>'available','rental_duration_unit'=>'day','rental_duration_value'=>1,'item_stock'=>12],
            ['item_name'=>'QSC K12.2 Active Speaker Pair','item_description'=>'2000W powered PA speakers with advanced DSP processing','item_type'=>'buy','item_price'=>18000000,'item_status'=>'available','rental_duration_unit'=>null,'rental_duration_value'=>null,'item_stock'=>15],
            
            // Lighting Equipment
            ['item_name'=>'Martin MAC Aura XB Moving Head','item_description'=>'High-performance LED wash fixture with zoom and beam shaping','item_type'=>'rent','item_price'=>650000,'item_status'=>'available','rental_duration_unit'=>'day','rental_duration_value'=>1,'item_stock'=>20],
            ['item_name'=>'Chauvet Rogue R2X Beam','item_description'=>'Powerful 230W beam moving head with prismatic effects','item_type'=>'rent','item_price'=>550000,'item_status'=>'available','rental_duration_unit'=>'day','rental_duration_value'=>1,'item_stock'=>16],
            ['item_name'=>'Elation KL Panel XL','item_description'=>'High-output RGBW LED panel for stage washing and audience blinding','item_type'=>'buy','item_price'=>32000000,'item_status'=>'available','rental_duration_unit'=>null,'rental_duration_value'=>null,'item_stock'=>10],
            ['item_name'=>'GrandMA3 Lighting Console','item_description'=>'Industry-standard lighting control desk with 16,384 DMX parameters','item_type'=>'rent','item_price'=>2500000,'item_status'=>'available','rental_duration_unit'=>'day','rental_duration_value'=>1,'item_stock'=>4],
            
            // Stage Platforms
            ['item_name'=>'Aluminium Stage Platform 8x6m','item_description'=>'Modular stage system with adjustable height (0.6m-1.2m), load capacity 750kg/m²','item_type'=>'rent','item_price'=>3500000,'item_status'=>'available','rental_duration_unit'=>'day','rental_duration_value'=>1,'item_stock'=>5],
            ['item_name'=>'Mobile Stage Trailer 12x8m','item_description'=>'Self-contained mobile stage with hydraulic roof, sides, and PA wings','item_type'=>'rent','item_price'=>8500000,'item_status'=>'available','rental_duration_unit'=>'day','rental_duration_value'=>1,'item_stock'=>2],
            ['item_name'=>'Stage Deck Riser Set','item_description'=>'Portable risers for drum platforms and elevated performance areas','item_type'=>'rent','item_price'=>450000,'item_status'=>'available','rental_duration_unit'=>'day','rental_duration_value'=>1,'item_stock'=>10],
            
            // Rigging & Truss
            ['item_name'=>'Prolyte H30V Truss 3m','item_description'=>'Heavy-duty aluminum box truss, TUV certified, 400kg safe working load','item_type'=>'rent','item_price'=>250000,'item_status'=>'available','rental_duration_unit'=>'day','rental_duration_value'=>1,'item_stock'=>30],
            ['item_name'=>'CM Lodestar 1-Ton Chain Hoist','item_description'=>'Electric chain motor with variable speed control and safety brake','item_type'=>'rent','item_price'=>550000,'item_status'=>'available','rental_duration_unit'=>'day','rental_duration_value'=>1,'item_stock'=>12],
            ['item_name'=>'Ground Support System 6m','item_description'=>'Freestanding truss tower system with base plates and outriggers','item_type'=>'rent','item_price'=>1200000,'item_status'=>'available','rental_duration_unit'=>'day','rental_duration_value'=>1,'item_stock'=>6],
            
            // Power & Generators
            ['item_name'=>'Caterpillar 150KVA Generator','item_description'=>'Diesel generator with automatic voltage regulation and sound-proof enclosure','item_type'=>'rent','item_price'=>4500000,'item_status'=>'available','rental_duration_unit'=>'day','rental_duration_value'=>1,'item_stock'=>4],
            ['item_name'=>'Distro Box 400A 3-Phase','item_description'=>'Power distribution system with CEE sockets and RCD protection','item_type'=>'rent','item_price'=>350000,'item_status'=>'available','rental_duration_unit'=>'day','rental_duration_value'=>1,'item_stock'=>8],
            
            // DJ Controllers & Equipment
            ['item_name'=>'Pioneer DJ CDJ-3000 Pair','item_description'=>'Flagship professional multi-player with 9-inch touch screen','item_type'=>'rent','item_price'=>850000,'item_status'=>'available','rental_duration_unit'=>'day','rental_duration_value'=>1,'item_stock'=>6],
            ['item_name'=>'Allen & Heath Xone:96 Mixer','item_description'=>'6-channel analog DJ mixer with digital FX and dual USB soundcard','item_type'=>'buy','item_price'=>42000000,'item_status'=>'available','rental_duration_unit'=>null,'rental_duration_value'=>null,'item_stock'=>4],
            
            // LED Panels
            ['item_name'=>'ROE Visual CB5 LED Panel','item_description'=>'Indoor/outdoor LED screen with 5.9mm pixel pitch, 1000 nits brightness','item_type'=>'rent','item_price'=>1200000,'item_status'=>'available','rental_duration_unit'=>'day','rental_duration_value'=>1,'item_stock'=>40],
            ['item_name'=>'Absen A3 Pro LED Screen 3x2m','item_description'=>'High-resolution LED video wall panel with front/rear service access','item_type'=>'rent','item_price'=>2500000,'item_status'=>'available','rental_duration_unit'=>'day','rental_duration_value'=>1,'item_stock'=>16],
            
            // Crew Services
            ['item_name'=>'Professional Audio Engineer','item_description'=>'Certified FOH/Monitor engineer with 10+ years concert experience','item_type'=>'rent','item_price'=>2500000,'item_status'=>'available','rental_duration_unit'=>'day','rental_duration_value'=>1,'item_stock'=>5],
            ['item_name'=>'Lighting Designer & Operator','item_description'=>'Experienced LD with GrandMA3 certification and creative programming','item_type'=>'rent','item_price'=>2800000,'item_status'=>'available','rental_duration_unit'=>'day','rental_duration_value'=>1,'item_stock'=>4],
            ['item_name'=>'Rigging Crew Team (4 persons)','item_description'=>'IRATA certified riggers with full safety equipment and insurance','item_type'=>'rent','item_price'=>5000000,'item_status'=>'available','rental_duration_unit'=>'day','rental_duration_value'=>1,'item_stock'=>3],
            ['item_name'=>'Stage Manager','item_description'=>'Professional stage manager coordinating all technical departments','item_type'=>'rent','item_price'=>2000000,'item_status'=>'available','rental_duration_unit'=>'day','rental_duration_value'=>1,'item_stock'=>6],
        ];

        foreach ($itemsData as $data) {
            \App\Models\Item::updateOrCreate(
                ['item_name' => $data['item_name']],
                array_merge($data, ['vendor_id' => $vendor->id])
            );
        }
    }
}
