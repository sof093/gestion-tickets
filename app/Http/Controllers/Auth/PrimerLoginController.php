<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PrimerLoginController extends Controller
{
    // Muestra la vista del formulario de cambio de contraseña
    public function show()
    {
        return view('auth.primer-cambio-password');
    }

    // Procesa la actualización de la contraseña y cambia la bandera
    public function update(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $user = Auth::user();

        // Actualizamos la contraseña utilizando tu campo 'contrasena_hash'
        $user->contrasena_hash = Hash::make($request->password);
        
        // Desactivamos la bandera de primer inicio para que no vuelva a pedirlo
        $user->primer_login = false;
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', '¡Contraseña actualizada con éxito! Bienvenido al sistema.');
    }
}