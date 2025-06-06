<?php

use App\Http\Controllers\{AuthController, ContentController, UploadController, };
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'success' => true,
        'api'     => 'operational',
    ]);
});

Route::prefix('auth')
    ->middleware('guest')
    ->group(function () {
        /**
         * Auth
         */
        Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
        Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    });

Route::prefix('/v1')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

        /**
        * Uploads and Contents
        */
        Route::get('/upload-history', [UploadController::class, 'index']);
        Route::post('/uploads', [UploadController::class, 'store']);

        Route::get('/contents', [ContentController::class, 'index']);       
    });