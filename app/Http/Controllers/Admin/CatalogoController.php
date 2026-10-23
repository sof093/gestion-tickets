<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patio;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CatalogoController extends Controller
{
    // ==========================================
    // VISTA PRINCIPAL DE CATÁLOGOS
    // ==========================================
    public function index()
    {
        $patios = Patio::orderBy('nombre')->get();
        $departamentos = Departamento::orderBy('nombre')->get();
        
        return view('admin.catalogos.index', compact('patios', 'departamentos'));
    }

    // ==========================================
    // CRUD DE PATIOS (AJAX)
    // ==========================================
    
    // 1. CREAR
    public function storePatio(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:patios,nombre',
            'ciudad' => 'required|string|max:255',
            'tipo'   => 'nullable|string|max:255',
        ]);

        $patio = Patio::create([
            'nombre' => $request->nombre,
            'ciudad' => $request->ciudad,
            'tipo'   => $request->tipo ?? 'Patio de Maniobras',
            'estatus' => 'activo',
        ]);

        // AJAX: Devolvemos JSON en lugar de redirect
        return response()->json([
            'success' => true,
            'message' => 'Patio creado correctamente.',
            'patio' => $patio
        ]);
    }

    // 2. EDITAR (Devuelve los datos en JSON para el Modal)
    public function editPatio($id)
    {
        $patio = Patio::findOrFail($id);
        return response()->json($patio);
    }

    // 3. ACTUALIZAR
    public function updatePatio(Request $request, $id)
    {
        $patio = Patio::findOrFail($id);
        
        $request->validate([
            'nombre' => [
                'required', 'string', 'max:255', 
                Rule::unique('patios', 'nombre')->ignore($patio->id_patio, 'id_patio')
            ],
            'ciudad' => 'required|string|max:255',
            'tipo'   => 'required|string|max:255',
        ]);
        
        $patio->update([
            'nombre' => $request->nombre,
            'ciudad' => $request->ciudad,
            'tipo'   => $request->tipo, 
        ]);

        // AJAX: Devolvemos JSON
        return response()->json([
            'success' => true,
            'message' => 'Patio actualizado correctamente.',
            'patio' => $patio
        ]);
    }

    // 4. CAMBIAR ESTATUS (Activar / Desactivar)
    public function togglePatioStatus($id)
    {
        $patio = Patio::findOrFail($id);
        $nuevoEstatus = $patio->estatus === 'activo' ? 'inactivo' : 'activo';
        $patio->update(['estatus' => $nuevoEstatus]);

        $mensaje = $nuevoEstatus === 'activo' 
            ? 'Patio activado. Volverá a aparecer en los formularios de usuarios y tickets.' 
            : 'Patio desactivado. No se podrá asignar a nuevos usuarios, pero los existentes no se verán afectados.';

        // AJAX: Devolvemos JSON con el nuevo estatus para actualizar el badge visual
        return response()->json([
            'success' => true,
            'message' => $mensaje,
            'estatus' => $nuevoEstatus
        ]);
    }

    // 5. ELIMINAR
    public function destroyPatio($id)
    {
        $patio = Patio::findOrFail($id);
        $patio->delete();

        // AJAX: Devolvemos JSON
        return response()->json([
            'success' => true,
            'message' => 'Patio eliminado permanentemente.'
        ]);
    }

    // ==========================================
    // CRUD DE DEPARTAMENTOS (AJAX)
    // ==========================================
    
    // 1. CREAR
    public function storeDepartamento(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:departamentos,nombre',
            'segmento' => 'nullable|string|max:255',
        ]);

        $departamento = Departamento::create([
            'nombre' => $request->nombre,
            'segmento' => $request->segmento,
            'estatus' => 'activo',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Departamento creado correctamente.',
            'departamento' => $departamento
        ]);
    }

    // 2. EDITAR
    public function editDepartamento($id)
    {
        $departamento = Departamento::findOrFail($id);
        return response()->json($departamento);
    }

    // 3. ACTUALIZAR
    public function updateDepartamento(Request $request, $id)
    {
        $departamento = Departamento::findOrFail($id);
        
        $request->validate([
            'nombre' => [
                'required', 'string', 'max:255', 
                Rule::unique('departamentos', 'nombre')->ignore($departamento->id_departamento, 'id_departamento')
            ],
            'segmento' => 'nullable|string|max:255',
        ]);

        $departamento->update([
            'nombre' => $request->nombre,
            'segmento' => $request->segmento,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Departamento actualizado correctamente.',
            'departamento' => $departamento
        ]);
    }

    // 4. CAMBIAR ESTATUS
    public function toggleDepartamentoStatus($id)
    {
        $departamento = Departamento::findOrFail($id);
        $nuevoEstatus = $departamento->estatus === 'activo' ? 'inactivo' : 'activo';
        $departamento->update(['estatus' => $nuevoEstatus]);

        return response()->json([
            'success' => true,
            'message' => 'Estatus del departamento actualizado.',
            'estatus' => $nuevoEstatus
        ]);
    }

    // 5. ELIMINAR
    public function destroyDepartamento($id)
    {
        $departamento = Departamento::findOrFail($id);
        $departamento->delete();

        return response()->json([
            'success' => true,
            'message' => 'Departamento eliminado permanentemente.'
        ]);
    }
}