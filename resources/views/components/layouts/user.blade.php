<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal del Usuario - Grupo TRACOMEX')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        tailwind.config = {
          theme: {
            extend: {
              colors: {
                "primary": "#0059bb",
                "surface": "#f8f9ff",
                "on-surface": "#0b1c30",
                "text-secondary": "#49607e",
              },
              fontFamily: { sans: ['Inter', 'sans-serif'] }
            }
          }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 500, 'GRAD' 0, 'opsz' 24; line-height: 1; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-50 text-on-surface min-h-screen flex antialiased" x-data="{ sidebarOpen: false }">

    <!-- Overlay móvil -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden"></div>

    <!-- Sidebar Usuario (Blanco) -->
    <aside class="bg-white text-slate-800 w-64 flex-shrink-0 flex flex-col justify-between fixed top-0 bottom-0 left-0 z-50 border-r border-slate-200 shadow-sm transition-transform duration-300"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
        
        <div class="flex flex-col">
            <!-- Logo -->
            <div class="h-16 px-6 flex items-center gap-3 border-b border-slate-100">
                <div class="w-9 h-9 rounded-lg bg-primary flex items-center justify-center text-white font-bold text-sm shadow-md">GT</div>
                <div class="flex flex-col">
                    <span class="font-bold text-sm tracking-tight text-slate-800">TRACOMEX</span>
                    <span class="text-[10px] text-slate-500 font-medium uppercase tracking-wider">Portal Operativo</span>
                </div>
            </div>

            <!-- Menú de Usuario -->
            <nav class="flex flex-col gap-1 px-3 mt-6">
                <a class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg {{ request()->routeIs('user.dashboard') ? 'bg-primary text-white font-semibold shadow-sm' : 'text-slate-600 hover:text-primary hover:bg-slate-100 font-medium' }} text-sm transition-colors" href="{{ route('user.dashboard') }}">
                    <span class="material-symbols-outlined text-[20px]">dashboard</span>
                    <span>Panel de Inicio</span>
                </a>
                <a class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg {{ request()->routeIs('user.tickets.index') ? 'bg-primary text-white font-semibold shadow-sm' : 'text-slate-600 hover:text-primary hover:bg-slate-100 font-medium' }} text-sm transition-colors" href="{{ route('user.tickets.index') }}">
                    <span class="material-symbols-outlined text-[20px]">confirmation_number</span>
                    <span>Mis Tickets</span>
                </a>
                <a class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg {{ request()->routeIs('user.tickets.create') ? 'bg-primary text-white font-semibold shadow-sm' : 'text-slate-600 hover:text-primary hover:bg-slate-100 font-medium' }} text-sm transition-colors" href="{{ route('user.tickets.create') }}">
                    <span class="material-symbols-outlined text-[20px]">add_circle</span>
                    <span>Crear Ticket</span>
                </a>
                <a class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg {{ request()->routeIs('user.profile') ? 'bg-primary text-white font-semibold shadow-sm' : 'text-slate-600 hover:text-primary hover:bg-slate-100 font-medium' }} text-sm transition-colors" href="{{ route('user.profile') }}">
    <span class="material-symbols-outlined text-[20px]">person</span>
    <span>Mi Perfil</span>
</a>
            </nav>
        </div>

        <!-- Footer Sidebar -->
        <div class="p-4 border-t border-slate-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-red-600 hover:bg-red-50 text-sm font-medium transition-colors">
                    <span class="material-symbols-outlined text-[20px]">logout</span>
                    <span>Cerrar Sesión</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Contenedor Principal -->
    <div class="flex-1 flex flex-col min-h-screen lg:ml-64 transition-all duration-300">
        
        <!-- Navbar Superior -->
        <header class="sticky top-0 z-30 bg-white border-b border-slate-200 shadow-sm h-16 flex items-center justify-between px-4 lg:px-8">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-slate-600 p-2 rounded-lg hover:bg-slate-100">
                    <span class="material-symbols-outlined text-[24px]">menu</span>
                </button>
                <div class="flex items-center gap-2 text-xs text-slate-500 font-medium bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200">
                    <span class="material-symbols-outlined text-[16px] text-primary">location_on</span>
                    Patio: {{ auth()->user()->patio->nombre ?? 'N/A' }}
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <div class="text-sm font-bold text-slate-800 leading-tight">{{ auth()->user()->nombre_completo }}</div>
                    <div class="text-xs text-slate-500 leading-tight">Nómina: {{ auth()->user()->num_nomina }}</div>
                </div>
                <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm shadow-sm border-2 border-white">
                    {{ strtoupper(substr(auth()->user()->nombre_completo ?? 'A', 0, 2)) }}
                </div>
            </div>
        </header>

        <!-- Contenido -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-200 py-4 px-8 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500">
            <div class="font-semibold text-slate-700">TRANSDRIZA S.A. DE C.V. • Grupo TRACOMEX</div>
            <div>© {{ date('Y') }} Sistema de Gestión de Tickets • Todos los derechos reservados</div>
        </footer>
    </div>
</body>
</html>