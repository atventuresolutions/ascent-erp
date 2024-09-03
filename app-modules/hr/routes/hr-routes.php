<?php

use Illuminate\Support\Facades\Route;
use Modules\Hr\Http\Controllers\AdditionController;
use Modules\Hr\Http\Controllers\DeductionController;
use Modules\Hr\Http\Controllers\EmployeeController;
use Modules\Hr\Http\Controllers\HolidayController;
use Modules\Hr\Http\Controllers\TimekeepingController;

Route::group(['prefix' => 'hr'], function () {
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('holidays', HolidayController::class);
    Route::apiResource('timekeepings', TimekeepingController::class);
    Route::apiResource('additions', AdditionController::class);
    Route::apiResource('deductions', DeductionController::class);
})->middleware('auth:sanctum');
