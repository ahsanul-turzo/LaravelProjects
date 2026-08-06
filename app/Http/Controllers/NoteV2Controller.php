<?php

namespace App\Http\Controllers;

use App\Models\NoteV2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NoteV2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notes = DB::table('notes_v2')->get();
        return view('notes.v2', compact('notes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('notes.v2create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('notes.v2show', ['note' => NoteV2::findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NoteV2 $v2)
    {
        return view('notes.v2edit', compact('v2'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NoteV2 $v2)
    {
        $validated = request()->validate([
            'note' => 'required|string|max:55',
            'description' => 'required|string',
        ]);

        $v2->update($validated);

        return $this->index();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
