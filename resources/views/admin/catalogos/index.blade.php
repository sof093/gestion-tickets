@extends('components.layouts.admin')

@section('title', 'Catálogos - Grupo TRACOMEX')

@section('content')
<div class="flex flex-col w-full pb-6">
    
    <!-- 1. TÍTULO Y PESTAÑAS -->
    <div class="flex flex-col w-full mb-6">
        <h1 class="text-2xl font-extrabold text-on-surface tracking-tight">Catálogos del sistema</h1>
        <p class="text-sm text-text-secondary mt-1">Configuración y estandarización de parámetros operativos de la red logística.</p>
        
        <!-- Botones de pestañas -->
        <div class="flex gap-2 mt-4 border-b border-gray-200">
            <button onclick="mostrarSeccion('patios')" id="btn-patios" class="px-4 py-2 border-b-2 border-primary text-primary font-bold transition-all">Patios</button>
            <button onclick="mostrarSeccion('departamentos')" id="btn-departamentos" class="px-4 py-2 text-gray-500 hover:text-gray-700 transition-all">Departamentos</button>
            <button onclick="mostrarSeccion('categorias')" id="btn-categorias" class="px-4 py-2 text-gray-500 hover:text-gray-700 transition-all">Categorías</button>
            <button onclick="mostrarSeccion('prioridades')" id="btn-prioridades" class="px-4 py-2 text-gray-500 hover:text-gray-700 transition-all">Prioridades</button>
        </div>
    </div>

    <!-- 2. CONTENEDORES -->
    <div id="sec-patios" class="block">
        @include('admin.catalogos.partials._patios')
    </div>

    <div id="sec-departamentos" class="hidden">
        @include('admin.catalogos.partials._departamentos')
    </div>

    <div id="sec-categorias" class="hidden">
        @include('admin.catalogos.partials._categorias')
    </div>

    <div id="sec-prioridades" class="hidden">
        @include('admin.catalogos.partials._prioridades')
    </div>

</div>

<!-- SCRIPT INFALIBLE -->
<!-- SCRIPT INFALIBLE CON MEMORIA DE PESTAÑA -->
<script>
    function mostrarSeccion(nombre) {
        // 1. Ocultar todas las secciones
        document.getElementById('sec-patios').classList.add('hidden');
        document.getElementById('sec-departamentos').classList.add('hidden');
        document.getElementById('sec-categorias').classList.add('hidden');
        document.getElementById('sec-prioridades').classList.add('hidden');

        // 2. Mostrar la seleccionada
        document.getElementById('sec-' + nombre).classList.remove('hidden');

        // 3. Resetear estilos de todos los botones
        const botones = ['patios', 'departamentos', 'categorias', 'prioridades'];
        botones.forEach(b => {
            const btn = document.getElementById('btn-' + b);
            btn.classList.remove('border-b-2', 'border-primary', 'text-primary', 'font-bold');
            btn.classList.add('text-gray-500', 'hover:text-gray-700');
        });

        // 4. Activar botón seleccionado
        const btnActivo = document.getElementById('btn-' + nombre);
        btnActivo.classList.remove('text-gray-500', 'hover:text-gray-700');
        btnActivo.classList.add('border-b-2', 'border-primary', 'text-primary', 'font-bold');

        // 5. Guardar la pestaña en la URL para que al recargar se mantenga
        const url = new URL(window.location.href);
        url.searchParams.set('tab', nombre);
        window.history.replaceState({}, '', url);
    }

    // Al cargar la página, verificar si hay una pestaña en la URL
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const tabGuardada = urlParams.get('tab');
        
        if (tabGuardada && ['patios', 'departamentos', 'categorias', 'prioridades'].includes(tabGuardada)) {
            mostrarSeccion(tabGuardada);
        }
    });
</script>
@endsection