<?php

use App\Http\Controllers\API\NotesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('notes', NotesController::class);

Route::fallback(function () {
    return response()->json([
        'message' => 'Route not found.',
        'status' => 404,
        'data' => null
    ], 404);
});
