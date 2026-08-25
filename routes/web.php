<?php

use App\Http\Controllers\Backend\IndustryController;
use App\Http\Controllers\Backend\PaymentController;
use App\Http\Controllers\Backend\ServiceController;
use App\Http\Controllers\Backend\StaffPermissionController;
use App\Http\Controllers\Backend\VendorController;
use App\Http\Controllers\Backend\WorkflowTemplateController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
 * |--------------------------------------------------------------------------
 * | Helper Function: Detect Mobile Device or NativePHP Jump App
 * |--------------------------------------------------------------------------
 */
if (!function_exists('isMobileOrNative')) {
    function isMobileOrNative(Request $request): bool
    {
        $userAgent = $request->header('User-Agent', '');
        $host = $request->header('Host', '') . ' ' . $request->header('X-Forwarded-Host', '');

        return str_contains($userAgent, 'Android') ||
            str_contains($userAgent, 'iPhone') ||
            str_contains($userAgent, 'iPad') ||
            str_contains($userAgent, 'Mobile') ||
            $request->hasHeader('X-NativePHP') ||
            str_contains($host, '3000') ||
            str_contains($host, '8100') ||
            str_contains($host, '192.168') ||
            str_contains($host, '10.') ||
            str_contains($host, '172.');
    }
}

/*
 * |--------------------------------------------------------------------------
 * | Smart Root & Auth URL Routers
 * |--------------------------------------------------------------------------
 * | - Mobile / NativePHP Jump App -> Serves React SPA (app.blade.php)
 * | - Desktop Web Browser -> Redirects to /admin/login (Backend Admin)
 */
Route::get('/', function (Request $request) {
    if (isMobileOrNative($request)) {
        return view('app');
    }

    return redirect('/admin/login');
});

Route::get('/login', function (Request $request) {
    if (isMobileOrNative($request)) {
        return view('app');
    }

    return redirect('/admin/login');
});

/*
 * |--------------------------------------------------------------------------
 * | Web Admin Portal Routes (Backend Blade Views & JS: backend.tsx)
 * |--------------------------------------------------------------------------
 */
Route::prefix('admin')->group(function () {
    Route::get('/', function () {
        return redirect('/admin/login');
    });

    Route::middleware('auth', 'verified')->group(function () {
        Route::get('/dashboard', function () {
            return view('backend.dashboard');
        })->name('dashboard');

        Route::resource('industries', IndustryController::class);
        Route::resource('services', ServiceController::class);
        Route::resource('workflow-templates', WorkflowTemplateController::class);
        Route::resource('vendors', VendorController::class);
        Route::resource('staff-permissions', StaffPermissionController::class);
        Route::resource('payments', PaymentController::class);

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    require __DIR__ . '/auth.php';
});

/*
 * |--------------------------------------------------------------------------
 * | React Frontend Mobile App Catch-All Route (Bottom Fallback)
 * |--------------------------------------------------------------------------
 * | Directs all React SPA routes (/dashboard, /profile/*, /register, /frontend)
 * | to view('app') so React Router handles them seamlessly without 404s.
 */
Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!admin|api|assets|build|storage).*')->name('react.frontend');
