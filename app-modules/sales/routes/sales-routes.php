<?php

// Inventory routes
use Illuminate\Support\Facades\Route;
use Modules\Sales\Http\Controllers\TransactionController;

Route::group(['prefix' => 'sales'], function () {
    // Transactions routes
    Route::get('transactions', [TransactionController::class, 'index'])
        ->name('sales.transactions.index');
    Route::post('transactions', [TransactionController::class, 'store'])
        ->name('sales.transactions.store');
    Route::get('transactions/{id}', [TransactionController::class, 'show'])
        ->name('sales.transactions.show');
    Route::put('transactions/{id}', [TransactionController::class, 'update'])
        ->name('sales.transactions.update');
    Route::delete('transactions/{id}', [TransactionController::class, 'destroy'])
        ->name('sales.transactions.destroy');
})->middleware('auth:sanctum');
