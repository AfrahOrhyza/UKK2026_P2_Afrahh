<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\LogAktivitasController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect('/login'));

/*
|--------------------------------------------------------------------------
| GUEST
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| AUTH (SEMUA ROLE LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |-----------------------------
    | LOGOUT
    |-----------------------------
    */
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /*
    |-----------------------------
    | DASHBOARD (ROLE BASED)
    |-----------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    /*
    | OPTIONAL BACKUP ROUTE
    */
    Route::get('/admin', fn () => redirect('/dashboard'));

    /*
    |-----------------------------
    | USER (HANYA ADMIN DI BATASI DI CONTROLLER/MIDDLEWARE)
    |-----------------------------
    */
    Route::resource('user', UserController::class);

    Route::patch('user/{id}/toggle-status', [UserController::class, 'toggleStatus'])
        ->name('user.toggle-status');

    /*
    |-----------------------------
    | TARIF
    |-----------------------------
    */
    Route::resource('tarif', TarifController::class)
        ->except(['show', 'create', 'edit']);

    /*
    |-----------------------------
    | AREA
    |-----------------------------
    */
    Route::resource('area', AreaController::class);

    /*
    |-----------------------------
    | KENDARAAN
    |-----------------------------
    */
    Route::prefix('kendaraan')->name('kendaraan.')->group(function () {

        Route::get('/', [KendaraanController::class, 'index'])->name('index');
        Route::post('/', [KendaraanController::class, 'store'])->name('store');
        Route::put('/{id}', [KendaraanController::class, 'update'])->name('update');
        Route::delete('/{id}', [KendaraanController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/toggle-status', [KendaraanController::class, 'toggleStatus'])
            ->name('toggle-status');
    });

    /*
    |-----------------------------
    | LOG
    |-----------------------------
    */
    Route::get('/log', [LogAktivitasController::class, 'index'])->name('log.index');
    Route::delete('/log/all', [LogAktivitasController::class, 'destroyAll'])->name('log.destroy-all');
    Route::delete('/log/{id}', [LogAktivitasController::class, 'destroy'])->name('log.destroy');

});