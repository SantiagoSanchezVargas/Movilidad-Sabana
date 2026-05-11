<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 py-2">
            <div>
                <h2 class="font-black text-3xl text-slate-800 tracking-tighter uppercase">
                    {{ __('Reportes e Inteligencia') }} <span class="text-rose-500">MovSabana</span>
                </h2>
                <p class="text-sm text-slate-500 font-bold italic">
                    Análisis de cumplimiento y tráfico — Chía, Cundinamarca
                </p>
            </div>
            
            <a href="{{ route('admin.rutas.index') }}" 
               class="inline-flex items-center bg-white border-2 border-slate-200 text-slate-700 px-5 py-2.5 rounded-xl text-sm font-black shadow-sm hover:bg-slate-50 transition-all uppercase tracking-tight">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver al Panel
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- FILTROS DE REPORTE -->
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                <form action="#" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Rango de Fecha</label>
                        <input type="date" class="w-full bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-rose-500/10 font-bold text-slate-700">
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Tipo de Ruta</label>
                        <select class="w-full bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-rose-500/10 font-bold text-slate-700">
                            <option>Todas las rutas</option>
                            <option>Urbanas Chía</option>
                            <option>Intermunicipales</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-2">Formato</label>
                        <select class="w-full bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-rose-500/10 font-bold text-slate-700">
                            <option>PDF Profesional</option>
                            <option>Excel (CSV)</option>
                        </select>
                    </div>

                    <button type="submit" class="bg-rose-500 text-white p-4 rounded-2xl font-black uppercase tracking-tighter shadow-lg shadow-rose-500/30 hover:bg-rose-600 transition-all flex justify-center items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Generar Reporte Ahora
                    </button>
                </form>
            </div>

            <!-- VISTA PREVIA / ESTADÍSTICAS -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card: Rutas más congestionadas -->
                <div class="md:col-span-2 bg-[#001529] rounded-[2.5rem] p-8 text-white shadow-2xl relative overflow-hidden">
                    <div class="relative z-10">
                        <h3 class="text-xl font-black uppercase tracking-tighter mb-6 italic">Puntos Críticos de Retraso</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-4 bg-white/5 rounded-2xl border border-white/10">
                                <span class="font-bold text-slate-300 tracking-tight">Variante Chía - Cundinamarca</span>
                                <span class="text-rose-400 font-black">88% Congestión</span>
                            </div>
                            <div class="flex justify-between items-center p-4 bg-white/5 rounded-2xl border border-white/10">
                                <span class="font-bold text-slate-300 tracking-tight">Anillo Vial Central</span>
                                <span class="text-amber-400 font-black">45% Congestión</span>
                            </div>
                        </div>
                    </div>
                    <!-- Decoración al fondo -->
                    <div class="absolute -right-10 -bottom-10 opacity-10">
                        <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"></path></svg>
                    </div>
                </div>

                <!-- Card: Eficiencia -->
                <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 flex flex-col justify-center items-center text-center shadow-sm">
                    <div class="w-24 h-24 rounded-full border-8 border-emerald-500 border-t-slate-100 flex items-center justify-center mb-4">
                        <span class="text-2xl font-black text-slate-800">92%</span>
                    </div>
                    <h4 class="font-black text-slate-800 uppercase tracking-tighter">Eficiencia de Flota</h4>
                    <p class="text-xs text-slate-400 font-bold italic mt-2">Cumplimiento de horarios en la última semana.</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>