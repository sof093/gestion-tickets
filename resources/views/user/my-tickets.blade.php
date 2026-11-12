@extends('components.layouts.user')

@section('title', 'Mis Tickets - Grupo TRACOMEX')

@section('content')
<div class="flex flex-col w-full pb-6">
    
    <!-- Encabezado -->
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Mis Tickets de Soporte</h1>
            <p class="text-sm text-slate-500 mt-1">Historial y seguimiento operativo de todas las incidencias.</p>
        </div>
        <a href="{{ route('user.tickets.create') }}" class="bg-primary hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm flex items-center gap-2 transition-colors">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Levantar Nuevo Ticket
        </a>
    </div>

    <!-- Tarjetas de KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200 flex flex-col gap-1">
            <span class="text-[10px] font-bold text-slate-400 uppercase">Total Registrados</span>
            <span class="text-2xl font-extrabold text-slate-800">{{ $totalTickets ?? 0 }}</span>
            <span class="text-[10px] text-slate-500">Todos tus tickets</span>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200 flex flex-col gap-1">
            <span class="text-[10px] font-bold text-slate-400 uppercase">En Progreso</span>
            <span class="text-2xl font-extrabold text-slate-800">{{ $enProgreso ?? 0 }}</span>
            <span class="text-[10px] text-slate-500">En atención</span>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200 flex flex-col gap-1">
            <span class="text-[10px] font-bold text-slate-400 uppercase">Resueltos / En Espera</span>
            <span class="text-2xl font-extrabold text-slate-800">{{ $resueltos ?? 0 }}</span>
            <span class="text-[10px] text-slate-500">Listos para validar</span>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200 flex flex-col gap-1">
            <span class="text-[10px] font-bold text-slate-400 uppercase">Tiempo Promedio</span>
            <span class="text-2xl font-extrabold text-slate-800">1.8h</span>
            <span class="text-[10px] text-slate-500">SLA Promedio</span>
        </div>
    </div>

    <!-- Tabla de Tickets -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-slate-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Folio</th>
                        <th class="py-3 px-4">Asunto de Incidencia</th>
                        <th class="py-3 px-4">Categoría / Ubicación</th>
                        <th class="py-3 px-4">Fecha Creación</th>
                        <th class="py-3 px-4 text-center">Estado</th>
                        <th class="py-3 px-4 text-center">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-slate-50 transition-colors">
                            
                            <!-- Folio -->
                            <td class="py-3 px-4">
                                <span class="font-mono font-bold text-primary">{{ $ticket->folio }}</span>
                            </td>
                            
                            <!-- Asunto -->
                            <td class="py-3 px-4 max-w-xs">
                                <span class="block font-semibold text-slate-800 truncate">{{ $ticket->descripcion }}</span>
                                <span class="text-[11px] text-slate-400 truncate">{{ $ticket->falla->nombre ?? 'Sin falla' }}</span>
                            </td>
                            
                            <!-- Categoría / Ubicación -->
                            <td class="py-3 px-4">
                                <span class="block text-xs font-medium text-slate-700">{{ $ticket->categoria->nombre ?? 'N/A' }}</span>
                                <span class="text-[11px] text-slate-400">{{ $ticket->patio->nombre ?? 'N/A' }}</span>
                            </td>
                            
                            <!-- Fecha -->
                            <td class="py-3 px-4 text-xs text-slate-500">
                                {{ \Carbon\Carbon::parse($ticket->fecha_creacion)->format('d/m/Y H:i') }}
                            </td>
                            
                            <!-- Estado -->
                            <td class="py-3 px-4 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold 
                                    @if($ticket->estado->nombre == 'Nuevo') bg-blue-100 text-blue-700
                                    @elseif($ticket->estado->nombre == 'En Progreso') bg-amber-100 text-amber-700
                                    @elseif($ticket->estado->nombre == 'Resuelto') bg-emerald-100 text-emerald-700
                                    @else bg-slate-100 text-slate-700 @endif">
                                    {{ $ticket->estado->nombre }}
                                </span>
                            </td>
                            
                            <!-- Acción -->
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('user.tickets.show', $ticket->id_ticket) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-600 hover:text-primary transition-colors">
                                    Ver detalle
                                    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center text-slate-400">
                                <span class="material-symbols-outlined text-6xl text-slate-300 mb-3">inbox</span>
                                <p class="text-sm font-medium">Aún no has levantado ningún ticket.</p>
                                <p class="text-xs text-slate-400 mt-1">Haz clic en "Levantar Nuevo Ticket" para reportar una incidencia.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        <div class="p-4 border-t border-slate-100">
            {{ $tickets->links() }}
        </div>
    </div>
</div>
@endsection