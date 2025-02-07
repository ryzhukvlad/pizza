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

    Route::middleware('guest:sanctum')->name('user.')->prefix('user')->group(function () {
        Route::post('register', [UserController::class, 'register'])->name('register');
        Route::post('login', [UserController::class, 'login'])->name('login');
    });

    Route::middleware('auth:sanctum')->name('user.')->prefix('user')->group(function () {
        Route::get('logout', [UserController::class, 'logout'])->name('logout');
        Route::get('profile', [UserController::class, 'profile'])->name('profile');
        Route::get('cart', [UserController::class, 'showCart'])->name('cart.show');
        Route::post('cart', [UserController::class, 'storeCart'])->name('cart.store');

        Route::get('orders/history', [OrderController::class, 'history'])->name('orders.history');
        Route::resource('orders', OrderController::class)->only([
            'show', 'store'
        ]);
    });

    Route::middleware(AdminMiddleware::class)->name('admin.')->prefix('admin')->group(function () {
        Route::resource('products', ProductController::class)->except(['create', 'edit']);
        Route::resource('orders', OrderController::class)->only(['index', 'show', 'update']);
    });
});
