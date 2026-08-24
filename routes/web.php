<?php

use App\Http\Controllers\Backend\IndustryController;
use App\Http\Controllers\Backend\VendorController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Admin Portal Routes (Blade Views: backend.tsx)
|--------------------------------------------------------------------------
| Handles all backend routes under /admin (e.g. /admin/login, /admin/dashboard)
*/
Route::prefix('admin')->group(function () {
    Route::middleware('auth', 'verified')->group(function () {
        Route::get('/dashboard', function () {
            return view('backend.dashboard');
        })->name('dashboard');

        Route::resource('industries', IndustryController::class);
        Route::resource('vendors', VendorController::class);

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    require __DIR__ . '/auth.php';
});

/*
|--------------------------------------------------------------------------
| Frontend Mobile App Routes (React SPA: app.tsx)
|--------------------------------------------------------------------------
| Serves /, /login, /register, /dashboard for Mobile App (NativePHP) & Frontend
*/
Route::get('/{any?}', function () {
    return view('app');
})->where('any', '^(?!admin|api|_native).*')->name('react.frontend');
