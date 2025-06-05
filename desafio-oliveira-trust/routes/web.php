<?php

use App\Http\Controllers\ContentController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/upload-history', [UploadController::class, 'index']);
Route::post('/uploads', [UploadController::class, 'store']);

Route::get('/contents', [ContentController::class, 'index']);
