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

    <div class="py-10 bg-gradient-to-br from-slate-100 via-white to-cyan-50 min-h-screen">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            <!-- KPIs -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <!-- CARD -->
                <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 p-6 rounded-3xl shadow-xl text-white hover:scale-[1.02] transition-all duration-300">

                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase font-black tracking-widest opacity-70">
                                Vehículos Activos
                            </p>

                            <h3 class="text-5xl font-black mt-3">
                                18
                            </h3>
                        </div>

                        <div class="text-5xl opacity-30">
                            🚌
                        </div>
                    </div>

                </div>

                <!-- CARD -->
                <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-6 rounded-3xl shadow-xl text-white hover:scale-[1.02] transition-all duration-300">

                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-xs uppercase font-black tracking-widest opacity-70">
                                Rutas Operativas
                            </p>

                            <h3 class="text-5xl font-black mt-3">
                                {{ $rutas->count() }}
                            </h3>
                        </div>

                        <div class="text-5xl opacity-30">
                            📍
                        </div>

                    </div>

                </div>

                <!-- CARD -->
                <div class="bg-gradient-to-br from-rose-500 to-rose-600 p-6 rounded-3xl shadow-xl text-white hover:scale-[1.02] transition-all duration-300">

                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-xs uppercase font-black tracking-widest opacity-70">
                                Incidentes
                            </p>

                            <h3 class="text-5xl font-black mt-3">
                                03
                            </h3>
                        </div>

                        <div class="text-5xl opacity-30">
                            ⚠️
                        </div>

                    </div>

                </div>

                <!-- CARD -->
                <div class="bg-gradient-to-br from-amber-400 to-orange-500 p-6 rounded-3xl shadow-xl text-white hover:scale-[1.02] transition-all duration-300">

                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-xs uppercase font-black tracking-widest opacity-70">
                                Usuarios Online
                            </p>

                            <h3 class="text-5xl font-black mt-3">
                                248
                            </h3>
                        </div>

                        <div class="text-5xl opacity-30">
                            👥
                        </div>

                    </div>

                </div>

            </div>

            <!-- GRID -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- IZQUIERDA -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- HERO -->
                    <div class="bg-gradient-to-br from-[#001529] via-slate-900 to-slate-950 rounded-[2rem] overflow-hidden shadow-2xl relative">

                        <div class="absolute top-0 right-0 w-80 h-80 bg-cyan-500/10 rounded-full blur-3xl"></div>

                        <div class="relative p-10">

                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">

                                <div>

                                    <p class="text-cyan-400 uppercase tracking-[0.3em] text-xs font-black">
                                        Centro Inteligente
                                    </p>

                                    <h2 class="text-5xl font-black text-white mt-3 tracking-tight">
                                        MovSabana PRO
                                    </h2>

                                    <p class="text-slate-300 mt-4 max-w-2xl leading-relaxed">
                                        Plataforma de monitoreo y movilidad inteligente
                                        para Chía y la Sabana Centro.
                                    </p>

                                </div>

                                <div class="flex flex-wrap gap-4">

                                    <a href="/mapa"
                                       class="px-6 py-4 rounded-2xl bg-cyan-500 text-white font-black shadow-xl hover:scale-105 transition-all duration-300">
                                        📍 Abrir Mapa
                                    </a>

                                    <a href="{{ route('admin.rutas.index') }}"
                                       class="px-6 py-4 rounded-2xl bg-white/10 backdrop-blur border border-white/10 text-white font-black hover:bg-white/20 transition-all duration-300">
                                        🚌 Gestionar Rutas
                                    </a>

                                </div>

                            </div>

                            <!-- MINI STATS -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-10">

                                <div class="bg-white/5 border border-white/10 rounded-3xl p-6">

                                    <p class="text-xs uppercase tracking-widest text-slate-400 font-black">
                                        Estado de Red
                                    </p>

                                    <h3 class="text-3xl font-black text-emerald-400 mt-2">
                                        OPERATIVO
                                    </h3>

                                </div>

                                <div class="bg-white/5 border border-white/10 rounded-3xl p-6">

                                    <p class="text-xs uppercase tracking-widest text-slate-400 font-black">
                                        Pasaje Promedio
                                    </p>

                                    <h3 class="text-3xl font-black text-cyan-400 mt-2">
                                        $4.500
                                    </h3>

                                </div>

                                <div class="bg-white/5 border border-white/10 rounded-3xl p-6">

                                    <p class="text-xs uppercase tracking-widest text-slate-400 font-black">
                                        Cobertura
                                    </p>

                                    <h3 class="text-3xl font-black text-white mt-2">
                                        Sabana Norte
                                    </h3>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- GRAFICA -->
                    <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 hover:shadow-xl transition-all duration-300">

                        <div class="flex items-center justify-between mb-6">

                            <div>
                                <h3 class="font-black text-slate-800 uppercase tracking-tight">
                                    Flujo Operacional
                                </h3>

                                <p class="text-sm text-slate-400 font-medium mt-1">
                                    Usuarios conectados durante el día
                                </p>
                            </div>

                            <div class="px-4 py-2 rounded-xl bg-emerald-100 text-emerald-700 text-xs font-black uppercase">
                                Tiempo real
                            </div>

                        </div>

                        <canvas id="rutasChart" height="110"></canvas>

                    </div>

                    <!-- ACTIVIDAD -->
                    <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 hover:shadow-xl transition-all duration-300">

                        <div class="flex items-center justify-between mb-8">

                            <h3 class="font-black text-slate-800 uppercase tracking-tight">
                                Actividad en Vivo
                            </h3>

                            <span class="text-xs font-black uppercase text-emerald-500">
                                EN TIEMPO REAL
                            </span>

                        </div>

                        <div class="space-y-6">

                            <div class="flex items-start gap-4">

                                <div class="w-3 h-3 rounded-full bg-emerald-500 mt-2 animate-pulse"></div>

                                <div>
                                    <p class="font-black text-slate-700">
                                        Ruta Chía → Portal Norte inició recorrido.
                                    </p>

                                    <p class="text-sm text-slate-400 mt-1">
                                        Hace 2 minutos
                                    </p>
                                </div>

                            </div>

                            <div class="flex items-start gap-4">

                                <div class="w-3 h-3 rounded-full bg-rose-500 mt-2 animate-pulse"></div>

                                <div>
                                    <p class="font-black text-slate-700">
                                        Congestión detectada en Variante Chía.
                                    </p>

                                    <p class="text-sm text-slate-400 mt-1">
                                        Hace 7 minutos
                                    </p>
                                </div>

                            </div>

                            <div class="flex items-start gap-4">

                                <div class="w-3 h-3 rounded-full bg-cyan-500 mt-2 animate-pulse"></div>

                                <div>
                                    <p class="font-black text-slate-700">
                                        Nuevo usuario conectado al sistema.
                                    </p>

                                    <p class="text-sm text-slate-400 mt-1">
                                        Hace 12 minutos
                                    </p>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- DERECHA -->
                <div class="space-y-8">

                    <!-- IA -->
                    <div class="bg-gradient-to-br from-cyan-500 to-blue-600 rounded-[2rem] p-8 text-white shadow-2xl hover:scale-[1.02] transition-all duration-300">

                        <p class="text-xs uppercase tracking-widest font-black opacity-70">
                            IA Predictiva
                        </p>

                        <h3 class="text-3xl font-black mt-3">
                            Alta congestión estimada
                        </h3>

                        <p class="mt-4 text-sm leading-relaxed opacity-90">
                            Se espera incremento de tráfico en Autopista Norte
                            entre 5PM y 7PM.
                        </p>

                    </div>

                    <!-- INCIDENTES -->
                    <div class="bg-[#001529] rounded-[2rem] shadow-2xl p-8 text-white border-b-8 border-rose-500">

                        <h3 class="font-black text-xl mb-6 uppercase tracking-tight">
                            Incidentes de Red
                        </h3>

                        <div class="space-y-5">

                            <div class="p-5 bg-white/5 rounded-2xl border border-white/10">

                                <p class="text-[10px] text-rose-400 uppercase tracking-widest font-black">
                                    GPS ALERT
                                </p>

                                <p class="mt-2 text-sm font-bold">
                                    Desvío detectado en Variante Chía.
                                </p>

                            </div>

                            <div class="p-5 bg-white/5 rounded-2xl border border-white/10">

                                <p class="text-[10px] text-cyan-400 uppercase tracking-widest font-black">
                                    SISTEMA
                                </p>

                                <p class="mt-2 text-sm font-bold">
                                    Todos los servicios operan normalmente.
                                </p>

                            </div>

                            <div class="p-5 bg-white/5 rounded-2xl border border-white/10">

                                <p class="text-[10px] text-amber-400 uppercase tracking-widest font-black">
                                    CLIMA
                                </p>

                                <p class="mt-2 text-sm font-bold">
                                    Lluvia ligera prevista para las 6PM.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- CHART JS -->
    <script>
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
    </script>

</x-app-layout>