<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Si por alguna razón el admin entra aquí, ve todo. Si es usuario, ve solo lo suyo.
        $query = Ticket::query();
        
        // Asumiendo que tu modelo User tiene la columna 'id_rol' y el rol 1 es Admin
        if ($user->id_rol != 1) { 
            $query->where('id_usuario', $user->id_usuario); 
        }

        // Contadores
        $metrics = [
            'total'      => (clone $query)->count(),
            'nuevos'     => (clone $query)->whereHas('estado', fn($q) => $q->where('nombre', 'Nuevo'))->count(),
            'enProgreso' => (clone $query)->whereHas('estado', fn($q) => $q->where('nombre', 'En Progreso'))->count(),
            'resueltos'  => (clone $query)->whereHas('estado', fn($q) => $q->where('nombre', 'Resuelto'))->count(),
        ];

        // Últimos 5 tickets
        $ticketsRecientes = (clone $query)->with(['patio', 'prioridad', 'estado', 'categoria'])
                                  ->orderBy('fecha_creacion', 'desc')
                                  ->take(5)
                                  ->get();

        return view('user.dashboard', array_merge($metrics, compact('ticketsRecientes')));
    }
}