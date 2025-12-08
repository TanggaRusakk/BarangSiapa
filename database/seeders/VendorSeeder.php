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
                'name' => 'ProStage Productions',
                'password' => 'password',
                'role' => 'vendor',
                'phone_number' => '081234567890'
            ]
        );

        // Create vendor profile for the user
        \App\Models\Vendor::updateOrCreate(
            ['user_id' => $user->id],
            [
                'vendor_name' => 'ProStage Audio & Lighting',
                'location' => 'Jakarta, Indonesia',
                'description' => 'Professional concert & event equipment rental company. Serving festivals, concerts, corporate events since 2010. TUV certified rigging, premium sound & lighting systems.',
                'logo_path' => 'images/item/default_image.png'
            ]
        );
    }
}
