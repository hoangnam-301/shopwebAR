<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;

Route::prefix('v1')->group(function () {

    // ========================
    // AUTH ROUTES
    // ========================
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    // Reset password routes
    Route::post('forgot-password', [ForgotPasswordController::class, 'requestReset']);
    Route::post('forgot-password/send-otp', [ForgotPasswordController::class, 'sendOtp']);
    Route::post('forgot-password/reset', [ForgotPasswordController::class, 'resetPassword']);

    // ========================
    // PROTECTED ROUTES
    // ========================
    Route::middleware(['auth:sanctum'])->group(function () {

        // Logout
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('logout-all', [AuthController::class, 'logoutAll']);

        // ========================
        // PRODUCT ROUTES
        // ========================
        Route::get('products', [ProductController::class, 'index']);
        Route::get('products/{id}', [ProductController::class, 'show']);

        // Admin-only product routes
        Route::middleware(['role:admin'])->group(function () {
            Route::post('products', [ProductController::class, 'store']);
            Route::put('products/{id}', [ProductController::class, 'update']);
            Route::delete('products/{id}', [ProductController::class, 'destroy']);
        });

        // ========================
        // CATEGORY ROUTES
        // ========================
        Route::get('categories', [CategoryController::class, 'index']);
        Route::get('categories/{id}', [CategoryController::class, 'show']);

        // Admin-only category routes
        Route::middleware(['role:admin'])->group(function () {
            Route::post('categories', [CategoryController::class, 'store']);
            Route::put('categories/{id}', [CategoryController::class, 'update']);
            Route::delete('categories/{id}', [CategoryController::class, 'destroy']);
        });

        // ========================
        // ORDER ROUTES
        // ========================
        Route::get('orders', [OrderController::class, 'index']);
        Route::get('orders/{id}', [OrderController::class, 'show']);

        // Admin-only order routes
        Route::middleware(['role:admin'])->group(function () {
            Route::post('orders', [OrderController::class, 'store']);
            Route::put('orders/{id}', [OrderController::class, 'update']);
            Route::delete('orders/{id}', [OrderController::class, 'destroy']);
        });

        // ========================
        // PRODUCT IMAGE ROUTES
        // ========================
        Route::get('product-images', [ProductImageController::class, 'index']);
        Route::get('product-images/{id}', [ProductImageController::class, 'show']);

        // Admin-only product image routes
        Route::middleware(['role:admin'])->group(function () {
            Route::post('product-images', [ProductImageController::class, 'store']);
            Route::put('product-images/{id}', [ProductImageController::class, 'update']);
            Route::delete('product-images/{id}', [ProductImageController::class, 'destroy']);
        });

        // ========================
        // ROLE ROUTES (ADMIN ONLY)
        // ========================
        Route::middleware(['role:admin'])->group(function () {
            Route::get('roles', [RoleController::class, 'index']);
            Route::get('roles/{id}', [RoleController::class, 'show']);
            Route::post('roles', [RoleController::class, 'store']);
            Route::put('roles/{id}', [RoleController::class, 'update']);
            Route::delete('roles/{id}', [RoleController::class, 'destroy']);
        });

    }); // end auth:sanctum
});
