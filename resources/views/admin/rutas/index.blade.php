<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 py-2">
            <div>
                <h2 class="font-black text-3xl text-slate-800 tracking-tighter uppercase">
                    {{ __('Gestión de Rutas') }} <span class="text-cyan-500">MovSabana</span>
                </h2>
                <p class="text-sm text-slate-500 font-bold italic">
                    Panel de administración de rutas y frecuencias — Chía
                </p>
            </div>
            
            <a href="{{ route('admin.rutas.create') }}" 
               class="inline-flex items-center bg-[#001529] text-white px-6 py-3 rounded-2xl text-sm font-black shadow-lg hover:bg-cyan-600 transition-all uppercase tracking-widest">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                </svg>
                Nueva Ruta
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Barra de Búsqueda -->
            <div class="bg-white p-4 rounded-3xl shadow-sm border border-slate-100">
                <form action="{{ route('admin.rutas.index') }}" method="GET" class="relative">
                    <input type="text" name="buscar" value="{{ $buscar }}"
                           placeholder="Buscar por nombre o código de ruta..."
                           class="w-full bg-slate-50 border-none rounded-2xl py-4 pl-12 pr-4 font-bold text-slate-700 focus:ring-4 focus:ring-cyan-500/10 transition-all">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </form>
            </div>

            <!-- Tabla de Rutas -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50/80">
                            <tr>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Info. Ruta</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Código</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse ($rutas as $ruta)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-8 py-6">
                                        <p class="text-sm font-black text-slate-700 uppercase tracking-tighter">{{ $ruta->nombre }}</p>
                                        <p class="text-[10px] text-slate-400 font-bold italic">{{ Str::limit($ruta->descripcion, 50) }}</p>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-black font-mono border border-slate-200">
                                            {{ $ruta->codigo }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('admin.rutas.edit', $ruta->id) }}" class="p-2 text-slate-400 hover:text-cyan-500 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>
                                            <form action="{{ route('admin.rutas.destroy', $ruta->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta ruta?')">
                                                @propto
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-8 py-20 text-center">
                                        <p class="text-slate-400 font-bold italic">No se encontraron rutas registradas.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Paginación -->
                @if($rutas->hasPages())
                    <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-50">
                        {{ $rutas->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>