<?php

use App\Filament\Pages\MainDashboard; // Import the Filament page
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\ManualController;
use App\Http\Controllers\ProfileThemeController;
use App\Http\Controllers\UserManagementController;
use App\Modules\Profile\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Laravel Boost sends GET requests from the browser logger; allow a no-op response to avoid MethodNotAllowed.
Route::get('/_boost/browser-logs', fn () => response('', 204));

/*
|--------------------------------------------------------------------------
| Legal Routes (Públicas)
|--------------------------------------------------------------------------
*/
Route::prefix('legal')->name('legal.')->group(function () {
    Route::view('/terms', 'legal.terms')->name('terms');
    Route::view('/privacy', 'legal.privacy')->name('privacy');
    Route::view('/data-protection', 'legal.data-protection')->name('data-protection');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => redirect(MainDashboard::getUrl()))
        ->middleware('verified')
        ->name('dashboard');

    // Profile (vista + datos básicos)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Theme
    Route::patch('/profile/theme', [ProfileThemeController::class, 'update'])->name('profile.theme.update');

    // Password
    Route::post('/password/verify', [PasswordController::class, 'verify'])->name('password.verify');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

});
Route::get('/manual', [ManualController::class, 'index'])->name('manual.index');

/*
|--------------------------------------------------------------------------
| CONTROL DE USUARIOS (Solo SuperAdmin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:superadmin'])
    ->prefix('user-management')
    ->name('user-management.')
    ->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::get('/pending', fn () => redirect()->route('user-management.index', ['view' => 'pending']))
            ->name('pending');
        Route::get('/blocked', fn () => redirect()->route('user-management.index', ['view' => 'blocked']))
            ->name('blocked');
        Route::post('{user}/approve', [UserManagementController::class, 'approve'])->name('approve');
        Route::delete('{user}/reject', [UserManagementController::class, 'reject'])->name('reject');
        Route::patch('{user}/deactivate', [UserManagementController::class, 'deactivate'])->name('deactivate');
        Route::patch('{user}/block', [UserManagementController::class, 'block'])->name('block');
        Route::patch('{user}/unblock', [UserManagementController::class, 'unblock'])->name('unblock');
        Route::put('{user}/update-role', [UserManagementController::class, 'updateRole'])->name('update-role');
        Route::delete('{user}', [UserManagementController::class, 'destroy'])->name('destroy');
    });

require __DIR__.'/auth.php';
