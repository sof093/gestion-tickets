@extends('components.layouts.user')

@section('title', 'Mi Perfil - Grupo TRACOMEX')

@section('content')
<div class="flex flex-col w-full pb-6 max-w-5xl mx-auto">
    
    <!-- Encabezado -->
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Mi Perfil</h1>
        <p class="text-sm text-slate-500 mt-1">Información del colaborador, medios de contacto operativo.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Columna Izquierda: Tarjeta de Identificación -->
        <div class="md:col-span-1 bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col items-center text-center">
            <!-- Iniciales -->
            <div class="w-24 h-24 rounded-full bg-[#0059bb] text-white flex items-center justify-center font-bold text-3xl mb-4 shadow-md">
                {{ strtoupper(substr($user->nombre_completo, 0, 2)) }}
            </div>
            
            <h2 class="text-lg font-bold text-slate-800">{{ $user->nombre_completo }}</h2>
            
            <!-- Jalamos el nombre del Rol desde la relación en el modelo User -->
            <p class="text-sm text-slate-500 mt-1">
                {{ $user->rol->nombre ?? 'Usuario' }}<br>en {{ $user->patio->nombre ?? 'Patio Asignado' }}
            </p>

            <div class="w-full border-t border-slate-100 mt-6 pt-6 space-y-4 text-left">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500 font-medium">Número de Nómina</span>
                    <span class="font-bold text-slate-800">{{ $user->num_nomina }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500 font-medium">Patio Asignado</span>
                    <span class="font-bold text-slate-800 text-right">{{ $user->patio->nombre ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500 font-medium">Departamento</span>
                    <span class="font-bold text-slate-800 text-right">{{ $user->departamento->nombre ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500 font-medium">Estatus</span>
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $user->estatus === 'activo' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                        {{ ucfirst($user->estatus) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Datos de Contacto (Solo Lectura) -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2 mb-1">
                    <span class="material-symbols-outlined text-[#0059bb]">contact_mail</span>
                    Datos de Contacto Operativo
                </h3>
                <p class="text-xs text-slate-500 mb-6">Canales directos para notificaciones de tickets y avisos de rampa.</p>

                <div class="space-y-5">
                    <!-- Correo (Solo lectura con estilo de caja) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Correo Electrónico Institucional</label>
                        <div class="flex items-center bg-slate-50 border border-slate-200 rounded-lg p-3">
                            <span class="material-symbols-outlined text-slate-400 text-[20px] mr-3">mail</span>
                            <span class="text-slate-700 text-sm font-medium">{{ $user->correo }}</span>
                        </div>
                    </div>

                    <!-- Teléfono (Solo lectura con estilo de caja) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Teléfono Móvil / WhatsApp</label>
                        <div class="flex items-center bg-slate-50 border border-slate-200 rounded-lg p-3">
                            <span class="material-symbols-outlined text-slate-400 text-[20px] mr-3">smartphone</span>
                            <span class="text-slate-700 text-sm font-medium">{{ $user->telefono ?? 'No registrado' }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Aviso de soporte -->
                <div class="mt-8 flex items-start gap-2 bg-blue-50 p-4 rounded-lg border border-blue-100">
                    <span class="material-symbols-outlined text-primary text-[20px]">info</span>
                    <p class="text-xs text-blue-800 leading-relaxed">
                        <strong>Nota:</strong> Por seguridad de los datos corporativos, la modificación de tu departamento, patio o número de nómina debe ser solicitada directamente al administrador del sistema.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection