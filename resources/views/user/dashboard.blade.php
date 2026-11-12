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
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase">Tickets Activos</p>
                <p class="text-3xl font-extrabold text-slate-800">0</p>
                <p class="text-[10px] text-primary font-semibold mt-1">En Progreso</p>
            </div>
            <span class="material-symbols-outlined text-primary text-4xl opacity-50">confirmation_number</span>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase">Resueltos Recientes</p>
                <p class="text-3xl font-extrabold text-slate-800">0</p>
                <p class="text-[10px] text-slate-500 mt-1">Validados</p>
            </div>
            <span class="material-symbols-outlined text-emerald-500 text-4xl opacity-50">task_alt</span>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase">Reportados este mes</p>
                <p class="text-3xl font-extrabold text-slate-800">0</p>
                <p class="text-[10px] text-slate-500 mt-1">SLA Promedio: --</p>
            </div>
            <span class="material-symbols-outlined text-amber-500 text-4xl opacity-50">timer</span>
        </div>
    </div>

    <!-- Tabla de Tickets Recientes -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-slate-800">Mis Solicitudes Recientes</h2>
        </div>
        
        <div class="text-center py-12 text-slate-500">
            <span class="material-symbols-outlined text-5xl text-slate-300 mb-2">inbox</span>
            <p class="text-sm font-medium">Aún no has levantado ningún ticket.</p>
            <p class="text-xs text-slate-400 mt-1">Tus solicitudes aparecerán aquí.</p>
        </div>
    </div>
</div>
@endsection