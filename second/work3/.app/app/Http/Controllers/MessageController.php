<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Channel;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request, Channel $channel)
    {
        $validated = $request->validate([
            'content' => 'required|string'
        ]);

        $message = $channel->messages()->create([
            ...$validated,
            'user_id' => auth()->id()
        ]);

        return response()->json($message->load('user'));
    }
} 