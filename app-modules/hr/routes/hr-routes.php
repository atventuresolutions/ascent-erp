<?php

use Illuminate\Support\Facades\Route;
use Modules\Hr\Http\Controllers\AdditionController;
use Modules\Hr\Http\Controllers\DeductionController;
use Modules\Hr\Http\Controllers\EmployeeAdditionController;
use Modules\Hr\Http\Controllers\EmployeeController;
use Modules\Hr\Http\Controllers\EmployeeDeductionController;
use Modules\Hr\Http\Controllers\HolidayController;
use Modules\Hr\Http\Controllers\NoteController;
use Modules\Hr\Http\Controllers\PayrollController;
use Modules\Hr\Http\Controllers\TimekeepingController;

Route::group(['prefix' => 'api/hr'], function () {
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('holidays', HolidayController::class);
    Route::apiResource('timekeepings', TimekeepingController::class);
    Route::apiResource('additions', AdditionController::class);
    Route::apiResource('deductions', DeductionController::class);
    Route::apiResource('payrolls', PayrollController::class);

    Route::apiResource('employees.employeeDeductions', EmployeeDeductionController::class)->shallow();
    Route::apiResource('employees.employeeAdditions', EmployeeAdditionController::class)->shallow();
    Route::apiResource('employees.notes', NoteController::class)->shallow();
})->middleware('auth:sanctum');
