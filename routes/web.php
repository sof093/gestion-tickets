<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\PrimerLoginController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;

// ==========================================
// RUTAS PÚBLICAS (No requieren iniciar sesión)
// ==========================================
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas públicas de recuperación de contraseña
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');


// ==========================================
// RUTAS PROTEGIDAS (Requieren iniciar sesión)
// ==========================================
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Ruta para la Gestión de Tickets
    Route::get('/admin/tickets', [TicketController::class, 'index'])->name('admin.tickets.index');
    
    // Rutas completas del CRUD de Usuarios
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    
    // Cambio obligatorio de contraseña (este sí va protegido porque el usuario ya se logueó con su clave temporal)
    Route::get('/primer-cambio-password', [PrimerLoginController::class, 'show'])->name('password.primer_cambio.show');
    Route::put('/primer-cambio-password', [PrimerLoginController::class, 'update'])->name('password.primer_cambio.update');

    Route::get('/user/dashboard', function () {
        return "Portal del Usuario Operativo";
    })->name('user.dashboard');
});