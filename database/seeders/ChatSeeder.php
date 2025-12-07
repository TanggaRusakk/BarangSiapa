<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::first();
        if (! $user) return;

        \App\Models\Chat::updateOrCreate(
            ['user_id' => $user->id],
            ['last_message_at' => now()]
        );
    }
}
