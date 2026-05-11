<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between py-1">

            <div>
                <h2 class="font-black text-2xl text-slate-800 tracking-tighter uppercase">
                    Modo <span class="text-cyan-500">Conductor</span>
                </h2>

                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    Unidad Operativa • {{ Auth::user()->name }}
                </p>
            </div>

            <div class="flex items-center gap-2 bg-emerald-50 px-4 py-2 rounded-full border border-emerald-100 shadow-sm">

                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>

                <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">
                    GPS ACTIVO
                </span>

            </div>

        </div>
    </x-slot>

    <!-- LEAFLET -->
    <link rel="stylesheet"
          href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    <div class="py-6 bg-slate-900 min-h-screen pb-28">

        <div class="max-w-xl mx-auto px-4 space-y-6">

            <!-- ALERTAS -->
            @foreach($alertasGps as $alerta)

            <div class="bg-[#001529] rounded-[2rem] p-6 border-b-4 border-rose-600 shadow-2xl">

                <div class="flex items-center gap-3 mb-3">

                    <div class="w-2 h-2 bg-rose-500 rounded-full animate-pulse"></div>

                    <h3 class="text-rose-500 font-black italic uppercase tracking-tighter text-lg">
                        Incidente Detectado
                    </h3>

                </div>

                <div class="bg-slate-800/50 rounded-2xl p-4 border border-white/5">

                    <p class="text-rose-400 text-[10px] font-black uppercase tracking-widest mb-1 font-mono">
                        GPS ALERT
                    </p>

                    <p class="text-white font-bold text-lg leading-tight">
                        {{ $alerta->descripcion }}
                    </p>

                </div>

            </div>

            @endforeach

            <!-- CARD PRINCIPAL -->
            <div class="bg-white rounded-[2.5rem] p-8 shadow-xl">

                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                    Ruta Operativa
                </p>

                @if($ruta)

                    <h1 class="text-3xl font-black text-slate-800 tracking-tighter uppercase leading-none mb-3">
                        {{ $ruta->nombre }}
                    </h1>

                    <div class="flex items-center gap-2 flex-wrap">

                        <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-lg text-xs font-black font-mono border border-slate-200">
                            {{ $ruta->codigo }}
                        </span>

                        <span class="text-xs font-bold text-slate-400 italic">
                            Próxima parada: Centro Chía
                        </span>

                    </div>

                @else

                    <p class="text-slate-400 font-bold italic">
                        Sin ruta asignada.
                    </p>

                @endif

            </div>

            <!-- MINI MAPA -->
            <div class="bg-white rounded-[2.5rem] p-4 shadow-xl overflow-hidden">

                <div class="flex items-center justify-between px-2 pt-2 pb-4">

                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                            Navegación
                        </p>

                        <h3 class="text-xl font-black text-slate-800">
                            Ruta en Tiempo Real
                        </h3>
                    </div>

                    <div class="bg-emerald-100 text-emerald-600 text-[10px] font-black px-3 py-1 rounded-full uppercase">
                        EN MOVIMIENTO
                    </div>

                </div>

                <div id="map"
                     class="w-full h-[280px] rounded-[1.5rem] border border-slate-100">
                </div>

            </div>

            <!-- KPIs -->
            <div class="grid grid-cols-2 gap-4">

                <div class="bg-white rounded-[2rem] p-5 shadow-lg">

                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                        Velocidad
                    </p>

                    <h3 class="text-4xl font-black text-cyan-500">
                        42
                    </h3>

                    <p class="text-xs font-bold text-slate-400">
                        km/h
                    </p>

                </div>

                <div class="bg-white rounded-[2rem] p-5 shadow-lg">

                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                        Estado
                    </p>

                    <h3 class="text-2xl font-black text-emerald-500 uppercase">
                        Activo
                    </h3>

                    <p class="text-xs font-bold text-slate-400">
                        Ruta normal
                    </p>

                </div>

                <div class="bg-white rounded-[2rem] p-5 shadow-lg">

                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                        Tiempo Activo
                    </p>

                    <h3 class="text-3xl font-black text-slate-800">
                        01:32
                    </h3>

                    <p class="text-xs font-bold text-slate-400">
                        horas
                    </p>

                </div>

                <div class="bg-white rounded-[2rem] p-5 shadow-lg">

                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                        Precisión GPS
                    </p>

                    <h3 class="text-3xl font-black text-emerald-500">
                        98%
                    </h3>

                    <p class="text-xs font-bold text-slate-400">
                        señal estable
                    </p>

                </div>

            </div>

            <!-- BOTONES OPERACIONALES -->
            <div class="grid grid-cols-2 gap-4">

                <button class="bg-emerald-500 hover:bg-emerald-600 text-white h-32 rounded-[2rem] shadow-lg shadow-emerald-500/20 flex flex-col items-center justify-center gap-2 transition-transform active:scale-95">

                    <svg class="w-8 h-8"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="3"
                              d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                        </path>

                    </svg>

                    <span class="font-black uppercase tracking-tighter text-sm">
                        Iniciar Ruta
                    </span>

                </button>

                <button class="bg-rose-500 hover:bg-rose-600 text-white h-32 rounded-[2rem] shadow-lg shadow-rose-500/20 flex flex-col items-center justify-center gap-2 transition-transform active:scale-95">

                    <svg class="w-8 h-8"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="3"
                              d="M6 6h12v12H6z">
                        </path>

                    </svg>

                    <span class="font-black uppercase tracking-tighter text-sm">
                        Finalizar
                    </span>

                </button>

            </div>

            <!-- REPORTES -->
            <div class="grid grid-cols-2 gap-4">

                <button class="bg-amber-500 text-white py-5 rounded-[2rem] font-black uppercase tracking-tight shadow-lg">
                    🚧 Tráfico
                </button>

                <button class="bg-rose-600 text-white py-5 rounded-[2rem] font-black uppercase tracking-tight shadow-lg">
                    🚨 Emergencia
                </button>

                <button class="bg-slate-800 text-white py-5 rounded-[2rem] font-black uppercase tracking-tight shadow-lg">
                    🔧 Avería
                </button>

                <button class="bg-cyan-500 text-white py-5 rounded-[2rem] font-black uppercase tracking-tight shadow-lg">
                    📍 Desvío
                </button>

            </div>

        </div>

    </div>

    <!-- LEAFLET -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const map = L.map('map', {
                zoomControl:false
            }).setView([4.8621, -74.0335], 13);

            L.tileLayer(
                'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'
            ).addTo(map);

            // BUS
            const busIcon = L.divIcon({
                html: `
                    <div class="w-5 h-5 bg-cyan-400 rounded-full border-4 border-white shadow-xl animate-pulse"></div>
                `,
                className: '',
                iconSize:[20,20]
            });

            const marker = L.marker(
                [4.8621, -74.0335],
                { icon: busIcon }
            ).addTo(map);

            marker.bindPopup('Unidad Activa');

            // RUTA DEMO
            const ruta = [
                [4.8621,-74.0335],
                [4.8680,-74.0400],
                [4.8730,-74.0480],
                [4.8790,-74.0550]
            ];

            L.polyline(ruta,{
                color:'#06b6d4',
                weight:5,
                opacity:0.9
            }).addTo(map);

        });

    </script>

</x-app-layout>