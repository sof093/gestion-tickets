<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patio;
use App\Models\Departamento;
use App\Mail\UsuarioCreadoMail; // 👈 Importar el Mailable
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail; // 👈 Importar la fachada Mail

class UserController extends Controller
{
    // 1. VER (Listar todos los usuarios)
    public function index()
    {
        $usuarios = User::all();
        $totalUsuarios = $usuarios->count();
        return view('admin.users.index', compact('usuarios', 'totalUsuarios'));
    }

    // 2. CREAR (Guardar nuevo usuario y enviar credenciales por correo)
    public function store(Request $request)
    {
        // Validaciones estrictas solicitadas
        $request->validate([
            'num_nomina' => 'required|numeric|unique:usuarios,num_nomina',
            'nombre_completo' => ['required', 'string', 'max:150', 'regex:/^[A-ZÁÉÍÓÚÑa-zàèìòùñäëïöü\s]+$/u'],
            'telefono' => 'required|digits:10',
            'correo' => 'required|email|unique:usuarios,correo',
            'id_rol' => 'required|integer',
            'id_patio_descripcion' => 'required',
            'id_departamento' => 'required',
        ], [
            'num_nomina.numeric' => 'El número de nómina debe contener únicamente dígitos numéricos.',
            'nombre_completo.regex' => 'El nombre completo solo debe contener letras, acentos y espacios.',
            'telefono.digits' => 'El teléfono de contacto debe tener exactamente 10 dígitos.',
            'correo.email' => 'Proporcione un correo institucional válido con formato @.',
        ]);

        // Obtener o registrar automáticamente el Patio para asignarle su ID numérico correcto
        $patio = Patio::firstOrCreate(
            ['nombre' => $request->id_patio_descripcion],
            ['ciudad' => 'S/N']
        );

        // Obtener o registrar automáticamente el Departamento para asignarle su ID numérico correcto
        $departamento = Departamento::firstOrCreate(
            ['nombre' => $request->id_departamento],
            ['segmento' => 'General']
        );

        // Generar contraseña temporal automática segura
        $passwordTemporal = 'Trx-' . rand(100000, 999999);

        // Crear el usuario vinculando los IDs correspondientes de tu esquema SQL
        $usuario = User::create([
            'num_nomina' => $request->num_nomina,
            'nombre_completo' => $request->nombre_completo,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'contrasena_hash' => Hash::make($passwordTemporal),
            'id_rol' => $request->id_rol,
            'id_patio_descripcion' => $patio->id_patio,        // ID numérico de la tabla patios
            'id_departamento' => $departamento->id_departamento, // ID numérico de la tabla departamentos
            'estatus' => 'activo',
        ]);

        // Enviar el correo electrónico con las credenciales de acceso de forma automática
        try {
            Mail::to($usuario->correo)->send(new UsuarioCreadoMail($usuario, $passwordTemporal));
            
            return redirect()->route('admin.users.index')
                ->with('success', 'Usuario registrado con éxito. Sus credenciales de acceso han sido enviadas por correo electrónico.');
        } catch (\Exception $e) {
            // Plan de respaldo si falla el servidor de correo por red o configuración SMTP
            return redirect()->route('admin.users.index')
                ->with('success', "Usuario registrado, pero no se pudo enviar el correo. Contraseña temporal generada: <strong>{$passwordTemporal}</strong>");
        }
    }

    // 3. EDITAR (Obtener datos para actualizar)
    public function edit(User $user)
    {
        return response()->json($user);
    }

    // 4. ACTUALIZAR (Modificar datos existentes)
    public function update(Request $request, User $user)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:150',
            'correo' => 'required|email|unique:usuarios,correo,' . $user->id_usuario . ',id_usuario',
            'id_rol' => 'required',
            'id_patio_descripcion' => 'required',
        ]);

        $data = $request->only(['nombre_completo', 'telefono', 'correo', 'id_rol', 'id_patio_descripcion', 'id_departamento']);

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    // 5. ELIMINAR (Dar de baja o borrar de la BD)
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}