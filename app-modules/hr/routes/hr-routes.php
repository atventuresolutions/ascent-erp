<?php

use Illuminate\Support\Facades\Route;
use Modules\Hr\Http\Controllers\EmployeeController;
use Modules\Hr\Http\Controllers\HolidayController;

Route::group(['prefix' => 'hr'], function () {
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('holidays', HolidayController::class);
})->middleware('auth:sanctum');
