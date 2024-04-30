<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;
use Carbon\Carbon;
use App\Http\Controllers\FixtureController;

Route::get('/', function () {
    return response()->json(['message' => 'Method is not allowed!'], 405);
});

Route::get('/timestamp', function () {
    $timestamp = Carbon::now()->timestamp;
    return response()->json(['timestamp' => $timestamp]);
});

Route::get('/fixtures', [FixtureController::class, 'index']);
Route::post('/fixtures', [FixtureController::class, 'save']);
//Route::get('/fixtures/{id}', [FixtureController::class, 'show']);

Route::post('/json-escaper', function () {
    // Get the JSON data from the request body
    //$data = $request->getBody()->getContents();

    // Check if data is not empty and is a valid JSON
    // if (!empty($data)) {
    //     // Escape all double quotes in the JSON string
    // $escapedString = str_replace('"', '\"', $data);
    //  trim($escapedString, '[');
    //     trim($escapedString, ']');
    // }
    // Send the escaped string as response
    // $response->getBody()->write(json_encode(['data' => $escapedString]));
    //     return $response->withHeader('Content-Type', 'application/json');
    // return response()->json(['timestamp' => $timestamp]);
});
