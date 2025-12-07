<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or fetch demo vendor user
        $user = \App\Models\User::updateOrCreate(
            ['email' => 'vendor@example.test'],
            [
                'name' => 'Demo Vendor',
                'password' => 'password',
                'phone_number' => '081200000000'
            ]
        );

        // Create vendor profile for the user
        \App\Models\Vendor::updateOrCreate(
            ['user_id' => $user->id],
            [
                'vendor_name' => 'Demo Store',
                'location' => 'Jakarta, Indonesia',
                'description' => 'Official Demo Vendor for BarangSiapa',
                'logo_path' => 'images/item/default_image.png'
            ]
        );
    }
}
