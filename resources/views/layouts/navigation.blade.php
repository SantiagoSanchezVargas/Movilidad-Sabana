<nav class="bg-[#001529] border-b-4 border-cyan-400 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo Nuevo: Sin rastro de Laravel -->
                <div class="flex-shrink-0 flex items-center">
                    <div class="bg-cyan-500 p-1.5 rounded-lg mr-2 shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                        </svg>
                    </div>
                    <span class="text-white font-black text-xl tracking-tighter uppercase italic">Mov<span class="text-cyan-400">Sabana</span></span>
                </div>
                
                <!-- Links con el color cian activo -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex text-[11px] font-black uppercase tracking-widest">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white border-cyan-400">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <!-- Agrega aquí tus rutas de Vehículos, Conductores, etc -->
                </div>
            </div>

            <!-- Menú de Usuario -->
            <div class="flex items-center gap-4">
                <div class="flex items-center text-gray-300">
                    @if(auth()->user()->hasRole('administrador'))
                        <span class="text-[10px] font-black mr-3 uppercase border border-red-500 px-2 py-1 rounded text-red-400">Admin</span>
                    @elseif(auth()->user()->hasRole('conductor'))
                        <span class="text-[10px] font-black mr-3 uppercase border border-blue-500 px-2 py-1 rounded text-blue-400">Conductor</span>
                    @else
                        <span class="text-[10px] font-black mr-3 uppercase border border-cyan-400 px-2 py-1 rounded text-cyan-400">Usuario</span>
                    @endif
                    <span class="text-xs font-bold">{{ Auth::user()->nombre }}</span>
                </div>

                <!-- Botón Logout -->
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-[10px] font-black uppercase px-3 py-1.5 rounded transition-all duration-200">
                        🚪 Salir
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>