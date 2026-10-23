<!-- CONTENIDO DE DEPARTAMENTOS -->
<div class="bg-white rounded-xl shadow-sm p-6 border border-surface-border">
    <div class="flex justify-end mb-4">
        <button onclick="openModalDepto()" class="bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-all shadow-sm font-semibold text-sm">
            <span class="material-symbols-outlined text-[18px]">add_circle</span>
            Agregar Departamento
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <th class="py-3 px-4 font-bold">Código / ID</th>
                    <th class="py-3 px-4 font-bold">Nombre del Departamento</th>
                    <th class="py-3 px-4 font-bold">Segmento</th>
                    <th class="py-3 px-4 font-bold text-center">Estatus</th>
                    <th class="py-3 px-4 font-bold text-right">Acciones</th>
                </tr>
            </thead>
            <!-- 🔥 ID en el tbody para AJAX -->
            <tbody class="divide-y divide-gray-100" id="tbody-deptos">
                @forelse($departamentos as $depto)
                    <!-- 🔥 ID único en cada fila -->
                    <tr class="hover:bg-gray-50 transition-colors" id="fila-depto-{{ $depto->id_departamento }}">
                        <td class="py-3 px-4">
                            <span class="font-bold text-primary bg-blue-50 px-2 py-1 rounded text-xs">DEP-{{ $depto->id_departamento }}</span>
                        </td>
                        <td class="py-3 px-4"><span class="font-semibold text-on-surface block">{{ $depto->nombre }}</span></td>
                        <td class="py-3 px-4 text-gray-600 text-sm">{{ $depto->segmento ?? 'General' }}</td>
                        <!-- 🔥 ID único en el contenedor del estatus -->
                        <td class="py-3 px-4 text-center" id="estatus-depto-{{ $depto->id_departamento }}">
                            @if($depto->estatus === 'activo')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">ACTIVO</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">INACTIVO</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button onclick="editarDepto({{ $depto->id_departamento }})" class="p-1.5 text-gray-400 hover:text-amber-600 rounded transition-colors" title="Editar">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </button>
                                
                                <button type="button" onclick="confirmarCambioEstatusDepto({{ $depto->id_departamento }}, '{{ $depto->nombre }}', '{{ $depto->estatus }}')" class="p-1.5 text-gray-400 hover:text-red-600 rounded transition-colors" title="Cambiar Estatus">
                                    <span class="material-symbols-outlined text-[18px]">{{ $depto->estatus === 'activo' ? 'block' : 'check_circle' }}</span>
                                </button>
                                
                                <button onclick="eliminarDepto({{ $depto->id_departamento }})" class="p-1.5 text-gray-400 hover:text-red-600 rounded transition-colors" title="Eliminar">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-8 text-center text-gray-500">No hay departamentos registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL DEPARTAMENTOS -->
<div id="depto-modal" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden flex flex-col">
        <div class="bg-tracomex-dark text-white p-5 flex items-center justify-between">
            <h3 class="text-lg font-bold" id="depto-modal-title">Agregar Departamento</h3>
            <button onclick="closeModalDepto()" class="text-white hover:text-red-400"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form id="depto-form" action="{{ route('admin.catalogos.departamentos.store') }}" method="POST" class="p-6 flex flex-col gap-4">
            @csrf
            <div class="flex flex-col gap-1.5">
                <label class="font-bold text-sm text-slate-700">Nombre del Departamento *</label>
                <input name="nombre" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg" required>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="font-bold text-sm text-slate-700">Segmento</label>
                <input name="segmento" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg" placeholder="Ej. General">
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeModalDepto()" class="px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-100">Cancelar</button>
                <button type="submit" class="bg-primary text-white px-5 py-2 rounded-lg hover:bg-blue-700">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- SCRIPTS EXCLUSIVOS DE DEPARTAMENTOS (AJAX) -->
<script>
    // Obtenemos el token CSRF
    const csrfTokenDepto = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    function openModalDepto() {
        const modal = document.getElementById('depto-modal');
        const form = document.getElementById('depto-form');
        form.reset();
        form.dataset.method = 'POST'; 
        form.dataset.id = ''; 
        form.action = "{{ route('admin.catalogos.departamentos.store') }}";
        document.getElementById('depto-modal-title').innerText = 'Agregar Departamento';
        const methodInput = form.querySelector('input[name="_method"]');
        if (methodInput) methodInput.remove();
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModalDepto() {
        document.getElementById('depto-modal').classList.add('hidden');
        document.getElementById('depto-modal').classList.remove('flex');
    }

    async function editarDepto(id) {
        try {
            const response = await fetch(`/admin/catalogos/departamentos/${id}/edit`);
            const data = await response.json();
            const modal = document.getElementById('depto-modal');
            const form = document.getElementById('depto-form');
            
            form.querySelector('input[name="nombre"]').value = data.nombre;
            form.querySelector('input[name="segmento"]').value = data.segmento;
            
            document.getElementById('depto-modal-title').innerText = 'Editar Departamento';
            
            form.dataset.method = 'PUT';
            form.dataset.id = id;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } catch (error) {
            Swal.fire('Error', 'No se pudieron cargar los datos.', 'error');
        }
    }

    // 🚀 INTERCEPTAR EL SUBMIT DEL FORMULARIO
    document.getElementById('depto-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const form = e.target;
        const method = form.dataset.method || 'POST';
        const id = form.dataset.id;
        
        let url = "{{ route('admin.catalogos.departamentos.store') }}";
        if (method === 'PUT' && id) url = `/admin/catalogos/departamentos/${id}`;

        const formData = new FormData(form);
        if (method === 'PUT') formData.append('_method', 'PUT');

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfTokenDepto,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok) {
                closeModalDepto();
                Swal.fire({ icon: 'success', title: '¡Listo!', text: data.message, timer: 2000, showConfirmButton: false });
                actualizarTablaDeptos(); 
            } else {
                let errorMsg = Object.values(data.errors).flat().join('<br>');
                Swal.fire({ icon: 'error', title: 'Error de validación', html: errorMsg });
            }
        } catch (error) {
            Swal.fire('Error', 'Ocurrió un error en el servidor.', 'error');
        }
    });

    // 🚀 ACTUALIZAR TABLA SIN RECARGAR
    async function actualizarTablaDeptos() {
        try {
            const response = await fetch(window.location.href);
            const html = await response.text();
            
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const nuevoTbody = doc.getElementById('tbody-deptos');
            
            if(nuevoTbody) {
                document.getElementById('tbody-deptos').innerHTML = nuevoTbody.innerHTML;
            }
        } catch (error) {
            console.error('Error al actualizar tabla:', error);
        }
    }

    // 🚀 ELIMINAR CON AJAX
    function eliminarDepto(id) {
        Swal.fire({
            title: '¿Eliminar departamento?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ba1a1a',
            cancelButtonColor: '#49607e',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/admin/catalogos/departamentos/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfTokenDepto,
                            'Accept': 'application/json'
                        },
                        body: new URLSearchParams({ '_method': 'DELETE' })
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok) {
                        document.getElementById(`fila-depto-${id}`).remove();
                        Swal.fire({ icon: 'success', title: 'Eliminado', text: data.message, timer: 2000, showConfirmButton: false });
                        
                        if(document.querySelectorAll('#tbody-deptos tr').length <= 1) {
                             actualizarTablaDeptos();
                        }
                    }
                } catch (error) {
                    Swal.fire('Error', 'No se pudo eliminar.', 'error');
                }
            }
        });
    }

    // 🚀 CAMBIAR ESTATUS CON AJAX (URL corregida)
    function confirmarCambioEstatusDepto(id, nombre, estatusActual) {
        let accion = estatusActual === 'activo' ? 'desactivar' : 'activar';
        Swal.fire({
            title: `¿Estás seguro de ${accion} este departamento?`,
            html: `El departamento <b>${nombre}</b> cambiará su estatus.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: estatusActual === 'activo' ? '#ba1a1a' : '#0059bb',
            cancelButtonColor: '#49607e',
            confirmButtonText: `Sí, ${accion}`,
            cancelButtonText: 'Cancelar'
        }).then(async (result) => {
            if (result.isConfirmed) {
                // ✅ Ahora apunta a departamentos, no a patios
                const actionUrl = `/admin/catalogos/departamentos/${id}/status`; 
                
                try {
                    const response = await fetch(actionUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfTokenDepto,
                            'Accept': 'application/json'
                        },
                        body: new URLSearchParams({ '_method': 'PATCH' })
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok) {
                        const contenedorEstatus = document.getElementById(`estatus-depto-${id}`);
                        
                        if (data.estatus === 'activo') {
                            contenedorEstatus.innerHTML = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">ACTIVO</span>';
                        } else {
                            contenedorEstatus.innerHTML = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">INACTIVO</span>';
                        }
                        
                        Swal.fire({ icon: 'success', title: '¡Listo!', text: data.message, timer: 2000, showConfirmButton: false });
                    }
                } catch (error) {
                    Swal.fire('Error', 'No se pudo cambiar el estatus.', 'error');
                }
            }
        });
    }
</script>