<!-- TABLA DE PATIOS -->
<div class="bg-white rounded-xl shadow-sm p-6 border border-surface-border">
    <!-- Botón para abrir el modal -->
    <div class="flex justify-end mb-4">
        <button onclick="openModal()" class="bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-all shadow-sm font-semibold text-sm">
            <span class="material-symbols-outlined text-[18px]">add_circle</span>
            Agregar Patio
        </button>
    </div>

    <!-- Tabla de patios -->
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <th class="py-3 px-4 font-bold">Código / ID</th>
                    <th class="py-3 px-4 font-bold">Nombre del Patio / Centro</th>
                    <th class="py-3 px-4 font-bold">Ciudad</th>
                    <th class="py-3 px-4 font-bold">Tipo de Instalación</th>
                    <th class="py-3 px-4 font-bold text-center">Estatus</th>
                    <th class="py-3 px-4 font-bold text-right">Acciones</th>
                </tr>
            </thead>
            <!-- 🔥 IMPORTANTE: ID en el tbody para AJAX -->
            <tbody class="divide-y divide-gray-100" id="tbody-patios">
                @forelse($patios as $patio)
                    <!-- 🔥 IMPORTANTE: ID único en cada fila para AJAX -->
                    <tr class="hover:bg-gray-50 transition-colors" id="fila-patio-{{ $patio->id_patio }}">
                        <td class="py-3 px-4">
                            <span class="font-bold text-primary bg-blue-50 px-2 py-1 rounded text-xs">
                                PAT-{{ $patio->id_patio }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="font-semibold text-on-surface block">{{ $patio->nombre }}</span>
                        </td>
                        <td class="py-3 px-4 text-gray-600 text-sm">
                            {{ $patio->ciudad }}
                        </td>
                        <td class="py-3 px-4 text-gray-600 text-sm">
                            {{ $patio->tipo ?? 'Patio de Maniobras' }}
                        </td>
                        <!-- 🔥 IMPORTANTE: ID único en el estatus para AJAX -->
                        <td class="py-3 px-4 text-center" id="estatus-patio-{{ $patio->id_patio }}">
                            @if($patio->estatus === 'activo')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                    ACTIVO
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                    INACTIVO
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button onclick="verPatio({{ $patio->id_patio }})" class="p-1.5 text-gray-400 hover:text-blue-600 rounded transition-colors" title="Ver Detalles">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                </button>
                                <button onclick="editarPatio({{ $patio->id_patio }})" class="p-1.5 text-gray-400 hover:text-amber-600 rounded transition-colors" title="Editar Patio">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </button>
                                <form action="{{ route('admin.catalogos.patios.status', $patio->id_patio) }}" method="POST" class="inline form-toggle-status">
                                    @csrf
                                    @method('PATCH')
                                    <button type="button" onclick="confirmarCambioEstatus(this, '{{ $patio->nombre }}', '{{ $patio->estatus }}')" class="p-1.5 text-gray-400 hover:text-red-600 rounded transition-colors" title="Cambiar Estatus">
                                        <span class="material-symbols-outlined text-[18px]">
                                            {{ $patio->estatus === 'activo' ? 'block' : 'check_circle' }}
                                        </span>
                                    </button>
                                </form>
                                <button onclick="eliminarPatio({{ $patio->id_patio }})" class="p-1.5 text-gray-400 hover:text-red-600 rounded transition-colors" title="Eliminar Definitivamente">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-500">
                            <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">warehouse</span>
                            <p>No hay patios registrados. ¡Agrega el primero!</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL CREAR/EDITAR PATIO -->
<div id="yard-modal" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden flex flex-col">
        <div class="bg-tracomex-dark text-white p-5 flex items-center justify-between">
            <h3 class="text-lg font-bold" id="patio-modal-title">Agregar Nuevo Patio</h3>
            <button onclick="closeModal()" class="text-white hover:text-red-400 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form id="patio-form" action="{{ route('admin.catalogos.patios.store') }}" method="POST" class="p-6 flex flex-col gap-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex flex-col gap-1.5">
                    <label class="font-label-md text-sm font-semibold text-on-surface">Nombre del Patio *</label>
                    <input name="nombre" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-primary" placeholder="ej. Patio Guadalajara" required>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="font-label-md text-sm font-semibold text-on-surface">Ciudad *</label>
                    <input name="ciudad" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-primary" placeholder="ej. Guadalajara" required>
                </div>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="font-label-md text-sm font-semibold text-on-surface">Tipo de Instalación</label>
                <select name="tipo" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-primary">
                    <option value="Patio de Maniobras">Patio de Maniobras</option>
                    <option value="Terminal Automotriz">Terminal Automotriz</option>
                    <option value="Puerto Marítimo">Puerto Marítimo</option>
                </select>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors font-medium">Cancelar</button>
                <button type="submit" class="bg-primary text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-sm">Guardar Patio</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL VER DETALLES -->
<div id="ver-modal" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden flex flex-col">
        <div class="bg-tracomex-dark text-white p-5 flex items-center justify-between">
            <h3 class="text-lg font-bold">Detalles del Patio</h3>
            <button onclick="document.getElementById('ver-modal').classList.add('hidden'); document.getElementById('ver-modal').classList.remove('flex');" class="text-white hover:text-red-400 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 flex flex-col gap-4 text-sm">
            <div class="flex justify-between border-b border-gray-100 pb-2">
                <span class="text-gray-500 font-semibold">Código ID:</span>
                <span id="ver-id" class="font-bold text-primary"></span>
            </div>
            <div class="flex justify-between border-b border-gray-100 pb-2">
                <span class="text-gray-500 font-semibold">Nombre:</span>
                <span id="ver-nombre" class="font-semibold text-on-surface"></span>
            </div>
            <div class="flex justify-between border-b border-gray-100 pb-2">
                <span class="text-gray-500 font-semibold">Ciudad:</span>
                <span id="ver-ciudad" class="text-gray-700"></span>
            </div>
            <div class="flex justify-between border-b border-gray-100 pb-2">
                <span class="text-gray-500 font-semibold">Tipo:</span>
                <span id="ver-tipo" class="text-gray-700"></span>
            </div>
            <div class="flex justify-between pb-2">
                <span class="text-gray-500 font-semibold">Estatus actual:</span>
                <span id="ver-estatus" class="font-bold"></span>
            </div>
        </div>
        <div class="p-4 bg-gray-50 border-t border-gray-100 flex justify-end">
            <button onclick="document.getElementById('ver-modal').classList.add('hidden'); document.getElementById('ver-modal').classList.remove('flex');" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">Cerrar</button>
        </div>
    </div>
</div>

<!-- SCRIPTS EXCLUSIVOS DE PATIOS (AJAX) -->
<script>
    // Obtenemos el token CSRF desde la meta etiqueta del layout
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    function openModal() {
        const modal = document.getElementById('yard-modal');
        const form = document.getElementById('patio-form');
        
        form.reset();
        form.dataset.method = 'POST'; 
        form.dataset.id = ''; 
        form.action = "{{ route('admin.catalogos.patios.store') }}";
        
        document.getElementById('patio-modal-title').innerText = 'Agregar Nuevo Patio';
        
        const methodInput = form.querySelector('input[name="_method"]');
        if (methodInput) methodInput.remove();

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        const modal = document.getElementById('yard-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    async function editarPatio(id) {
        try {
            const response = await fetch(`/admin/catalogos/patios/${id}/edit`);
            const data = await response.json();

            const modal = document.getElementById('yard-modal');
            const form = document.getElementById('patio-form');

            form.querySelector('input[name="nombre"]').value = data.nombre;
            form.querySelector('input[name="ciudad"]').value = data.ciudad;
            
            const selectTipo = form.querySelector('select[name="tipo"]');
            if(selectTipo) selectTipo.value = data.tipo || 'Patio de Maniobras';

            document.getElementById('patio-modal-title').innerText = 'Editar Patio: ' + data.nombre;
            
            form.dataset.method = 'PUT';
            form.dataset.id = id;

            modal.classList.remove('hidden');
            modal.classList.add('flex');

        } catch (error) {
            Swal.fire('Error', 'No se pudieron cargar los datos.', 'error');
        }
    }

    // 🚀 INTERCEPTAR EL SUBMIT DEL FORMULARIO (CREAR / EDITAR)
    document.getElementById('patio-form').addEventListener('submit', async function(e) {
        e.preventDefault(); 

        const form = e.target;
        const method = form.dataset.method || 'POST';
        const id = form.dataset.id;
        
        let url = "{{ route('admin.catalogos.patios.store') }}";
        if (method === 'PUT' && id) url = `/admin/catalogos/patios/${id}`;

        const formData = new FormData(form);
        if (method === 'PUT') formData.append('_method', 'PUT');

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok) {
                closeModal();
                Swal.fire({ icon: 'success', title: '¡Listo!', text: data.message, timer: 2000, showConfirmButton: false });
                actualizarTablaPatios(); 
            } else {
                let errorMsg = Object.values(data.errors).flat().join('<br>');
                Swal.fire({ icon: 'error', title: 'Error de validación', html: errorMsg });
            }
        } catch (error) {
            Swal.fire('Error', 'Ocurrió un error en el servidor.', 'error');
        }
    });

    // 🚀 ACTUALIZAR TABLA SIN RECARGAR
    async function actualizarTablaPatios() {
        try {
            const response = await fetch(window.location.href);
            const html = await response.text();
            
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const nuevoTbody = doc.getElementById('tbody-patios');
            
            if(nuevoTbody) {
                document.getElementById('tbody-patios').innerHTML = nuevoTbody.innerHTML;
            }
        } catch (error) {
            console.error('Error al actualizar tabla:', error);
        }
    }

    async function verPatio(id) {
        try {
            const response = await fetch(`/admin/catalogos/patios/${id}/edit`);
            const data = await response.json();

            document.getElementById('ver-id').innerText = 'PAT-' + data.id_patio;
            document.getElementById('ver-nombre').innerText = data.nombre;
            document.getElementById('ver-ciudad').innerText = data.ciudad;
            document.getElementById('ver-tipo').innerText = data.tipo || 'Patio de Maniobras';
            
            const estatusSpan = document.getElementById('ver-estatus');
            if (data.estatus === 'activo') {
                estatusSpan.innerText = 'ACTIVO';
                estatusSpan.className = 'font-bold text-green-600';
            } else {
                estatusSpan.innerText = 'INACTIVO';
                estatusSpan.className = 'font-bold text-red-600';
            }

            const modalVer = document.getElementById('ver-modal');
            modalVer.classList.remove('hidden');
            modalVer.classList.add('flex');

        } catch (error) {
            Swal.fire('Error', 'No se pudieron cargar los datos.', 'error');
        }
    }

    // 🚀 ELIMINAR CON AJAX
    function eliminarPatio(id) {
        Swal.fire({
            title: '¿Eliminar patio definitivamente?',
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
                    const response = await fetch(`/admin/catalogos/patios/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: new URLSearchParams({ '_method': 'DELETE' })
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok) {
                        document.getElementById(`fila-patio-${id}`).remove();
                        Swal.fire({ icon: 'success', title: 'Eliminado', text: data.message, timer: 2000, showConfirmButton: false });
                        
                        if(document.querySelectorAll('#tbody-patios tr').length <= 1) {
                             actualizarTablaPatios();
                        }
                    }
                } catch (error) {
                    Swal.fire('Error', 'No se pudo eliminar.', 'error');
                }
            }
        });
    }

    // 🚀 CAMBIAR ESTATUS CON AJAX
    function confirmarCambioEstatus(boton, nombrePatio, estatusActual) {
        let accion = estatusActual === 'activo' ? 'desactivar' : 'activar';
        let mensaje = estatusActual === 'activo' 
            ? 'Si desactivas el patio <b>' + nombrePatio + '</b>, ya no aparecerá en los formularios para nuevos tickets y usuarios.' 
            : 'Si activas el patio <b>' + nombrePatio + '</b>, volverá a estar disponible para la operación.';

        Swal.fire({
            title: '¿Estás seguro de ' + accion + ' este patio?',
            html: mensaje,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: estatusActual === 'activo' ? '#ba1a1a' : '#0059bb',
            cancelButtonColor: '#49607e',
            confirmButtonText: 'Sí, ' + accion,
            cancelButtonText: 'Cancelar'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const form = boton.closest('form');
                const actionUrl = form.action; 
                
                try {
                    const response = await fetch(actionUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: new URLSearchParams({ '_method': 'PATCH' })
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok) {
                        const idPatio = actionUrl.split('/').slice(-2, -1)[0]; 
                        const contenedorEstatus = document.getElementById(`estatus-patio-${idPatio}`);
                        
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