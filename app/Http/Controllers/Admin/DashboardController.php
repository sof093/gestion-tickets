<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // En este punto del MVC se consultarán los modelos (ej. Ticket::where(...)->count())
        // Por ahora pasamos las métricas iniciales limpias a la vista.
        $metrics = [
            'nuevos'     => 0,
            'enProgreso' => 0,
            'resueltos'  => 0,
            'cerrados'   => 0,
        ];

        return view('admin.dashboard', $metrics);
    }
}