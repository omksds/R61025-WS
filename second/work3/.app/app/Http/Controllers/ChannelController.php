<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function index()
    {
        $channels = Channel::with('creator')->get();
        return view('channels.index', compact('channels'));
    }

    public function show(Channel $channel)
    {
        $messages = $channel->messages()->with('user')->latest()->get();
        return view('channels.show', compact('channel', 'messages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $channel = Channel::create([
            ...$validated,
            'created_by' => auth()->id()
        ]);

        return redirect()->route('channels.show', $channel);
    }
} 