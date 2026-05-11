<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 py-2">
            <div>
                <h2 class="font-black text-3xl text-slate-800 tracking-tighter uppercase">
                    ➕ Nueva <span class="text-emerald-500">Ruta</span>
                </h2>
                <p class="text-sm text-slate-500 font-bold italic">
                    Registro de itinerarios para el sistema MovSabana
                </p>
            </div>
            
            <a href="{{ route('admin.rutas.index') }}" 
               class="inline-flex items-center bg-white border-2 border-slate-200 text-slate-700 px-5 py-2.5 rounded-xl text-sm font-black shadow-sm hover:bg-slate-50 transition-all uppercase tracking-tight">
                Cancelar
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50/50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-8 border-b border-slate-50 bg-slate-50/30">
                    <h3 class="font-black text-slate-800 uppercase tracking-tighter">Configuración de Ruta</h3>
                </div>

                <div class="p-8">
                    <form method="POST" action="{{ route('admin.rutas.store') }}" class="space-y-8">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <!-- Código -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Código de Ruta</label>
                                <input type="text" name="codigo" required
                                       class="w-full bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-emerald-500/10 font-bold text-slate-700 placeholder-slate-300"
                                       placeholder="Ej: RUTA-01">
                                @error('codigo') <p class="text-xs text-rose-500 font-bold mt-1 ml-2">{{ $message }}</p> @enderror
                            </div>

                            <!-- Nombre -->
                            <div class="md:col-span-2 space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Nombre Descriptivo</label>
                                <input type="text" name="nombre" required
                                       class="w-full bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-emerald-500/10 font-bold text-slate-700 placeholder-slate-300"
                                       placeholder="Ej: Variante Chía - Universidad de la Sabana">
                                @error('nombre') <p class="text-xs text-rose-500 font-bold mt-1 ml-2">{{ $message }}</p> @enderror
                            </div>

                            <!-- Descripción -->
                            <div class="md:col-span-3 space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Descripción del Recorrido</label>
                                <textarea name="descripcion" rows="4"
                                          class="w-full bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-emerald-500/10 font-bold text-slate-700 placeholder-slate-300"
                                          placeholder="Detalla las paradas principales o puntos de referencia..."></textarea>
                            </div>
                        </div>

                        <div class="pt-6">
                            <button type="submit" 
                                    class="w-full md:w-auto bg-[#001529] text-white px-10 py-4 rounded-2xl text-sm font-black shadow-xl hover:bg-emerald-600 transition-all uppercase tracking-widest">
                                Guardar y Publicar Ruta
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>