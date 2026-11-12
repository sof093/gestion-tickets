<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="vewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel de Control - Grupo TRACOMEX')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              colors: {
                "tracomex-dark": "#0d2b45",
                "tracomex-dark-hover": "#133857",
                "primary": "#0059bb",
                "primary-container": "#0070ea",
                "surface": "#f8f9ff",
                "surface-card": "#ffffff",
                "surface-subtle": "#eff4ff",
                "surface-border": "#dce9ff",
                "on-surface": "#0b1c30",
                "text-secondary": "#49607e",
                "accent-red": "#ba1a1a",
                "accent-teal": "#007dac",
                "accent-light": "#81cfff"
              },
              fontFamily: {
                sans: ['Inter', 'sans-serif']
              }
            }
          }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined {
          font-variation-settings: 'FILL' 0, 'wght' 500, 'GRAD' 0, 'opsz' 24;
          line-height: 1;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-surface text-on-surface min-h-screen flex antialiased" x-data="{ sidebarOpen: false, sidebarCollapsed: false }">

    <!-- Overlay móvil -->
    <div x-show="sidebarOpen" 
         x-cloak 
         @click="sidebarOpen = false" 
         class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden transition-opacity"></div>

    <!-- 1. BARRA LATERAL IZQUIERDA -->
    <aside class="bg-tracomex-dark text-white flex-shrink-0 flex flex-col justify-between fixed top-0 bottom-0 left-0 z-50 shadow-xl select-none transition-all duration-300"
           :class="{
               'translate-x-0': sidebarOpen,
               '-translate-x-full lg:translate-x-0': !sidebarOpen,
               'w-64': !sidebarCollapsed,
               'w-20': sidebarCollapsed
           }">
        <div class="flex flex-col">
            <!-- Logo / Isotipo -->
            <div class="h-16 px-4 flex items-center justify-between border-b border-white/10 bg-tracomex-dark">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="w-10 h-10 rounded-lg bg-primary flex-shrink-0 flex items-center justify-center text-white font-bold text-lg shadow-md tracking-wider">
                        GT
                    </div>
                    <div class="flex flex-col whitespace-nowrap" x-show="!sidebarCollapsed" x-cloak>
                        <span class="font-bold text-sm tracking-tight text-white leading-tight">GRUPO TRACOMEX</span>
                        <span class="text-[11px] text-blue-200/70 font-medium">Soporte y Operaciones</span>
                    </div>
                </div>
                <!-- Botón colapsar menú en escritorio -->
                <button @click="sidebarCollapsed = !sidebarCollapsed" class="hidden lg:flex text-blue-200/70 hover:text-white p-1 rounded-lg hover:bg-white/10">
                    <span class="material-symbols-outlined text-[20px]" x-text="sidebarCollapsed ? 'chevron_right' : 'chevron_left'"></span>
                </button>
            </div>

            <!-- Menú de navegación completo -->
            <nav class="flex flex-col gap-1 px-3 mt-4">
                <!-- Tablero de control -->
                <a class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-colors 
                          {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white shadow-sm' : 'text-blue-100/75 hover:text-white hover:bg-tracomex-dark-hover' }}" 
                   href="{{ route('admin.dashboard') }}" :class="{ 'justify-center px-0': sidebarCollapsed }">
                    <span class="material-symbols-outlined text-[20px]">dashboard</span>
                    <span x-show="!sidebarCollapsed" x-cloak class="whitespace-nowrap">Tablero de control</span>
                </a>

                <!-- Gestión de Tickets -->
                <a class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-colors 
                          {{ request()->routeIs('admin.tickets.*') ? 'bg-primary text-white shadow-sm' : 'text-blue-100/75 hover:text-white hover:bg-tracomex-dark-hover' }}" 
                   href="{{ route('admin.tickets.index') }}" :class="{ 'justify-center px-0': sidebarCollapsed }">
                    <span class="material-symbols-outlined text-[20px]">confirmation_number</span>
                    <span x-show="!sidebarCollapsed" x-cloak class="whitespace-nowrap">Gestión de Tickets</span>
                </a>

                <!-- Usuarios -->
                <a class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-colors 
                          {{ request()->routeIs('admin.users.*') ? 'bg-primary text-white shadow-sm' : 'text-blue-100/75 hover:text-white hover:bg-tracomex-dark-hover' }}" 
                   href="{{ route('admin.users.index') }}" :class="{ 'justify-center px-0': sidebarCollapsed }">
                    <span class="material-symbols-outlined text-[20px]">group</span>
                    <span x-show="!sidebarCollapsed" x-cloak class="whitespace-nowrap">Usuarios</span>
                </a>

                <!-- Catálogos -->
                <a class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-colors 
                          {{ request()->routeIs('admin.catalogos.*') ? 'bg-primary text-white shadow-sm' : 'text-blue-100/75 hover:text-white hover:bg-tracomex-dark-hover' }}" 
                   href="{{ route('admin.catalogos.index') }}" :class="{ 'justify-center px-0': sidebarCollapsed }">
                    <span class="material-symbols-outlined text-[20px]">warehouse</span>
                    <span x-show="!sidebarCollapsed" x-cloak class="whitespace-nowrap">Catálogos</span>
                </a>

                <!-- Bitácora -->
                <a class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-colors 
                          {{ request()->routeIs('admin.bitacora.*') ? 'bg-primary text-white shadow-sm' : 'text-blue-100/75 hover:text-white hover:bg-tracomex-dark-hover' }}" 
                   href="#" :class="{ 'justify-center px-0': sidebarCollapsed }">
                    <span class="material-symbols-outlined text-[20px]">assignment_turned_in</span>
                    <span x-show="!sidebarCollapsed" x-cloak class="whitespace-nowrap">Bitácora</span>
                </a>

                <!-- Reportes y Métricas -->
                <a class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-colors 
                          {{ request()->routeIs('admin.reportes.*') ? 'bg-primary text-white shadow-sm' : 'text-blue-100/75 hover:text-white hover:bg-tracomex-dark-hover' }}" 
                   href="#" :class="{ 'justify-center px-0': sidebarCollapsed }">
                    <span class="material-symbols-outlined text-[20px]">monitoring</span>
                    <span x-show="!sidebarCollapsed" x-cloak class="whitespace-nowrap">Reportes y Métricas</span>
                </a>
            </nav>
        </div>

        <!-- Parte inferior: Configuración TI y Cierre de Sesión -->
        <div class="p-3 border-t border-white/10 bg-tracomex-dark space-y-1">
            <a class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-blue-100/75 hover:text-white hover:bg-tracomex-dark-hover text-sm font-medium transition-colors" href="#" :class="{ 'justify-center px-0': sidebarCollapsed }">
                <span class="material-symbols-outlined text-[20px]">settings</span>
                <span x-show="!sidebarCollapsed" x-cloak class="whitespace-nowrap">Configuración TI</span>
            </a>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-red-300 hover:text-white hover:bg-red-600/20 text-sm font-medium transition-colors" :class="{ 'justify-center px-0': sidebarCollapsed }">
                    <span class="material-symbols-outlined text-[20px]">logout</span>
                    <span x-show="!sidebarCollapsed" x-cloak class="whitespace-nowrap">Cerrar Sesión</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- 2. CONTENEDOR PRINCIPAL -->
    <div class="flex-1 flex flex-col min-h-screen bg-surface transition-all duration-300"
         :class="{
             'lg:ml-64': !sidebarCollapsed,
             'lg:ml-20': sidebarCollapsed
         }">
        
        <!-- Navbar Superior -->
        <header class="sticky top-0 z-30 bg-white border-b border-surface-border shadow-sm h-16 flex items-center justify-between px-4 lg:px-8">
            <div class="flex items-center gap-3 flex-1">
                <!-- Botón Hamburguesa Móvil -->
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-on-surface p-2 rounded-lg hover:bg-surface-subtle">
                    <span class="material-symbols-outlined text-[24px]">menu</span>
                </button>

                @if(!request()->routeIs('admin.tickets.show') && !request()->routeIs('admin.tickets.index'))
            <div class="relative w-48 sm:w-72 hidden sm:block">
                    <div class="relative w-48 sm:w-72 hidden sm:block">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[20px]">search</span>
                        <input id="global-search-input" 
                            class="w-full pl-10 pr-4 py-2 bg-surface-subtle border border-surface-border rounded-lg text-sm text-on-surface placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:bg-white transition-all" 
                            placeholder="Buscar por Folio (ej. TK-2026-001)..." 
                            type="text"
                            autocomplete="off">
                    </div>
                @endif
            </div>
            
            <!-- Usuario Activo -->
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <div class="text-sm font-bold text-on-surface leading-tight">{{ auth()->user()->nombre_completo ?? 'Administrador TI' }}</div>
                    <div class="text-xs text-text-secondary leading-tight">Nómina: {{ auth()->user()->num_nomina ?? 'N/A' }}</div>
                </div>
                <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm shadow-sm border-2 border-primary">
                    {{ strtoupper(substr(auth()->user()->nombre_completo ?? 'A', 0, 2)) }}
                </div>
            </div>
        </header>

        <!-- Contenido de la Página -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>

        <!-- Pie de página -->
        <footer class="bg-white border-t border-surface-border py-4 px-8 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-text-secondary">
            <div class="font-semibold text-on-surface flex items-center gap-2">
                <span>TRANSDRIZA S.A. DE C.V.</span>
                <span class="text-gray-300">•</span>
                <span class="text-primary font-bold">Grupo TRACOMEX</span>
            </div>
            <div>
                © {{ date('Y') }} Sistema de Gestión de Tickets • Todos los derechos reservados
            </div>
        </footer>
    </div>

    <!-- Script para las pestañas de catálogos -->
    <script>
        function cambiarTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.add('hidden');
                el.classList.remove('block');
            });
            document.querySelectorAll('button[id^="btn-tab-"]').forEach(btn => {
                btn.classList.remove('border-b-2', 'border-primary', 'text-primary', 'font-bold');
                btn.classList.add('text-gray-500', 'hover:text-gray-700');
            });
            const content = document.getElementById('content-' + tabId);
            if (content) {
                content.classList.remove('hidden');
                content.classList.add('block');
            }
            const btn = document.getElementById('btn-tab-' + tabId);
            if (btn) {
                btn.classList.remove('text-gray-500', 'hover:text-gray-700');
                btn.classList.add('border-b-2', 'border-primary', 'text-primary', 'font-bold');
            }
        }
    </script>

    <!-- Script para el Buscador Global AJAX -->
    <!-- Script para el Buscador Global AJAX -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('global-search-input');
            const tablaBody = document.querySelector('table tbody'); 

            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault(); 
                        const query = searchInput.value.trim();

                        if(query === '') {
                            // Si borran el input, recargamos para dejar la tabla normal
                            window.location.reload(); 
                            return;
                        }

                        // Si no estamos en el dashboard, redirigimos
                        if (!tablaBody) {
                            window.location.href = `{{ route('admin.tickets.index') }}?search=${query}`;
                            return;
                        }

                        // Hacemos la petición AJAX
                        fetch(`{{ route('admin.dashboard.search') }}?query=${query}`)
                            .then(response => response.json())
                            .then(data => {
                                // 1. Limpiamos resultados de búsquedas anteriores
                                document.querySelectorAll('.fila-buscada').forEach(el => el.remove());

                                if (data.tickets.length > 0) {
                                    data.tickets.forEach(ticket => {
                                        const tr = document.createElement('tr');
                                        // Le ponemos una clase 'fila-buscada' para poder borrarla luego
                                        tr.className = 'fila-buscada bg-yellow-50 border-l-4 border-yellow-400 transition-all duration-500'; 
                                        
                                        tr.innerHTML = `
                                            <td class="px-6 py-4 font-mono font-bold text-primary">${ticket.folio}</td>
                                            <td class="px-6 py-4 font-medium max-w-[200px] truncate">${ticket.descripcion}</td>
                                            <td class="px-6 py-4 text-text-secondary">${ticket.patio ? ticket.patio.nombre : 'N/A'}</td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase bg-red-100 text-red-700">
                                                    ${ticket.prioridad ? ticket.prioridad.nombre : 'N/A'}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase bg-blue-100 text-blue-700">
                                                    ${ticket.estado ? ticket.estado.nombre : 'N/A'}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-text-secondary text-xs font-bold text-yellow-600">Encontrado</td>
                                            <td class="px-6 py-4 text-right">
                                                <a href="/admin/tickets/${ticket.id_ticket}" class="text-xs font-semibold text-primary hover:underline">Ver detalle</a>
                                            </td>
                                        `;
                                        tablaBody.prepend(tr);
                                    });

                                    // Quitamos el resaltado amarillo después de 3 segundos (pero la fila se queda)
                                    setTimeout(() => {
                                        document.querySelectorAll('.bg-yellow-50').forEach(el => {
                                            el.classList.remove('bg-yellow-50', 'border-l-4', 'border-yellow-400');
                                        });
                                    }, 3000);

                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Ticket no encontrado',
                                        text: 'Verifica el folio e intenta de nuevo.',
                                        confirmButtonColor: '#0059bb'
                                    });
                                }
                            })
                            .catch(error => console.error('Error en la búsqueda:', error));
                    }
                });
            }
        });
    </script>
</body>
</html>