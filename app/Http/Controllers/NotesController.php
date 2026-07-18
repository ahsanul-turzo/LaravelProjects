<?php
namespace App\Http\Controllers;

use App\Http\Requests\NoteRequest;
use App\Models\Note;
use Illuminate\Http\Request;

class NotesController
{
    public function index(Request $request)
    {
        $notes = Note::all();

        return response()->json($notes);
    }
    public function show(Note $note){
        return response()->json($note);
    }
    public function store(NoteRequest $request) {
        $note = Note::create($request->validated);
        return response()->json($note);
    }
    public function update(NoteRequest $request, Note $note){
        $note->update($request->validated);
        return response()->json($note);
    }
    public function destroy($note){

    }
}
