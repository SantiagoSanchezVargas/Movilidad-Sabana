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
                    <p class="text-3xl font-bold text-red-600">{{ $incidentes->count() ?? 0 }}</p>
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
    @elseif(auth()->user()->hasRole('conductor'))
    <!-- DASHBOARD CONDUCTOR -->
    <div class="py-10 bg-gradient-to-br from-slate-100 via-white to-cyan-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-bold mb-4">Mi Ruta Actual</h3>
                <p class="text-gray-600">Dashboard de conductor en desarrollo...</p>
            </div>

        </div>
    </div>
   @else
<!-- DASHBOARD PASAJERO -->
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

    <script>
        // Chart placeholder
        if (document.getElementById('rutasChart')) {
            const ctx = document.getElementById('rutasChart');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['6AM','8AM','10AM','12PM','2PM','4PM','6PM'],
                    datasets: [{
                        label: 'Usuarios',
                        data: [120, 300, 250, 400, 380, 520, 610],
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
    </script>

</x-app-layout>