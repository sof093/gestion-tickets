<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Contadores por estado (buscando por el nombre de la relación)
        $metrics = [
            'nuevos'     => Ticket::whereHas('estado', fn($q) => $q->where('nombre', 'Nuevo'))->count(),
            'enProgreso' => Ticket::whereHas('estado', fn($q) => $q->where('nombre', 'En Progreso'))->count(),
            'resueltos'  => Ticket::whereHas('estado', fn($q) => $q->where('nombre', 'Resuelto'))->count(),
            'cerrados'   => Ticket::whereHas('estado', fn($q) => $q->where('nombre', 'Cerrado'))->count(),
        ];

        // 2. Traer los últimos 5 tickets con sus relaciones
        $ticketsRecientes = Ticket::with(['usuario', 'patio', 'prioridad', 'estado', 'categoria'])
                                  ->orderBy('fecha_creacion', 'desc')
                                  ->take(5)
                                  ->get();

        // 3. Pasar los datos a la vista
        return view('admin.dashboard', array_merge($metrics, compact('ticketsRecientes')));
    }
    public function searchTicket(Request $request)
    {
        $search = $request->input('query');

        if (!$search) {
            return response()->json(['tickets' => []]);
        }

        // Buscamos SOLO por folio exacto
        $tickets = Ticket::with(['usuario', 'patio', 'prioridad', 'estado', 'categoria'])
            ->where('folio', $search) 
            ->get();

        return response()->json(['tickets' => $tickets]);
    }
}