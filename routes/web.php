<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CatalogoController; // Importamos el controlador de catálogos
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
    
    // Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Gestión de Tickets
    Route::get('/admin/tickets', [TicketController::class, 'index'])->name('admin.tickets.index');
    
    // CRUD de Usuarios
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    
    // ==========================================
    // CRUD DE CATÁLOGOS (Patios y Departamentos)
    // ==========================================
    // Vista principal
    Route::get('/admin/catalogos', [CatalogoController::class, 'index'])->name('admin.catalogos.index');
    
    // Rutas de Patios
    Route::post('/admin/catalogos/patios', [CatalogoController::class, 'storePatio'])->name('admin.catalogos.patios.store');
    Route::get('/admin/catalogos/patios/{id}/edit', [CatalogoController::class, 'editPatio'])->name('admin.catalogos.patios.edit');
    Route::put('/admin/catalogos/patios/{id}', [CatalogoController::class, 'updatePatio'])->name('admin.catalogos.patios.update');
    Route::patch('/admin/catalogos/patios/{id}/status', [CatalogoController::class, 'togglePatioStatus'])->name('admin.catalogos.patios.status');
    Route::delete('/admin/catalogos/patios/{id}', [CatalogoController::class, 'destroyPatio'])->name('admin.catalogos.patios.destroy');
    
    // Rutas de Departamentos
        // Rutas CRUD de Departamentos
    Route::post('/admin/catalogos/departamentos', [CatalogoController::class, 'storeDepartamento'])->name('admin.catalogos.departamentos.store');
    Route::get('/admin/catalogos/departamentos/{id}/edit', [CatalogoController::class, 'editDepartamento'])->name('admin.catalogos.departamentos.edit');
    Route::put('/admin/catalogos/departamentos/{id}', [CatalogoController::class, 'updateDepartamento'])->name('admin.catalogos.departamentos.update');
    Route::patch('/admin/catalogos/departamentos/{id}/status', [CatalogoController::class, 'toggleDepartamentoStatus'])->name('admin.catalogos.departamentos.status');
    Route::delete('/admin/catalogos/departamentos/{id}', [CatalogoController::class, 'destroyDepartamento'])->name('admin.catalogos.departamentos.destroy');
    // Cambio obligatorio de contraseña
    Route::get('/primer-cambio-password', [PrimerLoginController::class, 'show'])->name('password.primer_cambio.show');
    Route::put('/primer-cambio-password', [PrimerLoginController::class, 'update'])->name('password.primer_cambio.update');

    Route::get('/user/dashboard', function () {
        return "Portal del Usuario Operativo";
    })->name('user.dashboard');
});