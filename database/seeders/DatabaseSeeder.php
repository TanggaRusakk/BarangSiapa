<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Ensure a Test User exists (avoid duplicate unique errors)
        \App\Models\User::updateOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => 'password', 'phone_number' => '081234567890']
        );

        // Seed demo data in focused seeders
        $this->call([
            CategorySeeder::class,
            VendorSeeder::class,
            ItemSeeder::class,
            ItemGallerySeeder::class,
            ItemCategorySeeder::class,
            AdSeeder::class,
            ReviewSeeder::class,
            ChatSeeder::class,
            MessageSeeder::class,
            ServiceFeeSeeder::class,
            OrderSeeder::class,
            OrderServiceFeeSeeder::class,
            PaymentSeeder::class,
            RentalInfoSeeder::class,
        ]);
    }
}
