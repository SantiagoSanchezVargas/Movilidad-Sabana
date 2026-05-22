<!DOCTYPE html>
<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>MovSabana - Sistema de Movilidad</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
       
    </head>
    <body class="font-sans antialiased">
        <!-- Navbar -->
        <nav class="bg-[#001529] border-b-4 border-cyan-400 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <div class="bg-cyan-500 p-1.5 rounded-lg mr-2 shadow-sm">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                </svg>
                            </div>
                            <span class="text-white font-black text-xl tracking-tighter uppercase italic">Mov<span class="text-cyan-400">Sabana</span></span>
                        </div>
                    </div>
                    
                    <!-- Botones de Autenticación -->
                    <div class="flex items-center gap-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-cyan-400 hover:text-cyan-300 font-black text-sm uppercase transition">
                                    → Dashboard
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-[10px] font-black uppercase px-3 py-2 rounded transition">
                                        🚪 Salir
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="text-cyan-400 hover:text-cyan-300 font-black text-sm uppercase transition">
                                    Iniciar Sesión
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-cyan-600 hover:bg-cyan-700 text-white font-black text-sm uppercase px-4 py-2 rounded transition">
                                        Registrarse
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-cyan-900 flex items-center justify-center px-4">
            <div class="max-w-4xl mx-auto text-center">
                <!-- Title -->
                <div class="mb-12">
                    <h2 class="text-6xl md:text-7xl font-black text-white mb-6 tracking-tighter">
                        Bienvenido a <span class="text-cyan-500">MovSabana</span>
                    </h2>
                    <p class="text-xl md:text-2xl text-gray-300">
                        Sistema integral de movilidad para la Sabana de Bogotá
                    </p>
                </div>

                <!-- Features Grid -->
                <div class="grid md:grid-cols-3 gap-6 mb-12">
                    <!-- Card 1 -->
                    <div class="bg-slate-800/50 backdrop-blur rounded-lg p-8 border border-cyan-500/20 hover:border-cyan-500 transition duration-300 group">
                        <div class="text-5xl mb-4 transform group-hover:scale-110 transition">🗺️</div>
                        <h3 class="text-3xl font-black text-white mb-3">Rutas en Tiempo Real</h3>
                        <p class="text-white-400">Consulta rutas disponibles y ubicaciones de buses en tu zona</p>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-slate-800/50 backdrop-blur rounded-lg p-8 border border-cyan-500/20 hover:border-cyan-500 transition duration-300 group">
                        <div class="text-5xl mb-4 transform group-hover:scale-110 transition">📊</div>
                        <h3 class="text-3xl font-black text-white mb-3">Gestión Administrativa</h3>
                        <p class="text-white-400">Panel de control completo para administradores del sistema</p>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-slate-800/50 backdrop-blur rounded-lg p-8 border border-cyan-500/20 hover:border-cyan-500 transition duration-300 group">
                        <div class="text-5xl mb-4 transform group-hover:scale-110 transition">🚗</div>
                        <h3 class="text-3xl font-black text-white mb-3">Para Conductores</h3>
                        <p class="text-white-400">Control de rutas, alertas GPS y reportes en tiempo real</p>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-cyan-600 hover:bg-cyan-700 text-white px-8 py-4 rounded-lg font-bold text-lg transition duration-200 shadow-lg">
                            → Ir al Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-3xl font-black text-black mb-3">
                            🔐 Iniciar Sesión
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="border-2 border-cyan-600 hover:bg-cyan-600/10 text-cyan-400 px-8 py-4 rounded-lg font-bold text-lg transition duration-200">
                                📝 Registrarse
                            </a>
                        @endif
                    @endauth
                </div>

                <!-- Info Footer -->
                <div class="text-gray-400 text-sm border-t border-gray-700 pt-8">
                    <p class="mb-2">Sistema de Movilidad Sostenible</p>
                    <p>Chía, Cundinamarca | 2026</p>
                </div>
            </div>
        </div>
    </body>
</html>