<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        // Obtenemos el usuario logueado con sus relaciones (patio, departamento, rol)
        $user = auth()->user()->load(['patio', 'departamento', 'rol']);
        
        return view('user.profile', compact('user'));
    }
}