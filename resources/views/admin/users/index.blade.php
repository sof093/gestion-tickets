@extends('components.layouts.admin')

@section('title', 'Gestión de Usuarios - Grupo TRACOMEX')

@section('content')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Contenedor principal con estado de Alpine.js para Modal y Formulario -->
<div class="flex flex-col w-full pb-6" 
     x-data="{ 
        openModal: false,
        showConfirm: false,
        openEditModal: false,
        openViewModal: false,
        
        // Variables para Crear
        num_nomina: @js(old('num_nomina', '')),
        nombre_completo: @js(old('nombre_completo', '')),
        telefono: @js(old('telefono', '')),
        correo: @js(old('correo', '')),
        id_rol: @js(old('id_rol', '')),
        id_patio_descripcion: @js(old('id_patio_descripcion', '')),
        id_departamento: @js(old('id_departamento', '')),
        
        // Variables para Editar
        editId: null,
        editNumNomina: '',
        editNombre: '',
        editNombreCompleto: '',
        editTelefono: '',
        editCorreo: '',
        editIdRol: '',
        editIdPatio: '',
        editIdDepartamento: '',
        
        // Variables para Ver
        viewNombre: '',
        viewNomina: '',
        viewCorreo: '',
        viewTelefono: '',
        viewPatio: '',
        viewDepartamento: '',
        viewRol: '',
        
        hasData() {
            return this.num_nomina.trim() !== '' || 
                   this.nombre_completo.trim() !== '' || 
                   this.telefono.trim() !== '' || 
                   this.correo.trim() !== '' || 
                   this.id_rol !== '' || 
                   this.id_patio_descripcion !== '' || 
                   this.id_departamento !== '';
        },

        cerrarConConfirmacion() {
            if (this.showConfirm) return;
            if (this.hasData()) {
                this.showConfirm = true;
                confirmarCierreModal(this);
            } else {
                this.forceClose();
            }
        },

        cerrarEditConConfirmacion() {
            if (this.showConfirm) return;
            this.showConfirm = true;
            confirmarCierreEdicion(this);
        },

        forceClose() {
            this.openModal = false;
            this.showConfirm = false;
            this.num_nomina = '';
            this.nombre_completo = '';
            this.telefono = '';
            this.correo = '';
            this.id_rol = '';
            this.id_patio_descripcion = '';
            this.id_departamento = '';
        },

        forceCloseEdit() {
            this.openEditModal = false;
            this.showConfirm = false;
            this.editId = null;
            this.editNumNomina = '';
            this.editNombre = '';
            this.editNombreCompleto = '';
            this.editTelefono = '';
            this.editCorreo = '';
            this.editIdRol = '';
            this.editIdPatio = '';
            this.editIdDepartamento = '';
        },

        cargarDatosEdicion(user) {
            this.editId = user.id_usuario;
            this.editNumNomina = user.num_nomina;
            this.editNombre = user.nombre_completo;
            this.editNombreCompleto = user.nombre_completo;
            this.editTelefono = user.telefono;
            this.editCorreo = user.correo;
            this.editIdRol = String(user.id_rol);
            this.editIdPatio = user.patio?.nombre ?? '';
            this.editIdDepartamento = user.departamento?.nombre ?? '';
            this.openEditModal = true;
        },

        cargarDatosVer(user) {
            this.viewNombre = user.nombre;
            this.viewNomina = user.nomina;
            this.viewCorreo = user.correo;
            this.viewTelefono = user.telefono;
            this.viewPatio = user.patio;
            this.viewDepartamento = user.departamento;
            this.viewRol = user.rol;
            this.openViewModal = true;
        }
     }"
     @abrir-editar.window="cargarDatosEdicion($event.detail)"
     @abrir-ver.window="cargarDatosVer($event.detail)">

    <!-- Header Section -->
    <section class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pt-2 mb-6">
        <div class="flex flex-col gap-1 max-w-3xl">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="px-2 py-0.5 rounded text-[10px] tracking-wider uppercase font-bold bg-primary/10 text-primary">Control de Acceso & Permisos</span>
                <span class="w-1.5 h-1.5 rounded-full bg-slate-400 hidden sm:inline-block"></span>
                <span class="text-xs text-slate-500">TRANSDRIZA & TRACOMEX</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-on-surface tracking-tight">Gestión de usuarios y personal de patio</h1>
            <p class="text-xs sm:text-sm text-slate-600">Administración centralizada de cuentas, roles de sistema y asignación por patio</p>
        </div>
        
        <!-- Top Action Buttons -->
        <div class="flex items-center gap-2 shrink-0">
            <button class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 rounded-lg bg-white text-on-surface font-semibold text-xs shadow-sm hover:bg-slate-50 transition-all border border-slate-200" type="button">
                <span class="material-symbols-outlined text-[16px] text-slate-500">file_download</span>
                <span class="hidden sm:inline">Exportar lista</span>
            </button>
            <button @click="openModal = true" class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 rounded-lg bg-primary text-white font-semibold text-xs shadow-md hover:bg-blue-600 transition-all cursor-pointer" type="button">
                <span class="material-symbols-outlined text-[16px]">person_add</span>
                <span>Nuevo usuario</span>
            </button>
        </div>
    </section>

    <!-- Filter Bar Section -->
<!-- Filter Bar Section -->
<!-- Filter Bar Section -->
<section class="bg-white rounded-xl shadow-sm p-4 mb-6 flex flex-col gap-4 border border-slate-200">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
        <div class="md:col-span-6 relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>

            <input id="searchInput"
                   name="search"
                   value="{{ request('search') }}"
                   oninput="buscarEnTiempoReal(this.value)"
                   onkeydown="if(event.key === 'Enter'){ event.preventDefault(); }"
                   autocomplete="off"
                   class="w-full pl-9 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-on-surface placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all"
                   placeholder="Escribe para buscar por Nombre, Nómina o Correo..."
                   type="text">

            <button id="clearSearch"
                    type="button"
                    onclick="limpiarBusqueda()"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 font-bold text-xs {{ request('search') ? '' : 'hidden' }}"
                    title="Limpiar búsqueda">✕</button>
        </div>

        <div class="md:col-span-6 flex justify-between md:justify-end items-center gap-3">
            <span class="text-xs text-slate-500 font-semibold">Total registros: <span id="totalRegistros">{{ $usuarios->total() }}</span></span>
        </div>
    </div>
</section>

    <!-- Users Table Card -->
    <section class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden flex flex-col mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[600px] sm:min-w-full">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-200">
                        <th class="py-3 px-4">Personal / Nombre</th>
                        <th class="py-3 px-4 hidden sm:table-cell">Nómina</th>
                        <th class="py-3 px-4 hidden md:table-cell">Correo Institucional</th>
                        <th class="py-3 px-4 hidden lg:table-cell">Rol Asignado</th>
                        <th class="py-3 px-4 hidden lg:table-cell">Patio Operativo</th>
                        <th class="py-3 px-4">Estatus</th>
                        <th class="py-3 px-4 text-right">Acciones</th>
                    </tr>
                </thead>
   <tbody id="usersTableBody" class="divide-y divide-slate-100 text-xs text-slate-700">
    @include('admin.users.partials.table-rows', ['usuarios' => $usuarios])
</tbody>
            </table>
        </div>
        
    </section>

    <!-- ========================================================== -->
    <!-- MODAL: REGISTRAR NUEVO USUARIO                             -->
    <!-- ========================================================== -->
    <div x-show="openModal && !showConfirm" 
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/70 backdrop-blur-xs p-2 sm:p-4 overflow-y-auto">
    
    <div @click.away="!showConfirm && cerrarConConfirmacion()" 
         class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-xl max-h-[92vh] sm:max-h-[90vh] flex flex-col overflow-hidden transform transition-all my-auto">
            
            <!-- Cabecera del Modal -->
            <div class="bg-slate-900 text-white px-4 sm:px-5 py-3.5 flex items-center justify-between shrink-0">
                <div>
                    <span class="text-[10px] tracking-wider uppercase font-bold text-blue-300">Control de Acceso</span>
                    <h3 class="text-base sm:text-lg font-bold">Registrar nuevo usuario</h3>
                </div>
                <button @click="cerrarConConfirmacion()" class="text-slate-300 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer" type="button">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <!-- Formulario del Modal -->
            <form action="{{ route('admin.users.store') }}" method="POST" class="p-4 sm:p-6 flex flex-col gap-3.5 text-xs overflow-y-auto">
                @csrf
                <p class="text-slate-500">Completa los campos. La contraseña temporal se generará automáticamente.</p>

                <!-- 1. Nómina -->
                <div class="flex flex-col gap-1">
                    <div class="flex items-center justify-between">
                        <label class="font-bold text-slate-700">1. Núm. de Nómina (Solo números)</label>
                        <span class="text-[10px] font-bold text-primary">REQUERIDO</span>
                    </div>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 font-mono text-slate-400 font-bold text-xs">#</span>
                        <input type="text" name="num_nomina" x-model="num_nomina" required pattern="[0-9]+" placeholder="Ej. 482 o 48201" 
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                               class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-mono text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                </div>

                <!-- 2. Nombre Completo -->
                <div class="flex flex-col gap-1">
                    <label class="font-bold text-slate-700">2. Nombre completo (Solo letras y espacios)</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">badge</span>
                        <input type="text" name="nombre_completo" x-model="nombre_completo" required placeholder="Ing. Héctor Valdés" 
                               oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚÑáéíóúñ\s]/g, '').replace(/\s{2,}/g, ' ')"
                               class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                </div>

                <!-- 3. Teléfono -->
                <div class="flex flex-col gap-1">
                    <label class="font-bold text-slate-700">3. Teléfono de contacto (10 dígitos)</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-3 font-mono text-slate-500 font-bold text-xs bg-slate-200 px-1.5 py-1 rounded">+52</span>
                        <input type="text" name="telefono" x-model="telefono" required maxlength="10" placeholder="7224501928" 
                               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                               class="w-full pl-14 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-mono text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                </div>

                <!-- 4. Correo Institucional -->
                <div class="flex flex-col gap-1">
                    <label class="font-bold text-slate-700">4. Correo institucional</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">mail</span>
                        <input type="email" name="correo" x-model="correo" required placeholder="hvaldes@tracomex.com.mx" class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                </div>

                <!-- 5. Rol -->
                <div class="flex flex-col gap-1">
                    <label class="font-bold text-slate-700">5. Rol de Privilegios en el Sistema</label>
                    <select name="id_rol" x-model="id_rol" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 cursor-pointer">
                        <option value="" disabled selected>Selecciona un rol de acceso...</option>
                        <option value="1">Usuario (Operativo / Colaborador estándar)</option>
                        <option value="2">Administrador (Gestión total de accesos y configuración)</option>
                    </select>
                </div>

                <!-- 6. Patio Operativo Asignado -->
                <div class="flex flex-col gap-1">
                    <label class="font-bold text-slate-700">6. Patio operativo asignado</label>
                    <select name="id_patio_descripcion" x-model="id_patio_descripcion" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 cursor-pointer">
                        <option value="" disabled selected>Selecciona un patio...</option>
                        <option value="TRANSDRIZA">TRANSDRIZA</option>
                        <option value="TRANSLADO">TRANSLADO</option>
                        <option value="CDMX">CDMX</option>
                        <option value="SALTILLO">SALTILLO</option>
                        <option value="LAZARO CARDENAS">LAZARO CARDENAS</option>
                        <option value="SAN LUIS">SAN LUIS</option>
                        <option value="VERACRUZ">VERACRUZ</option>
                        <option value="QUERETARO">QUERETARO</option>
                    </select>
                </div>

                <!-- 7. Departamento -->
                <div class="flex flex-col gap-1">
                    <label class="font-bold text-slate-700">7. Departamento</label>
                    <select name="id_departamento" x-model="id_departamento" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 cursor-pointer">
                        <option value="" disabled selected>Selecciona un departamento...</option>
                        <option value="Gerencia - Juridico">Gerencia - Jurídico</option>
                        <option value="Recursos humanos – Contabilidad y sistemas">Recursos humanos – Contabilidad y sistemas</option>
                        <option value="Nominas - Medicina">Nóminas - Medicina</option>
                        <option value="Taller – Seguridad e higiene">Taller – Seguridad e higiene</option>
                        <option value="Compras - Calidad">Compras - Calidad</option>
                        <option value="Almacen - Seguridad patrimonial – Servicios generales">Almacén - Seguridad patrimonial – Servicios generales</option>
                    </select>
                </div>

                <!-- Botones del Modal -->
                <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-2 pt-3 border-t border-slate-100 mt-2 shrink-0">
                    <button type="button" @click="cerrarConConfirmacion()" class="w-full sm:w-auto px-4 py-2 rounded-lg bg-slate-100 text-slate-700 font-semibold text-xs hover:bg-slate-200 transition-colors cursor-pointer text-center">
                        Cancelar
                    </button>
                    <button type="submit" class="w-full sm:w-auto px-5 py-2 rounded-lg bg-primary text-white font-semibold text-xs shadow-md hover:bg-blue-600 transition-colors flex items-center justify-center gap-1.5 cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">save</span>
                        <span>Guardar Usuario</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
  @include('admin.users.partials.edit-modal')
      <!-- MODAL: VER DETALLES -->
    <div x-show="openViewModal && !showConfirm" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/70 backdrop-blur-xs p-4 overflow-y-auto">
        <div @click.away="openViewModal = false" 
             class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-md flex flex-col overflow-hidden my-auto">
            
            <div class="bg-slate-800 text-white px-5 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[20px]">badge</span>
                    <div>
                        <h3 class="font-bold text-sm">Detalles del Usuario</h3>
                        <p class="text-[10px] text-slate-300 uppercase tracking-wider">Solo lectura</p>
                    </div>
                </div>
                <button @click="openViewModal = false" class="text-slate-300 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer" type="button">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <div class="p-5 flex flex-col gap-3.5 text-xs">
                <div class="flex flex-col border-b border-slate-100 pb-2">
                    <span class="font-bold text-slate-400 text-[10px] uppercase mb-0.5">Nombre Completo</span>
                    <span class="font-bold text-slate-800 text-sm" x-text="viewNombre"></span>
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    <div class="flex flex-col border-b border-slate-100 pb-2">
                        <span class="font-bold text-slate-400 text-[10px] uppercase mb-0.5">Nómina</span>
                        <span class="font-mono text-primary font-semibold" x-text="viewNomina"></span>
                    </div>
                    <div class="flex flex-col border-b border-slate-100 pb-2">
                        <span class="font-bold text-slate-400 text-[10px] uppercase mb-0.5">Rol</span>
                        <span class="font-semibold text-slate-800" x-text="viewRol"></span>
                    </div>
                </div>

                <div class="flex flex-col border-b border-slate-100 pb-2">
                    <span class="font-bold text-slate-400 text-[10px] uppercase mb-0.5">Correo</span>
                    <span class="text-slate-700" x-text="viewCorreo"></span>
                </div>
                
                <div class="flex flex-col border-b border-slate-100 pb-2">
                    <span class="font-bold text-slate-400 text-[10px] uppercase mb-0.5">Teléfono</span>
                    <span class="font-mono text-slate-700">+52 <span x-text="viewTelefono"></span></span>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="flex flex-col border-b border-slate-100 pb-2">
                        <span class="font-bold text-slate-400 text-[10px] uppercase mb-0.5">Patio</span>
                        <span class="text-slate-700" x-text="viewPatio"></span>
                    </div>
                    <div class="flex flex-col border-b border-slate-100 pb-2">
                        <span class="font-bold text-slate-400 text-[10px] uppercase mb-0.5">Departamento</span>
                        <span class="text-slate-700" x-text="viewDepartamento"></span>
                    </div>
                </div>

                <div class="flex items-center justify-end pt-3 mt-1">
                    <button type="button" @click="openViewModal = false" class="px-5 py-2 rounded-lg bg-slate-800 text-white font-semibold text-xs hover:bg-slate-700 transition-colors">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* 1. Limitar el ancho máximo de SweetAlert2 globalmente */
    .swal2-popup {
        width: 90% !important;
        max-width: 420px !important; /* Evita que se estire en monitores grandes */
    }

    /* 2. Compactar el icono de SweetAlert2 */
    .swal2-icon {
        width: 44px !important;
        height: 44px !important;
        margin: 0.5rem auto 0.5rem !important;
        border-width: 3px !important;
    }
    .swal2-icon .swal2-icon-content {
        font-size: 1.4rem !important;
    }

    /* 3. Evitar animaciones lentas */
    .swal2-show { 
        animation: swal2-show .18s !important; 
    }

    /* 1. Limitar el ancho máximo de SweetAlert2 globalmente */
    .swal2-popup {
        width: 90% !important;
        max-width: 420px !important;
    }

    /* 2. Compactar el icono de SweetAlert2 */
    .swal2-icon {
        width: 44px !important;
        height: 44px !important;
        margin: 0.5rem auto 0.5rem !important;
        border-width: 3px !important;
    }
    .swal2-icon .swal2-icon-content {
        font-size: 1.4rem !important;
    }

    /* 3. Evitar animaciones lentas */
    .swal2-show { 
        animation: swal2-show .18s !important; 
    }

    /* 4. Forzar la separación entre botones de SweetAlert2 */
    .swal2-actions {
        gap: 1rem !important; /* Separación de 16px entre botones */
    }
    .swal2-actions button {
        margin: 0 !important; /* Elimina márgenes antiguos de SweetAlert */
    }
</style>

<script>
    // ============================================================
    // Mixin para alertas responsivas y compactas
    // ============================================================
    const SwalCompact = Swal.mixin({
            buttonsStyling: false,
            customClass: {
                popup: 'rounded-2xl shadow-xl border border-slate-100 p-6',
                title: 'text-base font-bold text-slate-800 !p-0 !mb-2 text-center',
                htmlContainer: 'text-xs text-slate-600 !m-0 text-center',
                actions: '!flex !flex-row items-center justify-center gap-5 !mt-5 w-full', // <-- Cambiamos gap-3 por gap-5
                confirmButton: 'inline-flex items-center justify-center px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs transition-colors cursor-pointer shadow-sm',
                cancelButton: 'inline-flex items-center justify-center px-4 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs border border-slate-200 transition-colors cursor-pointer'
            }
        });

    // Confirmación al cerrar modal con datos
function confirmarCierreModal(component) {
    SwalCompact.fire({
        title: '¿Cerrar ventana?',
        text: 'Se perderán los datos ingresados en el formulario.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, cerrar',
        cancelButtonText: 'Continuar editando',
        // ⚠️ Estas dos opciones evitan que el click se propague
        allowOutsideClick: false,
        allowEscapeKey: false
    }).then((result) => {
        // ⚠️ CLAVE: liberar la bandera SIEMPRE
        component.showConfirm = false;

        if (result.isConfirmed) {
            component.forceClose();
        }
        // Si canceló, no hacemos nada → el modal sigue abierto
    });
}
    // ============================================================
    // Edición de usuario
    // ============================================================
    async function abrirModalEdicion(id) {
        try {
            const response = await fetch(`{{ url('admin/users') }}/${id}/edit`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('No se pudo cargar el usuario');

            const user = await response.json();

            // Obtenemos el componente de Alpine
            const rootEl = document.querySelector('[x-data]');
            const alpine = Alpine.$data(rootEl);

            alpine.editId              = user.id_usuario;
            alpine.editNumNomina       = user.num_nomina;
            alpine.editNombre          = user.nombre_completo;
            alpine.editNombreCompleto  = user.nombre_completo;
            alpine.editTelefono        = user.telefono;
            alpine.editCorreo          = user.correo;
            alpine.editIdRol           = String(user.id_rol);
            alpine.editIdPatio         = user.patio?.nombre ?? '';
            alpine.editIdDepartamento  = user.departamento?.nombre ?? '';
            alpine.openEditModal       = true;
        } catch (err) {
            console.error(err);
            SwalCompact.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron cargar los datos del usuario.'
            });
        }
    }

    function confirmarCierreEdicion(component) {
        SwalCompact.fire({
            title: '¿Cerrar sin guardar?',
            text: 'Se perderán los cambios realizados.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, cerrar',
            cancelButtonText: 'Continuar editando',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            component.showConfirm = false;
            if (result.isConfirmed) component.forceCloseEdit();
        });
    }
    // Confirmación para eliminar usuario
    function confirmarEliminacion(id, nombre) {
            SwalCompact.fire({
                title: '¿Eliminar usuario?',
                text: `¿Deseas eliminar a "${nombre}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    ...SwalCompact.customClass,
                    confirmButton: 'inline-flex items-center justify-center px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white font-semibold text-xs transition-colors cursor-pointer shadow-sm',
                    cancelButton: 'inline-flex items-center justify-center px-4 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs border border-slate-200 transition-colors cursor-pointer'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

    // Alertas activadas por controladores de Laravel
    document.addEventListener("DOMContentLoaded", function() {
        @if(session('success'))
            SwalCompact.fire({
                icon: 'success',
                title: '¡Operación Exitosa!',
                html: "{!! session('success') !!}",
                confirmButtonText: 'Aceptar'
            });
        @endif

        @if($errors->any())
            SwalCompact.fire({
                icon: 'error',
                title: 'Atención / Validación Requerida',
                html: `
                    <div class="bg-red-50 p-3 rounded-lg border border-red-100 mt-2">
                        <ul class="text-left text-xs text-red-600 list-disc pl-4 flex flex-col gap-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                `,
                confirmButtonText: 'Entendido',
                customClass: {
                    ...SwalCompact.customClass,
                    confirmButton: 'rounded-lg px-5 py-2.5 text-xs font-semibold text-white bg-red-600 hover:bg-red-700 transition-colors cursor-pointer shadow-sm'
                }
            });
        @endif
    });
    // ============================================================
    // Búsqueda en tiempo real con debounce
    // ============================================================
// ============================================================
// Búsqueda en tiempo real con AJAX (sin recargar la página)
// ============================================================
let timeoutSearch = null;
let abortController = null;

async function buscarEnTiempoReal(value) {
    clearTimeout(timeoutSearch);

    const clearBtn = document.getElementById('clearSearch');
    if (value.trim() !== '') clearBtn.classList.remove('hidden');
    else clearBtn.classList.add('hidden');

    timeoutSearch = setTimeout(async () => {
        const tbody = document.getElementById('usersTableBody');
        const total = document.getElementById('totalRegistros');

        if (abortController) abortController.abort();
        abortController = new AbortController();

        tbody.style.opacity = '0.5';

        try {
            const url = new URL("{{ route('admin.users.index') }}", window.location.origin);
            if (value.trim() !== '') url.searchParams.set('search', value);

            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                signal: abortController.signal
            });

            if (!response.ok) throw new Error('Error en la búsqueda');

            const data = await response.json();
            tbody.innerHTML = data.html;
            total.textContent = data.total;

            window.history.replaceState({}, '', url.toString());
        } catch (err) {
            if (err.name !== 'AbortError') console.error(err);
        } finally {
            tbody.style.opacity = '1';
        }
    }, 350);
}

function limpiarBusqueda() {
    const input = document.getElementById('searchInput');
    input.value = '';
    buscarEnTiempoReal('');
    input.focus();
}
    function abrirModalVer(id, nombre, nomina, correo, telefono, patio, departamento, rol) {
        const rootEl = document.querySelector('[x-data]');
        const alpine = Alpine.$data(rootEl);

        alpine.viewNombre       = nombre;
        alpine.viewNomina       = nomina;
        alpine.viewCorreo       = correo;
        alpine.viewTelefono     = telefono;
        alpine.viewPatio        = patio;
        alpine.viewDepartamento = departamento;
        alpine.viewRol          = rol;
        alpine.openViewModal    = true;
    }
        // ============================================================
    // Abrir Modal de Editar
    // ============================================================
    async function abrirModalEdicion(id) {
        try {
            const response = await fetch(`{{ url('admin/users') }}/${id}/edit`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('No se pudo cargar el usuario');

            const user = await response.json();
            
            // Disparamos el evento que Alpine está escuchando
            window.dispatchEvent(new CustomEvent('abrir-editar', { detail: user }));

        } catch (err) {
            console.error(err);
            SwalCompact.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron cargar los datos del usuario.'
            });
        }
    }

    // ============================================================
    // Abrir Modal de Ver
    // ============================================================
    function abrirModalVer(id, nombre, nomina, correo, telefono, patio, departamento, rol) {
        window.dispatchEvent(new CustomEvent('abrir-ver', { 
            detail: { id, nombre, nomina, correo, telefono, patio, departamento, rol } 
        }));
    }
</script>
@endsection
