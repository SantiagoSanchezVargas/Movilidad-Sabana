<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold">Mi Hoja de Ruta - {{ auth()->user()->nombre }}</h2>
    </x-slot>

    <div class="py-10 bg-gradient-to-br from-slate-100 via-white to-cyan-50">
        <div class="max-w-7xl mx-auto px-4 space-y-8">

            @if($ruta)
            <!-- Ruta Actual -->
            <div class="bg-gradient-to-r from-cyan-600 to-blue-600 text-white rounded-lg shadow-lg p-8">
                <h3 class="text-2xl font-bold mb-6">Ruta Asignada Hoy</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-cyan-100 text-sm">Nombre Ruta</p>
                        <p class="text-xl font-bold">{{ $ruta->nombre }}</p>
                    </div>
                    <div>
                        <p class="text-cyan-100 text-sm">Vehículo</p>
                        <p class="text-xl font-bold">{{ $ruta->vehiculo->placa ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-cyan-100 text-sm">Estado</p>
                        <span class="px-4 py-2 bg-white text-cyan-600 rounded-full font-bold text-sm inline-block">
                            {{ ucfirst($ruta->estado) }}
                        </span>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex gap-3 mt-6">
                    <button class="bg-white text-cyan-600 px-6 py-2 rounded-lg font-bold hover:bg-cyan-50">
                        📍 Iniciar Ruta
                    </button>
                    <button class="bg-cyan-500 text-white px-6 py-2 rounded-lg font-bold hover:bg-cyan-700">
                        ⚠️ Reportar Incidente
                    </button>
                </div>
            </div>
            @else
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded">
                <p class="text-yellow-800 font-semibold">❌ No tienes ruta asignada para hoy</p>
            </div>
            @endif

            <!-- Alertas GPS -->
            @if($alertasGps->count() > 0)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-red-50">
                    <h3 class="text-lg font-bold text-red-900">⚠️ Alertas de Desvíos</h3>
                </div>
                <div class="space-y-3 p-6">
                    @foreach($alertasGps as $alerta)
                        <div class="flex items-start gap-4 p-4 bg-red-50 rounded-lg border border-red-200">
                            <div class="text-2xl">🚨</div>
                            <div class="flex-1">
                                <h4 class="font-bold text-red-900">{{ $alerta->tipo }}</h4>
                                <p class="text-sm text-red-700">{{ $alerta->descripcion }}</p>
                                <p class="text-xs text-red-600 mt-1">{{ $alerta->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-500 text-sm font-semibold">Total Viajes</h3>
                    <p class="text-3xl font-bold text-cyan-600">{{ $estadisticas['viajes_totales'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-500 text-sm font-semibold">Rating</h3>
                    <p class="text-3xl font-bold text-yellow-600">⭐ {{ $estadisticas['rating_promedio'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-500 text-sm font-semibold">Status</h3>
                    <p class="text-3xl font-bold text-green-600">✓ Activo</p>
                </div>
            </div>

            <!-- Últimas Rutas -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold">Últimas Rutas</h3>
                </div>
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Ruta</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Fecha</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($misRutas as $ruta)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-semibold">{{ $ruta->nombre }}</td>
                                <td class="px-6 py-4">{{ $ruta->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold 
                                        {{ $ruta->estado === 'completado' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($ruta->estado) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">No hay historial</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>