<?php

use App\Http\Controllers\ContentController;
use App\Http\Controllers\UploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/upload-history', [UploadController::class, 'index']);
Route::post('/uploads', [UploadController::class, 'store']);

Route::get('/contents', [ContentController::class, 'index']);
