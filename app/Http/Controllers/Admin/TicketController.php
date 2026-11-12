<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Patio;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Falla;
use App\Models\Marca;
use App\Models\Prioridad;
use App\Models\Estado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comentario;

class TicketController extends Controller
{
    // ==========================================
    // VISTA DEL TABLERO (ADMIN) CON FILTROS Y AJAX
    // ==========================================
    public function index(Request $request)
    {
        // 1. Consulta base
        $query = Ticket::with(['usuario', 'patio', 'prioridad', 'estado', 'categoria']);

        // 2. Aplicar filtros
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('folio', 'LIKE', "%{$search}%")
                  ->orWhere('descripcion', 'LIKE', "%{$search}%")
                  ->orWhereHas('usuario', function($qUser) use ($search) {
                      $qUser->where('nombre_completo', 'LIKE', "%{$search}%");
                  });
            });
        }
        if ($request->filled('patio_id')) {
            $query->where('id_patio', $request->patio_id);
        }
        if ($request->filled('categoria_id')) {
            $query->where('id_categoria', $request->categoria_id);
        }
        if ($request->filled('prioridad_id')) {
            $query->where('id_prioridad', $request->prioridad_id);
        }
        if ($request->filled('estado_id')) {
            $query->where('id_estado', $request->estado_id);
        }

        // 3. Ejecutar y agrupar
        $tickets = $query->orderBy('fecha_creacion', 'desc')->get();
        $ticketsPorEstado = $tickets->groupBy(function ($ticket) {
            return $ticket->estado->nombre ?? 'Sin Estado';
        });

        // 4. Calcular KPI (Nuevos + En Progreso)
        $totalActivos = $tickets->filter(function($ticket) {
            return in_array($ticket->estado->nombre, ['Nuevo', 'En Progreso']);
        })->count();

        // 5. SI ES UNA PETICIÓN AJAX (Para los filtros sin recargar)
        if ($request->ajax()) {
            return response()->json([
                'tickets' => $tickets,
                'ticketsPorEstado' => $ticketsPorEstado,
                'totalActivos' => $totalActivos,
            ]);
        }

        // 6. Obtener listas para llenar los selects
        $patios = Patio::orderBy('nombre')->get();
        $categorias = Categoria::orderBy('nombre')->get();
        $prioridades = Prioridad::orderBy('nombre')->get();
        $estadosList = Estado::orderBy('id_estado')->get();

        // 7. Retornar vista normal
        return view('admin.tickets.index', compact(
            'tickets', 'ticketsPorEstado', 'totalActivos', 
            'patios', 'categorias', 'prioridades', 'estadosList'
        ));
    }

    // ==========================================
    // FORMULARIO DE NUEVO TICKET (ADMIN/USUARIO)
    // ==========================================
    public function create()
    {
        $patios = Patio::where('estatus', 'activo')->orderBy('nombre')->get();
        $categorias = Categoria::with(['subcategorias.fallas'])->orderBy('nombre')->get();
        $marcas = Marca::orderBy('nombre')->get();
        
        $subcategorias = Subcategoria::orderBy('nombre')->get();
        $fallas = Falla::orderBy('nombre')->get();
        
        $usuario = Auth::user();
        
        return view('user.create-ticket', compact('patios', 'categorias', 'marcas', 'usuario', 'subcategorias', 'fallas'));
    }

    // ==========================================
    // GUARDAR EL TICKET (LÓGICA DE PRIORIDAD)
    // ==========================================
    public function store(Request $request)
    {
        $request->validate([
            'id_patio' => 'required|exists:patios,id_patio',
            'id_categoria' => 'required|exists:categorias,id_cat',
            'id_subcat' => 'required|exists:subcategorias,id_subcat',
            'id_falla' => 'required|exists:fallas,id_falla',
            'descripcion' => 'required|string|max:200',
            'id_marca' => 'nullable|exists:marcas,id_marca',
        ]);

        $usuario = Auth::user();
        $departamento = $usuario->departamento;
        $nivelImpacto = $departamento->nivel_impacto ?? 4; 

        $idPrioridad = $nivelImpacto; 

        $estatusNuevo = Estado::where('nombre', 'Nuevo')->first();
        if (!$estatusNuevo) {
            $estatusNuevo = Estado::create(['nombre' => 'Nuevo', 'orden' => 1]);
        }

        $ultimoTicket = Ticket::orderBy('id_ticket', 'desc')->first();
        $siguienteNumero = $ultimoTicket ? $ultimoTicket->id_ticket + 1 : 1;
        $folio = 'TK-' . date('Y') . '-' . str_pad($siguienteNumero, 3, '0', STR_PAD_LEFT);

        $ticket = Ticket::create([
            'folio' => $folio,
            'id_usuario' => $usuario->id_usuario,
            'id_patio' => $request->id_patio,
            'id_categoria' => $request->id_categoria,
            'id_subcat' => $request->id_subcat,
            'id_falla' => $request->id_falla,
            'id_marca' => $request->id_marca,
            'id_prioridad' => $idPrioridad,
            'id_estado' => $estatusNuevo->id_estado,
            'descripcion' => $request->descripcion,
            'fecha_creacion' => now(),
        ]);
        
        return redirect()->route('user.dashboard')
            ->with('success', "Ticket {$folio} creado correctamente. Te atenderemos a la brevedad.");
    }

    // ==========================================
    // MOSTRAR DETALLE DEL TICKET (ADMIN)
    // ==========================================
    public function show($id)
    {
        $ticket = Ticket::with(['usuario', 'patio', 'categoria', 'subcategoria', 'falla', 'marca', 'prioridad', 'estado', 'comentarios.usuario'])
                        ->findOrFail($id);
        $estados = Estado::orderBy('orden')->get();
        
        return view('admin.tickets.show', compact('ticket', 'estados'));
    }

    // ==========================================
    // CAMBIAR ESTADO DEL TICKET (ADMIN)
    // ==========================================
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'id_estado' => 'required|exists:estados,id_estado',
            'id_tecnico' => 'nullable|exists:usuarios,id_usuario',
        ]);

        $ticket = Ticket::findOrFail($id);
        $nuevoEstado = Estado::findOrFail($request->id_estado);
        
        $datos = ['id_estado' => $nuevoEstado->id_estado];
        
        if ($request->id_tecnico) {
            $datos['id_tecnico'] = $request->id_tecnico;
            if (!$ticket->fecha_asignacion) {
                $datos['fecha_asignacion'] = now();
            }
        }

        if ($nuevoEstado->nombre === 'En Progreso' && !$ticket->id_tecnico && !$request->id_tecnico) {
            $datos['id_tecnico'] = Auth::id();
            $datos['fecha_asignacion'] = now();
        }

        if ($nuevoEstado->nombre === 'Resuelto') {
            $datos['fecha_resolucion'] = now();
        }

        if ($nuevoEstado->nombre === 'En Progreso' && $ticket->fecha_resolucion) {
            $datos['fecha_resolucion'] = null;
        }

        $ticket->update($datos);

        return redirect()->back()->with('success', 'Ticket actualizado correctamente.');
    }

    // ==========================================
    // CERRAR TICKET (USUARIO VALIDA SOLUCIÓN)
    // ==========================================
    public function closeTicket($id)
    {
        $ticket = Ticket::findOrFail($id);
        
        if ($ticket->id_usuario !== Auth::id()) {
            abort(403, 'No tienes permiso para cerrar este ticket.');
        }

        if ($ticket->estado->nombre !== 'Resuelto') {
            return redirect()->back()->with('error', 'Solo puedes cerrar tickets que estén en estado Resuelto.');
        }

        $estadoCerrado = Estado::where('nombre', 'Cerrado')->first();
        $ticket->update(['id_estado' => $estadoCerrado->id_estado]);

        return redirect()->back()->with('success', '¡Gracias! El ticket ha sido cerrado correctamente.');
    }

    // ==========================================
    // GUARDAR COMENTARIO (ADMIN/TÉCNICO)
    // ==========================================
    public function storeComment(Request $request, $id)
    {
        $request->validate([
            'mensaje' => 'required|string|max:1000',
        ]);

        $ticket = Ticket::findOrFail($id);

        Comentario::create([
            'id_ticket' => $ticket->id_ticket,
            'id_usuario' => Auth::id(),
            'mensaje' => $request->mensaje,
            'fecha' => now(),
        ]);

        return redirect()->back()->with('success', 'Comentario agregado correctamente.');
    }

    // ==========================================
    // ASIGNAR TÉCNICO
    // ==========================================
    public function assignTecnico(Request $request, $id)
    {
        $request->validate([
            'id_tecnico' => 'required|exists:usuarios,id_usuario',
        ]);

        $ticket = Ticket::findOrFail($id);
        
        $ticket->id_tecnico = $request->id_tecnico;
        $ticket->fecha_asignacion = now();
        $ticket->save();

        return redirect()->back()->with('success', 'Técnico asignado correctamente.');
    }

    // ==========================================
    // MIS TICKETS (VISTA DEL USUARIO)
    // ==========================================
    public function myTickets()
    {
        $usuario = Auth::user();
        
        $tickets = Ticket::where('id_usuario', $usuario->id_usuario)
                         ->with(['patio', 'categoria', 'estado', 'prioridad'])
                         ->orderBy('fecha_creacion', 'desc')
                         ->paginate(10);

        $totalTickets = $tickets->total();
        
        $enProgreso = Ticket::where('id_usuario', $usuario->id_usuario)
                            ->whereHas('estado', function($q) { $q->where('nombre', 'En Progreso'); })
                            ->count();
                            
        $resueltos = Ticket::where('id_usuario', $usuario->id_usuario)
                           ->whereHas('estado', function($q) { $q->where('nombre', 'Resuelto'); })
                           ->count();

        return view('user.my-tickets', compact('tickets', 'totalTickets', 'enProgreso', 'resueltos'));
    }

    // ==========================================
    // MOSTRAR DETALLE DEL TICKET (USUARIO)
    // ==========================================
    public function showUserTicket($id)
    {
        $ticket = Ticket::with(['usuario', 'patio', 'categoria', 'subcategoria', 'falla', 'marca', 'estado', 'comentarios.usuario'])
                        ->findOrFail($id);
        
        if ($ticket->id_usuario !== Auth::id()) {
            abort(403, 'No tienes permiso para ver este ticket.');
        }
        
        return view('user.ticket-detail', compact('ticket'));
    }

    // ==========================================
    // OBTENER DATOS PARA EL KANBAN (AJAX)
    // ==========================================
    public function getTicketsData()
    {
        $tickets = Ticket::with(['usuario', 'patio', 'prioridad', 'estado'])
                         ->orderBy('fecha_creacion', 'desc')
                         ->get();

        $ticketsPorEstado = $tickets->groupBy(function ($ticket) {
            return $ticket->estado->nombre ?? 'Sin Estado';
        });

        return response()->json([
            'tickets' => $tickets,
            'ticketsPorEstado' => $ticketsPorEstado,
        ]);
    }
}