<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectUser(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'employee_id' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'employee_id.required' => 'El número de nómina es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        $attempt = Auth::attempt([
            'num_nomina' => $credentials['employee_id'],
            'password' => $credentials['password'],
        ], $request->boolean('remember'));

        if ($attempt) {
            $request->session()->regenerate();
            return $this->redirectUser(Auth::user());
        }

        return back()->withErrors([
            'employee_id' => 'Las credenciales ingresadas no coinciden con nuestros registros.',
        ])->onlyInput('employee_id');
    }

    protected function redirectUser($user)
{
    $user->load('rol');

    // Validación por id_rol o nombre de rol
    $nombreRol = strtolower($user->rol->nombre_rol ?? '');

    if ($user->id_rol == 1 || in_array($nombreRol, ['administrador', 'admin', 'soporte', 'tecnico'])) {
        return redirect()->intended('/admin/dashboard');
    }

    return redirect()->intended('/user/dashboard');
}
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}