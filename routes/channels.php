<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Chat channel - authorize if user is part of the chat
Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    $chat = \App\Models\Chat::find($chatId);
    if (!$chat) {
        return false;
    }
    // User is authorized if they are either the user or vendor in this chat
    return $chat->user_id === $user->id || $chat->vendor_user_id === $user->id;
});
