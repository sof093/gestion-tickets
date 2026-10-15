<x-layouts.guest title="Recuperar Contraseña - Grupo TRACOMEX">
    <div class="min-h-screen w-full flex flex-col lg:flex-row bg-surface">
        <div class="relative hidden lg:flex lg:w-1/2 items-end justify-start p-12 text-white bg-cover bg-center" style="background-image: url('{{ asset('images/hero-tracomex.jpg') }}');">
            <!-- Capa oscura semitransparente para legibilidad perfecta -->
            <div class="absolute inset-0 bg-slate-950/70"></div>

            <!-- Contenido informativo superpuesto -->
            <div class="relative z-10 max-w-lg">
                <span class="inline-block px-3 py-1 mb-4 text-xs font-semibold tracking-wider text-white uppercase bg-blue-600 rounded-md">TRACOMEX</span>
                <h1 class="text-3xl font-bold leading-tight mb-3">Recuperación de Acceso Institucional</h1>
                <p class="text-sm text-slate-300 leading-relaxed mb-6">
                    Plataforma centralizada para la gestión de credenciales y restablecimiento seguro de claves de acceso operativo en la red de patios de TRACOMEX.
                </p>
                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-white/10 text-xs text-slate-300">
                    <div>
                        <strong class="block text-white font-semibold">Tokens temporales</strong>
                        Caducidad estricta de 15 minutos
                    </div>
                    <div>
                        <strong class="block text-white font-semibold">Atención 24/7</strong>
                        Soporte operativo ininterrumpido
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT PANEL: Utilitarian Recovery Form -->
        <div class="w-full lg:w-7/12 xl:w-1/2 flex items-center justify-center p-6 sm:p-10 lg:p-14 bg-surface">
            <div class="w-full max-w-xl flex flex-col justify-between h-full max-h-[850px] py-4">
                
                <div>
                    <!-- Back Navigation link -->
                    <a class="inline-flex items-center gap-2 font-label-md text-label-md text-primary hover:text-on-primary-fixed-variant transition-colors group mb-8" href="{{ route('login') }}">
                        <span class="material-symbols-outlined text-base transition-transform group-hover:-translate-x-1">arrow_back</span>
                        <span>Volver al inicio de sesión</span>
                    </a>

                    <!-- Form Header -->
                    <div class="space-y-2 mb-8">
                        <div class="flex items-center gap-2">
                            <span class="font-label-sm tracking-wider uppercase text-on-surface-variant">GRUPO TRACOMEX</span>
                        </div>
                        <h2 class="font-headline-lg text-on-surface tracking-tight text-2xl font-bold">Recuperar contraseña</h2>
                        <p class="font-body-md text-on-surface-variant leading-relaxed text-xs">
                            Ingresa tu correo institucional registrado en la red de patios de TRACOMEX para recibir un enlace de restablecimiento de contraseña.
                        </p>
                    </div>

                    <!-- Alertas de éxito o error de Laravel -->
                    @if (session('status'))
                        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start gap-3">
                            <span class="material-symbols-outlined text-emerald-600 mt-0.5">mark_email_read</span>
                            <div class="flex-1 text-xs">
                                <div class="font-bold">Instrucciones despachadas correctamente</div>
                                <p class="mt-0.5">{{ session('status') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-600 text-xs">
                            <ul class="list-disc pl-4 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Form Area -->
                    <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="space-y-2">
                            <label class="block font-label-md text-on-surface text-xs font-bold" for="identifierInput">
                                Correo corporativo <span class="text-error">*</span>
                            </label>
                            <div class="relative flex items-center shadow-sm rounded-lg bg-surface-container-lowest border border-slate-200">
                                <span class="material-symbols-outlined absolute left-3.5 text-secondary text-xl pointer-events-none">mail</span>
                                <input class="w-full pl-11 pr-4 py-3 bg-surface-container-lowest text-on-surface placeholder:text-outline-variant font-body-md text-xs rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all" 
                                       id="identifierInput" name="correo" placeholder="Ej. usuario@tracomex.com.mx" required type="email">
                            </div>
                            <p class="font-body-sm text-xs text-outline flex items-center gap-1.5 pt-0.5">
                                <span class="material-symbols-outlined text-sm text-secondary">info</span>
                                <span>Recibirás un enlace seguro con vigencia de 15 minutos</span>
                            </p>
                        </div>

                        <!-- Submit CTA -->
                        <button class="w-full flex items-center justify-center gap-3 py-3.5 px-6 rounded-lg bg-slate-900 text-white hover:bg-slate-800 active:scale-[0.99] font-label-md tracking-wider uppercase shadow-md transition-all text-xs cursor-pointer font-bold" type="submit">
                            <span>Enviar Instrucciones de Recuperación</span>
                            <span class="material-symbols-outlined text-lg">arrow_forward</span>
                        </button>
                    </form>

                    <!-- Security Policy Callout -->
                    <div class="mt-8 p-4 rounded-xl bg-surface-container-low shadow-sm flex items-start gap-3 border border-slate-200">
                        <span class="material-symbols-outlined text-secondary mt-0.5 text-xl shrink-0">admin_panel_settings</span>
                        <div class="space-y-1">
                            <span class="font-label-md text-on-surface text-xs font-bold">Políticas de seguridad</span>
                            <p class="font-body-sm text-on-surface-variant leading-relaxed text-[11px]">
                                Por políticas de ciberseguridad industrial de Grupo TRACOMEX, si superas los intentos permitidos, el acceso temporal se bloqueará de forma automática.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layouts.guest>