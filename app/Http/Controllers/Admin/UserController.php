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
public function index(Request $request)
{
    $search = $request->input('search');

    $usuarios = User::query()
        ->when($search, function ($q) use ($search) {
            $q->where(function ($q2) use ($search) {
                $q2->where('nombre_completo', 'like', "%{$search}%")
                   ->orWhere('num_nomina', 'like', "%{$search}%")
                   ->orWhere('correo', 'like', "%{$search}%");
            });
        })
        ->orderByDesc('id_usuario')
        ->paginate(15)
        ->withQueryString();

    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'html'  => view('admin.users.partials.table-rows', compact('usuarios'))->render(),
            'total' => $usuarios->total(),
        ]);
    }

    // Catálogos para el modal de edición
    $roles         = \App\Models\Rol::orderBy('nombre')->get();
    $patios        = \App\Models\Patio::orderBy('nombre')->get();
    $departamentos = \App\Models\Departamento::orderBy('nombre')->get();

    $totalUsuarios = $usuarios->total();

    return view('admin.users.index', compact('usuarios', 'totalUsuarios', 'roles', 'patios', 'departamentos'));
}

    // 2. CREAR (Guardar nuevo usuario y enviar credenciales por correo)
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

        // ✅ CORRECCIÓN: Buscamos el patio por su NOMBRE, si no existe lo crea con estatus activo
        $patio = Patio::firstOrCreate(
            ['nombre' => $request->id_patio_descripcion],
            ['ciudad' => 'S/N', 'estatus' => 'activo']
        );

        // Buscamos el departamento por su NOMBRE, si no existe lo crea
        $departamento = Departamento::firstOrCreate(
            ['nombre' => $request->id_departamento],
            ['segmento' => 'General', 'estatus' => 'activo']
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
// 3. EDITAR (devolver datos del usuario en JSON)
// 3. EDITAR (datos en JSON para el modal)
public function edit(User $user)
{
    return response()->json([
        'id_usuario'           => $user->id_usuario,
        'num_nomina'           => $user->num_nomina,
        'nombre_completo'      => $user->nombre_completo,
        'telefono'             => $user->telefono,
        'correo'               => $user->correo,
        'id_rol'               => $user->id_rol,
        'id_patio_descripcion' => $user->id_patio_descripcion,
        'id_departamento'      => $user->id_departamento,
        'estatus'              => $user->estatus,
        'patio'                => $user->patio ? ['id_patio' => $user->patio->id_patio, 'nombre' => $user->patio->nombre] : null,
        'departamento'         => $user->departamento ? ['id_departamento' => $user->departamento->id_departamento, 'nombre' => $user->departamento->nombre] : null,
    ]);
}

// 4. ACTUALIZAR
public function update(Request $request, User $user)
{
    $request->validate([
        'nombre_completo'      => ['required', 'string', 'max:150', 'regex:/^[A-Za-zÁÉÍÓÚÑáéíóúñ\s]+$/u'],
        'telefono'             => 'required|digits:10',
        'correo'               => 'required|email|unique:usuarios,correo,' . $user->id_usuario . ',id_usuario',
        'id_rol'               => 'required|integer',
        'id_patio_descripcion' => 'required',
        'id_departamento'      => 'required',
    ], [
        'nombre_completo.regex' => 'El nombre completo solo debe contener letras, acentos y espacios.',
        'telefono.digits'       => 'El teléfono debe tener exactamente 10 dígitos.',
        'correo.email'          => 'Proporcione un correo válido con formato @.',
    ]);

    $patio = Patio::firstOrCreate(
        ['nombre' => $request->id_patio_descripcion],
        ['ciudad' => 'S/N']
    );

    $departamento = Departamento::firstOrCreate(
        ['nombre' => $request->id_departamento],
        ['segmento' => 'General']
    );

    $user->update([
        'nombre_completo'      => $request->nombre_completo,
        'telefono'             => $request->telefono,
        'correo'               => $request->correo,
        'id_rol'               => $request->id_rol,
        'id_patio_descripcion' => $patio->id_patio,
        'id_departamento'      => $departamento->id_departamento,
    ]);

    return redirect()->route('admin.users.index')
        ->with('success', "Usuario <strong>{$user->nombre_completo}</strong> actualizado correctamente.");
}
    // 5. ELIMINAR (Dar de baja o borrar de la BD)
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}