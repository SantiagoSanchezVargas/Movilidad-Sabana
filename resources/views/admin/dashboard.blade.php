<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold">Dashboard Administrativo MovSabana</h2>
    </x-slot>

    <div class="py-10 bg-gradient-to-br from-slate-100 via-white to-cyan-50">
        <div class="max-w-7xl mx-auto px-4 space-y-8">

            <!-- KPIs -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-500 text-sm font-semibold">Total Rutas</h3>
                    <p class="text-3xl font-bold text-cyan-600">{{ $totalRutas }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-500 text-sm font-semibold">Rutas Activas</h3>
                    <p class="text-3xl font-bold text-green-600">{{ $rutasActivas }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-500 text-sm font-semibold">En Ruta</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['conductores_en_ruta'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-500 text-sm font-semibold">Alertas Hoy</h3>
                    <p class="text-3xl font-bold text-red-600">{{ $stats['alertas_hoy'] }}</p>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex gap-4">
                <a href="{{ route('admin.rutas.create') }}" class="bg-cyan-600 text-white px-6 py-3 rounded-lg font-bold">
                    + Nueva Ruta
                </a>
                <a href="{{ route('admin.reportes.create') }}" class="bg-slate-600 text-white px-6 py-3 rounded-lg font-bold">
                    Generar Reporte
                </a>
            </div>

            <!-- Tabla de Rutas -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold">Rutas</h3>
                </div>
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Ruta</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Conductor</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Estado</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rutas as $ruta)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-semibold">{{ $ruta->nombre }}</td>
                                <td class="px-6 py-4">{{ $ruta->conductor->nombre ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold 
                                        {{ $ruta->estado === 'activo' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($ruta->estado) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('admin.rutas.edit', $ruta) }}" class="text-cyan-600 hover:underline">Editar</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No hay rutas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Incidentes -->
            @if($incidentes->count() > 0)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-red-50">
                    <h3 class="text-lg font-bold text-red-900">⚠️ Incidentes Activos</h3>
                </div>
                <div class="space-y-3 p-6">
                    @foreach($incidentes as $incidente)
                        <div class="p-4 bg-red-50 rounded-lg border border-red-200">
                            <h4 class="font-bold text-red-900">{{ $incidente->tipo }}</h4>
                            <p class="text-sm text-red-700">{{ $incidente->descripcion }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>