<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-3xl text-slate-800 uppercase">📊 Dashboard Administrativo MovSabana</h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-slate-100 via-white to-cyan-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- CARDS CON ESTADÍSTICAS -->
            <div class="grid grid-cols-4 gap-6 mb-8">
                <!-- Total Rutas -->
                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-cyan-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-bold">Total Rutas</p>
                            <p class="text-3xl font-black text-cyan-600">{{ $totalRutas }}</p>
                        </div>
                        <div class="text-4xl">🛣️</div>
                    </div>
                </div>

                <!-- Rutas Activas -->
                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-bold">Rutas Activas</p>
                            <p class="text-3xl font-black text-green-600">{{ $rutasActivas }}</p>
                        </div>
                        <div class="text-4xl">✅</div>
                    </div>
                </div>

                <!-- Total Conductores -->
                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-bold">Conductores</p>
                            <p class="text-3xl font-black text-blue-600">{{ $totalConductores }}</p>
                        </div>
                        <div class="text-4xl">🚗</div>
                    </div>
                </div>

                <!-- Total Usuarios -->
                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-bold">Usuarios</p>
                            <p class="text-3xl font-black text-purple-600">{{ $totalUsuarios }}</p>
                        </div>
                        <div class="text-4xl">👥</div>
                    </div>
                </div>
            </div>

            <!-- BOTONES DE ACCIÓN -->
            <div class="flex gap-4 mb-8">
                <a href="{{ route('admin.rutas.create') }}" class="bg-cyan-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-cyan-700">
                    + Nueva Ruta
                </a>
                <a href="{{ route('admin.conductores.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-700">
                    + Nuevo Conductor
                </a>
            </div>

            <!-- GRÁFICOS -->
            <div class="grid grid-cols-2 gap-6 mb-8">
                <!-- Gráfico de Rutas por Estado -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="font-bold text-lg text-slate-800 mb-4">📈 Estado de Rutas</h3>
                    <canvas id="rutasChart"></canvas>
                </div>

                <!-- Gráfico de Usuarios por Rol -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="font-bold text-lg text-slate-800 mb-4">👥 Usuarios por Rol</h3>
                    <canvas id="usuariosChart"></canvas>
                </div>
            </div>

            <!-- TABLA DE RUTAS -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-slate-800 text-white">
                    <h3 class="text-lg font-bold">Rutas Recientes</h3>
                </div>
                <table class="w-full">
                    <thead class="bg-slate-100">
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
                                <td class="px-6 py-4 font-bold">{{ $ruta->nombre }}</td>
                                <td class="px-6 py-4">{{ $ruta->conductor->nombre ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold 
                                        {{ $ruta->estado === 'activo' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($ruta->estado) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('admin.rutas.edit', $ruta) }}" class="text-cyan-600 hover:text-cyan-800 font-bold">✏️ Editar</a>
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

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        // Gráfico de Rutas por Estado
        const rutasCtx = document.getElementById('rutasChart').getContext('2d');
        new Chart(rutasCtx, {
            type: 'doughnut',
            data: {
                labels: ['Activas', 'Inactivas'],
                datasets: [{
                    data: [{{ $rutasActivas }}, {{ $totalRutas - $rutasActivas }}],
                    backgroundColor: ['#10b981', '#ef4444'],
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

        // Gráfico de Usuarios por Rol
        const usuariosCtx = document.getElementById('usuariosChart').getContext('2d');
        new Chart(usuariosCtx, {
            type: 'bar',
            data: {
                labels: ['Admin', 'Conductor', 'Pasajero'],
                datasets: [{
                    label: 'Cantidad',
                    data: [{{ $adminCount ?? 0 }}, {{ $conductorCount ?? 0 }}, {{ $pasajeroCount ?? 0 }}],
                    backgroundColor: ['#3b82f6', '#8b5cf6', '#ec4899'],
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y',
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { beginAtZero: true }
                }
            }
        });
    </script>
</x-app-layout>