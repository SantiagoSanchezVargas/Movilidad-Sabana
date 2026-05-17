<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-3xl text-slate-800">✏️ Editar Conductor</h2>
        <p class="text-sm text-slate-500 font-bold">Actualiza la información del conductor</p>
    </x-slot>

    <div class="py-10 bg-gradient-to-br from-slate-100 via-white to-cyan-50">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <form action="{{ route('admin.conductores.update', $conductor->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Nombre -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nombre Completo</label>
                        <input type="text" name="nombre" placeholder="Juan Pérez García"
                               class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                               value="{{ old('nombre', $conductor->nombre) }}" required>
                        @error('nombre')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Licencia -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Número de Licencia</label>
                        <input type="text" name="licencia" placeholder="ej: 123456789"
                               class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                               value="{{ old('licencia', $conductor->licencia) }}" required>
                        @error('licencia')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Teléfono</label>
                        <input type="tel" name="telefono" placeholder="ej: 3001234567"
                               class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                               value="{{ old('telefono', $conductor->telefono) }}" required>
                        @error('telefono')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estado -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Estado</label>
                        <select name="estado" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent" required>
                            <option value="activo" {{ $conductor->estado === 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="inactivo" {{ $conductor->estado === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                            <option value="suspendido" {{ $conductor->estado === 'suspendido' ? 'selected' : '' }}>Suspendido</option>
                        </select>
                        @error('estado')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-4 justify-end pt-6">
                        <a href="{{ route('admin.conductores.index') }}" 
                           class="px-6 py-3 border-2 border-slate-300 text-slate-700 rounded-lg font-bold hover:bg-slate-50 transition">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-cyan-600 text-white rounded-lg font-bold hover:bg-cyan-700 transition">
                            ✅ Actualizar Conductor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>