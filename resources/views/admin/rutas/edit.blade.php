<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 py-2">
            <div>
                <h2 class="font-black text-3xl text-slate-800 tracking-tighter uppercase">
                    ✏️ Editar <span class="text-cyan-500">Ruta</span>
                </h2>
                <p class="text-sm text-slate-500 font-bold italic">
                    Modificando: {{ $ruta->nombre }}
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
                    <h3 class="font-black text-slate-800 uppercase tracking-tighter">Actualizar Información</h3>
                </div>

                <div class="p-8">
                    <form method="POST" action="{{ route('admin.rutas.update', $ruta->id) }}" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <!-- Código -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Código de Ruta</label>
                                <input type="text" name="codigo" value="{{ old('codigo', $ruta->codigo) }}" required
                                       class="w-full bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-cyan-500/10 font-bold text-slate-700 placeholder-slate-300">
                                @error('codigo') <p class="text-xs text-rose-500 font-bold mt-1 ml-2">{{ $message }}</p> @enderror
                            </div>

                            <!-- Nombre -->
                            <div class="md:col-span-2 space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Nombre de la Ruta</label>
                                <input type="text" name="nombre" value="{{ old('nombre', $ruta->nombre) }}" required
                                       class="w-full bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-cyan-500/10 font-bold text-slate-700 placeholder-slate-300">
                                @error('nombre') <p class="text-xs text-rose-500 font-bold mt-1 ml-2">{{ $message }}</p> @enderror
                            </div>

                            <!-- Descripción -->
                            <div class="md:col-span-3 space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Descripción Detallada</label>
                                <textarea name="descripcion" rows="5"
                                          class="w-full bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-cyan-500/10 font-bold text-slate-700 placeholder-slate-300">{{ old('descripcion', $ruta->descripcion) }}</textarea>
                            </div>
                        </div>
                    </div>

                        <!-- AGREGAR ESTO ⬇️ -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Origen -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Origen</label>
                                <input type="text" name="origen" value="{{ old('origen', $ruta->origen) }}" required
                                       class="w-full bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-cyan-500/10 font-bold text-slate-700 placeholder-slate-300">
                                @error('origen') <p class="text-xs text-rose-500 font-bold mt-1 ml-2">{{ $message }}</p> @enderror
                            </div>

                            <!-- Destino -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Destino</label>
                                <input type="text" name="destino" value="{{ old('destino', $ruta->destino) }}" required
                                       class="w-full bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-cyan-500/10 font-bold text-slate-700 placeholder-slate-300">
                                @error('destino') <p class="text-xs text-rose-500 font-bold mt-1 ml-2">{{ $message }}</p> @enderror
                            </div>

                            <!-- Distancia -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Distancia (km)</label>
                                <input type="number" name="distancia_km" value="{{ old('distancia_km', $ruta->distancia_km) }}" step="0.01" required
                                       class="w-full bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-cyan-500/10 font-bold text-slate-700 placeholder-slate-300">
                                @error('distancia_km') <p class="text-xs text-rose-500 font-bold mt-1 ml-2">{{ $message }}</p> @enderror
                            </div>

                            <!-- Duración Estimada -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Duración Estimada</label>
                                <input type="text" name="duracion_estimada" value="{{ old('duracion_estimada', $ruta->duracion_estimada) }}" required
                                       class="w-full bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-cyan-500/10 font-bold text-slate-700 placeholder-slate-300">
                                @error('duracion_estimada') <p class="text-xs text-rose-500 font-bold mt-1 ml-2">{{ $message }}</p> @enderror
                            </div>

                            <!-- Estado -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Estado</label>
                                <select name="estado" required
                                        class="w-full bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-cyan-500/10 font-bold text-slate-700">
                                    <option value="activo" {{ old('estado', $ruta->estado) === 'activo' ? 'selected' : '' }}>Activo</option>
                                    <option value="inactivo" {{ old('estado', $ruta->estado) === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                                @error('estado') <p class="text-xs text-rose-500 font-bold mt-1 ml-2">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Seleccionar Conductor -->
                        <!-- Seleccionar Conductor -->
<div>
    <label class="block text-sm font-bold text-slate-700 mb-2">Asignar Conductor</label>
    <select name="conductor_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
        <option value="">-- Sin asignar --</option>
        @foreach($conductores as $conductor)
            <option value="{{ $conductor->id }}" {{ $ruta->conductor_id === $conductor->id ? 'selected' : '' }}>
                {{ $conductor->nombre }}
            </option>
        @endforeach
    </select>
    @error('conductor_id')
        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
                        <div class="pt-6">
                            <button type="submit" 
                                    class="w-full md:w-auto bg-[#001529] text-white px-10 py-4 rounded-2xl text-sm font-black shadow-xl hover:bg-cyan-600 transition-all uppercase tracking-widest">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>