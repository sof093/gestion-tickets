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
                <span class="text-2xl font-bold text-on-surface mt-1">{{ $totalActivos ?? 0 }}</span>
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

    <!-- Cabecera de Página & Botón Nuevo Ticket -->
    <div class="bg-surface-card rounded-xl p-5 border border-surface-border shadow-xs flex flex-col gap-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-on-surface tracking-tight">Gestión de tickets de soporte técnico</h1>
                <p class="text-xs text-text-secondary mt-0.5">Control operativo, tiempos de SLA y atención en patios industriales</p>
            </div>
            <div class="flex items-center gap-2">
                <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary hover:bg-primary-container text-white text-sm font-semibold shadow-sm transition-colors active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    <span>+ Nuevo Ticket</span>
                </button>
            </div>
        </div>

        <!-- Barra de Filtros Multi-criterio -->
        <div class="bg-surface-subtle/70 border border-surface-border rounded-lg p-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-center">
                <!-- Buscar texto -->
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-secondary text-[18px]">search</span>
                    <input class="w-full pl-9 pr-3 py-2 bg-surface-card border border-surface-border rounded-lg text-xs text-on-surface focus:outline-none focus:ring-1 focus:ring-primary shadow-2xs" placeholder="Folio, título o solicitante..." type="text"/>
                </div>
                <!-- Filtro Patio -->
                <div>
                    <select class="w-full px-3 py-2 bg-surface-card border border-surface-border rounded-lg text-xs text-on-surface focus:outline-none focus:ring-1 focus:ring-primary shadow-2xs cursor-pointer">
                        <option value="">🏢 Todos los Patios</option>
                        <option value="toluca">Patio Toluca</option>
                        <option value="saltillo">Patio Saltillo</option>
                        <option value="lazaro">Puerto Lázaro Cárdenas</option>
                        <option value="veracruz">Patio Veracruz Santa Fe</option>
                        <option value="slp">San Luis Potosí</option>
                        <option value="aguascalientes">Aguascalientes</option>
                    </select>
                </div>
                <!-- Filtro Categoría -->
                <div>
                    <select class="w-full px-3 py-2 bg-surface-card border border-surface-border rounded-lg text-xs text-on-surface focus:outline-none focus:ring-1 focus:ring-primary shadow-2xs cursor-pointer">
                        <option value="">🏷️ Todas las Categorías</option>
                        <option value="zebra">Escáneres Zebra / VIN</option>
                        <option value="redes">Redes &amp; Wi-Fi Industrial</option>
                        <option value="erp">Software ERP Tracomex</option>
                        <option value="basculas">Básculas y Sensores Rampa</option>
                        <option value="sso">Cuentas y Accesos SSO</option>
                    </select>
                </div>
                <!-- Filtro Prioridad -->
                <div>
                    <select class="w-full px-3 py-2 bg-surface-card border border-surface-border rounded-lg text-xs text-on-surface focus:outline-none focus:ring-1 focus:ring-primary shadow-2xs cursor-pointer">
                        <option value="">⚡ Prioridad: Todas</option>
                        <option value="critica">Crítica</option>
                        <option value="alta">Alta</option>
                        <option value="media">Media</option>
                        <option value="baja">Baja</option>
                    </select>
                </div>
                <!-- Filtro Estado -->
                <div>
                    <select class="w-full px-3 py-2 bg-surface-card border border-surface-border rounded-lg text-xs text-on-surface focus:outline-none focus:ring-1 focus:ring-primary shadow-2xs cursor-pointer">
                        <option value="">📌 Estado: Todos</option>
                        <option value="nuevo">Nuevo</option>
                        <option value="progreso">En Progreso</option>
                        <option value="resuelto">Resuelto</option>
                        <option value="cerrado">Cerrado</option>
                    </select>
                </div>
            </div>
            <!-- Limpiar Filtros -->
            <div class="mt-2.5 pt-2 border-t border-surface-border flex items-center justify-between text-xs">
                <span class="text-text-secondary">Filtrado activo en tiempo real</span>
                <button class="inline-flex items-center gap-1 text-text-secondary hover:text-accent-red font-medium transition-colors">
                    <span class="material-symbols-outlined text-[16px]">filter_alt_off</span>
                    <span>Limpiar Filtros</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Tablero Kanban de Tickets -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 items-start">

        <!-- ==================== COLUMNA: NUEVO ==================== -->
        <div class="bg-surface-subtle rounded-xl p-3 border border-surface-border flex flex-col gap-3 min-h-[580px]">
            <div class="flex items-center justify-between px-1 py-0.5">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-primary"></span>
                    <span class="font-bold text-xs uppercase tracking-wide text-on-surface">NUEVO</span>
                </div>
                <span class="px-2 py-0.5 rounded-full bg-blue-100 text-primary font-bold text-xs">14</span>
            </div>
            <div class="flex flex-col gap-3">
                <!-- Ticket 1 -->
                <div class="bg-surface-card rounded-lg p-3.5 border border-surface-border shadow-xs relative overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-accent-red"></div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="font-mono text-xs font-bold text-primary">TK-2025-092</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-red-100 text-accent-red">CRÍTICA</span>
                    </div>
                    <h3 class="font-bold text-sm text-on-surface leading-snug mb-2 group-hover:text-primary transition-colors">Falla en lector de código VIN en rampa 3</h3>
                    <div class="space-y-1 mb-3 text-xs text-text-secondary">
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[15px]">location_on</span>
                            <span class="truncate">Patio Toluca • Rampa 03</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[15px]">person</span>
                            <span class="truncate">Ing. Héctor Valdés (Madrinas)</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[15px]">schedule</span>
                            <span>Hace 18 min</span>
                        </div>
                    </div>
                    <div class="pt-2 border-t border-surface-border flex items-center justify-between">
                        <span class="inline-flex items-center gap-1 text-[11px] text-text-secondary font-medium">
                            <span class="material-symbols-outlined text-[14px]">person_off</span> Sin asignar
                        </span>
                        <button class="px-2.5 py-1 rounded bg-surface-subtle text-primary hover:bg-primary hover:text-white transition-colors text-xs font-semibold flex items-center gap-1">
                            <span>Ver detalle</span>
                            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== COLUMNA: EN PROGRESO ==================== -->
        <div class="bg-surface-subtle rounded-xl p-3 border border-surface-border flex flex-col gap-3 min-h-[580px]">
            <div class="flex items-center justify-between px-1 py-0.5">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-accent-teal"></span>
                    <span class="font-bold text-xs uppercase tracking-wide text-on-surface">EN PROGRESO</span>
                </div>
                <span class="px-2 py-0.5 rounded-full bg-sky-100 text-accent-teal font-bold text-xs">28</span>
            </div>
            <div class="flex flex-col gap-3">
                <!-- Ticket 1 -->
                <div class="bg-surface-card rounded-lg p-3.5 border border-surface-border shadow-xs relative overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-accent-red"></div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="font-mono text-xs font-bold text-primary">TK-2025-084</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-red-100 text-accent-red">CRÍTICA</span>
                    </div>
                    <h3 class="font-bold text-sm text-on-surface leading-snug mb-2 group-hover:text-primary transition-colors">Descalibración en báscula de pesaje de ejes Madrinas</h3>
                    <div class="space-y-1 mb-2 text-xs text-text-secondary">
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[15px]">location_on</span>
                            <span class="truncate">Puerto Lázaro Cárdenas</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[15px]">person</span>
                            <span class="truncate">Manuel Arredondo</span>
                        </div>
                    </div>
                    <div class="mb-3 bg-surface-subtle p-2 rounded border border-surface-border">
                        <div class="flex items-center justify-between text-[11px] mb-1">
                            <span class="text-text-secondary font-medium">SLA Restante</span>
                            <span class="font-bold text-accent-red">45m de 2h</span>
                        </div>
                        <div class="w-full bg-surface-border rounded-full h-1.5 overflow-hidden">
                            <div class="bg-accent-red h-full rounded-full" style="width: 72%;"></div>
                        </div>
                    </div>
                    <div class="pt-2 border-t border-surface-border flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <div class="w-6 h-6 rounded-full bg-primary text-white flex items-center justify-center text-[10px] font-bold">CM</div>
                            <span class="text-xs font-medium text-on-surface truncate max-w-[80px]">C. Mendoza</span>
                        </div>
                        <button class="px-2.5 py-1 rounded bg-surface-subtle text-primary hover:bg-primary hover:text-white transition-colors text-xs font-semibold flex items-center gap-1">
                            <span>Ver detalle</span>
                            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== COLUMNA: RESUELTO ==================== -->
        <div class="bg-surface-subtle rounded-xl p-3 border border-surface-border flex flex-col gap-3 min-h-[580px]">
            <div class="flex items-center justify-between px-1 py-0.5">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-green-600"></span>
                    <span class="font-bold text-xs uppercase tracking-wide text-on-surface">RESUELTO</span>
                </div>
                <span class="px-2 py-0.5 rounded-full bg-green-100 text-green-700 font-bold text-xs">65</span>
            </div>
            <div class="flex flex-col gap-3">
                <!-- Ticket 1 -->
                <div class="bg-surface-card rounded-lg p-3.5 border border-surface-border shadow-xs relative overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-green-600"></div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="font-mono text-xs font-bold text-primary">TK-2025-065</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-green-100 text-green-700">RESUELTO</span>
                    </div>
                    <h3 class="font-bold text-sm text-on-surface leading-snug mb-2 group-hover:text-primary transition-colors">Actualización de firmware antena RFID en caseta salida</h3>
                    <div class="space-y-1 mb-3 text-xs text-text-secondary">
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[15px]">location_on</span>
                            <span class="truncate">Aguascalientes • Salida 01</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-green-700 font-medium">
                            <span class="material-symbols-outlined text-[15px]">task_alt</span>
                            <span class="truncate">Validado por Ing. Bernal</span>
                        </div>
                    </div>
                    <div class="pt-2 border-t border-surface-border flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <div class="w-6 h-6 rounded-full bg-primary text-white flex items-center justify-center text-[10px] font-bold">FB</div>
                            <span class="text-xs font-medium text-on-surface truncate max-w-[80px]">F. Bernal</span>
                        </div>
                        <button class="px-2.5 py-1 rounded bg-surface-subtle text-primary hover:bg-primary hover:text-white transition-colors text-xs font-semibold flex items-center gap-1">
                            <span>Ver detalle</span>
                            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== COLUMNA: CERRADO ==================== -->
        <div class="bg-surface-subtle rounded-xl p-3 border border-surface-border flex flex-col gap-3 min-h-[580px]">
            <div class="flex items-center justify-between px-1 py-0.5">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-gray-500"></span>
                    <span class="font-bold text-xs uppercase tracking-wide text-on-surface">CERRADO</span>
                </div>
                <span class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-700 font-bold text-xs">182</span>
            </div>
            <div class="flex flex-col gap-3">
                <!-- Ticket 1 -->
                <div class="bg-surface-card/80 rounded-lg p-3.5 border border-surface-border shadow-xs relative overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-gray-400"></div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="font-mono text-xs font-bold text-text-secondary">TK-2025-045</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-gray-100 text-gray-700">CERRADO</span>
                    </div>
                    <h3 class="font-bold text-sm text-on-surface leading-snug mb-2">Instalación punto de red en caseta de pesaje</h3>
                    <div class="space-y-1 mb-3 text-xs text-text-secondary">
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[15px]">location_on</span>
                            <span class="truncate">Patio Toluca</span>
                        </div>
                    </div>
                    <div class="pt-2 border-t border-surface-border flex items-center justify-between">
                        <span class="text-xs text-text-secondary">Archivado</span>
                        <button class="px-2.5 py-1 rounded bg-surface-subtle text-text-secondary hover:bg-slate-200 transition-colors text-xs font-semibold flex items-center gap-1">
                            <span>Ver historial</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection