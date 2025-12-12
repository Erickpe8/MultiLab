<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LegalController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\Modules\EducacionSuperior\EducacionSuperiorController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Legal Routes (Públicas)
|--------------------------------------------------------------------------
*/
Route::prefix('legal')->name('legal.')->group(function () {
    Route::get('/terms', [LegalController::class, 'terms'])->name('terms');
    Route::get('/privacy', [LegalController::class, 'privacy'])->name('privacy');
    Route::get('/data-protection', [LegalController::class, 'dataProtection'])->name('data-protection');
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
    Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/superadmin/users', [UserManagementController::class, 'index'])->name('superadmin.users');
    });
    Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin/users')->group(function () {
    Route::get('/', [UserManagementController::class, 'index'])->name('superadmin.users');
    Route::get('/{id}', [UserManagementController::class, 'show'])->name('superadmin.users.show');
    });

/*
|--------------------------------------------------------------------------
| MÓDULO POA – PILAR 1: EDUCACIÓN SUPERIOR
| Actor: Director de Programa
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'area:educacion_superior'])
    ->prefix('poa/educacion-superior')
    ->name('poa.educacion_superior.')
    ->group(function () {
    // Dashboard del Director de Programa (Pilar 1)
    Route::get('/director', [EducacionSuperiorController::class, 'directorDashboard'])
    ->name('director.dashboard');
});
Route::middleware(['auth', 'check.area:aux_admin'])->group(function () {
// solo auxiliar (y superadmin)
});

Route::middleware(['auth', 'check.area:docente,aux_admin'])->group(function () {
// docente o auxiliar (y superadmin)
});


require __DIR__.'/auth.php';
