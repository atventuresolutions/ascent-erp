<?php

// use Modules\Auth\Http\Controllers\AuthController;

// Route::get('/auths', [AuthController::class, 'index'])->name('auths.index');
// Route::get('/auths/create', [AuthController::class, 'create'])->name('auths.create');
// Route::post('/auths', [AuthController::class, 'store'])->name('auths.store');
// Route::get('/auths/{auth}', [AuthController::class, 'show'])->name('auths.show');
// Route::get('/auths/{auth}/edit', [AuthController::class, 'edit'])->name('auths.edit');
// Route::put('/auths/{auth}', [AuthController::class, 'update'])->name('auths.update');
// Route::delete('/auths/{auth}', [AuthController::class, 'destroy'])->name('auths.destroy');

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;

Route::group(['prefix' => 'api/auth'], function() {
    Route::post(
        'login', [AuthController::class, 'login']
    )->name('auth.login');
    Route::post(
        'logout', [AuthController::class, 'logout']
    )->name('auth.logout')->middleware('auth:sanctum');
});
