<!-- CONTENIDO DE CATEGORÍAS -->
<div class="bg-white rounded-xl shadow-sm p-6 border border-surface-border">
    <div class="flex justify-end mb-4">
        <button onclick="openModalCategoria()" class="bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-all shadow-sm font-semibold text-sm">
            <span class="material-symbols-outlined text-[18px]">add_circle</span>
            Nueva Categoría
        </button>
    </div>
 <!-- Sección de Marcas -->
    <div class="mb-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
        <div class="flex items-center justify-between mb-3">
            <div>
                <h3 class="font-bold text-sm text-gray-800">Catálogo de Marcas</h3>
                <p class="text-xs text-gray-500">Estas marcas se usarán al levantar tickets de Hardware.</p>
            </div>
            <button onclick="openModalMarca()" class="bg-primary text-white px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-blue-700 flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">add</span> Nueva Marca
            </button>
        </div>
        <div class="flex flex-wrap gap-2" id="contenedor-marcas">
            @forelse($marcas as $marca)
                <div class="flex items-center gap-2 bg-white border border-gray-300 px-3 py-1 rounded-full text-xs" id="fila-marca-{{ $marca->id_marca }}">
                    <span class="text-gray-700">{{ $marca->nombre }}</span>
                    <button onclick="eliminarMarca({{ $marca->id_marca }})" class="text-gray-400 hover:text-red-500">
                        <span class="material-symbols-outlined text-[14px]">close</span>
                    </button>
                </div>
            @empty
                <p class="text-xs text-gray-400">No hay marcas registradas.</p>
            @endforelse
        </div>
    </div>
    <!-- Acordeón de Categorías -->
    <div class="flex flex-col gap-4" id="acordeon-categorias">
        @forelse($categorias as $cat)
            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <!-- Cabecera de la Categoría -->
                <div class="bg-gray-50 p-4 flex items-center justify-between cursor-pointer hover:bg-gray-100 transition-colors" onclick="toggleAcordeon({{ $cat->id_cat }})">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-primary text-white flex items-center justify-center font-bold text-sm">
                            {{ substr($cat->nombre, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-on-surface text-sm">{{ $cat->nombre }}</h3>
                            <p class="text-xs text-gray-500">{{ $cat->descripcion ?? 'Sin descripción' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500">{{ $cat->subcategorias->count() }} Subcategorías</span>
                        <span class="material-symbols-outlined text-gray-400">expand_more</span>
                    </div>
                </div>

                <!-- Contenido de la Categoría (Subcategorías) -->
                <div class="p-4 hidden" id="contenido-cat-{{ $cat->id_cat }}">
                    <div class="flex justify-end mb-2">
                        <button onclick="openModalSubcat({{ $cat->id_cat }})" class="text-xs bg-primary text-white px-2 py-1 rounded hover:bg-blue-700">
                            + Nueva Subcategoría
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3" id="grid-subcat-{{ $cat->id_cat }}">
                        @forelse($cat->subcategorias as $subcat)
                            <!-- ID en la tarjeta de la subcategoría -->
                            <div class="border border-gray-100 rounded-lg p-3 bg-white" id="fila-subcat-{{ $subcat->id_subcat }}">
                                <div class="flex justify-between items-start">
                                    <h4 class="font-semibold text-sm text-gray-800">{{ $subcat->nombre }}</h4>
                                    <!-- Botones de Editar y Eliminar -->
                                    <div class="flex items-center gap-2">
                                        <!-- Botón de editar: solo envía el ID, el fetch trae los datos frescos -->
                                        <button onclick="openModalEditSubcat({{ $subcat->id_subcat }})" class="text-blue-500 hover:text-blue-700" title="Editar">
                                            <span class="material-symbols-outlined text-[16px]">edit</span>
                                        </button>
                                        <button onclick="confirmarEliminarSubcat({{ $subcat->id_subcat }}, '{{ $subcat->nombre }}')" class="text-red-400 hover:text-red-600" title="Eliminar">
                                            <span class="material-symbols-outlined text-[16px]">delete</span>
                                        </button>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1" id="desc-subcat-{{ $subcat->id_subcat }}">{{ $subcat->descripcion ?? '' }}</p>
                                
                                <!-- Fallas de la subcategoría -->
                                <div class="mt-2 pt-2 border-t border-gray-100">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-[10px] font-bold uppercase text-gray-500">Síntomas / Fallas</span>
                                        <button onclick="openModalFalla({{ $subcat->id_subcat }})" class="text-[10px] bg-white border border-gray-300 px-2 py-0.5 rounded hover:bg-gray-100">
                                            + Agregar Falla
                                        </button>
                                    </div>
                                    <div class="flex flex-col gap-1" id="fallas-subcat-{{ $subcat->id_subcat }}">
                                        @forelse($subcat->fallas as $falla)
                                            <div class="flex items-center justify-between bg-white border border-gray-100 p-2 rounded text-xs" id="fila-falla-{{ $falla->id_falla }}">
                                                <span class="text-gray-700">{{ $falla->nombre }} <span class="text-gray-400 font-mono">({{ $falla->codigo_falla ?? 'N/A' }})</span></span>
                                                <button onclick="eliminarFalla({{ $falla->id_falla }})" class="text-gray-300 hover:text-red-500">
                                                    <span class="material-symbols-outlined text-[14px]">close</span>
                                                </button>
                                            </div>
                                        @empty
                                            <p class="text-[11px] text-gray-400">No hay fallas registradas.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 col-span-2">No hay subcategorías aún.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-10 text-gray-500">No hay categorías registradas.</div>
        @endforelse
    </div>
</div>

<!-- MODAL CATEGORÍA -->
<div id="modal-categoria" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 flex flex-col gap-4">
        <h3 class="text-lg font-bold text-on-surface">Nueva Categoría</h3>
        <form id="form-categoria" class="flex flex-col gap-3">
            @csrf
            <input name="nombre" class="w-full px-3 py-2 border rounded-lg" placeholder="Nombre de la categoría" required>
            <input name="descripcion" class="w-full px-3 py-2 border rounded-lg" placeholder="Descripción (opcional)">
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModalCategoria()" class="px-4 py-2 border rounded-lg">Cancelar</button>
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg">Guardar</button>
            </div>
        </form>
    </div>
</div>
   
<!-- MODAL SUBCATEGORÍA -->
<div id="modal-subcat" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 flex flex-col gap-4">
        <h3 class="text-lg font-bold text-on-surface">Nueva Subcategoría</h3>
        <form id="form-subcat" class="flex flex-col gap-3">
            @csrf
            <input type="hidden" name="id_cat" id="subcat_id_cat">
            <input name="nombre" class="w-full px-3 py-2 border rounded-lg" placeholder="Nombre de la subcategoría" required>
            <input name="descripcion" class="w-full px-3 py-2 border rounded-lg" placeholder="Descripción (opcional)">
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModalSubcat()" class="px-4 py-2 border rounded-lg">Cancelar</button>
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDITAR SUBCATEGORÍA -->
<div id="modal-edit-subcat" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 flex flex-col gap-4">
        <h3 class="text-lg font-bold text-on-surface">Editar Subcategoría</h3>
        <form id="form-edit-subcat" class="flex flex-col gap-3">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="id_subcat" id="edit_subcat_id">
            <input name="nombre" id="edit_subcat_nombre" class="w-full px-3 py-2 border rounded-lg" required>
            <input name="descripcion" id="edit_subcat_descripcion" class="w-full px-3 py-2 border rounded-lg" placeholder="Descripción (opcional)">
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModalEditSubcat()" class="px-4 py-2 border rounded-lg">Cancelar</button>
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg">Actualizar</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL FALLA -->
<div id="modal-falla" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 flex flex-col gap-4">
        <h3 class="text-lg font-bold text-on-surface">Nueva Falla / Síntoma</h3>
        <form id="form-falla" class="flex flex-col gap-3">
            @csrf
            <input type="hidden" name="id_subcat" id="falla_id_subcat">
            <input name="nombre" class="w-full px-3 py-2 border rounded-lg" placeholder="Ej: Pantalla azul" required>
            <input name="descripcion" class="w-full px-3 py-2 border rounded-lg" placeholder="Descripción detallada (opcional)">
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModalFalla()" class="px-4 py-2 border rounded-lg">Cancelar</button>
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg">Guardar</button>
            </div>
        </form>
    </div>
</div>
<!-- MODAL MARCA -->
<div id="modal-marca" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 flex flex-col gap-4">
        <h3 class="text-lg font-bold text-on-surface">Nueva Marca</h3>
        <form id="form-marca" class="flex flex-col gap-3">
            @csrf
            <input name="nombre" class="w-full px-3 py-2 border rounded-lg" placeholder="Ej: Dell, Lenovo, HP" required>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModalMarca()" class="px-4 py-2 border rounded-lg">Cancelar</button>
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg">Guardar</button>
            </div>
        </form>
    </div>
</div>
<script>
    const csrfTokenCat = '{{ csrf_token() }}';

    // Acordeón
    function toggleAcordeon(id) {
        const contenido = document.getElementById('contenido-cat-' + id);
        if (contenido) contenido.classList.toggle('hidden');
    }

    // Modales
    function openModalCategoria() { 
        document.getElementById('form-categoria').reset();
        document.getElementById('modal-categoria').classList.replace('hidden', 'flex'); 
    }
    function closeModalCategoria() { 
        document.getElementById('modal-categoria').classList.replace('flex', 'hidden'); 
        document.getElementById('form-categoria').reset();
    }
    function openModalSubcat(id_cat) { 
        document.getElementById('form-subcat').reset();
        document.getElementById('subcat_id_cat').value = id_cat;
        document.getElementById('modal-subcat').classList.replace('hidden', 'flex'); 
    }
    function closeModalSubcat() { document.getElementById('modal-subcat').classList.replace('flex', 'hidden'); }
    function openModalFalla(id_subcat) { 
        document.getElementById('falla_id_subcat').value = id_subcat;
        document.getElementById('modal-falla').classList.replace('hidden', 'flex'); 
    }
    function closeModalFalla() { document.getElementById('modal-falla').classList.replace('flex', 'hidden'); }

    // 🚀 SUBMIT CATEGORÍA
    document.getElementById('form-categoria').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        try {
            const response = await fetch("{{ route('admin.catalogos.categorias.store') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfTokenCat, 'Accept': 'application/json' },
                body: formData
            });
            const data = await response.json();
            if (response.ok) {
                closeModalCategoria();
                Swal.fire({ icon: 'success', title: 'Guardado', timer: 1500, showConfirmButton: false });
                agregarCategoriaAlDOM(data.categoria);
            } else {
                Swal.fire({ icon: 'error', title: 'Error', html: Object.values(data.errors).flat().join('<br>') });
            }
        } catch (error) {
            Swal.fire('Error', 'Error de conexión', 'error');
        }
    });

    function agregarCategoriaAlDOM(cat) {
        const contenedor = document.getElementById('acordeon-categorias');
        const div = document.createElement('div');
        div.className = 'border border-gray-200 rounded-xl overflow-hidden shadow-sm';
        div.innerHTML = `
            <div class="bg-gray-50 p-4 flex items-center justify-between cursor-pointer hover:bg-gray-100 transition-colors" onclick="toggleAcordeon(${cat.id_cat})">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-primary text-white flex items-center justify-center font-bold text-sm">${cat.nombre.charAt(0)}</div>
                    <div>
                        <h3 class="font-bold text-on-surface text-sm">${cat.nombre}</h3>
                        <p class="text-xs text-gray-500">${cat.descripcion || 'Sin descripción'}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500">0 Subcategorías</span>
                    <span class="material-symbols-outlined text-gray-400">expand_more</span>
                </div>
            </div>
            <div class="p-4 hidden" id="contenido-cat-${cat.id_cat}">
                <div class="flex justify-end mb-2">
                    <button onclick="openModalSubcat(${cat.id_cat})" class="text-xs bg-primary text-white px-2 py-1 rounded hover:bg-blue-700">+ Nueva Subcategoría</button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3" id="grid-subcat-${cat.id_cat}">
                    <p class="text-xs text-gray-400 col-span-2">No hay subcategorías aún.</p>
                </div>
            </div>
        `;
        contenedor.appendChild(div);
    }

    // 🚀 SUBMIT SUBCATEGORÍA (CORREGIDO)
    document.getElementById('form-subcat').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const idCat = document.getElementById('subcat_id_cat').value;
        try {
            const response = await fetch("{{ route('admin.catalogos.subcategorias.store') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfTokenCat, 'Accept': 'application/json' },
                body: formData
            });
            const data = await response.json();
            if (response.ok) {
                closeModalSubcat();
                this.reset();
                Swal.fire({ icon: 'success', title: 'Guardado', timer: 1500, showConfirmButton: false });
                
                // Llamamos a la función de insertar
                agregarSubcatAlDOM(data.subcategoria, idCat);
                
            } else {
                Swal.fire({ icon: 'error', title: 'Error', html: Object.values(data.errors).flat().join('<br>') });
            }
        } catch (error) {
            Swal.fire('Error', 'Error de conexión', 'error');
        }
    });

    function agregarSubcatAlDOM(subcat, idCat) {
        const grid = document.getElementById('grid-subcat-' + idCat);
        if (!grid) {
            console.error("No se encontró grid-subcat-" + idCat);
            return;
        }
        
        // 🔥 ESTO ES LO QUE FALTA: Eliminar el mensaje de "No hay subcategorías"
        // Buscamos y eliminamos el <p> de "No hay subcategorías"
        const mensajeVacio = grid.querySelector('p.text-gray-400');
        if (mensajeVacio) {
            mensajeVacio.remove(); // Lo eliminamos para que aparezca la tarjeta
        }
        
        const div = document.createElement('div');
        div.className = 'border border-gray-100 rounded-lg p-3 bg-white';
        div.id = `fila-subcat-${subcat.id_subcat}`;
        
        div.innerHTML = `
            <div class="flex justify-between items-start">
                <h4 class="font-semibold text-sm text-gray-800">${subcat.nombre}</h4>
                <div class="flex items-center gap-2">
                    <button onclick="openModalEditSubcat(${subcat.id_subcat})" class="text-blue-500 hover:text-blue-700">
                        <span class="material-symbols-outlined text-[16px]">edit</span>
                    </button>
                    <button onclick="confirmarEliminarSubcat(${subcat.id_subcat}, '${subcat.nombre.replace(/'/g, "\\'")}')" class="text-red-400 hover:text-red-600">
                        <span class="material-symbols-outlined text-[16px]">delete</span>
                    </button>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-1" id="desc-subcat-${subcat.id_subcat}">${subcat.descripcion || ''}</p>
            <div class="mt-2 pt-2 border-t border-gray-100">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-[10px] font-bold uppercase text-gray-500">Síntomas / Fallas</span>
                    <button onclick="openModalFalla(${subcat.id_subcat})" class="text-[10px] bg-white border border-gray-300 px-2 py-0.5 rounded hover:bg-gray-100">+ Agregar Falla</button>
                </div>
                <div class="flex flex-col gap-1" id="fallas-subcat-${subcat.id_subcat}">
                    <p class="text-[11px] text-gray-400">No hay fallas registradas.</p>
                </div>
            </div>
        `;
        grid.appendChild(div);
        
        // Actualizar el contador de la categoría
        const tarjetaCategoria = grid.closest('.border-gray-200');
        const contadorSpan = tarjetaCategoria.querySelector('span.text-xs.text-gray-500');
        if (contadorSpan) {
            let count = parseInt(contadorSpan.innerText) || 0;
            contadorSpan.innerText = (count + 1) + ' Subcategorías';
        }
    }

    // 🚀 SUBMIT FALLA
    document.getElementById('form-falla').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const idSubcat = document.getElementById('falla_id_subcat').value;
        try {
            const response = await fetch("{{ route('admin.catalogos.fallas.store') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfTokenCat, 'Accept': 'application/json' },
                body: formData
            });
            const data = await response.json();
            if (response.ok) {
                closeModalFalla();
                this.reset();
                Swal.fire({ icon: 'success', title: 'Guardado', timer: 1500, showConfirmButton: false });
                agregarFallaAlDOM(data.falla, idSubcat);
            } else {
                Swal.fire({ icon: 'error', title: 'Error', html: Object.values(data.errors).flat().join('<br>') });
            }
        } catch (error) {
            Swal.fire('Error', 'Error de conexión', 'error');
        }
    });

    function agregarFallaAlDOM(falla, idSubcat) {
        const contenedor = document.getElementById('fallas-subcat-' + idSubcat);
        if (!contenedor) return;
        
        const mensajeVacio = contenedor.querySelector('p.text-gray-400');
        if (mensajeVacio) mensajeVacio.remove();
        
        const div = document.createElement('div');
        div.className = 'flex items-center justify-between bg-white border border-gray-100 p-2 rounded text-xs';
        div.id = `fila-falla-${falla.id_falla}`;
        div.innerHTML = `
            <span class="text-gray-700">${falla.nombre} <span class="text-gray-400 font-mono">(${falla.codigo_falla || 'N/A'})</span></span>
            <button onclick="eliminarFalla(${falla.id_falla})" class="text-gray-300 hover:text-red-500">
                <span class="material-symbols-outlined text-[14px]">close</span>
            </button>
        `;
        contenedor.appendChild(div);
    }

    // 🚀 EDITAR SUBCATEGORÍA
    async function openModalEditSubcat(id) {
        try {
            const response = await fetch(`/admin/catalogos/subcategorias/${id}/edit`);
            const data = await response.json();
            document.getElementById('edit_subcat_id').value = data.id_subcat;
            document.getElementById('edit_subcat_nombre').value = data.nombre;
            document.getElementById('edit_subcat_descripcion').value = data.descripcion || '';
            document.getElementById('modal-edit-subcat').classList.replace('hidden', 'flex');
        } catch (error) {
            Swal.fire('Error', 'No se pudieron cargar los datos.', 'error');
        }
    }

    function closeModalEditSubcat() {
        document.getElementById('modal-edit-subcat').classList.replace('flex', 'hidden');
    }

    document.getElementById('form-edit-subcat').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const id = document.getElementById('edit_subcat_id').value;
        try {
            const response = await fetch(`/admin/catalogos/subcategorias/${id}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfTokenCat, 'Accept': 'application/json' },
                body: formData
            });
            const data = await response.json();
            if (response.ok) {
                closeModalEditSubcat();
                Swal.fire({ icon: 'success', title: 'Actualizado', timer: 1500, showConfirmButton: false });
                const tarjeta = document.getElementById(`fila-subcat-${id}`);
                if (tarjeta) {
                    tarjeta.querySelector('h4').innerText = data.subcategoria.nombre;
                    const descElemento = document.getElementById(`desc-subcat-${id}`);
                    if (descElemento) descElemento.innerText = data.subcategoria.descripcion || '';
                }
            } else {
                let errorMsg = data.errors ? Object.values(data.errors).flat().join('<br>') : (data.message || 'Error desconocido');
                Swal.fire({ icon: 'error', title: 'Error', html: errorMsg });
            }
        } catch (error) {
            Swal.fire('Error', 'Error de conexión', 'error');
        }
    });

    // 🚀 ELIMINAR SUBCATEGORÍA
    function confirmarEliminarSubcat(id, nombre) {
        Swal.fire({
            title: '¿Eliminar subcategoría?',
            text: `¿Estás seguro de eliminar "${nombre}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ba1a1a',
            cancelButtonColor: '#49607e',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const response = await fetch(`/admin/catalogos/subcategorias/${id}`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfTokenCat, 'Accept': 'application/json' },
                    body: new URLSearchParams({ '_method': 'DELETE' })
                });
                if (response.ok) {
                    document.getElementById(`fila-subcat-${id}`)?.remove();
                    Swal.fire({ icon: 'success', title: 'Eliminado', timer: 1500, showConfirmButton: false });
                }
            }
        });
    }

    // 🚀 ELIMINAR FALLA
    function eliminarFalla(id) {
        Swal.fire({
            title: '¿Eliminar falla?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Sí', cancelButtonText: 'No'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const response = await fetch(`/admin/catalogos/fallas/${id}`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfTokenCat, 'Accept': 'application/json' },
                    body: new URLSearchParams({ '_method': 'DELETE' })
                });
                if (response.ok) {
                    document.getElementById(`fila-falla-${id}`)?.remove();
                    Swal.fire({ icon: 'success', title: 'Eliminado', timer: 1500, showConfirmButton: false });
                }
            }
        });
    }

    // 🚀 MARCAS
    function openModalMarca() { document.getElementById('modal-marca').classList.replace('hidden', 'flex'); }
    function closeModalMarca() { document.getElementById('modal-marca').classList.replace('flex', 'hidden'); }

    document.getElementById('form-marca').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        try {
            const response = await fetch("{{ route('admin.catalogos.marcas.store') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfTokenCat, 'Accept': 'application/json' },
                body: formData
            });
            const data = await response.json();
            if (response.ok) {
                closeModalMarca();
                this.reset();
                Swal.fire({ icon: 'success', title: 'Guardado', timer: 1500, showConfirmButton: false });
                agregarMarcaAlDOM(data.marca);
            } else {
                Swal.fire({ icon: 'error', title: 'Error', html: Object.values(data.errors).flat().join('<br>') });
            }
        } catch (error) {
            Swal.fire('Error', 'Error de conexión', 'error');
        }
    });

    function agregarMarcaAlDOM(marca) {
        const contenedor = document.getElementById('contenedor-marcas');
        if (contenedor.querySelector('p')) contenedor.innerHTML = '';
        const div = document.createElement('div');
        div.className = 'flex items-center gap-2 bg-white border border-gray-300 px-3 py-1 rounded-full text-xs';
        div.id = `fila-marca-${marca.id_marca}`;
        div.innerHTML = `
            <span class="text-gray-700">${marca.nombre}</span>
            <button onclick="eliminarMarca(${marca.id_marca})" class="text-gray-400 hover:text-red-500">
                <span class="material-symbols-outlined text-[14px]">close</span>
            </button>
        `;
        contenedor.appendChild(div);
    }

    function eliminarMarca(id) {
        Swal.fire({
            title: '¿Eliminar marca?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ba1a1a',
            cancelButtonColor: '#49607e',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const response = await fetch(`/admin/catalogos/marcas/${id}`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfTokenCat, 'Accept': 'application/json' },
                    body: new URLSearchParams({ '_method': 'DELETE' })
                });
                if (response.ok) {
                    document.getElementById(`fila-marca-${id}`)?.remove();
                    Swal.fire({ icon: 'success', title: 'Eliminado', timer: 1500, showConfirmButton: false });
                }
            }
        });
    }
</script>