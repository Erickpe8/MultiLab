<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\UserManagementController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

/*
|--------------------------------------------------------------------------
| Legal Routes (Públicas)
|--------------------------------------------------------------------------
*/
    Route::prefix('legal')->name('legal.')->group(function () {
    Route::view('/terms', 'legal.terms')->name('terms');
    Route::view('/privacy', 'legal.privacy')->name('privacy');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Password
    Route::post('/password/verify', [PasswordController::class, 'verify'])->name('password.verify');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
});

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
        Route::post('{user}/approve', [UserManagementController::class, 'approve'])->name('approve');
        Route::delete('{user}/reject', [UserManagementController::class, 'reject'])->name('reject');
        Route::patch('{user}/deactivate', [UserManagementController::class, 'deactivate'])->name('deactivate');
        Route::put('{user}/update-role', [UserManagementController::class, 'updateRole'])->name('update-role');
        Route::delete('{user}', [UserManagementController::class, 'destroy'])->name('destroy');
    });

require __DIR__ . '/auth.php';
