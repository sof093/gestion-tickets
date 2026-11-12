@extends('components.layouts.admin')

@section('title', 'Detalle del Ticket - ' . $ticket->folio) 

@section('content')
<div class="max-w-6xl mx-auto flex flex-col gap-6 pb-10">
    
    <!-- Encabezado -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-xl shadow-sm border border-slate-200">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="font-mono text-lg font-bold text-primary">Folio #{{ $ticket->folio }}</span>
                <span class="px-2 py-0.5 rounded text-xs font-bold uppercase 
                    @if($ticket->estado->nombre == 'Nuevo') bg-blue-100 text-blue-700
                    @elseif($ticket->estado->nombre == 'En Progreso') bg-amber-100 text-amber-700
                    @elseif($ticket->estado->nombre == 'Resuelto') bg-emerald-100 text-emerald-700
                    @else bg-slate-100 text-slate-700 @endif">
                    {{ $ticket->estado->nombre }}
                </span>
                <span class="px-2 py-0.5 rounded text-xs font-bold uppercase bg-red-100 text-red-700">{{ $ticket->prioridad->nombre }}</span>
            </div>
            <h1 class="text-xl font-bold text-slate-800">{{ $ticket->categoria->nombre }} - {{ $ticket->subcategoria->nombre ?? 'N/A' }}</h1>
            <p class="text-sm text-slate-500 mt-1">Reportado por: <strong>{{ $ticket->usuario->nombre_completo }}</strong> el {{ \Carbon\Carbon::parse($ticket->fecha_creacion)->format('d/m/Y H:i') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.tickets.index') }}" class="px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-100 font-medium text-sm transition-colors">← Volver al tablero</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Columna Izquierda (Datos del Ticket y Chat) -->
        <div class="lg:col-span-2 flex flex-col gap-6">
            
            <!-- Datos del Ticket -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-sm font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Datos de la Incidencia</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div><span class="text-[10px] font-bold text-slate-400 uppercase block">Patio</span><span class="text-slate-700 font-medium">{{ $ticket->patio->nombre ?? 'N/A' }}</span></div>
                    <div><span class="text-[10px] font-bold text-slate-400 uppercase block">Falla Reportada</span><span class="text-slate-700 font-medium">{{ $ticket->falla->nombre ?? 'N/A' }}</span></div>
                    <div><span class="text-[10px] font-bold text-slate-400 uppercase block">Marca</span><span class="text-slate-700 font-medium">{{ $ticket->marca->nombre ?? 'No especificada' }}</span></div>
                    <div><span class="text-[10px] font-bold text-slate-400 uppercase block">Técnico Asignado</span><span class="text-slate-700 font-medium">{{ $ticket->tecnico->nombre_completo ?? 'Sin asignar' }}</span></div>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Descripción del Problema</span>
                    <p class="text-sm text-slate-700 whitespace-pre-line">{{ $ticket->descripcion }}</p>
                </div>
            </div>

            <!-- Chat de Comentarios -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col gap-4">
                <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2">Bitácora de seguimiento y mensajería</h3>
                
                <div class="flex flex-col gap-3 max-h-[400px] overflow-y-auto pr-2">
                    @forelse($ticket->comentarios as $comentario)
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center font-bold text-xs text-slate-600 shrink-0">
                                {{ strtoupper(substr($comentario->usuario->nombre_completo ?? 'U', 0, 2)) }}
                            </div>
                            <div class="flex flex-col bg-slate-50 p-3 rounded-lg rounded-tl-none border border-slate-100 w-full">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-xs font-bold text-slate-700">{{ $comentario->usuario->nombre_completo ?? 'Usuario' }}</span>
                                    <span class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($comentario->fecha)->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-slate-700">{{ $comentario->mensaje }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 text-center py-4">No hay comentarios aún. Sé el primero en escribir.</p>
                    @endforelse
                </div>

                <!-- Formulario de nuevo comentario -->
                <form action="{{ route('admin.tickets.comment', $ticket->id_ticket) }}" method="POST" class="mt-2 pt-4 border-t border-slate-100">
                    @csrf
                    <div class="flex gap-2">
                        <input type="text" name="mensaje" required placeholder="Escribe una respuesta..." class="flex-1 px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
                        <button type="submit" class="bg-primary hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm flex items-center gap-1">
                            <span class="material-symbols-outlined text-[18px]">send</span>
                            Enviar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Columna Derecha (Controles y Datos del Solicitante) -->
        <div class="flex flex-col gap-6">
            
            <!-- Control y Gestión -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-sm font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Control y Gestión</h3>
                
                <div class="flex flex-col gap-4">
                    @if($ticket->estado->nombre == 'Nuevo')
                        <form action="{{ route('admin.tickets.status', $ticket->id_ticket) }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_estado" value="{{ $estados->where('nombre', 'En Progreso')->first()->id_estado }}">
                            <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white px-5 py-3 rounded-lg text-sm font-bold shadow-sm flex items-center justify-center gap-2 transition-colors">
                                <span class="material-symbols-outlined text-[20px]">build</span>
                                Atender Ticket (En Progreso)
                            </button>
                        </form>

                    @elseif($ticket->estado->nombre == 'En Progreso')
                        <form action="{{ route('admin.tickets.status', $ticket->id_ticket) }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_estado" value="{{ $estados->where('nombre', 'Resuelto')->first()->id_estado }}">
                            <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-3 rounded-lg text-sm font-bold shadow-sm flex items-center justify-center gap-2 transition-colors">
                                <span class="material-symbols-outlined text-[20px]">task_alt</span>
                                Marcar como Resuelto
                            </button>
                        </form>

                    @elseif($ticket->estado->nombre == 'Resuelto')
                        <div class="text-center py-4 px-5 bg-emerald-50 border border-emerald-200 rounded-lg">
                            <p class="text-sm font-bold text-emerald-700 flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">hourglass_top</span>
                                Esperando validación del usuario
                            </p>
                        </div>

                    @elseif($ticket->estado->nombre == 'Cerrado')
                        <div class="text-center py-4 px-5 bg-slate-50 border border-slate-200 rounded-lg">
                            <p class="text-sm font-bold text-slate-700 flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">check_circle</span>
                                Ticket Cerrado
                            </p>
                        </div>
                    @endif

                    <!-- Asignación manual de técnico -->
                    @if($ticket->estado->nombre == 'Nuevo' || $ticket->estado->nombre == 'En Progreso')
                        <div class="pt-4 border-t border-slate-100">
                            <form action="{{ route('admin.tickets.assign', $ticket->id_ticket) }}" method="POST" class="flex flex-col gap-2">
                                @csrf
                                <label class="text-xs font-bold text-slate-700">Reasignar Técnico</label>
                                <div class="flex gap-2">
                                    <select name="id_tecnico" class="flex-1 px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm" required>
                                        <option value="">Selecciona un técnico...</option>
                                        @foreach(\App\Models\User::where('id_rol', 1)->get() as $tecnico)
                                            <option value="{{ $tecnico->id_usuario }}" {{ $ticket->id_tecnico == $tecnico->id_usuario ? 'selected' : '' }}>{{ $tecnico->nombre_completo }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-lg text-xs font-semibold">Asignar</button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Datos del Solicitante -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-sm font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Datos del Solicitante</h3>
                <div class="flex flex-col gap-3 text-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold">
                            {{ strtoupper(substr($ticket->usuario->nombre_completo, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-800">{{ $ticket->usuario->nombre_completo }}</p>
                            <p class="text-xs text-slate-500">{{ $ticket->usuario->departamento->nombre ?? 'Sin depto' }}</p>
                        </div>
                    </div>
                    <div class="pt-3 border-t border-slate-100">
                        <span class="text-[10px] font-bold text-slate-400 uppercase block">Nómina</span>
                        <span class="text-slate-700">{{ $ticket->usuario->num_nomina }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase block">Correo</span>
                        <span class="text-slate-700 text-xs">{{ $ticket->usuario->correo }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase block">Teléfono</span>
                        <span class="text-slate-700 text-xs">+52 {{ $ticket->usuario->telefono }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection