<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['Electronics', 'Home', 'Sports', 'Fashion', 'Books'];
        foreach ($categories as $category_name) {
            \App\Models\Category::updateOrCreate(['category_name' => $category_name], ['category_name' => $category_name]);
        }
    }
}
