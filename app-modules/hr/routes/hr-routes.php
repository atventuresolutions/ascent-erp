<?php

use Illuminate\Support\Facades\Route;
use Modules\Hr\Http\Controllers\EmployeeController;

Route::group(['prefix' => 'hr'], function () {
    // Employee routes
    Route::get('employees', [EmployeeController::class, 'index'])
        ->name('hr.employees.index');
    Route::post('employees', [EmployeeController::class, 'store'])
        ->name('hr.employees.store');
    Route::get('employees/{id}', [EmployeeController::class, 'show'])
        ->name('hr.employees.show');
    Route::put('employees/{id}', [EmployeeController::class, 'update'])
        ->name('hr.employees.update');
    Route::delete('employees/{id}', [EmployeeController::class, 'destroy'])
        ->name('hr.employees.destroy');
})->middleware('auth:sanctum');
