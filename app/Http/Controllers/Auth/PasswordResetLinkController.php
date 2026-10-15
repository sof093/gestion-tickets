<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class PasswordResetLinkController extends Controller
{
    public function create()
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request)
    {
        $request->validate([
            'correo' => ['required', 'email'],
        ], [
            'correo.required' => 'El campo de correo corporativo es obligatorio.',
            'correo.email' => 'Debe ingresar un formato de correo válido.',
        ]);

        // 1. Buscamos al usuario por su columna 'correo'
        $user = User::where('correo', $request->correo)->first();

        if (!$user) {
            return back()->withErrors(['correo' => 'No encontramos ningún usuario registrado con este correo.']);
        }

        // 2. Generamos un token seguro
        $token = Str::random(60);

        // 3. Guardamos usando la columna 'email' de la tabla auxiliar password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->correo],
            [
                'token' => password_hash($token, PASSWORD_DEFAULT),
                'created_at' => now()
            ]
        );

        // 4. Creamos el enlace de restablecimiento apuntando a tu vista
        $resetUrl = route('password.reset', ['token' => $token, 'correo' => $user->correo]);

        // 5. Enviamos el correo directamente a Mailtrap
        Mail::raw("Hola {$user->nombre_completo},\n\nHas solicitado restablecer tu contraseña. Haz clic en el siguiente enlace para continuar:\n\n{$resetUrl}\n\nEste enlace expirará en 15 minutos.", function ($message) use ($user) {
            $message->to($user->correo)
                    ->subject('Restablecer Contraseña - Grupo TRACOMEX');
        });

        return back()->with('status', 'Hemos enviado el enlace de recuperación a tu correo institucional.');
    }
}