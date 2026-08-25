<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\IndustryApiController;
use App\Http\Controllers\Api\VendorDashboardController;
use App\Http\Controllers\Backend\AdminAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
 * |--------------------------------------------------------------------------
 * | React Frontend & Mobile App Auth API Routes
 * |--------------------------------------------------------------------------
 */
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthApiController::class, 'login']);
    Route::post('/register', [AuthApiController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthApiController::class, 'logout']);
        Route::get('/me', [AuthApiController::class, 'me']);
    });
});

/*
 * |--------------------------------------------------------------------------
 * | Vendor Portal Dashboard Routes
 * |--------------------------------------------------------------------------
 */
Route::middleware('auth:sanctum')->prefix('vendor')->group(function () {
    Route::get('/me', [VendorDashboardController::class, 'me']);
    Route::put('/profile', [VendorDashboardController::class, 'updateProfile']);
    Route::get('/dashboard/alerts', [VendorDashboardController::class, 'alerts']);
    Route::get('/dashboard/summary', [VendorDashboardController::class, 'summary']);
    Route::get('/dashboard/activity', [VendorDashboardController::class, 'activity']);
    Route::get('/dashboard', [VendorDashboardController::class, 'index']);
});

// Public Industry & Services lookup routes for vendor onboarding
Route::get('/industries', [IndustryApiController::class, 'index']);
Route::get('/services', [IndustryApiController::class, 'services']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
 * |--------------------------------------------------------------------------
 * | Web Admin Backend API Routes
 * |--------------------------------------------------------------------------
 */
Route::prefix('admin/auth')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/register', [AdminAuthController::class, 'register']);
        Route::post('/logout', [AdminAuthController::class, 'logout']);
        Route::get('/me', [AdminAuthController::class, 'me']);
    });
});
