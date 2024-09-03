<?php

namespace Modules\Hr\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Hr\Models\Holiday;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()
            ->json(Holiday::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'date' => ['required', 'date'],
            'type' => ['required', 'in:REGULAR,SPECIAL'],
        ]);

        $holiday = Holiday::create([
            'name' => $validated['name'],
            'date' => $validated['date'],
            'type' => $validated['type'],
        ]);

        return response()
            ->json($holiday);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $holiday)
    {
        return response()
            ->json(Holiday::findOrFail($holiday));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $holiday)
    {
        $holiday = Holiday::findOrFail($holiday);

        $validated = $request->validate([
            'name' => ['required', 'string'],
            'date' => ['required', 'date'],
            'type' => ['required', 'in:REGULAR,SPECIAL'],
        ]);

        $holiday->update([
            'name' => $validated['name'],
            'date' => $validated['date'],
            'type' => $validated['type'],
        ]);

        return response()
            ->json($holiday);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $holiday)
    {
        $holiday = Holiday::findOrFail($holiday);

        $holiday->delete();

        return response()
            ->json($holiday, 204);
    }
}
