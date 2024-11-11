<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::all();
        return view('notes.index', compact('notes'));
    }

    public function store(Request $request)
    {
        $note = Note::create([
            'title' => $request->title,
            'content' => $request->content
        ]);

        return response()->json($note);
    }

    public function update(Request $request, Note $note)
    {
        $note->update([
            'title' => $request->title,
            'content' => $request->content
        ]);

        return response()->json($note);
    }

    public function destroy(Note $note)
    {
        $note->delete();
        return response()->json(['message' => 'Note deleted successfully']);
    }
} 