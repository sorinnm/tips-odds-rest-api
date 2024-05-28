<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use App\Http\Controllers\FixtureController;
use Opcodes\LogViewer\Http\Controllers\IndexController;

Route::get('/', function () {
    return response()->json(['message' => 'Method is not allowed!'], 405);
});

Route::get('/timestamp', function () {
    $timestamp = Carbon::now()->timestamp;
    return response()->json(['timestamp' => $timestamp]);
});

Route::get('/fixtures', [FixtureController::class, 'index']);
Route::post('/fixtures', [FixtureController::class, 'save']);

// admin
Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'login']);
    Route::post('/', [AdminController::class, 'authenticate']);

    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/sports', [AdminController::class, 'sports']);
        Route::get('/countries', [AdminController::class, 'countries']);
        Route::get('/leagues', [AdminController::class, 'leagues']);
        Route::get('/fixtures', [AdminController::class, 'fixtures']);
        Route::get('/logs', [AdminController::class, 'logs']);

        // admin user
        Route::prefix('user')->group(function () {
            Route::get('/logout', [AdminController::class, 'logout']);
        });
    });

});
