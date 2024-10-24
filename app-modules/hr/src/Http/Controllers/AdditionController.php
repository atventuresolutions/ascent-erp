<?php

namespace Modules\Hr\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Hr\Models\Addition;

class AdditionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    { 
        $additions = Addition::query();

        // Search
        if (request('search')) {
            $additions->where('name', 'like', '%' . request('search') . '%');
        }

        // Sorting
        if (request('sortField')) {
            $additions->orderBy(
                request('sortField'), 
                request('sortOrder') > 0 ? 'asc' : 'desc'
            );
        }

        $additions = $additions->paginate(request('per_page', 10));

        return response()
            ->json($additions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
        ]);

        $addition = Addition::create($validated);

        return response()
            ->json($addition);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $addition)
    {
        return response()
            ->json(Addition::findOrFail($addition));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $addition)
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
        ]);

        $addition = Addition::findOrFail($addition);
        $addition->update($validated);

        return response()
            ->json($addition);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $addition)
    {
        $addition = Addition::findOrFail($addition);
        $addition->delete();

        return response()
            ->noContent();
    }
}
