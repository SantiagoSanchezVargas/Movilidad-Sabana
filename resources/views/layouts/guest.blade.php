<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
    </head>
    <body class="font-sans text-gray-900 antialiased">
       <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#0f172a]"> <!-- Fondo oscuro -->
    <div>
        <!-- Reemplazamos el logo de Laravel por un texto estilizado -->
        <a href="/">
            <span class="text-3xl font-bold text-white tracking-tight">
                Mov<span class="text-indigo-500">Sabana</span>
            </span>
        </a>
    </div>

    <!-- Tarjeta con el color del dashboard -->
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-[#1e293b] shadow-2xl overflow-hidden sm:rounded-lg border border-slate-700">
        {{ $slot }}
    </div>
</div>
    </body>
</html>
