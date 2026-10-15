<x-layouts.guest title="Portal de Soporte | Grupo TRACOMEX">
    <div class="w-full flex-1 flex flex-col lg:flex-row bg-[#f8f9ff]">
        
        <!-- PANEL IZQUIERDO: Banner Corporativo -->
        <div class="relative w-full lg:w-5/12 xl:w-1/2 min-h-[280px] lg:min-h-full flex flex-col justify-between p-6 sm:p-10 lg:p-12 overflow-hidden bg-[#001c37]">
            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 hover:scale-105" 
                 style="background-image: url('{{ asset('images/hero-tracomex.jpg') }}');">
            </div>
            
            <div class="absolute inset-0 bg-gradient-to-t from-[#001c37] via-[#001c37]/85 to-[#001c37]/60"></div>

            <div class="relative z-10 max-w-lg my-auto py-6">
                <div class="inline-flex items-center px-3 py-1 rounded bg-[#0070ea]/40 backdrop-blur-md text-white text-xs font-bold tracking-widest uppercase mb-4 border border-white/10">
                    TRACOMEX
                </div>

                <h1 class="text-2xl sm:text-3xl lg:text-4xl text-white font-bold tracking-tight leading-tight mb-3">
                    Gestión de Incidencias y Soporte Técnico de Sistemas
                </h1>
                <p class="text-gray-300 max-w-md leading-relaxed text-xs sm:text-sm mb-6 sm:mb-8">
                    Plataforma Institucional centralizada para el control, reporte y resolución técnica de equipos operativos, redes e infraestructura.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-6 border-t border-white/20 text-white">
                    <div>
                        <div class="font-bold text-sm text-blue-200">Patios</div>
                        <div class="text-xs text-gray-300 mt-1 leading-snug">Toluca, Saltillo, Veracruz, Lázaro Cárdenas, SLP, Qro</div>
                    </div>
                    <div>
                        <div class="font-bold text-sm text-blue-200">Sistemas & TI</div>
                        <div class="text-xs text-gray-300 mt-1 leading-snug">Software y Hardware</div>
                    </div>
                    <div>
                        <div class="font-bold text-sm text-blue-200">Atención 24/7</div>
                        <div class="text-xs text-gray-300 mt-1 leading-snug">Soporte operativo ininterrumpido</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PANEL DERECHO: Formulario de Autenticación -->
        <div class="flex-1 flex flex-col justify-center items-center p-6 sm:p-10 lg:p-12 bg-[#f8f9ff]">
            <div class="w-full max-w-md bg-white p-6 sm:p-10 rounded-2xl shadow-sm border border-gray-100 my-auto">
                
                <div class="mb-6">
                    <div class="text-[#0059bb] font-bold text-xs tracking-wider uppercase mb-1">
                        GRUPO TRACOMEX
                    </div>
                    <h2 class="text-xl sm:text-2xl text-[#0b1c30] font-bold tracking-tight">Portal de soporte de sistemas</h2>
                    <p class="text-xs sm:text-sm text-gray-600 mt-2 leading-relaxed">
                        Comprometidos con brindarte la mejor atención y soporte continuo para mantener en movimiento toda nuestra red operativa.
                    </p>
                </div>

                <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                    @csrf
                    
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-[#0b1c30]" for="employeeId">Número de nómina</label>
                        <div class="relative flex items-center rounded-lg bg-[#eff4ff] border border-transparent transition-all focus-within:border-[#0059bb] focus-within:bg-white focus-within:ring-2 focus-within:ring-[#0059bb]/20">
                            <div class="pl-3 text-gray-400 flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-[20px]">badge</span>
                            </div>
                            <input class="w-full bg-transparent py-3 pl-2.5 pr-3 text-sm text-[#0b1c30] placeholder:text-gray-400 focus:outline-none tracking-wide" 
                                   id="employeeId" name="employee_id" placeholder="Ej. 001452" required type="text" autocomplete="username">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold text-[#0b1c30]" for="password">Contraseña</label>
                            <button type="button" class="text-xs text-[#0059bb] font-medium hover:underline focus:outline-none" onclick="mostrarContactoSoporte()">
                                ¿Olvidaste tu contraseña?
                            </button>
                        </div>
                        <div class="relative flex items-center rounded-lg bg-[#eff4ff] border border-transparent transition-all focus-within:border-[#0059bb] focus-within:bg-white focus-within:ring-2 focus-within:ring-[#0059bb]/20">
                            <div class="pl-3 text-gray-400 flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-[20px]">lock</span>
                            </div>
                            <input class="w-full bg-transparent py-3 pl-2.5 pr-10 text-sm text-[#0b1c30] placeholder:text-gray-400 focus:outline-none" 
                                   id="password" name="password" placeholder="••••••••••••" required type="password" autocomplete="current-password">
                            <button type="button" class="absolute right-3 text-gray-400 hover:text-gray-600 transition-colors flex items-center focus:outline-none" onclick="togglePasswordVisibility()">
                                <span class="material-symbols-outlined text-[20px]" id="passwordEyeIcon">visibility</span>
                            </button>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button class="w-full h-12 flex items-center justify-center gap-2 bg-[#0059bb] hover:bg-[#004493] active:bg-[#00387a] text-white font-bold text-sm rounded-xl shadow-md shadow-[#0059bb]/20 transition-all duration-200 cursor-pointer group" type="submit">
                            <span>INGRESAR AL SISTEMA</span>
                            <span class="material-symbols-outlined text-[20px] transition-transform duration-200 group-hover:translate-x-1">arrow_forward</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-layouts.guest>