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
        $categories = [
            'Sound Systems',
            'Lighting Equipment',
            'Stage Platforms',
            'Rigging & Truss',
            'Power & Generators',
            'DJ Controllers',
            'LED Panels',
            'Crew Services'
        ];
        
        foreach ($categories as $category_name) {
            \App\Models\Category::updateOrCreate(['category_name' => $category_name], ['category_name' => $category_name]);
        }
    }
}
