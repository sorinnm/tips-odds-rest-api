<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\WordpressController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FixtureController;
use App\Http\Controllers\TextGenerationController;

Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/login', [AuthController::class, 'loginUser']);

Route::get('/fixtures', [FixtureController::class, 'index'])->middleware(['auth:sanctum', 'ability:read-api,write-api']);
Route::get('/fixtures/{id}', [FixtureController::class, 'show'])->middleware(['auth:sanctum', 'ability:read-api,write-api']);
Route::delete('/fixtures/{id}', [FixtureController::class, 'delete'])->middleware(['auth:sanctum', 'abilities:write-api']);
Route::post('/json-escaper', [FixtureController::class, 'escape'])->middleware(['auth:sanctum', 'abilities:write-api']);

// API FOOTBALL
Route::post('/fixtures', [FixtureController::class, 'storeFixturesAllData'])->middleware(['auth:sanctum', 'ability:read-api,write-api']);

// ChatGPT - text generation
Route::post('/generate', [TextGenerationController::class, 'index'])->middleware(['auth:sanctum', 'ability:read-api,write-api']);

// WordPress post
Route::post('/wordpress/post', [WordpressController::class, 'post'])->middleware(['auth:sanctum', 'ability:read-api,write-api']);

