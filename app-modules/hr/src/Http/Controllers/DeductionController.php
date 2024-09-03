<?php

namespace Modules\Hr\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Hr\Models\Deduction;

class DeductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()
            ->json(Deduction::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
        ]);

        $deduction = Deduction::create($validated);

        return response()
            ->json($deduction);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $deduction)
    {
        return response()
            ->json(Deduction::findOrFail($deduction));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $deduction)
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
        ]);

        $deduction = Deduction::findOrFail($deduction);
        $deduction->update($validated);

        return response()
            ->json($deduction);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $deduction)
    {
        $deduction = Deduction::findOrFail($deduction);
        $deduction->delete();

        return response()
            ->noContent();
    }
}
