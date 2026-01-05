<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\TestController;
use App\Http\Controllers\FileController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/test', [TestController::class, 'index']);
// Route::get('/file', [FileController::class, 'index']);
// Route::get('/file/{id}', [FileController::class, 'show']);

Route::resource('files' , FileController::class);
