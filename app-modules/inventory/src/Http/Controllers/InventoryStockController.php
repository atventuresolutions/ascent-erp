<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Inventory\Models\InventoryItem;

class InventoryStockController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function update(
        Request $request,
        string $itemId
    ): JsonResponse {
        $validated = $request->validate([
            'quantity' => ['required', 'numeric'],
            'type'     => ['required', 'in:STOCK_IN,STOCK_OUT'],
            'notes'    => ['nullable'],
        ]);

        $item  = InventoryItem::find($itemId);
        $stock = $item->inventoryStock;
        if ($validated['type'] === 'STOCK_IN') {
            $stock->current += $validated['quantity'];
        } elseif ($validated['type'] === 'STOCK_OUT') {
            $stock->current -= $validated['quantity'];
        }
        $stock->save();

        // Add history
        $item->inventoryStock->inventoryStockHistories()
            ->create([
                'quantity' => $validated['quantity'],
                'type'     => $validated['type'],
                'system'   => 'INVENTORY',
                'notes'    => $validated['notes'] ?? null,
            ]);

        return response()->json(['message' => 'Stock updated successfully']);
    }
}
