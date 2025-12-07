<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $messages = Message::latest()->take(200)->get();
        return response()->json($messages);
    }
}
