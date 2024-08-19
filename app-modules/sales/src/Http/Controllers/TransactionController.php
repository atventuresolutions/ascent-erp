<?php

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Inventory\Models\InventoryItem;
use Modules\Sales\Models\Transaction;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()
            ->json(Transaction::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['nullable', 'numeric'],
            'status' => ['required', 'string'],
            'discount' => ['required', 'numeric'],
            'shipping' => ['required', 'numeric'],
            'notes' => ['nullable', 'string'],

            'items' => ['required', 'array'],
            'items.*.sku' => ['nullable', 'string'],
            'items.*.name' => ['required', 'string'],
            'items.*.price' => ['required', 'numeric'],
            'items.*.quantity' => ['required', 'numeric'],
        ]);

        $transactionItems = $validated['items'];
        $transactionItemTotal = 0;
        foreach ($transactionItems as $item) {

            if (!empty($item['sku'])) { // If SKU is provided, get available inventory item information
                $inventoryItem = InventoryItem::whereSku($item['sku'])->first();
                if ($inventoryItem) {
                    $item['inventory_item_id'] = $inventoryItem->id;
                    $item['name'] = $inventoryItem->name;
                }
            }

            $transactionItemTotal += $item['price'] * $item['quantity'];
        }

        // Calculate tax
        $taxRate = env('TAX_RATE', 0.1);
        $validated['tax'] = $transactionItemTotal * $taxRate;

        $transaction = Transaction::create([
            'customer_id' => $validated['customer_id'] ?? null,
            'status' => $validated['status'],
            'total' => $transactionItemTotal,
            'discount' => $validated['discount'],
            'shipping' => $validated['shipping'],
            'tax' => $validated['tax'],
            'grand_total' => $transactionItemTotal + $validated['shipping'] - $validated['discount'],
            'notes' => $validated['notes'] ?? null,
        ]);

        $transaction->transactionItems()->createMany($transactionItems);

        return response()
            ->json($transaction->load('transactionItems'), 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()
            ->json(Transaction::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $transaction = Transaction::findOrFail($id);
        $transaction->update($validated);

        return response()
            ->json($transaction, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return response()
            ->json(null, 204);
    }
}
