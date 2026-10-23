<div x-show="openEditModal && !showConfirm" 
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/70 backdrop-blur-xs p-2 sm:p-4 overflow-y-auto">
    
    <div @click.away="!showConfirm && cerrarEditConConfirmacion()" 
         class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-xl max-h-[92vh] sm:max-h-[90vh] flex flex-col overflow-hidden transform transition-all my-auto">
        
        <!-- Cabecera del Modal -->
        <div class="bg-slate-900 text-white px-4 sm:px-5 py-3.5 flex items-center justify-between shrink-0">
            <div>
                <span class="text-[10px] tracking-wider uppercase font-bold text-blue-300">Edición de cuenta</span>
                <h3 class="text-base sm:text-lg font-bold">Editar usuario</h3>
            </div>
            <button @click="cerrarEditConConfirmacion()" class="text-slate-300 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer" type="button">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <!-- Formulario del Modal -->
        <form :action="`{{ url('admin/users') }}/${editId}`" method="POST" class="p-4 sm:p-6 flex flex-col gap-3.5 text-xs overflow-y-auto">
            @csrf
            @method('PUT')

            <p class="text-slate-500">
                Editando a <strong class="text-slate-700" x-text="editNombre"></strong>
                (Nómina: <span class="font-mono text-primary" x-text="editNumNomina"></span>)
            </p>

            <!-- Nómina (solo lectura) -->
            <div class="flex flex-col gap-1">
                <label class="font-bold text-slate-700">Núm. de Nómina (no editable)</label>
                <input type="text" :value="editNumNomina" disabled
                       class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-lg text-xs font-mono text-slate-500 cursor-not-allowed">
            </div>

            <!-- Nombre -->
            <div class="flex flex-col gap-1">
                <label class="font-bold text-slate-700">Nombre completo</label>
                <input type="text" name="nombre_completo" x-model="editNombreCompleto" required
                       oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚÑáéíóúñ\s]/g, '').replace(/\s{2,}/g, ' ')"
                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>

            <!-- Teléfono -->
            <div class="flex flex-col gap-1">
                <label class="font-bold text-slate-700">Teléfono (10 dígitos)</label>
                <div class="relative flex items-center">
                    <span class="absolute left-3 font-mono text-slate-500 font-bold text-xs bg-slate-200 px-1.5 py-1 rounded">+52</span>
                    <input type="text" name="telefono" x-model="editTelefono" required maxlength="10"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                           class="w-full pl-14 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-mono focus:outline-none focus:ring-2 focus:ring-primary/20">
                </div>
            </div>

            <!-- Correo -->
            <div class="flex flex-col gap-1">
                <label class="font-bold text-slate-700">Correo institucional</label>
                <input type="email" name="correo" x-model="editCorreo" required
                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>

            <!-- Rol -->
            <div class="flex flex-col gap-1">
                <label class="font-bold text-slate-700">Rol en el sistema</label>
                <select name="id_rol" x-model="editIdRol" required
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="1">Usuario (Operativo / Colaborador estándar)</option>
                    <option value="2">Administrador (Gestión total de accesos)</option>
                </select>
            </div>
<!-- Patio (EDITAR) -->
<!-- Patio (EDITAR) -->
<div class="flex flex-col gap-1" x-data="{ 
        open: false, 
        selected: editIdPatio, 
        search: '',
        options: [ @foreach($patios as $patio) '{{ $patio->nombre }}', @endforeach ],
        get filteredOptions() {
            if (this.search === '') return this.options;
            return this.options.filter(opt => opt.toLowerCase().includes(this.search.toLowerCase()));
        }
    }" x-init="$watch('editIdPatio', value => selected = value)">
    
    <label class="font-bold text-slate-700">Patio operativo</label>
    <input type="hidden" name="id_patio_descripcion" x-model="selected" required>

    <div class="relative">
        <!-- El click.away va en el botón para que al abrir no se cierre, y el click.stop en el panel para que al escribir no se cierre -->
        <button type="button" @click="open = !open" @click.away="open = false" 
                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-left flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-primary/20 cursor-pointer">
            <span x-text="selected ? selected : 'Selecciona un patio...'" :class="selected ? 'text-slate-800' : 'text-slate-400'"></span>
            <span class="material-symbols-outlined text-slate-400 text-[16px] transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
        </button>

        <!-- Agregamos @click.stop aquí para evitar que se cierre al hacer clic dentro -->
        <div x-show="open" x-transition x-cloak @click.stop
             class="absolute z-50 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-lg">
            
            <div class="p-2 border-b border-slate-100 bg-slate-50 rounded-t-lg">
                <input type="text" x-model="search" placeholder="Buscar patio..." class="w-full px-2 py-1.5 text-xs bg-white border border-slate-200 rounded focus:outline-none focus:ring-1 focus:ring-primary/50">
            </div>

            <div class="max-h-[110px] overflow-y-auto overscroll-contain">
                <template x-for="option in filteredOptions" :key="option">
                    <div @click="selected = option; open = false; search = ''" 
                         class="px-3 py-2 text-xs text-slate-700 hover:bg-primary/10 hover:text-primary cursor-pointer transition-colors"
                         x-text="option">
                    </div>
                </template>
                <div x-show="filteredOptions.length === 0" class="px-3 py-2 text-xs text-slate-400 text-center">
                    No se encontraron resultados
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Departamento (EDITAR) -->
<div class="flex flex-col gap-1 relative" x-data="{ 
        open: false, 
        selected: editIdDepartamento, 
        search: '',
        options: [ @foreach($departamentos as $depto) '{{ $depto->nombre }}', @endforeach ],
        get filteredOptions() {
            if (this.search === '') return this.options;
            return this.options.filter(opt => opt.toLowerCase().includes(this.search.toLowerCase()));
        }
    }" x-init="$watch('editIdDepartamento', value => selected = value)">
    
    <label class="font-bold text-slate-700">Departamento</label>
    <input type="hidden" name="id_departamento" x-model="selected" required>

    <div class="relative w-full">
        <button type="button" @click="open = !open" @click.away="open = false" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-left flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-primary/20 cursor-pointer">
            <span x-text="selected ? selected : 'Selecciona un departamento...'" :class="selected ? 'text-slate-800' : 'text-slate-400'"></span>
            <span class="material-symbols-outlined text-slate-400 text-[16px] transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
        </button>

        <!-- DROPUP: Usamos 'bottom-full mb-1' en lugar de 'top-full mt-1' para que se abra hacia arriba -->
        <div x-show="open" x-transition x-cloak @click.stop
             class="absolute z-[999] left-0 right-0 bottom-full mb-1 bg-white border border-slate-200 rounded-lg shadow-xl">
            
            <div class="p-2 border-b border-slate-100 bg-slate-50 rounded-t-lg">
                <input type="text" x-model="search" placeholder="Buscar departamento..." class="w-full px-2 py-1.5 text-xs bg-white border border-slate-200 rounded focus:outline-none focus:ring-1 focus:ring-primary/50">
            </div>

            <!-- Forzamos el scroll interno limitando la altura -->
            <div class="max-h-[110px] overflow-y-auto overscroll-contain">
                <template x-for="option in filteredOptions" :key="option">
                    <div @click="selected = option; open = false; search = ''" 
                         class="px-3 py-2 text-xs text-slate-700 hover:bg-primary/10 hover:text-primary cursor-pointer transition-colors" 
                         x-text="option">
                    </div>
                </template>
                <div x-show="filteredOptions.length === 0" class="px-3 py-2 text-xs text-slate-400 text-center">
                    No se encontraron resultados
                </div>
            </div>
        </div>
    </div>
</div>

            <!-- Botones del Modal -->
            <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-2 pt-3 border-t border-slate-100 mt-2 shrink-0">
                <button type="button" @click="cerrarEditConConfirmacion()" class="w-full sm:w-auto px-4 py-2 rounded-lg bg-slate-100 text-slate-700 font-semibold text-xs hover:bg-slate-200 transition-colors cursor-pointer text-center">
                    Cancelar
                </button>
                <button type="submit" class="w-full sm:w-auto px-5 py-2 rounded-lg bg-primary text-white font-semibold text-xs shadow-md hover:bg-blue-600 transition-colors flex items-center justify-center gap-1.5 cursor-pointer">
                    <span class="material-symbols-outlined text-[16px]">save</span>
                    <span>Guardar Cambios</span>
                </button>
            </div>
        </form>
    </div>
</div>