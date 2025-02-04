<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('public')->group(function () {
        Route::resource('products', ProductController::class)->only(
            ['index', 'show']
        );
    });

    Route::prefix('user')->group(function () {
        Route::get('current', [UserController::class, 'current'])->middleware('auth:sanctum');
        Route::post('login', [UserController::class, 'login']);
    });

    Route::prefix('admin')->group(function () {
        Route::resource('products', ProductController::class)->only([
            'store', 'update', 'destroy'
        ]);
    })->middleware(AdminMiddleware::class);
});
