<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Inventory\Models\InventoryItem;

class InventoryItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(InventoryItem::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sku'             => ['required'],
            'name'            => ['required'],
            'unit_of_measure' => ['required'],
            'price'           => ['required'],
            'location'        => ['required'],
            'status'          => ['required'],

            'description' => ['nullable'],
            'notes'       => ['nullable'],
        ]);

        $item = InventoryItem::create($validated);

        return response()->json($item, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        return response()->json(InventoryItem::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
        string $id
    ): JsonResponse {
        $item = InventoryItem::findOrFail($id);

        $validated = $request->validate([
            'sku'             => ['required'],
            'name'            => ['required'],
            'unit_of_measure' => ['required'],
            'price'           => ['required'],
            'location'        => ['required'],
            'status'          => ['required'],

            'description' => ['nullable'],
            'notes'       => ['nullable'],
        ]);

        $item->update($validated);

        return response()->json($item);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $item = InventoryItem::findOrFail($id);

        $item->delete();

        return response()->json(null, 204);
    }
}
