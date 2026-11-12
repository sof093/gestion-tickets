<!-- CONTENIDO DE ESTADOS (SOLO LECTURA) -->
<div class="bg-white rounded-xl shadow-sm p-6 border border-surface-border">
    <div class="mb-6">
        <h2 class="font-headline-md text-lg font-bold text-on-surface">Ciclo de vida del ticket (estados canónicos)</h2>
        <p class="text-sm text-text-secondary">Transiciones predefinidas bajo protocolo ITIL para trazabilidad de incidencias.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @forelse($estados as $estado)
            @php
                // Colores según el orden (1=Nuevo, 2=En Progreso, 3=Resuelto, 4=Cerrado)
                $colores = [
                    1 => ['bg' => 'bg-slate-50', 'border' => 'border-slate-200', 'dot' => 'bg-slate-400'],
                    2 => ['bg' => 'bg-amber-50', 'border' => 'border-amber-100', 'dot' => 'bg-amber-500'],
                    3 => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-100', 'dot' => 'bg-emerald-500'],
                    4 => ['bg' => 'bg-blue-50', 'border' => 'border-blue-100', 'dot' => 'bg-primary'],
                ];
                $c = $colores[$estado->orden] ?? $colores[1];
            @endphp
            <div class="p-4 rounded-xl {{ $c['bg'] }} border {{ $c['border'] }} flex flex-col relative">
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-3 h-3 rounded-full {{ $c['dot'] }}"></span>
                    <span class="font-title-sm text-sm font-bold text-on-surface">{{ $estado->orden }}. {{ $estado->nombre }}</span>
                </div>
                <p class="text-xs text-text-secondary flex-1">
                    @if($estado->orden == 1)
                        Generado por inspector en patio o despachador. En cola de asignación al técnico correspondiente.
                    @elseif($estado->orden == 2)
                        Técnico o mecánico asignado activamente en la resolución. Cronómetro SLA activo.
                    @elseif($estado->orden == 3)
                        Acción correctiva aplicada. Esperando confirmación y firma de conformidad de patio.
                    @else
                        Dictamen final emitido, validado por Supervisor TI y archivado en bitácora histórica.
                    @endif
                </p>
                <span class="mt-3 text-[11px] font-code-sm text-secondary">ID Estado: ST-00{{ $estado->orden }}</span>
            </div>
        @empty
            <div class="col-span-4 text-center py-10 text-gray-500 border-2 border-dashed rounded-xl">
                No hay estados registrados en la base de datos.
            </div>
        @endforelse
    </div>
</div>