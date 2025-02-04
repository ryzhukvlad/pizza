<?php

use App\Http\Controllers\OrderController;
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

    Route::post('user/login', [UserController::class, 'login'])->middleware('guest:sanctum');
    Route::prefix('user')->group(function () {
        Route::get('logout', [UserController::class, 'logout']);
        Route::get('current', [UserController::class, 'current']);
        Route::get('cart', [UserController::class, 'cart']);
        Route::resource('orders', OrderController::class)->only(['show', 'store']);
    })->middleware('auth:sanctum');

    Route::prefix('admin')->group(function () {
        Route::resource('products', ProductController::class)->only([
            'store', 'update', 'destroy'
        ]);
        Route::resource('orders', OrderController::class)->only(['index', 'update']);
    })->middleware(AdminMiddleware::class);
});
