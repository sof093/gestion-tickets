@extends('components.layouts.user')

@section('title', 'Nuevo Ticket - Grupo TRACOMEX')

@section('content')
<div class="flex flex-col w-full pb-6" x-data="ticketForm()">
    
    <!-- Encabezado -->
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Levantar nuevo Ticket de soporte técnico</h1>
            <p class="text-sm text-slate-500 mt-1">Reporte de incidencias en equipos de cómputo.</p>
        </div>
        <div class="bg-primary/10 text-primary px-4 py-2 rounded-lg border border-primary/20 text-xs font-bold">
            Folio Preliminar: TK-{{ date('Y') }}-XXX
        </div>
    </div>

    <!-- Formulario -->
    <form action="{{ route('user.tickets.store') }}" method="POST" class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col gap-6">
        @csrf
        
        <!-- Bloque 1: Datos de la Incidencia -->
        <div>
            <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[18px]">info</span>
                Datos de la Incidencia
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Patio -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-700">Patio de Operación *</label>
                    <select name="id_patio" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
                        @foreach($patios as $patio)
                            <option value="{{ $patio->id_patio }}" {{ $usuario->id_patio_descripcion == $patio->id_patio ? 'selected' : '' }}>
                                {{ $patio->nombre }} ({{ $patio->ciudad }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[10px] text-slate-400">Si estás en otro patio, cámbialo aquí.</p>
                </div>

                <!-- Departamento (Solo lectura) -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-700">Departamento (Asignado)</label>
                    <input type="text" value="{{ $usuario->departamento->nombre ?? 'Sin departamento' }}" disabled class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-lg text-sm text-slate-500 cursor-not-allowed">
                </div>
            </div>
        </div>

        <!-- Bloque 2: Clasificación del Problema -->
        <div>
            <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[18px]">category</span>
                Clasificación del Equipo / Servicio
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Categoría -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-700">Categoría *</label>
                    <select name="id_categoria" x-model="selectedCat" @change="updateSubcats()" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
                        <option value="">Selecciona...</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id_cat }}">{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Subcategoría -->
<!-- Subcategoría -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-700">Subcategoría *</label>
                    <select name="id_subcat" x-model="selectedSubcat" @change="updateFallas()" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
                        <option value="">Selecciona...</option>
                        @foreach($subcategorias as $sub)
                            <option value="{{ $sub->id_subcat }}" data-cat="{{ $sub->id_cat }}" x-show="selectedCat == {{ $sub->id_cat }}">
                                {{ $sub->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Falla -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-700">Falla / Síntoma *</label>
                    <select name="id_falla" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
                        <option value="">Selecciona...</option>
                        @foreach($fallas as $falla)
                            <option value="{{ $falla->id_falla }}" data-subcat="{{ $falla->id_subcat }}" x-show="selectedSubcat == {{ $falla->id_subcat }}">
                                {{ $falla->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

            <!-- Marca (Solo para Hardware por ahora) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-700">Marca (Opcional)</label>
                    <select name="id_marca" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
                        <option value="">Selecciona una marca...</option>
                        @foreach($marcas as $marca)
                            <option value="{{ $marca->id_marca }}">{{ $marca->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Bloque 3: Descripción -->
        <div>
            <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[18px]">description</span>
                Descripción detallada de la falla o problema
            </h3>
            <textarea name="descripcion" rows="5" required maxlength="1000" placeholder="Describe la falla indicando el número de serie o económico del equipo (ej. Zebra TC57 #E-104), la rampa o madrina donde ocurrió el incidente, y cualquier detalle adicional relevante..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 resize-none"></textarea>
        </div>

        <!-- Botones -->
        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('user.dashboard') }}" class="px-5 py-2.5 rounded-lg text-slate-600 hover:bg-slate-100 font-medium text-sm transition-colors">Cancelar</a>
            <button type="submit" class="bg-primary hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-semibold text-sm shadow-md flex items-center gap-2 transition-colors">
                <span class="material-symbols-outlined text-[18px]">send</span>
                Enviar Ticket
            </button>
        </div>
    </form>
</div>

<script>
    function ticketForm() {
        return {
            selectedCat: '',
            selectedSubcat: '',
            subcats: [],
            fallas: [],
            categorias: @json($categorias),
            
            updateSubcats() {
                const cat = this.categorias.find(c => c.id_cat == this.selectedCat);
                this.subcats = cat ? cat.subcategorias : [];
                this.selectedSubcat = '';
                this.fallas = [];
            },
            
            updateFallas() {
                const sub = this.subcats.find(s => s.id_subcat == this.selectedSubcat);
                this.fallas = sub ? sub.fallas : [];
            }
        }
    }
</script>
@endsection