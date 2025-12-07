<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // OrderItems are created by OrderSeeder to ensure consistency with orders/items.
        // This seeder is intentionally minimal to avoid duplication.
        return;
    }
}
