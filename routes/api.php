<?php

use App\Http\Controllers\Api\SalesController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/check-user', [AuthController::class, 'checkUser']);
Route::post('/save-google-auth', [AuthController::class, 'saveGoogleAuth']);
Route::post('/logout', [AuthController::class, 'logout']);
// Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/change-password', [AuthController::class, 'changePassword'])->middleware('auth:sanctum');
Route::get('/get-formatted-date', [AuthController::class, 'getFormattedDate']);
Route::get('/get-formatted-time', [AuthController::class, 'getFormattedTime']);

// Sales
// Route::prefix('sales')->middleware('auth:sanctum')->group(function () {
//     Route::post('/', [SalesController::class, 'index']);
//     Route::post('/save', [SalesController::class, 'store']);
//     Route::get('/status/{id}', [SalesController::class, 'status']);
//     Route::get('/markers/{id}', [SalesController::class, 'getMarkers']);
// });
