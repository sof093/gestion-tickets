<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;

class NewPasswordController extends Controller
{
    // Muestra la vista donde el usuario ingresa su nueva contraseña
    public function create(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'correo' => $request->query('correo')
        ]);
    }

    // Procesa el cambio y actualización de la contraseña
    public function store(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'correo' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:6'],
        ], [
            'correo.required' => 'El correo es obligatorio.',
            'password.required' => 'La nueva contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        // 1. Buscamos el registro del token en la tabla auxiliar
        $record = DB::table('password_reset_tokens')
                    ->where('email', $request->correo)
                    ->first();

        if (!$record || !password_verify($request->token, $record->token)) {
            return back()->withInput($request->only('correo'))
                         ->withErrors(['correo' => 'El enlace de recuperación es inválido o ha expirado.']);
        }

        // 2. Buscamos al usuario en la tabla 'usuarios'
        $user = User::where('correo', $request->correo)->first();

        if (!$user) {
            return back()->withErrors(['correo' => 'No encontramos un usuario con este correo.']);
        }

        // 3. Actualizamos la contraseña en tu columna personalizada 'contrasena_hash'
        $user->forceFill([
            'contrasena_hash' => Hash::make($request->password),
            'remember_token' => Str::random(60),
            'primer_login' => false,
        ])->save();

        // 4. Borramos el token usado para que no se pueda reutilizar
        DB::table('password_reset_tokens')->where('email', $request->correo)->delete();

        // 5. Redirigimos al login con mensaje de éxito
        return redirect()->route('login')->with('status', '¡Tu contraseña ha sido restablecida con éxito! Ya puedes iniciar sesión.');
    }
}