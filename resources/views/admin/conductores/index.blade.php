<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-black text-3xl text-slate-800 uppercase">🚗 Gestionar Conductores</h2>
                <p class="text-sm text-slate-500 font-bold">Administra todos los conductores del sistema</p>
            </div>
            <a href="{{ route('admin.conductores.create') }}" 
               class="px-6 py-3 bg-cyan-600 text-white rounded-lg font-bold hover:bg-cyan-700 transition">
                ➕ Nuevo Conductor
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-gradient-to-br from-slate-100 via-white to-cyan-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg font-bold">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <table class="w-full">
                    <thead class="bg-slate-800 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left font-bold">Nombre</th>
                            <th class="px-6 py-4 text-left font-bold">Licencia</th>
                            <th class="px-6 py-4 text-left font-bold">Teléfono</th>
                            <th class="px-6 py-4 text-left font-bold">Estado</th>
                            <th class="px-6 py-4 text-center font-bold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($conductores as $conductor)
                            <tr class="border-b hover:bg-slate-50 transition">
                                <td class="px-6 py-4 font-bold text-slate-800">{{ $conductor->nombre }}</td>
                                <td class="px-6 py-4 text-slate-600 font-mono">{{ $conductor->licencia }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $conductor->telefono }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold 
                                        {{ $conductor->estado === 'activo' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $conductor->estado === 'inactivo' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $conductor->estado === 'suspendido' ? 'bg-red-100 text-red-800' : '' }}
                                    ">
                                        {{ ucfirst($conductor->estado) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center space-x-2">
                                    <a href="{{ route('admin.conductores.edit', $conductor->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 font-bold">✏️ Editar</a>
                                    
                                    <form action="{{ route('admin.conductores.destroy', $conductor->id) }}" 
                                          method="POST" class="inline" 
                                          onsubmit="return confirm('¿Eliminar conductor?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-bold">
                                            🗑️ Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                    No hay conductores registrados aún.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="px-6 py-4 bg-slate-50 border-t">
                    {{ $conductores->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>