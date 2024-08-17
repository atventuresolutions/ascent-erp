<?php

use Illuminate\Support\Facades\Route;
use Modules\Crm\Http\Controllers\CustomerController;

// Inventory routes
Route::group(['prefix' => 'crm'], function() {
    // Inventory item routes
    Route::get('customers', [CustomerController::class, 'index'])
        ->name('crm.customers.index');
    Route::post('customers', [CustomerController::class, 'store'])
        ->name('crm.customers.store');
    Route::get('customers/{id}', [CustomerController::class, 'show'])
        ->name('crm.customers.show');
    Route::put('customers/{id}', [CustomerController::class, 'update'])
        ->name('crm.customers.update');
    Route::delete('customers/{id}', [CustomerController::class, 'destroy'])
        ->name('crm.customers.destroy');
})->middleware('auth:sanctum');
