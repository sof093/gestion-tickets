<x-layouts.guest title="Restablecer Contraseña - Grupo TRACOMEX">
    <div class="min-h-screen w-full flex">
        
        <!-- COLUMNA IZQUIERDA: Imagen institucional idéntica al login -->
        <div class="relative hidden lg:flex lg:w-1/2 items-end justify-start p-12 text-white bg-cover bg-center" style="background-image: url('{{ asset('images/hero-tracomex.jpg') }}');">
            <!-- Capa oscura semitransparente -->
            <div class="absolute inset-0 bg-slate-950/70"></div>

            <!-- Contenido informativo superpuesto -->
            <div class="relative z-10 max-w-lg">
                <span class="inline-block px-3 py-1 mb-4 text-xs font-semibold tracking-wider text-white uppercase bg-blue-600 rounded-md">SEGURIDAD</span>
                <h1 class="text-3xl font-bold leading-tight mb-3">Establecer Nueva Contraseña</h1>
                <p class="text-sm text-slate-300 leading-relaxed mb-6">
                    Asegura tu cuenta actualizando tus credenciales de acceso bajo los protocolos de seguridad industrial de Grupo TRACOMEX.
                </p>
                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-white/10 text-xs text-slate-300">
                    <div>
                        <strong class="block text-white font-semibold">Tokens temporales</strong>
                        Caducidad estricta de 15 minutos
                    </div>
                    <div>
                        <strong class="block text-white font-semibold">Cifrado seguro</strong>
                        Hash adaptativo en base de datos
                    </div>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: Formulario de nueva contraseña -->
        <div class="w-full lg:w-1/2 flex items-center justify-center bg-slate-100 p-6 lg:p-12">
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 w-full max-w-md overflow-hidden">
                
                <div class="bg-slate-900 text-white p-6 text-center">
                    <span class="text-[10px] tracking-wider uppercase font-bold text-blue-300">Seguridad</span>
                    <h2 class="text-xl font-bold mt-1">Nueva contraseña</h2>
                    <p class="text-xs text-slate-300 mt-1">Ingresa tu nueva contraseña para acceder al sistema.</p>
                </div>

                <form action="{{ route('password.update') }}" method="POST" class="p-6 flex flex-col gap-4 text-xs">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    @if($errors->any())
                        <div class="bg-red-50 p-3 rounded-lg border border-red-200 text-red-600">
                            <ul class="list-disc pl-4 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-2">
                        <label for="correo" class="block text-xs font-medium text-slate-700 mb-1">Correo institucional</label>
                        <input type="email" id="correo" name="correo" value="{{ $correo ?? request()->query('correo') }}" readonly class="w-full px-3 py-2 border rounded-md bg-slate-100 text-slate-600 cursor-not-allowed" />
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="font-bold text-slate-700">Nueva contraseña</label>
                        <input type="password" name="password" required placeholder="Mínimo 6 caracteres"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="font-bold text-slate-700">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" required placeholder="Repite tu contraseña"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    </div>

                    <button type="submit" class="w-full mt-2 py-2.5 px-4 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-md transition-colors text-xs flex items-center justify-center gap-1.5 cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">lock_reset</span>
                        <span>Actualizar contraseña</span>
                    </button>
                </form>
            </div>
        </div>

    </div>
</x-layouts.guest>