<x-layouts.guest title="Primer Inicio de Sesión - Grupo TRACOMEX">
    <div class="min-h-screen flex items-center justify-center bg-slate-100 p-4">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 w-full max-w-md overflow-hidden">
            
            <!-- Cabecera -->
            <div class="bg-slate-900 text-white p-6 text-center">
                <span class="text-[10px] tracking-wider uppercase font-bold text-blue-300">Seguridad y Control de Acceso</span>
                <h2 class="text-xl font-bold mt-1">Cambio de contraseña obligatorio</h2>
                <p class="text-xs text-slate-300 mt-1">Es tu primer acceso al sistema. Por seguridad, debes establecer una contraseña personal.</p>
            </div>

            <!-- Formulario -->
            <form action="{{ route('password.primer_cambio.update') }}" method="POST" class="p-6 flex flex-col gap-4 text-xs">
                @csrf
                @method('PUT')

                <!-- Errores de validación -->
                @if($errors->any())
                    <div class="bg-red-50 p-3 rounded-lg border border-red-200 text-red-600">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Nueva Contraseña -->
                <div class="flex flex-col gap-1">
                    <label class="font-bold text-slate-700">Nueva contraseña</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">lock</span>
                        <input type="password" name="password" required 
                               placeholder="Mínimo 8 caracteres"
                               class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                </div>

                <!-- Confirmar Contraseña -->
                <div class="flex flex-col gap-1">
                    <label class="font-bold text-slate-700">Confirmar nueva contraseña</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">lock_reset</span>
                        <input type="password" name="password_confirmation" required 
                               placeholder="Repite tu contraseña"
                               class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                </div>

                <button type="submit" class="w-full mt-3 py-2.5 px-4 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-md transition-colors text-xs flex items-center justify-center gap-1.5 cursor-pointer">
                    <span class="material-symbols-outlined text-[16px]">check_circle</span>
                    <span>Guardar contraseña e ingresar</span>
                </button>
            </form>
        </div>
    </div>
</x-layouts.guest>