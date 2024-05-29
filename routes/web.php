<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CountriesController;
use App\Http\Controllers\Admin\SportsController;
use App\Http\Controllers\FixtureController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

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

        Route::prefix('sports')->group(function () {
            Route::get('/', [SportsController::class, 'index'])->name('sports.index');
            Route::get('/add', [SportsController::class, 'add'])->name('sports.add');
            Route::post('/add', [SportsController::class, 'save'])->name('sports.save');
            Route::get('/edit/{id}', [SportsController::class, 'edit'])->name('sports.edit');
            Route::put('/edit/{id}', [SportsController::class, 'update'])->name('sports.update');
            Route::delete('/delete', [SportsController::class, 'delete'])->name('sports.delete');
        });

        Route::prefix('countries')->group(function () {
            Route::get('/', [CountriesController::class, 'index'])->name('countries.index');
            Route::get('/add', [CountriesController::class, 'add'])->name('countries.add');
            Route::post('/add', [CountriesController::class, 'save'])->name('countries.save');
            Route::get('/edit/{id}', [CountriesController::class, 'edit'])->name('countries.edit');
            Route::put('/edit/{id}', [CountriesController::class, 'update'])->name('countries.update');
            Route::delete('/delete', [CountriesController::class, 'delete'])->name('countries.delete');
        });

        Route::get('/leagues', [AdminController::class, 'leagues']);
        Route::get('/fixtures', [AdminController::class, 'fixtures']);
        Route::get('/logs', [AdminController::class, 'logs']);

        // admin user
        Route::prefix('user')->group(function () {
            Route::get('/logout', [AdminController::class, 'logout']);
        });
    });

});
