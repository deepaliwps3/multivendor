<?php

use App\Http\Controllers\Backend\IndustryController;
use App\Http\Controllers\Backend\PaymentController;
use App\Http\Controllers\Backend\ServiceController;
use App\Http\Controllers\Backend\StaffController;
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

if (! function_exists('isMobileOrNative')) {
    function isMobileOrNative(Request $request): bool
    {
        $userAgent = $request->header('User-Agent', '');
        $host      = $request->header('Host', '') . ' ' . $request->header('X-Forwarded-Host', '');

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
        Route::patch('industries/{industry}/toggle-status', [IndustryController::class, 'toggleStatus'])
            ->name('industries.toggle-status');
        Route::get('industries/{industry}/services', [WorkflowTemplateController::class, 'servicesByIndustry'])
            ->name('industries.services');
        Route::resource('services', ServiceController::class);
        Route::resource('workflow-templates', WorkflowTemplateController::class);
        Route::resource('vendors', VendorController::class);

        // Add Staff / Edit Staff (dedicated pages, not modals)
        Route::get('staff/create', [StaffController::class, 'create'])->name('staff.create');
        Route::post('staff', [StaffController::class, 'store'])->name('staff.store');
        Route::get('staff/{staff}/edit', [StaffController::class, 'edit'])->name('staff.edit');
        Route::put('staff/{staff}', [StaffController::class, 'update'])->name('staff.update');

        // Staff & Permissions listing (DataTable)
        Route::get('staff-permissions', [StaffPermissionController::class, 'index'])->name('staff-permissions.index');
        Route::patch('staff-permissions/{staff}/toggle-status', [StaffPermissionController::class, 'toggleStatus'])
            ->name('staff-permissions.toggle-status');

        // Assign Permissions matrix page
        Route::get('staff-permissions/{staff}/permissions', [StaffPermissionController::class, 'permissions'])
            ->name('staff-permissions.permissions');
        Route::post('staff-permissions/{staff}/permissions', [StaffPermissionController::class, 'savePermissions'])
            ->name('staff-permissions.permissions.save');

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
