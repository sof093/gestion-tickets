<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboards temporales de prueba
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return '<h1>¡Bienvenido al Panel Administrador, ' . e(auth()->user()->nombre_completo) . '!</h1>' .
               '<form method="POST" action="' . route('logout') . '">' .
               csrf_field() .
               '<button type="submit">Cerrar Sesión</button>' .
               '</form>';
    })->name('admin.dashboard');

    Route::get('/user/dashboard', function () {
        return '<h1>¡Bienvenido al Portal de Usuario, ' . e(auth()->user()->nombre_completo) . '!</h1>' .
               '<form method="POST" action="' . route('logout') . '">' .
               csrf_field() .
               '<button type="submit">Cerrar Sesión</button>' .
               '</form>';
    })->name('user.dashboard');
});