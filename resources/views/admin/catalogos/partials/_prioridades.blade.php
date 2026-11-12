<!-- CONTENIDO DE PRIORIDADES -->
<div class="bg-white rounded-xl shadow-sm p-6 border border-surface-border">
    <div class="mb-6">
        <h2 class="font-headline-md text-lg font-bold text-on-surface">Nivel de Prioridad</h2>
        <p class="text-sm text-text-secondary">Parámetros estrictos de compromiso para el equipo de Mesa de Ayuda TI y Soporte de Patios.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @forelse($prioridades as $prio)
            @php
                // Definimos los colores según el campo 'color' de la BD
                $colores = [
                    'red' => ['bg' => 'bg-red-50', 'border' => 'border-red-100', 'badge' => 'bg-red-600 text-white', 'text' => 'text-red-700', 'icon' => 'emergency'],
                    'amber' => ['bg' => 'bg-amber-50', 'border' => 'border-amber-100', 'badge' => 'bg-amber-500 text-white', 'text' => 'text-amber-700', 'icon' => 'warning'],
                    'blue' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-100', 'badge' => 'bg-blue-600 text-white', 'text' => 'text-blue-700', 'icon' => 'schedule'],
                    'gray' => ['bg' => 'bg-slate-100', 'border' => 'border-slate-200', 'badge' => 'bg-slate-700 text-white', 'text' => 'text-slate-700', 'icon' => 'info'],
                ];
                $c = $colores[$prio->color] ?? $colores['gray'];
            @endphp
            <div class="p-5 rounded-xl {{ $c['bg'] }} border {{ $c['border'] }} flex flex-col justify-between relative overflow-hidden" id="tarjeta-prioridad-{{ $prio->id_prioridad }}">
                
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2 py-0.5 rounded font-label-sm text-[11px] font-bold {{ $c['badge'] }} uppercase">{{ $prio->nombre }}</span>
                        <span class="material-symbols-outlined {{ $c['text'] }}">{{ $c['icon'] }}</span>
                    </div>
                    <!-- 🔥 BORRAMOS EL H3 DE LA DESCRIPCIÓN -->
                </div>

                <div class="mt-6 pt-3 flex flex-col gap-1 border-t border-black/5">
                    <div class="flex justify-between text-xs">
                        <span class="text-secondary font-medium">Primer Respuesta:</span>
                        <span class="font-bold {{ $c['text'] }}" id="respuesta-{{ $prio->id_prioridad }}">{{ $prio->tiempo_respuesta_min }} min</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-secondary font-medium">Resolución Máx:</span>
                        <span class="font-bold {{ $c['text'] }}" id="resolucion-{{ $prio->id_prioridad }}">{{ $prio->tiempo_resolucion_min }} min</span>
                    </div>
                </div>

                <!-- Botón para editar SLA -->
                <button onclick="openModalPrioridad({{ $prio->id_prioridad }}, '{{ addslashes($prio->nombre) }}', {{ $prio->tiempo_respuesta_min }}, {{ $prio->tiempo_resolucion_min }})" class="absolute top-2 right-2 text-gray-400 hover:text-primary transition-colors" title="Editar SLA">
    <span class="material-symbols-outlined text-[18px]">edit</span> <!-- 🔥 AGREGA ESTO -->
</button>
            </div>
        @empty
            <div class="col-span-4 text-center py-10 text-gray-500 border-2 border-dashed rounded-xl">
                No hay prioridades registradas en la base de datos.
            </div>
        @endforelse
    </div>
</div>

<!-- MODAL EDITAR PRIORIDAD -->
<div id="modal-prioridad" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 flex flex-col gap-4">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-bold text-on-surface" id="titulo-prioridad">Editar Prioridad</h3>
            <button onclick="closeModalPrioridad()" class="text-gray-400 hover:text-red-500"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form id="form-prioridad" class="flex flex-col gap-3">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="id_prioridad" id="edit_prio_id">
            
         
            <div class="grid grid-cols-2 gap-3">
                <div class="flex flex-col gap-1">
                    <label class="font-bold text-sm text-slate-700">Respuesta (min)</label>
                    <input type="number" name="tiempo_respuesta_min" id="edit_prio_respuesta" class="w-full px-3 py-2 border rounded-lg text-sm" required min="1">
                </div>
                <div class="flex flex-col gap-1">
                    <label class="font-bold text-sm text-slate-700">Resolución (min)</label>
                    <input type="number" name="tiempo_resolucion_min" id="edit_prio_resolucion" class="w-full px-3 py-2 border rounded-lg text-sm" required min="1">
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                <button type="button" onclick="closeModalPrioridad()" class="px-4 py-2 border rounded-lg text-sm">Cancelar</button>
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-semibold">Actualizar</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Abrir modal con los datos de la tarjeta
    function openModalPrioridad(id, nombre, respuesta, resolucion, descripcion) {
        document.getElementById('edit_prio_id').value = id;
        document.getElementById('titulo-prioridad').innerText = 'Editar SLA: ' + nombre;
        document.getElementById('edit_prio_respuesta').value = respuesta;
        document.getElementById('edit_prio_resolucion').value = resolucion;
        document.getElementById('modal-prioridad').classList.replace('hidden', 'flex');
    }

    function closeModalPrioridad() {
        document.getElementById('modal-prioridad').classList.replace('flex', 'hidden');
    }

    // Guardar cambios
    document.getElementById('form-prioridad').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const id = document.getElementById('edit_prio_id').value;
        
        try {
            const response = await fetch(`/admin/catalogos/prioridades/${id}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: formData
            });
            
            const data = await response.json();
            
            if (response.ok) {
                closeModalPrioridad();
                Swal.fire({ icon: 'success', title: 'Actualizado', timer: 1500, showConfirmButton: false });
                
                // Actualizamos la tarjeta visualmente
                document.getElementById(`respuesta-${id}`).innerText = data.prioridad.tiempo_respuesta_min + ' min';
                document.getElementById(`resolucion-${id}`).innerText = data.prioridad.tiempo_resolucion_min + ' min';
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo actualizar.' });
            }
        } catch (error) {
            Swal.fire('Error', 'Error de conexión', 'error');
        }
    });
</script>