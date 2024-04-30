<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FixtureController;

Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/login', [AuthController::class, 'loginUser']);

Route::get('/fixtures', [FixtureController::class, 'index'])->middleware(['auth:sanctum', 'ability:read-api,write-api']);
Route::get('/fixtures/{id}', [FixtureController::class, 'show'])->middleware(['auth:sanctum', 'ability:read-api,write-api']);
Route::delete('/fixtures/{id}', [FixtureController::class, 'delete'])->middleware(['auth:sanctum', 'abilities:write-api']);
Route::post('/fixtures', [FixtureController::class, 'save'])->middleware(['auth:sanctum', 'abilities:write-api']);
Route::post('/json-escaper', [FixtureController::class, 'escape'])->middleware(['auth:sanctum', 'abilities:write-api']);
