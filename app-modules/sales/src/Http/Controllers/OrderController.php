<?php

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Inventory\Models\InventoryItem;
use Modules\Sales\Models\Order;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()
            ->json(Order::paginate());
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

        $orderItems = $validated['items'];
        $orderItemTotal = 0;
        foreach ($orderItems as $item) {

            if (!empty($item['sku'])) { // If SKU is provided, get available inventory item information
                $inventoryItem = InventoryItem::whereSku($item['sku'])->first();
                if ($inventoryItem) {
                    $item['inventory_item_id'] = $inventoryItem->id;
                    $item['name'] = $inventoryItem->name;
                }
            }

            $orderItemTotal += $item['price'] * $item['quantity'];
        }

        // Calculate tax
        $taxRate = env('TAX_RATE', 0.1);
        $validated['tax'] = $orderItemTotal * $taxRate;

        $order = Order::create([
            'customer_id' => $validated['customer_id'] ?? null,
            'status' => $validated['status'],
            'total' => $orderItemTotal,
            'discount' => $validated['discount'],
            'shipping' => $validated['shipping'],
            'tax' => $validated['tax'],
            'grand_total' => $orderItemTotal + $validated['shipping'] - $validated['discount'],
            'notes' => $validated['notes'] ?? null,
        ]);

        $order->orderItems()->createMany($orderItems);

        return response()
            ->json($order->load('orderItems'), 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()
            ->json(Order::findOrFail($id));
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

        $order = Order::findOrFail($id);
        $order->update($validated);

        return response()
            ->json($order, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()
            ->json(null, 204);
    }
}
