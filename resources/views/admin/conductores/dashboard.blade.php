<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-3xl text-slate-800 uppercase">🚗 Dashboard Conductor</h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-slate-100 via-white to-cyan-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- CARDS -->
            <div class="grid grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-cyan-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-bold">Viajes Totales</p>
                            <p class="text-3xl font-black text-cyan-600">{{ $totalViajes }}</p>
                        </div>
                        <div class="text-4xl">🚗</div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-bold">Viajes Completados</p>
                            <p class="text-3xl font-black text-green-600">{{ $viajesCompletados }}</p>
                        </div>
                        <div class="text-4xl">✅</div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-bold">Rating Promedio</p>
                            <p class="text-3xl font-black text-yellow-600">⭐ {{ $ratingPromedio }}</p>
                        </div>
                        <div class="text-4xl">📊</div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-bold">KM Recorridos</p>
                            <p class="text-3xl font-black text-purple-600">{{ $kmRecorridos }}</p>
                        </div>
                        <div class="text-4xl">📍</div>
                    </div>
                </div>
            </div>

            <!-- GRÁFICOS -->
            <div class="grid grid-cols-2 gap-6 mb-8">
                <!-- Gráfico Viajes por Estado -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="font-bold text-lg text-slate-800 mb-4">📈 Mis Viajes</h3>
                    <canvas id="viajesChart"></canvas>
                </div>

                <!-- Gráfico de Ingresos -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="font-bold text-lg text-slate-800 mb-4">💰 Ingresos por Mes</h3>
                    <canvas id="ingresosChart"></canvas>
                </div>
            </div>

            <!-- MIS RUTAS ASIGNADAS -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-slate-800 text-white">
                    <h3 class="text-lg font-bold">Mis Rutas Asignadas</h3>
                </div>
                <table class="w-full">
                    <thead class="bg-slate-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Ruta</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Origen</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Destino</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($misRutas as $ruta)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-bold">{{ $ruta->nombre }}</td>
                                <td class="px-6 py-4">{{ $ruta->origen }}</td>
                                <td class="px-6 py-4">{{ $ruta->destino }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                        {{ ucfirst($ruta->estado) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No hay rutas asignadas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        // Gráfico de Viajes
        const viajesCtx = document.getElementById('viajesChart').getContext('2d');
        new Chart(viajesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Completados', 'En Progreso'],
                datasets: [{
                    data: [{{ $viajesCompletados }}, {{ $totalViajes - $viajesCompletados }}],
                    backgroundColor: ['#10b981', '#f59e0b'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        // Gráfico de Ingresos
        const ingresosCtx = document.getElementById('ingresosChart').getContext('2d');
        new Chart(ingresosCtx, {
            type: 'line',
            data: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                datasets: [{
                    label: 'Ingresos ($)',
                    data: [{{ $ingresosMes ?? '0,0,0,0,0,0' }}],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</x-app-layout>