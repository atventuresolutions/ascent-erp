<?php

namespace Modules\Hr\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Hr\Models\Note;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $employee)
    {
        $items = Note::whereEmployeeId($employee)
            ->with('user')
            ->paginate();

        return response()
            ->json($items);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $employee)
    {
        $validated = $request->validate([
            'type'    => ['required', 'in:EMPLOYMENT,COMPENSATION,REVIEW,DOCUMENT,OTHER'],
            'title'   => ['required', 'string'],
            'content' => ['required', 'string'],
        ]);

        $noteUrl = null;
        if ($request->has('file')) {
            // todo: upload file
        }

        $note = Note::create(array_merge($validated, [
            'employee_id' => $employee,
            'user_id'     => $request->user()->id,
            'file'        => $noteUrl,
        ]));

        return response()
            ->json($note);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $note)
    {
        return response()
            ->json(Note::findOrFail($note));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $note)
    {
        $validated = $request->validate([
            'type'    => ['required', 'in:EMPLOYMENT,COMPENSATION,REVIEW,DOCUMENT,OTHER'],
            'title'   => ['required', 'string'],
            'content' => ['required', 'string'],
        ]);

        $noteUrl = null;
        if ($request->has('file')) {
            // todo: upload file
        }

        $note = Note::findOrFail($note);
        $note->update(array_merge($validated, [
            'file' => $noteUrl,
        ]));

        return response()
            ->json($note);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $note)
    {
        $note = Note::findOrFail($note);
        $note->delete();

        return response()
            ->noContent();
    }
}
