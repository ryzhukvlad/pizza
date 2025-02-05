<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::name('public.')->prefix('public')->group(function () {
        Route::resource('products', ProductController::class)->only(
            ['index', 'show']
        );
    });

    Route::name('user.')->prefix('user')->group(function () {
        Route::post('register', [UserController::class, 'register'])->name('register');
        Route::post('login', [UserController::class, 'login'])->name('login');
    })->middleware('guest:sanctum');

    Route::name('user.')->prefix('user')->group(function () {
        Route::get('logout', [UserController::class, 'logout'])->name('logout');
        Route::get('current', [UserController::class, 'current'])->name('current');
        Route::get('cart', [UserController::class, 'cart'])->name('cart');

        Route::prefix('orders')->group(function () {
            Route::get('history', [OrderController::class, 'history']);
            Route::resource('', OrderController::class)->only([
                'show', 'store'
            ]);
        });
    })->middleware('auth:sanctum');

    Route::middleware(AdminMiddleware::class)->name('admin.')->prefix('admin')->group(function () {
        Route::resource('products', ProductController::class)->except(['create', 'edit']);
        Route::resource('orders', OrderController::class)->only(['index', 'show', 'update']);
    });
});
