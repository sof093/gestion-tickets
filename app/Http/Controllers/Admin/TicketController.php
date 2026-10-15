<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        // En fases posteriores consultaremos el modelo Ticket::count()
        $totalActivos = 107;

        return view('admin.tickets.index', compact('totalActivos'));
    }
}