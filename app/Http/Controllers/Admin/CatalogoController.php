<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patio;
use App\Models\Departamento;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Falla;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Marca;  
use App\Models\Prioridad;
use App\Models\Estado;              

class CatalogoController extends Controller
{
    // ==========================================
    // VISTA PRINCIPAL DE CATÁLOGOS
    // ==========================================
    public function index()
    {
        $patios = Patio::orderBy('nombre')->get();
        $departamentos = Departamento::orderBy('nombre')->get();
        $categorias = Categoria::with(['subcategorias.fallas'])->orderBy('nombre')->get();
        $marcas = Marca::orderBy('nombre')->get();
        $prioridades = Prioridad::orderBy('id_prioridad')->get();
        $estados = Estado::orderBy('orden')->get();

        return view('admin.catalogos.index', compact('patios', 'departamentos', 'categorias', 'marcas', 'prioridades', 'estados'));
    }

    // ==========================================
    // CRUD DE PATIOS (AJAX)
    // ==========================================
    
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

        return response()->json([
            'success' => true,
            'message' => 'Patio creado correctamente.',
            'patio' => $patio
        ]);
    }

    public function editPatio($id)
    {
        $patio = Patio::findOrFail($id);
        return response()->json($patio);
    }

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

        return response()->json([
            'success' => true,
            'message' => 'Patio actualizado correctamente.',
            'patio' => $patio
        ]);
    }

    public function togglePatioStatus($id)
    {
        $patio = Patio::findOrFail($id);
        $nuevoEstatus = $patio->estatus === 'activo' ? 'inactivo' : 'activo';
        $patio->update(['estatus' => $nuevoEstatus]);

        $mensaje = $nuevoEstatus === 'activo' 
            ? 'Patio activado. Volverá a aparecer en los formularios de usuarios y tickets.' 
            : 'Patio desactivado. No se podrá asignar a nuevos usuarios, pero los existentes no se verán afectados.';

        return response()->json([
            'success' => true,
            'message' => $mensaje,
            'estatus' => $nuevoEstatus
        ]);
    }

    public function destroyPatio($id)
    {
        $patio = Patio::findOrFail($id);
        $patio->delete();

        return response()->json([
            'success' => true,
            'message' => 'Patio eliminado permanentemente.'
        ]);
    }

    // ==========================================
    // CRUD DE DEPARTAMENTOS (AJAX)
    // ==========================================
    
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

    public function editDepartamento($id)
    {
        $departamento = Departamento::findOrFail($id);
        return response()->json($departamento);
    }

    public function updateDepartamento(Request $request, $id)
    {
        $departamento = Departamento::findOrFail($id);
        
        $request->validate([
            'nombre' => [
                'required', 'string', 'max:255', 
                Rule::unique('departamentos', 'nombre')->ignore($departamento->id_departamento, 'id_departamento')
            ],
            'segmento' => 'nullable|string|max:255',
            'nivel_impacto' => 'required|integer|between:1,4',
        ]);

        $departamento->update([
            'nombre' => $request->nombre,
            'segmento' => $request->segmento,
            'nivel_impacto' => $request->nivel_impacto, 
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Departamento actualizado correctamente.',
            'departamento' => $departamento
        ]);
    }

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

    public function destroyDepartamento($id)
    {
        $departamento = Departamento::findOrFail($id);
        $departamento->delete();

        return response()->json([
            'success' => true,
            'message' => 'Departamento eliminado permanentemente.'
        ]);
    }

    // ==========================================
    // CRUD DE CATEGORÍAS (AJAX)
    // ==========================================
    public function storeCategoria(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias,nombre',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $categoria = Categoria::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Categoría creada.',
            'categoria' => $categoria
        ]);
    }

    // ==========================================
    // CRUD DE SUBCATEGORÍAS (AJAX)
    // ==========================================
    public function storeSubcat(Request $request)
    {
        $request->validate([
            'id_cat' => 'required|exists:categorias,id_cat',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $subcat = Subcategoria::create($request->all());
        $subcat->load('fallas');

        return response()->json([
            'success' => true,
            'message' => 'Subcategoría creada.',
            'subcategoria' => $subcat
        ]);
    }

    // 🔥 ESTE ES EL MÉTODO QUE FALTABA
    public function editSubcat($id)
    {
        $subcat = Subcategoria::findOrFail($id);
        return response()->json($subcat);
    }

    // 🔥 ESTE ES EL MÉTODO QUE FALTABA
    public function updateSubcat(Request $request, $id)
    {
        $subcat = Subcategoria::findOrFail($id);
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $subcat->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Subcategoría actualizada correctamente.',
            'subcategoria' => $subcat
        ]);
    }

    // 🔥 ESTE ES EL MÉTODO QUE FALTABA
    public function destroySubcat($id)
    {
        $subcat = Subcategoria::findOrFail($id);
        $subcat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Subcategoría eliminada correctamente.'
        ]);
    }

    // ==========================================
    // CRUD DE FALLAS (AJAX)
    // ==========================================
    public function storeFalla(Request $request)
    {
        $request->validate([
            'id_subcat' => 'required|exists:subcategorias,id_subcat',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $subcat = Subcategoria::with('categoria')->findOrFail($request->id_subcat);
        $categoria = $subcat->categoria;
        $prefijo = strtoupper(substr($categoria->nombre, 0, 3));
        $consecutivo = Falla::where('id_subcat', $request->id_subcat)->count() + 1;
        $consecutivoFormateado = str_pad($consecutivo, 3, '0', STR_PAD_LEFT);
        $idSubcatFormateado = str_pad($subcat->id_subcat, 2, '0', STR_PAD_LEFT);
        $codigoGenerado = $prefijo . '-' . $idSubcatFormateado . '-' . $consecutivoFormateado;

        $falla = Falla::create([
            'id_subcat' => $request->id_subcat,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'codigo_falla' => $codigoGenerado,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Falla creada.',
            'falla' => $falla
        ]);
    }

    public function destroyFalla($id)
    {
        $falla = Falla::findOrFail($id);
        $falla->delete();

        return response()->json([
            'success' => true,
            'message' => 'Falla eliminada correctamente.'
        ]);
    }
        // ==========================================
    // CRUD DE MARCAS (AJAX)
    // ==========================================
    public function storeMarca(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:marcas,nombre',
        ]);

        $marca = Marca::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Marca creada correctamente.',
            'marca' => $marca
        ]);
    }

    public function destroyMarca($id)
    {
        $marca = Marca::findOrFail($id);
        $marca->delete();

        return response()->json([
            'success' => true,
            'message' => 'Marca eliminada correctamente.'
        ]);
    }
        // ==========================================
    // CRUD DE PRIORIDADES (AJAX)
    // ==========================================
    public function updatePrioridad(Request $request, $id)
    {
        $prioridad = Prioridad::findOrFail($id);
        
        $request->validate([
            'tiempo_respuesta_min' => 'required|integer|min:1',
            'tiempo_resolucion_min' => 'required|integer|min:1',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $prioridad->update([
            'tiempo_respuesta_min' => $request->tiempo_respuesta_min,
            'tiempo_resolucion_min' => $request->tiempo_resolucion_min,
            'descripcion' => $request->descripcion,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Prioridad actualizada.',
            'prioridad' => $prioridad
        ]);
    }
}

