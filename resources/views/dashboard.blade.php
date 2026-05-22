<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 py-2">

            <div>
                <h2 class="font-black text-3xl text-slate-800 tracking-tighter uppercase">
                    {{ __('Panel Central') }}
                    <span class="text-cyan-500">MovSabana</span>
                </h2>

                <p class="text-sm text-slate-500 font-bold italic">
                    @if(auth()->user()->hasRole('administrador')) GESTIÓN ESTRATÉGICA @endif
                    @if(auth()->user()->hasRole('conductor')) MI HOJA DE RUTA @endif
                    @if(auth()->user()->hasRole('usuario')) CONSULTA DE MOVILIDAD @endif
                    — Chía, Cundinamarca
                </p>
            </div>

            @if(auth()->user()->hasRole('administrador'))
            <div class="flex gap-3">
                <a href="{{ route('admin.reportes.create') }}"
                   class="inline-flex items-center bg-white border-2 border-slate-200 text-slate-700 px-5 py-2.5 rounded-xl text-sm font-black shadow-sm hover:bg-slate-50 transition-all uppercase tracking-tight">
                    Generar Reporte
                </a>

                <a href="{{ route('admin.rutas.create') }}"
                   class="inline-flex items-center bg-[#001529] text-white px-5 py-2.5 rounded-xl text-sm font-black shadow-lg hover:bg-cyan-600 transition-all uppercase tracking-tight">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nueva Ruta
                </a>
            </div>
            @endif

        </div>
    </x-slot>

    <!-- CHART -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- DASHBOARD ADMINISTRADOR --}}
    @if(auth()->user()->hasRole('administrador'))
    <div class="py-10 bg-gradient-to-br from-slate-100 via-white to-cyan-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            <!-- KPIs ADMIN -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-500 text-sm font-semibold">Total Rutas</h3>
                    <p class="text-3xl font-bold text-cyan-600">{{ $rutas->count() ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-500 text-sm font-semibold">Rutas Activas</h3>
                    <p class="text-3xl font-bold text-green-600">{{ $rutas->where('estado', 'activo')->count() ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-500 text-sm font-semibold">Eficiencia</h3>
                    <p class="text-3xl font-bold text-blue-600">92%</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-500 text-sm font-semibold">Alertas Hoy</h3>
                    <p class="text-3xl font-bold text-red-600">@if($incidentes->count() > 0){{ $incidentes->count() }}@endif</p>
                </div>
            </div>

            <!-- Tabla de Rutas -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold">Rutas</h3>
                </div>
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Ruta</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Conductor</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Estado</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Acciones</th>
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
            @if($incidentes && $incidentes->count() > 0)
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

    {{-- DASHBOARD CONDUCTOR MEJORADO --}}
@elseif(auth()->user()->hasRole('conductor'))
<div class="py-10 bg-gradient-to-br from-slate-100 via-white to-cyan-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">
 
        <!-- MIS RUTAS ASIGNADAS -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-slate-800 text-white">
                <h3 class="text-lg font-bold">🚗 Mis Rutas Asignadas</h3>
            </div>
            <table class="w-full">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Ruta</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Origen</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Destino</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Distancia</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Paradas</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($misRutas as $ruta)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-bold">{{ $ruta->nombre }}</td>
                            <td class="px-6 py-4">{{ $ruta->origen }}</td>
                            <td class="px-6 py-4">{{ $ruta->destino }}</td>
                            <td class="px-6 py-4">{{ $ruta->distancia_km }} km</td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-bold">
                                    {{ optional($ruta->paradas)->count() ?? 0 }} paradas
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                    {{ ucfirst($ruta->estado) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                📭 No hay rutas asignadas aún
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
 
        <!-- SECCIÓN MAPA CON PARADAS -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-slate-800 text-white">
                <h3 class="text-lg font-bold">🗺️ Mi Ruta en Tiempo Real</h3>
            </div>
            
            <!-- Contenedor del mapa -->
            <div id="map" style="height: 600px;"></div>
            
            <!-- Leyenda -->
            <div class="px-6 py-4 bg-gray-50 border-t">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                        <span><strong>Origen</strong></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-blue-500 rounded-full"></div>
                        <span><strong>Parada</strong></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                        <span><strong>Destino</strong></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-yellow-400 rounded-full border-2 border-yellow-600"></div>
                        <span><strong>Confirmada</strong></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-gray-400 rounded-full"></div>
                        <span><strong>Pendiente</strong></span>
                    </div>
                </div>
            </div>
        </div>
 
        <!-- PARADAS DE LA RUTA -->
        @foreach($misRutas as $ruta)
            @if($ruta->paradas && $ruta->paradas->count() > 0)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-blue-600 text-white">
                    <h3 class="text-lg font-bold">🛑 Paradas de {{ $ruta->nombre }}</h3>
                </div>
                
                <div class="space-y-3 p-6">
                    @foreach($ruta->paradas->sortBy('orden') as $parada)
                        {{-- Buscar confirmación de esta parada --}}
                        @php
                            $confirmacion = \App\Models\ParadaConfirmacion::where('parada_id', $parada->id)
                                ->where('conductor_id', auth()->user()->conductor->id)
                                ->where('ruta_id', $ruta->id)
                                ->first();
                        @endphp
 
                        <div class="p-4 rounded-lg border-2 
                            {{ $confirmacion && $confirmacion->confirmado_en ? 'bg-green-50 border-green-300' : 'bg-gray-50 border-gray-300' }}">
                            
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <!-- Número de parada -->
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white
                                        {{ $confirmacion && $confirmacion->confirmado_en ? 'bg-green-600' : 'bg-blue-600' }}">
                                        {{ $parada->orden }}
                                    </div>
                                    
                                    <!-- Info parada -->
                                    <div>
                                        <h4 class="font-bold text-lg text-gray-800">{{ $parada->nombre }}</h4>
                                        <p class="text-sm text-gray-600">
                                            📍 {{ $parada->latitud }}, {{ $parada->longitud }}
                                        </p>
                                        @if($parada->hora_estimada)
                                            <p class="text-sm text-gray-600">⏰ Hora estimada: {{ $parada->hora_estimada }}</p>
                                        @endif
                                        @if($parada->descripcion)
                                            <p class="text-sm text-gray-600">{{ $parada->descripcion }}</p>
                                        @endif
                                    </div>
                                </div>
 
                                <!-- Botón de confirmación -->
                                @if(!$confirmacion || !$confirmacion->confirmado_en)
                                    <form action="{{ route('conductor.parada.confirmar', $parada->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="ruta_id" value="{{ $ruta->id }}">
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition">
                                            ✅ Confirmar Llegada
                                        </button>
                                    </form>
                                @else
                                    <div class="text-center">
                                        <p class="text-green-700 font-bold text-sm">✓ Confirmado</p>
                                        <p class="text-gray-600 text-xs">{{ $confirmacion->confirmado_en->format('H:i') }}</p>
                                        @if($confirmacion->pasajeros_subieron)
                                            <p class="text-blue-600 text-sm font-semibold">👥 {{ $confirmacion->pasajeros_subieron }} pasajeros</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
 
                            <!-- Info de confirmación si ya está confirmada -->
                            @if($confirmacion && $confirmacion->confirmado_en)
                            <div class="mt-3 pt-3 border-t border-green-300">
                                <div class="grid grid-cols-3 gap-4 text-center text-sm">
                                    <div>
                                        <p class="text-gray-600">Llegada</p>
                                        <p class="font-bold">{{ $confirmacion->confirmado_en->format('H:i:s') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600">Ubicación</p>
                                        <p class="font-bold text-xs">{{ number_format($confirmacion->latitud_confirmacion, 4) }}, {{ number_format($confirmacion->longitud_confirmacion, 4) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600">Pasajeros</p>
                                        <p class="font-bold">{{ $confirmacion->pasajeros_subieron ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        @endforeach
 
    </div>
</div>
 
<!-- SCRIPTS LEAFLET Y MAPA CON PARADAS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
 
<script>
document.addEventListener('DOMContentLoaded', function() {
    const map = L.map('map').setView([4.8604, -74.0447], 12);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap',
        maxZoom: 19
    }).addTo(map);
 
    const rutasData = {!! json_encode($misRutas->map(function($ruta) {
        return [
            'nombre' => $ruta->nombre,
            'origen' => $ruta->origen,
            'destino' => $ruta->destino,
            'distancia' => $ruta->distancia_km,
            'originLat' => $ruta->origen_lat ?? 4.8604,
            'originLng' => $ruta->origen_lng ?? -74.0447,
            'destinoLat' => $ruta->destino_lat ?? 4.7110,
            'destinoLng' => $ruta->destino_lng ?? -74.0076,
            'paradas' => $ruta->paradas ? $ruta->paradas->map(function($p) {
                return [
                    'nombre' => $p->nombre,
                    'lat' => $p->latitud,
                    'lng' => $p->longitud,
                    'orden' => $p->orden,
                ];
            })->toArray() : [],
        ];
    })->toArray()) !!};
 
    rutasData.forEach(ruta => {
        // 🟢 Origen (verde)
        L.circleMarker([ruta.originLat, ruta.originLng], {
            color: '#22c55e',
            fillColor: '#22c55e',
            fillOpacity: 0.8,
            radius: 10,
            weight: 3
        }).addTo(map).bindPopup(`
            <strong>${ruta.nombre}</strong><br>
            📍 Origen: ${ruta.origen}<br>
            Distancia: ${ruta.distancia} km
        `);
 
        // 🔴 Destino (rojo)
        L.circleMarker([ruta.destinoLat, ruta.destinoLng], {
            color: '#ef4444',
            fillColor: '#ef4444',
            fillOpacity: 0.8,
            radius: 10,
            weight: 3
        }).addTo(map).bindPopup(`
            <strong>${ruta.nombre}</strong><br>
            🎯 Destino: ${ruta.destino}
        `);
 
        // 🔵 Paradas (azul)
        ruta.paradas.forEach(parada => {
            L.circleMarker([parada.lat, parada.lng], {
                color: '#3b82f6',
                fillColor: '#3b82f6',
                fillOpacity: 0.7,
                radius: 7,
                weight: 2
            }).addTo(map).bindPopup(`
                <strong>Parada ${parada.orden}</strong><br>
                🛑 ${parada.nombre}<br>
                📍 ${parada.lat}, ${parada.lng}
            `);
        });
 
        // 🔵 Línea de ruta (azul punteada)
        L.polyline(
            [[ruta.originLat, ruta.originLng], [ruta.destinoLat, ruta.destinoLng]],
            { color: '#3b82f6', weight: 3, opacity: 0.5, dashArray: '5, 5' }
        ).addTo(map);
 
        // Ajustar vista
        const group = new L.featureGroup([
            L.circleMarker([ruta.originLat, ruta.originLng]),
            L.circleMarker([ruta.destinoLat, ruta.destinoLng])
        ]);
        map.fitBounds(group.getBounds(), { padding: [50, 50] });
    });
});
</script>
 
@else
    {{-- DASHBOARD PASAJERO --}}
    <div class="py-10 bg-gradient-to-br from-slate-100 via-white to-cyan-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            <!-- CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-cyan-500">
                    <h3 class="text-gray-500 text-sm font-semibold">Viajes Realizados</h3>
                    <p class="text-3xl font-bold text-cyan-600">{{ $totalViajes ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                    <h3 class="text-gray-500 text-sm font-semibold">Gasto Total</h3>
                    <p class="text-3xl font-bold text-green-600">${{ $gastoTotal ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                    <h3 class="text-gray-500 text-sm font-semibold">Favoritos</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $favoritos ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                    <h3 class="text-gray-500 text-sm font-semibold">Rating</h3>
                    <p class="text-3xl font-bold text-purple-600">⭐ {{ $ratingPromedio ?? 4.5 }}</p>
                </div>
            </div>

            <!-- GRÁFICOS -->
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold text-lg mb-4">📈 Mis Viajes por Mes</h3>
                    <canvas id="viajesChart"></canvas>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold text-lg mb-4">💸 Gastos por Categoría</h3>
                    <canvas id="gastosChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Gráfico Viajes
        const viajesCtx = document.getElementById('viajesChart')?.getContext('2d');
        if (viajesCtx) {
            new Chart(viajesCtx, {
                type: 'bar',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Viajes',
                        data: [5, 8, 12, 10, 15, 18],
                        backgroundColor: '#3b82f6',
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        }

        // Gráfico Gastos
        const gastosCtx = document.getElementById('gastosChart')?.getContext('2d');
        if (gastosCtx) {
            new Chart(gastosCtx, {
                type: 'pie',
                data: {
                    labels: ['Transporte', 'Comidas', 'Diversión'],
                    datasets: [{
                        data: [45, 30, 25],
                        backgroundColor: ['#ef4444', '#10b981', '#3b82f6'],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }
    </script>
    @endif

</x-app-layout>