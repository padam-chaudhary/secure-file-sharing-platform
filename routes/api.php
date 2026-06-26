<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FileApiController;

// Public Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public Shared File Download Route
Route::get('/shared/{token}', [FileApiController::class, 'downloadShared'])->name('api.files.shared.download');

// Protected Routes (Sanctum Authenticated)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/files', [FileApiController::class, 'index']);
    Route::post('/files', [FileApiController::class, 'store']);
    Route::get('/files/{file}/download', [FileApiController::class, 'download']);
    Route::post('/files/{file}/share', [FileApiController::class, 'share']);
});
