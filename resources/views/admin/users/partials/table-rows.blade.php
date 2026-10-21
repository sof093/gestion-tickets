@forelse($usuarios ?? [] as $user)
    <tr class="hover:bg-slate-50/80 transition-colors">
        <td class="py-3.5 px-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs shrink-0 border border-primary/20">
                    {{ strtoupper(substr($user->nombre_completo ?? 'US', 0, 2)) }}
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="font-bold text-slate-800 truncate">{{ $user->nombre_completo }}</span>
                    <span class="text-slate-500 text-[10px] sm:hidden">{{ $user->num_nomina }} • {{ $user->correo }}</span>
                    <span class="text-slate-500 truncate hidden sm:inline">Personal Operativo</span>
                </div>
            </div>
        </td>
        <td class="py-3.5 px-4 hidden sm:table-cell">
            <span class="font-mono text-primary font-semibold bg-primary/10 px-2 py-0.5 rounded">{{ $user->num_nomina }}</span>
        </td>
        <td class="py-3.5 px-4 text-slate-600 hidden md:table-cell">{{ $user->correo ?? 'No registrado' }}</td>
        <td class="py-3.5 px-4 hidden lg:table-cell">
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] {{ $user->id_rol == 2 ? 'bg-indigo-900 text-indigo-100' : 'bg-slate-900 text-slate-100' }} font-semibold">
                {{ $user->id_rol == 1 ? 'Administrador' : 'Usuario' }}
            </span>
        </td>
        <td class="py-3.5 px-4 hidden lg:table-cell">
            <span>{{ $user->patio->nombre ?? ($user->id_patio_descripcion ?? 'General') }}</span>
        </td>
        <td class="py-3.5 px-4">
            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] sm:text-[11px] bg-emerald-50 text-emerald-700 font-bold">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> Activo
            </span>
        </td>
        <td class="py-3.5 px-4 text-right">
    <div class="inline-flex items-center justify-end gap-1">
        <!-- Botón VER -->
        <button type="button" 
                onclick="abrirModalVer('{{ $user->id_usuario }}', '{{ addslashes($user->nombre_completo) }}', '{{ $user->num_nomina }}', '{{ $user->correo }}', '{{ $user->telefono }}', '{{ $user->patio->nombre ?? 'General' }}', '{{ $user->departamento->nombre ?? 'General' }}', '{{ $user->id_rol == 1 ? 'Administrador' : 'Usuario' }}')"
                class="p-1.5 rounded-lg hover:bg-blue-50 text-slate-600 hover:text-blue-600 transition-colors inline-flex items-center cursor-pointer" 
                title="Ver Detalles">
            <span class="material-symbols-outlined text-[18px]">visibility</span>
        </button>

        <!-- Botón EDITAR -->
        <button type="button" 
                onclick="abrirModalEdicion({{ $user->id_usuario }})"
                class="p-1.5 rounded-lg hover:bg-slate-200 text-slate-600 hover:text-primary transition-colors inline-flex items-center cursor-pointer" 
                title="Editar Perfil">
            <span class="material-symbols-outlined text-[18px]">edit</span>
        </button>

        <!-- Botón ELIMINAR -->
        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" id="delete-form-{{ $user->id_usuario }}">
            @csrf @method('DELETE')
            <button type="button"
                    onclick="confirmarEliminacion('{{ $user->id_usuario }}', '{{ addslashes($user->nombre_completo) }}')"
                    class="p-1.5 rounded-lg hover:bg-red-50 text-slate-600 hover:text-red-600 transition-colors inline-flex items-center cursor-pointer"
                    title="Eliminar Usuario">
                <span class="material-symbols-outlined text-[18px]">delete</span>
            </button>
        </form>
    </div>
</td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center py-10 text-slate-400">
            <span class="material-symbols-outlined text-4xl mb-1">group_off</span>
            <p class="text-sm font-medium">No hay usuarios que coincidan con la búsqueda.</p>
        </td>
    </tr>
@endforelse