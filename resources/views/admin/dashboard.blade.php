<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 py-2">
            <div>
                <h2 class="font-black text-3xl text-slate-800 tracking-tighter uppercase">
                    {{ __('Panel de Control') }} <span class="text-cyan-500">Admin</span>
                </h2>
                <p class="text-sm text-slate-500 font-bold italic">
                    Sistema Inteligente de Movilidad — MovSabana Chía
                </p>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="text-right hidden md:block">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Estado del Sistema</p>
                    <p class="text-xs font-bold text-emerald-500 flex items-center justify-end gap-1">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        Operativo
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- KPIs SUPERIORES -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Rutas Activas</p>
                    <h3 class="text-3xl font-black text-slate-800 tracking-tighter">{{ $totalRutas }}</h3>
                </div>
                <div class="bg-[#001529] p-6 rounded-[2rem] shadow-xl text-white">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Alertas de Red</p>
                    <h3 class="text-3xl font-black text-rose-500 tracking-tighter">{{ $stats['alertas_hoy'] }}</h3>
                </div>
                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Eficiencia Flota</p>
                    <h3 class="text-3xl font-black text-slate-800 tracking-tighter">{{ $stats['eficiencia'] }}%</h3>
                </div>
                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center justify-center">
                    <a href="{{ route('admin.rutas.index') }}" class="text-xs font-black uppercase tracking-tighter text-cyan-600 hover:text-cyan-700 transition-colors">
                        Ver todas las rutas →
                    </a>
                </div>
            </div>

            <!-- MAPA E INCIDENTES -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Mapa de Chía -->
                <div class="lg:col-span-2 bg-white rounded-[2.5rem] p-4 shadow-sm border border-slate-100 h-[500px] relative overflow-hidden">
                    <div id="map" class="w-full h-full rounded-[2rem] z-10"></div>
                </div>

                <!-- Lista de Incidentes (Sidebar) -->
                <div class="space-y-6">
                    <h3 class="font-black text-slate-800 uppercase tracking-tighter flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        Incidentes Recientes
                    </h3>
                    
                    <div class="space-y-4 max-h-[420px] overflow-y-auto pr-2">
                        @forelse($incidentes as $incidente)
                            <div class="bg-white p-5 rounded-3xl border-l-4 border-rose-500 shadow-sm hover:shadow-md transition-all">
                                <p class="text-[10px] font-black text-rose-500 uppercase tracking-widest">GPS ALERT</p>
                                <h4 class="font-bold text-slate-800 text-sm tracking-tight">{{ $incidente->descripcion }}</h4>
                                <p class="text-[10px] text-slate-400 font-bold italic mt-1">Ubicación: Variante Chía</p>
                            </div>
                        @empty
                            <div class="bg-slate-100/50 p-6 rounded-3xl text-center border-2 border-dashed border-slate-200">
                                <p class="text-xs font-bold text-slate-400 italic">No hay incidentes reportados</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para Leaflet -->
    @push('scripts')
    <script>
        var map = L.map('map').setView([4.862, -74.053], 14); // Coordenadas de Chía

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Icono para incidentes (Estilo MovSabana)
        const alertIcon = L.divIcon({
            className: 'custom-div-icon',
            html: "<div class='relative flex h-5 w-5'><span class='animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75'></span><span class='relative inline-flex rounded-full h-5 w-5 bg-rose-600 border-2 border-white'></span></div>",
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });

        // Pintar incidentes desde la base de datos
        @foreach($incidentes as $inc)
            L.marker([{{ $inc->latitud }}, {{ $inc->longitud }}], { icon: alertIcon })
                .addTo(map)
                .bindPopup("<b class='font-black uppercase text-rose-600'>{{ $inc->titulo }}</b><br><span class='text-xs font-bold'>{{ $inc->descripcion }}</span>");
        @endforeach
    </script>
    @endpush
</x-app-layout>