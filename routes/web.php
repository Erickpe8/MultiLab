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
| Rutas oficiales del sistema Multilab, estructuradas por autenticación,
| roles, permisos y módulos. La arquitectura sigue línea modular.
*/

/*
|--------------------------------------------------------------------------
| Página inicial pública (Landing Multilab)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

/*
|--------------------------------------------------------------------------
| Autenticación (Laravel Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Rutas protegidas (usuario autenticado y verificado)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard general
    |--------------------------------------------------------------------------
    */
    Route::view('/dashboard', 'dashboard.index')->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Dashboard según rol (placeholders)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:superadmin')->get('/panel/superadmin', function () {
        return view('dashboard.superadmin');
    })->name('panel.superadmin');

    Route::middleware('role:aux_admin')->get('/panel/auxiliar', function () {
        return view('dashboard.auxiliar');
    })->name('panel.auxiliar');

    Route::middleware('role:docente')->get('/panel/docente', function () {
        return view('dashboard.docente');
    })->name('panel.docente');

    Route::middleware('role:estudiante')->get('/panel/estudiante', function () {
        return view('dashboard.estudiante');
    })->name('panel.estudiante');


    /*
    |--------------------------------------------------------------------------
    | Perfil de usuario
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
    Route::prefix('inventory')
        ->name('inventory.')
        ->middleware('permission:view inventory')
        ->group(function () {

            Route::resource('assets', AssetController::class);
            Route::resource('materials', MaterialController::class);
        });

    /*
    |--------------------------------------------------------------------------
    | Módulo de Préstamos y Reservas
    |--------------------------------------------------------------------------
    */
    Route::resource('loans', LoanController::class)
        ->middleware('permission:manage loans');

    Route::resource('reservations', ReservationController::class)
        ->middleware('permission:manage loans');

    /*
    |--------------------------------------------------------------------------
    | Administración del sistema (solo Superadmin)
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')
        ->name('admin.')
        ->middleware('role:superadmin')
        ->group(function () {

            Route::resource('users', UserController::class);
        });
});

/*
|--------------------------------------------------------------------------
| Rutas estáticas públicas
|--------------------------------------------------------------------------
*/
Route::view('/policies', 'static.policies')->name('policies.index');
Route::view('/privacy', 'static.privacy')->name('privacy.index');