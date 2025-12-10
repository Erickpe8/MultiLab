<?php

use Illuminate\Support\Facades\Route;

// Controladores nativos
use App\Http\Controllers\ProfileController;

// Controladores modulares
use App\Adapters\Http\Controllers\AssetController;
use App\Adapters\Http\Controllers\MaterialController;
use App\Adapters\Http\Controllers\LoanController;
use App\Adapters\Http\Controllers\ReservationController;
use App\Adapters\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes - Multilab FESC
|--------------------------------------------------------------------------
|
| Aquí se registran las rutas web del sistema bajo arquitectura modular.
| Incluye autenticación, roles, políticas y vistas estáticas públicas.
|
*/

// Página inicial (pública)
Route::get('/', function () {
    return view('welcome');
});

// Panel principal del sistema
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard principal
    Route::view('/dashboard', 'dashboard.index')->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Perfil del usuario autenticado
    |--------------------------------------------------------------------------
    */
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Módulo de Inventario
    |--------------------------------------------------------------------------
    */
    Route::prefix('inventory')->name('inventory.')->middleware('permission:view inventory')->group(function () {
        Route::resource('assets', AssetController::class);
        Route::resource('materials', MaterialController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | Módulo de Préstamos y Reservas
    |--------------------------------------------------------------------------
    */
    Route::resource('loans', LoanController::class)->middleware('permission:manage loans');
    Route::resource('reservations', ReservationController::class)->middleware('permission:manage loans');

    /*
    |--------------------------------------------------------------------------
    | Administración (solo Superadmin)
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->middleware('role:superadmin')->group(function () {
        Route::resource('users', UserController::class);
    });
});

/*
|--------------------------------------------------------------------------
| Rutas públicas (sin autenticación)
|--------------------------------------------------------------------------
*/
Route::view('/policies', 'static.policies')->name('policies.index');
Route::view('/privacy', 'static.privacy')->name('privacy.index');

/*
|--------------------------------------------------------------------------
| Autenticación (Laravel Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';