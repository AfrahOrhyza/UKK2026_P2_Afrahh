<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\LogAktivitasController;

Route::get('/', function () {
    return redirect('/login');
});

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

//user
// user
Route::resource('user', UserController::class);
Route::patch('user/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('user.toggle-status');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', fn() => redirect('/admin'))->name('dashboard');
    Route::get('/admin', [App\Http\Controllers\DashboardController::class, 'index'])->name('admin');

    // User
    Route::resource('user', UserController::class);
    Route::patch('user/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('user.toggle-status');

    // Tarif
    Route::resource('tarif', App\Http\Controllers\TarifController::class)->except(['show', 'create', 'edit']);

    // Area Parkir
   Route::resource('area', AreaController::class);

    // Kendaraan
 Route::prefix('kendaraan')->name('kendaraan.')->group(function () {
    Route::get('/',                     [KendaraanController::class, 'index'])->name('index');
    Route::post('/',                    [KendaraanController::class, 'store'])->name('store');
    Route::put('/{id}',                 [KendaraanController::class, 'update'])->name('update');
    Route::delete('/{id}',              [KendaraanController::class, 'destroy'])->name('destroy');
    Route::patch('/{id}/toggle-status', [KendaraanController::class, 'toggleStatus'])->name('toggle-status');
});

    // Log Aktivitas
    Route::get('/log',         [LogAktivitasController::class, 'index'])->name('log.index');
Route::delete('/log/all',  [LogAktivitasController::class, 'destroyAll'])->name('log.destroy-all');
Route::delete('/log/{id}', [LogAktivitasController::class, 'destroy'])->name('log.destroy');
});

// Protected routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        return redirect('/admin');
    })->name('dashboard');

    Route::get('/admin', [App\Http\Controllers\DashboardController::class, 'index'])->name('admin');
});