<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CatalogoController; // Importamos el controlador de catálogos
use App\Http\Controllers\Auth\PrimerLoginController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
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
    Route::get('/admin/dashboard/search', [App\Http\Controllers\Admin\DashboardController::class, 'searchTicket'])->name('admin.dashboard.search');
    
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

        // Categorías
    Route::post('/admin/catalogos/categorias', [CatalogoController::class, 'storeCategoria'])->name('admin.catalogos.categorias.store');
    
        // Subcategorías
    Route::post('/admin/catalogos/subcategorias', [CatalogoController::class, 'storeSubcat'])->name('admin.catalogos.subcategorias.store');
    Route::get('/admin/catalogos/subcategorias/{id}/edit', [CatalogoController::class, 'editSubcat'])->name('admin.catalogos.subcategorias.edit');    // <-- NUEVA
    Route::put('/admin/catalogos/subcategorias/{id}', [CatalogoController::class, 'updateSubcat'])->name('admin.catalogos.subcategorias.update');     // <-- NUEVA
    Route::delete('/admin/catalogos/subcategorias/{id}', [CatalogoController::class, 'destroySubcat'])->name('admin.catalogos.subcategorias.destroy');
    // Fallas
    Route::post('/admin/catalogos/fallas', [CatalogoController::class, 'storeFalla'])->name('admin.catalogos.fallas.store');
    Route::delete('/admin/catalogos/fallas/{id}', [CatalogoController::class, 'destroyFalla'])->name('admin.catalogos.fallas.destroy');
        // Marcas
    Route::post('/admin/catalogos/marcas', [CatalogoController::class, 'storeMarca'])->name('admin.catalogos.marcas.store');
    Route::delete('/admin/catalogos/marcas/{id}', [CatalogoController::class, 'destroyMarca'])->name('admin.catalogos.marcas.destroy');
    Route::put('/admin/catalogos/prioridades/{id}', [CatalogoController::class, 'updatePrioridad'])->name('admin.catalogos.prioridades.update');
    // Cambio obligatorio de contraseña
    Route::get('/primer-cambio-password', [PrimerLoginController::class, 'show'])->name('password.primer_cambio.show');
    Route::put('/primer-cambio-password', [PrimerLoginController::class, 'update'])->name('password.primer_cambio.update');

    // ==========================================
    // RUTAS DEL USUARIO (Portal Operativo)
    // ==========================================
    Route::prefix('user')->name('user.')->group(function () {
        // 1. Dashboard (AQUÍ ESTÁ EL CAMBIO)
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

        // 2. Mis Tickets
        Route::get('/tickets', [TicketController::class, 'myTickets'])->name('tickets.index');


        // 3. Crear Ticket (Formulario)
        Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');

        // 4. Guardar Ticket
        Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');

        // 5. Cerrar Ticket (Usuario valida solución)
        Route::post('/tickets/{id}/close', [TicketController::class, 'closeTicket'])->name('tickets.close');
                    // 6. Ver detalle de un ticket (Usuario)
    Route::get('/tickets/{id}', [TicketController::class, 'showUserTicket'])->name('tickets.show');
            // 7. Perfil del Usuario
        Route::get('/profile', [\App\Http\Controllers\User\ProfileController::class, 'index'])->name('profile');
    });

    // ==========================================
    // RUTAS DEL ADMINISTRADOR (Tickets - Detalle)
    // ==========================================
    Route::get('/admin/tickets/{id}', [TicketController::class, 'show'])->name('admin.tickets.show');
    Route::post('/admin/tickets/{id}/status', [TicketController::class, 'updateStatus'])->name('admin.tickets.status');
    Route::post('/admin/tickets/{id}/comment', [TicketController::class, 'storeComment'])->name('admin.tickets.comment');



    Route::post('/admin/tickets/{id}/assign', [TicketController::class, 'assignTecnico'])->name('admin.tickets.assign');
    Route::get('/admin/tickets/data', [TicketController::class, 'getTicketsData'])->name('admin.tickets.data');
});

