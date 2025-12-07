<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $chats = Chat::with('messages')->latest()->take(50)->get();
        return response()->json($chats);
    }
}
