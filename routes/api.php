<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| API ROUTES
|--------------------------------------------------------------------------
| Base URL: /api/v1
*/

Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PUBLIC ROUTES (NO AUTH)
    |--------------------------------------------------------------------------
    */

    // AUTH
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [ForgotPasswordController::class, 'requestReset']);
    Route::post('forgot-password/send-otp', [ForgotPasswordController::class, 'sendOtp']);
    Route::post('forgot-password/reset', [ForgotPasswordController::class, 'resetPassword']);

    // PRODUCTS
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{id}', [ProductController::class, 'show']);

    // CATEGORIES
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{id}', [CategoryController::class, 'show']);

    // PRODUCT IMAGES
    Route::get('product-images', [ProductImageController::class, 'index']);
    Route::get('product-images/{id}', [ProductImageController::class, 'show']);

    /*
    |--------------------------------------------------------------------------
    | PROTECTED ROUTES (AUTH:SANTCUM)
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth:sanctum')->group(function () {

        // AUTH
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('logout-all', [AuthController::class, 'logoutAll']);

        // ORDERS (USER)
        Route::get('orders', [OrderController::class, 'index']);
        Route::get('orders/{id}', [OrderController::class, 'show']);
        Route::post('orders', [OrderController::class, 'store']);

        /*
        |--------------------------------------------------------------------------
        | ADMIN ONLY
        |--------------------------------------------------------------------------
        */
        Route::middleware('role:admin')->group(function () {

            // PRODUCTS
            Route::post('products', [ProductController::class, 'store']);
            Route::put('products/{id}', [ProductController::class, 'update']);
            Route::delete('products/{id}', [ProductController::class, 'destroy']);

            // CATEGORIES
            Route::post('categories', [CategoryController::class, 'store']);
            Route::put('categories/{id}', [CategoryController::class, 'update']);
            Route::delete('categories/{id}', [CategoryController::class, 'destroy']);

            // PRODUCT IMAGES
            Route::post('product-images', [ProductImageController::class, 'store']);
            Route::put('product-images/{id}', [ProductImageController::class, 'update']);
            Route::delete('product-images/{id}', [ProductImageController::class, 'destroy']);

            // ROLES
            Route::get('roles', [RoleController::class, 'index']);
            Route::get('roles/{id}', [RoleController::class, 'show']);
            Route::post('roles', [RoleController::class, 'store']);
            Route::put('roles/{id}', [RoleController::class, 'update']);
            Route::delete('roles/{id}', [RoleController::class, 'destroy']);
        });
    });
});
