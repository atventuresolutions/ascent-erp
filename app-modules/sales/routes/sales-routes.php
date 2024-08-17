<?php

// Inventory routes
use Illuminate\Support\Facades\Route;
use Modules\Sales\Http\Controllers\OrderController;

Route::group(['prefix' => 'sales'], function () {
    // Inventory item routes
    Route::get('orders', [OrderController::class, 'index'])
        ->name('sales.orders.index');
    Route::post('orders', [OrderController::class, 'store'])
        ->name('sales.orders.store');
    Route::get('orders/{id}', [OrderController::class, 'show'])
        ->name('sales.orders.show');
    Route::put('orders/{id}', [OrderController::class, 'update'])
        ->name('sales.orders.update');
    Route::delete('orders/{id}', [OrderController::class, 'destroy'])
        ->name('sales.orders.destroy');
})->middleware('auth:sanctum');
