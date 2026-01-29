<?php

use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\CabangController;
use App\Http\Controllers\Api\MitraController;
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

Route::get('/get-branch-list', [BranchController::class, 'getBranchList']);

// Mitra
Route::prefix('mitra')->group(function () {
    Route::get('/load-pengumuman', [MitraController::class, 'loadPengumuman']);
    Route::post('/load-kritiksaran', [MitraController::class, 'loadKritikSaran']);
    Route::post('/save-kritiksaran', [MitraController::class, 'saveKritikSaran']);
    Route::post('/save-position', [MitraController::class, 'savePosition']);
    Route::post('/save-omzet', [MitraController::class, 'saveOmzet']);
    Route::post('/load-omzet', [MitraController::class, 'loadOmzet']);
    Route::post('/load-rekap', [MitraController::class, 'loadRekap']);
    Route::post('/load-omzet-pekanan', [MitraController::class, 'loadOmzetPekanan']);
    Route::delete('/hapus-pengeluaran', [MitraController::class, 'hapusPengeluaran']);
    Route::get('/get-jenis-pengeluaran-list', [MitraController::class, 'getJenisPengeluaranList']);
    Route::post('/load-image-pengeluaran', [MitraController::class, 'loadImagePengeluaran']);
    Route::post('/upload-image-pengeluaran', [MitraController::class, 'uploadImagePengeluaran']);
});

// Cabang
Route::prefix('cabang')->group(function () {
    Route::post('/gerobak-aktif', [CabangController::class, 'gerobakAktif']);
});

// Sales
// Route::prefix('sales')->middleware('auth:sanctum')->group(function () {
//     Route::post('/', [SalesController::class, 'index']);
//     Route::post('/save', [SalesController::class, 'store']);
//     Route::get('/status/{id}', [SalesController::class, 'status']);
//     Route::get('/markers/{id}', [SalesController::class, 'getMarkers']);
// });
