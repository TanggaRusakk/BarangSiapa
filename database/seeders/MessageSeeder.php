<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chat = \App\Models\Chat::first();
        $user = \App\Models\User::first();
        if (! $chat || ! $user) return;

        \App\Models\Message::updateOrCreate(
            ['chat_id' => $chat->id, 'user_id' => $user->id],
            ['content' => 'Hello, this is a demo message', 'sent_at' => now(), 'created_at' => now(), 'updated_at' => now()]
        );
    }
}
