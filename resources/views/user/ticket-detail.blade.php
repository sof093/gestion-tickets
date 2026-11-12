@extends('components.layouts.user')

@section('title', 'Detalle del Ticket - ' . $ticket->folio)

@section('content')
<div class="max-w-4xl mx-auto flex flex-col gap-6 pb-10">
    
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
            </div>
            <h1 class="text-xl font-bold text-slate-800">{{ $ticket->categoria->nombre }} - {{ $ticket->subcategoria->nombre ?? 'N/A' }}</h1>
            <p class="text-sm text-slate-500 mt-1">Reportado el {{ \Carbon\Carbon::parse($ticket->fecha_creacion)->format('d/m/Y H:i') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('user.tickets.index') }}" class="px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-100 font-medium text-sm transition-colors">← Volver a mis tickets</a>
        </div>
    </div>

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

    <!-- Chat de Comentarios (Solo lectura para el usuario) -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col gap-4">
        <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2">Bitácora de seguimiento</h3>
        
        <div class="flex flex-col gap-3 max-h-[300px] overflow-y-auto pr-2">
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
                <p class="text-sm text-slate-400 text-center py-4">No hay comentarios aún.</p>
            @endforelse
        </div>
    </div>

    <!-- Botón de Validar y Cerrar (Solo si está Resuelto) -->
    @if($ticket->estado->nombre == 'Resuelto')
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-6 text-center">
            <h3 class="text-lg font-bold text-emerald-800 mb-2">¿El problema se solucionó?</h3>
            <p class="text-sm text-emerald-600 mb-4">Si tu incidencia fue atendida correctamente, por favor valida la solución para cerrar el ticket.</p>
            <form action="{{ route('user.tickets.close', $ticket->id_ticket) }}" method="POST">
                @csrf
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-lg font-bold text-sm shadow-sm flex items-center justify-center gap-2 mx-auto transition-colors" onclick="return confirm('¿Confirmas que el problema se solucionó?')">
                    <span class="material-symbols-outlined">check_circle</span>
                    Validar y Cerrar Ticket
                </button>
            </form>
        </div>
    @endif

</div>
@endsection