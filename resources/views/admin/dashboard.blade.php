@extends('components.layouts.admin')

@section('title', 'Tablero de Control - Grupo TRACOMEX')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
        <h1 class="text-2xl font-extrabold text-on-surface tracking-tight">Panel de control administrativo</h1>
        <p class="text-sm text-text-secondary mt-0.5">Monitoreo en tiempo real de operaciones de soporte y logística en patios</p>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="bg-white rounded-xl p-5 border border-surface-border shadow-sm flex flex-col justify-between relative overflow-hidden group hover:border-primary/40 transition-all">
        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-primary"></div>
        <div>
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-text-secondary">Nuevos</span>
                <div class="w-9 h-9 rounded-lg bg-blue-50 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[22px]">mark_email_unread</span>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-on-surface">{{ $nuevos ?? 0 }}</span>
                <span class="text-xs font-medium text-text-secondary">tickets</span>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-blue-100 text-primary">Pendientes</span>
        </div>
    </div>

    <div class="bg-white rounded-xl p-5 border border-surface-border shadow-sm flex flex-col justify-between relative overflow-hidden group hover:border-accent-teal/40 transition-all">
        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-accent-teal"></div>
        <div>
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-text-secondary">En Progreso</span>
                <div class="w-9 h-9 rounded-lg bg-teal-50 text-accent-teal flex items-center justify-center">
                    <span class="material-symbols-outlined text-[22px]">engineering</span>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-on-surface">{{ $enProgreso ?? 0 }}</span>
                <span class="text-xs font-medium text-text-secondary">tickets</span>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-sky-100 text-accent-teal">En atención</span>
        </div>
    </div>

    <div class="bg-white rounded-xl p-5 border border-surface-border shadow-sm flex flex-col justify-between relative overflow-hidden group hover:border-green-500/40 transition-all">
        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-green-600"></div>
        <div>
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-text-secondary">Resueltos</span>
                <div class="w-9 h-9 rounded-lg bg-green-50 text-green-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[22px]">task_alt</span>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-on-surface">{{ $resueltos ?? 0 }}</span>
                <span class="text-xs font-medium text-text-secondary">tickets</span>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-green-100 text-green-700">Verificados</span>
        </div>
    </div>

    <div class="bg-white rounded-xl p-5 border border-surface-border shadow-sm flex flex-col justify-between relative overflow-hidden group hover:border-gray-400 transition-all">
        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gray-500"></div>
        <div>
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-text-secondary">Cerrados</span>
                <div class="w-9 h-9 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[22px]">inventory_2</span>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-on-surface">{{ $cerrados ?? 0 }}</span>
                <span class="text-xs font-medium text-text-secondary">tickets</span>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-gray-100 text-gray-700">Archivados</span>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl border border-surface-border shadow-sm p-6 mb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-base font-bold text-on-surface">Tickets recientes</h2>
    </div>
    <div class="text-center py-8 text-text-secondary">
        <span class="material-symbols-outlined text-4xl mb-2 text-gray-300">inbox</span>
        <p class="text-sm font-medium">Aún no hay tickets registrados en el sistema.</p>
    </div>
</div>
@endsection