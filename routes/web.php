<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ApiTestController;
use App\Http\Controllers\Admin\CountriesController;
use App\Http\Controllers\Admin\FixturesController;
use App\Http\Controllers\Admin\LeaguesController;
use App\Http\Controllers\Admin\SeasonsController;
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
    Route::get('/', [AdminController::class, 'login'])->name('admin.login');
    Route::post('/', [AdminController::class, 'authenticate'])->name('admin.authenticate');

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

        Route::prefix('leagues')->group(function () {
            Route::get('/', [LeaguesController::class, 'index'])->name('leagues.index');
            Route::get('/add', [LeaguesController::class, 'add'])->name('leagues.add');
            Route::post('/add', [LeaguesController::class, 'save'])->name('leagues.save');
            Route::get('/edit/{id}', [LeaguesController::class, 'edit'])->name('leagues.edit');
            Route::put('/edit/{id}', [LeaguesController::class, 'update'])->name('leagues.update');
            Route::delete('/delete', [LeaguesController::class, 'delete'])->name('leagues.delete');
        });

        Route::prefix('fixtures')->group(function () {
            Route::get('/', [FixturesController::class, 'index'])->name('fixtures.index');
            Route::get('/details/{id}', [FixturesController::class, 'details'])->name('fixtures.details');
            Route::post('/data-integrity-check', [FixturesController::class, 'ajaxDataIntegrity'])->name('ajax.admin.fixtures.dataIntegrityCheck');
        });

        Route::prefix('seasons')->group(function () {
            Route::get('/', [SeasonsController::class, 'index'])->name('seasons.index');
            Route::get('/add', [SeasonsController::class, 'add'])->name('seasons.add');
            Route::post('/add', [SeasonsController::class, 'save'])->name('seasons.save');
            Route::post('/delete', [SeasonsController::class, 'delete'])->name('seasons.delete');
            Route::post('/status', [SeasonsController::class, 'status'])->name('seasons.status');
        });

        Route::prefix('api/test')->group(function () {
            Route::get('/', [ApiTestController::class, 'index'])->name('api.test');
        });

        Route::get('/logs', [AdminController::class, 'logs']);

        Route::prefix('settings')->group(function () {
            Route::get('/', [AdminController::class, 'settings'])->name('settings.index');
        });

        // admin user
        Route::prefix('user')->group(function () {
            Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');
        });
    });

});
