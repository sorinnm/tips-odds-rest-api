<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FixtureController;

//Route::post('/tokens/create', function (Request $request) {
//    return $request->user()->createToken('token-name', ['server:update'])->plainTextToken;
//})->middleware('auth:sanctum');

Route::get('/fixtures', [FixtureController::class, 'index']);
Route::get('/fixtures/{id}', [FixtureController::class, 'show']);
Route::delete('/fixtures/{id}', [FixtureController::class, 'delete']);
Route::post('/fixtures', [FixtureController::class, 'save']);
Route::post('/json-escaper', [FixtureController::class, 'escape']);
