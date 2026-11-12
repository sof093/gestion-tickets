@extends('components.layouts.admin')

@section('title', 'Gestión de Tickets - TRANSDRIZA')

@section('content')
<div class="flex flex-col gap-6">

    <!-- Sección Superior: Indicadores Rápidos (KPIs) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- KPI 1 -->
        <div class="bg-surface-card p-4 rounded-xl border border-surface-border flex items-center justify-between shadow-xs">
            <div class="flex flex-col">
                <span class="text-xs font-semibold text-text-secondary uppercase tracking-wider">TOTAL TICKETS ACTIVOS</span>
                <span id="total-activos-kpi" class="text-2xl font-bold text-on-surface mt-1">{{ $totalActivos ?? 0 }}</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-[26px]">confirmation_number</span>
            </div>
        </div>
        <!-- KPI 2 -->
        <div class="bg-surface-card p-4 rounded-xl border border-surface-border flex items-center justify-between shadow-xs">
            <div class="flex flex-col">
                <span class="text-xs font-semibold text-text-secondary uppercase tracking-wider">TIEMPO PROMEDIO RESOLUCIÓN</span>
                <span class="text-2xl font-bold text-on-surface mt-1">1h 42m</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-teal-50 flex items-center justify-center text-accent-teal">
                <span class="material-symbols-outlined text-[26px]">timer</span>
            </div>
        </div>
    </div>

    <!-- Cabecera de Página -->
    <div class="bg-surface-card rounded-xl p-5 border border-surface-border shadow-xs flex flex-col gap-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-on-surface tracking-tight">Gestión de tickets de soporte técnico</h1>
                <p class="text-xs text-text-secondary mt-0.5">Control operativo, tiempos de SLA y atención en patios industriales</p>
            </div>
        </div>

        <!-- Barra de Filtros -->
        <form id="filter-form" action="{{ route('admin.tickets.index') }}" method="GET" class="bg-surface-subtle/70 border border-surface-border rounded-lg p-3 flex flex-col gap-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-center">
                
                <!-- 1. Buscador -->
                <div class="relative lg:col-span-2">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-secondary text-[18px]">search</span>
                    <input id="search-input" name="search" value="{{ request('search') }}" 
                           class="w-full pl-9 pr-3 py-2 bg-surface-card border border-surface-border rounded-lg text-sm text-on-surface focus:outline-none focus:ring-1 focus:ring-primary shadow-2xs" 
                           placeholder="Buscar por folio, título o solicitante..." type="text" />
                </div>

                <!-- 2. Filtro Patio -->
                <div>
                    <select name="patio_id" class="w-full px-3 py-2 bg-surface-card border border-surface-border rounded-lg text-sm text-on-surface focus:outline-none focus:ring-1 focus:ring-primary shadow-2xs cursor-pointer">
                        <option value="">🏢 Todos los Patios</option>
                        @foreach($patios as $patio)
                            <option value="{{ $patio->id_patio }}" {{ request('patio_id') == $patio->id_patio ? 'selected' : '' }}>{{ $patio->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- 3. Filtro Prioridad -->
                <div>
                    <select name="prioridad_id" class="w-full px-3 py-2 bg-surface-card border border-surface-border rounded-lg text-sm text-on-surface focus:outline-none focus:ring-1 focus:ring-primary shadow-2xs cursor-pointer">
                        <option value="">⚡ Prioridad: Todas</option>
                        @foreach($prioridades as $prio)
                            <option value="{{ $prio->id_prioridad }}" {{ request('prioridad_id') == $prio->id_prioridad ? 'selected' : '' }}>{{ $prio->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- 4. Filtro Estado -->
                <div>
                    <select name="estado_id" class="w-full px-3 py-2 bg-surface-card border border-surface-border rounded-lg text-sm text-on-surface focus:outline-none focus:ring-1 focus:ring-primary shadow-2xs cursor-pointer">
                        <option value="">📌 Estado: Todos</option>
                        @foreach($estadosList as $est)
                            <option value="{{ $est->id_estado }}" {{ request('estado_id') == $est->id_estado ? 'selected' : '' }}>{{ $est->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Botones y Estado -->
            <div class="flex items-center justify-between text-xs">
                <span class="text-text-secondary flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">info</span>
                    Los filtros se aplican automáticamente
                </span>
                
                <div class="flex items-center gap-2">
                    <button type="button" id="btn-limpiar" class="inline-flex items-center gap-1 text-text-secondary hover:text-accent-red font-medium transition-colors bg-white border border-surface-border px-3 py-1.5 rounded-lg shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">filter_alt_off</span> Limpiar filtros
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Tablero Kanban -->
    <div id="kanban-container" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 items-start transition-opacity duration-300">
        @php
            $estados = [
                'Nuevo' => ['bg' => 'bg-primary', 'badge' => 'bg-blue-100 text-primary'],
                'En Progreso' => ['bg' => 'bg-amber-500', 'badge' => 'bg-amber-100 text-amber-700'],
                'Resuelto' => ['bg' => 'bg-emerald-500', 'badge' => 'bg-emerald-100 text-emerald-700'],
                'Cerrado' => ['bg' => 'bg-slate-500', 'badge' => 'bg-slate-100 text-slate-700'],
            ];
        @endphp

        @foreach($estados as $estadoNombre => $color)
            @php
                $ticketsDelEstado = $ticketsPorEstado->get($estadoNombre, collect());
            @endphp

            <div class="bg-surface-subtle rounded-xl p-3 border border-surface-border flex flex-col gap-3 min-h-[580px]">
                <div class="flex items-center justify-between px-1 py-0.5">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full {{ $color['bg'] }}"></span>
                        <span class="font-bold text-xs uppercase tracking-wide text-on-surface">{{ $estadoNombre }}</span>
                    </div>
                    <span class="px-2 py-0.5 rounded-full {{ $color['badge'] }} font-bold text-xs">{{ $ticketsDelEstado->count() }}</span>
                </div>
                
                <div class="flex flex-col gap-3">
                    @forelse($ticketsDelEstado as $ticket)
                        <div class="bg-surface-card rounded-lg p-3.5 border border-surface-border shadow-xs relative overflow-hidden group hover:shadow-md transition-shadow">
                            <div class="absolute left-0 top-0 bottom-0 w-1 
                                @if($ticket->prioridad->nombre == 'CRÍTICA') bg-red-600 
                                @elseif($ticket->prioridad->nombre == 'ALTA') bg-amber-500 
                                @elseif($ticket->prioridad->nombre == 'MEDIA') bg-blue-500 
                                @else bg-gray-400 @endif">
                            </div>
                            
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="font-mono text-xs font-bold text-primary">{{ $ticket->folio }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase 
                                    @if($ticket->prioridad->nombre == 'CRÍTICA') bg-red-100 text-accent-red 
                                    @elseif($ticket->prioridad->nombre == 'ALTA') bg-amber-100 text-amber-700 
                                    @elseif($ticket->prioridad->nombre == 'MEDIA') bg-blue-100 text-blue-700 
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ $ticket->prioridad->nombre }}
                                </span>
                            </div>
                            
                            <h3 class="font-bold text-sm text-on-surface leading-snug mb-2 group-hover:text-primary transition-colors">
                                {{ Str::limit($ticket->descripcion, 60) }}
                            </h3>
                            
                            <div class="space-y-1 mb-3 text-xs text-text-secondary">
                                <div class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[15px]">location_on</span>
                                    <span class="truncate">{{ $ticket->patio->nombre ?? 'Sin Patio' }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[15px]">person</span>
                                    <span class="truncate">{{ $ticket->usuario->nombre_completo ?? 'Sin usuario' }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[15px]">schedule</span>
                                    <span>{{ \Carbon\Carbon::parse($ticket->fecha_creacion)->diffForHumans() }}</span>
                                </div>
                            </div>
                            
                            <div class="pt-2 border-t border-surface-border flex items-center justify-between">
                                <span class="inline-flex items-center gap-1 text-[11px] text-text-secondary font-medium">
                                    <span class="material-symbols-outlined text-[14px]">category</span> 
                                    {{ $ticket->categoria->nombre ?? 'Sin cat' }}
                                </span>
                                <a href="{{ route('admin.tickets.show', $ticket->id_ticket) }}" class="px-2.5 py-1 rounded bg-surface-subtle text-primary hover:bg-primary hover:text-white transition-colors text-xs font-semibold flex items-center gap-1">
                                    <span>Ver detalle</span>
                                    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-xs text-gray-400 border-2 border-dashed border-gray-200 rounded-lg">
                            No hay tickets en este estado.
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- SCRIPT AJAX PARA FILTRADO SIN RECARGA -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('filter-form');
    const searchInput = document.getElementById('search-input');
    const kanbanContainer = document.getElementById('kanban-container');
    const kpiActivos = document.getElementById('total-activos-kpi');
    const btnLimpiar = document.getElementById('btn-limpiar');
    
    // Función que hace la petición AJAX
    function fetchTickets() {
        const formData = new FormData(form);
        const params = new URLSearchParams(formData).toString();

        kanbanContainer.style.opacity = '0.5';

        fetch(`${form.action}?${params}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest' 
            }
        })
        .then(response => response.json())
        .then(data => {
            kpiActivos.innerText = data.totalActivos;
            renderKanban(data.ticketsPorEstado);
            kanbanContainer.style.opacity = '1';
        })
        .catch(error => {
            console.error('Error:', error);
            kanbanContainer.style.opacity = '1';
        });
    }

    // Escuchar Enter en el buscador
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            fetchTickets();
        }
    });

    // Escuchar cambios en los Selects
    form.querySelectorAll('select').forEach(select => {
        select.addEventListener('change', function() {
            fetchTickets();
        });
    });

    // NUEVO: Escuchar el botón de Limpiar Filtros
    btnLimpiar.addEventListener('click', function() {
        // 1. Limpiamos el texto del buscador
        searchInput.value = '';
        
        // 2. Reiniciamos los selects a su primera opción (value="")
        form.querySelectorAll('select').forEach(select => {
            select.value = '';
        });

        // 3. Volvemos a hacer la petición para traer todo
        fetchTickets();
    });

    // Función para pintar el Kanban
    function renderKanban(ticketsPorEstado) {
        const estados = {
            'Nuevo': { bg: 'bg-primary', badge: 'bg-blue-100 text-primary' },
            'En Progreso': { bg: 'bg-amber-500', badge: 'bg-amber-100 text-amber-700' },
            'Resuelto': { bg: 'bg-emerald-500', badge: 'bg-emerald-100 text-emerald-700' },
            'Cerrado': { bg: 'bg-slate-500', badge: 'bg-slate-100 text-slate-700' }
        };

        kanbanContainer.innerHTML = ''; 

        Object.keys(estados).forEach(estadoNombre => {
            const color = estados[estadoNombre];
            const tickets = ticketsPorEstado[estadoNombre] || [];

            const columna = document.createElement('div');
            columna.className = 'bg-surface-subtle rounded-xl p-3 border border-surface-border flex flex-col gap-3 min-h-[580px]';
            
            let htmlTickets = '';

            if (tickets.length === 0) {
                htmlTickets = `<div class="text-center py-8 text-xs text-gray-400 border-2 border-dashed border-gray-200 rounded-lg">No hay tickets en este estado.</div>`;
            } else {
                tickets.forEach(ticket => {
                    let colorPrioridad = 'bg-gray-400';
                    let textPrioridad = 'bg-gray-100 text-gray-700';
                    if (ticket.prioridad?.nombre === 'CRÍTICA') { colorPrioridad = 'bg-red-600'; textPrioridad = 'bg-red-100 text-accent-red'; }
                    else if (ticket.prioridad?.nombre === 'ALTA') { colorPrioridad = 'bg-amber-500'; textPrioridad = 'bg-amber-100 text-amber-700'; }
                    else if (ticket.prioridad?.nombre === 'MEDIA') { colorPrioridad = 'bg-blue-500'; textPrioridad = 'bg-blue-100 text-blue-700'; }

                    htmlTickets += `
                        <div class="bg-surface-card rounded-lg p-3.5 border border-surface-border shadow-xs relative overflow-hidden group hover:shadow-md transition-shadow">
                            <div class="absolute left-0 top-0 bottom-0 w-1 ${colorPrioridad}"></div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="font-mono text-xs font-bold text-primary">${ticket.folio}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase ${textPrioridad}">${ticket.prioridad?.nombre || 'N/A'}</span>
                            </div>
                            <h3 class="font-bold text-sm text-on-surface leading-snug mb-2 group-hover:text-primary transition-colors">
                                ${ticket.descripcion.length > 60 ? ticket.descripcion.substring(0, 60) + '...' : ticket.descripcion}
                            </h3>
                            <div class="space-y-1 mb-3 text-xs text-text-secondary">
                                <div class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[15px]">location_on</span><span class="truncate">${ticket.patio?.nombre || 'Sin Patio'}</span></div>
                                <div class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[15px]">person</span><span class="truncate">${ticket.usuario?.nombre_completo || 'Sin usuario'}</span></div>
                                <div class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[15px]">schedule</span><span>${new Date(ticket.fecha_creacion).toLocaleDateString('es-MX', { day: '2-digit', month: 'short', year: 'numeric' })}</span></div>
                            </div>
                            <div class="pt-2 border-t border-surface-border flex items-center justify-between">
                                <span class="inline-flex items-center gap-1 text-[11px] text-text-secondary font-medium"><span class="material-symbols-outlined text-[14px]">category</span> ${ticket.categoria?.nombre || 'Sin cat'}</span>
                                <a href="/admin/tickets/${ticket.id_ticket}" class="px-2.5 py-1 rounded bg-surface-subtle text-primary hover:bg-primary hover:text-white transition-colors text-xs font-semibold flex items-center gap-1">
                                    <span>Ver detalle</span><span class="material-symbols-outlined text-[14px]">chevron_right</span>
                                </a>
                            </div>
                        </div>
                    `;
                });
            }

            columna.innerHTML = `
                <div class="flex items-center justify-between px-1 py-0.5">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full ${color.bg}"></span>
                        <span class="font-bold text-xs uppercase tracking-wide text-on-surface">${estadoNombre}</span>
                    </div>
                    <span class="px-2 py-0.5 rounded-full ${color.badge} font-bold text-xs">${tickets.length}</span>
                </div>
                <div class="flex flex-col gap-3">${htmlTickets}</div>
            `;

            kanbanContainer.appendChild(columna);
        });
    }
});
</script>
@endsection