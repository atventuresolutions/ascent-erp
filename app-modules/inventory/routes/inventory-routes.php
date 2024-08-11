<?php

use Illuminate\Support\Facades\Route;
use Modules\Inventory\Http\Controllers\InventoryItemController;

// Inventory routes
Route::group(['prefix' => 'inventory'], function() {
    // Inventory item routes
    Route::get('items', [InventoryItemController::class, 'index'])
         ->name('inventory.items.index');
    Route::post('items', [InventoryItemController::class, 'store'])
         ->name('inventory.items.store');
    Route::get('items/{id}', [InventoryItemController::class, 'show'])
         ->name('inventory.items.show');
    Route::put('items/{id}', [InventoryItemController::class, 'update'])
         ->name('inventory.items.update');
    Route::delete('items/{id}', [InventoryItemController::class, 'destroy'])
         ->name('inventory.items.destroy');
})->middleware('auth:sanctum');
