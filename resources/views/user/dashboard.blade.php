@extends('components.layouts.user')

@section('title', 'Panel de Inicio - Grupo TRACOMEX')

@section('content')
<div class="flex flex-col w-full pb-6">
    
    <!-- Bienvenida -->
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Bienvenido de nuevo, {{ auth()->user()->nombre_completo }}</h1>
            <p class="text-sm text-slate-500 mt-1">
                {{ auth()->user()->departamento->nombre ?? 'Sin departamento' }} en Patio {{ auth()->user()->patio->nombre ?? 'Sin Patio' }}
            </p>
        </div>
        <a href="{{ route('user.tickets.create') }}" class="bg-primary hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-semibold transition-all shadow-md flex items-center gap-2">
            <span class="material-symbols-outlined">add_circle</span>
            Levantar Nuevo Ticket
        </a>
    </div>

    <!-- Tarjetas KPI -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <!-- Tarjeta 1: Activos -->
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase">Tickets Activos</p>
                <p class="text-3xl font-extrabold text-slate-800">{{ $enProgreso }}</p>
                <p class="text-[10px] text-primary font-semibold mt-1">En Progreso</p>
            </div>
            <span class="material-symbols-outlined text-primary text-4xl opacity-50">confirmation_number</span>
        </div>
        
        <!-- Tarjeta 2: Resueltos -->
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase">Resueltos Recientes</p>
                <p class="text-3xl font-extrabold text-slate-800">{{ $resueltos }}</p>
                <p class="text-[10px] text-slate-500 mt-1">Validados</p>
            </div>
            <span class="material-symbols-outlined text-emerald-500 text-4xl opacity-50">task_alt</span>
        </div>
        
        <!-- Tarjeta 3: Total -->
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase">Total de Tickets</p>
                <p class="text-3xl font-extrabold text-slate-800">{{ $total }}</p>
                <p class="text-[10px] text-slate-500 mt-1">Histórico</p>
            </div>
            <span class="material-symbols-outlined text-amber-500 text-4xl opacity-50">timer</span>
        </div>
    </div>

    <!-- Tabla de Tickets Recientes -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-slate-800">Mis Solicitudes Recientes</h2>
        </div>
        
        @if($ticketsRecientes->isEmpty())
            <div class="text-center py-12 text-slate-500">
                <span class="material-symbols-outlined text-5xl text-slate-300 mb-2">inbox</span>
                <p class="text-sm font-medium">Aún no has levantado ningún ticket.</p>
                <p class="text-xs text-slate-400 mt-1">Tus solicitudes aparecerán aquí.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 text-xs text-slate-500 uppercase">
                            <th class="py-3 px-4 font-bold">Folio</th>
                            <th class="py-3 px-4 font-bold">Descripción (Asunto)</th>
                            <th class="py-3 px-4 font-bold">Fecha</th>
                            <th class="py-3 px-4 font-bold">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ticketsRecientes as $ticket)
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <!-- Folio -->
                                <td class="py-4 px-4 text-sm font-semibold text-primary">{{ $ticket->folio }}</td>
                                
                                <!-- Descripción (Usando Str::limit para cortar si es muy larga) -->
                                <td class="py-4 px-4 text-sm text-slate-700">
                                    {{ \Illuminate\Support\Str::limit($ticket->descripcion, 50, '...') }}
                                </td>
                                
                                <!-- Fecha -->
                                <td class="py-4 px-4 text-sm text-slate-500">{{ \Carbon\Carbon::parse($ticket->fecha_creacion)->format('d/m/Y H:i') }}</td>
                                
                                <!-- Estado con colores dinámicos -->
                                <td class="py-4 px-4">
                                    @php
                                        $estadoNombre = $ticket->estado->nombre ?? 'Nuevo';
                                        $badgeClass = 'bg-slate-100 text-slate-600'; // Por defecto

                                        if (str_contains(strtolower($estadoNombre), 'nuevo')) {
                                            $badgeClass = 'bg-blue-100 text-blue-700';
                                        } elseif (str_contains(strtolower($estadoNombre), 'progreso')) {
                                            $badgeClass = 'bg-amber-100 text-amber-700';
                                        } elseif (str_contains(strtolower($estadoNombre), 'resuelto')) {
                                            $badgeClass = 'bg-emerald-100 text-emerald-700';
                                        } elseif (str_contains(strtolower($estadoNombre), 'cerrado')) {
                                            $badgeClass = 'bg-slate-200 text-slate-700';
                                        }
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">
                                        {{ $estadoNombre }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection