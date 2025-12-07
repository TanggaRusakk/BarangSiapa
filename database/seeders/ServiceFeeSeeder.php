<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceFeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\ServiceFee::updateOrCreate(
            ['fee_description' => 'Platform Fee'],
            ['fee_amount' => 1000, 'fee_description' => 'Flat platform service fee']
        );
    }
}
