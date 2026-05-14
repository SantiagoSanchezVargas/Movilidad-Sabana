<x-app-layout>

    <!-- Leaflet CSS - DEBE ir en el head -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />

    <x-slot name="header">
        <h2 class="font-black text-3xl text-slate-800 tracking-tighter uppercase">
            Crear Nueva Ruta
            <span class="text-cyan-500">MovSabana</span>
        </h2>
        <p class="text-sm text-slate-500 font-bold">Define la ruta y sus paradas en el mapa</p>
    </x-slot>

    <div class="py-10 bg-gradient-to-br from-slate-100 via-white to-cyan-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <form action="{{ route('admin.rutas.store') }}" method="POST" class="space-y-8">
                @csrf

                <!-- Datos Básicos -->
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h3 class="text-2xl font-bold mb-6 text-slate-800">📋 Datos de la Ruta</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Código -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Código de Ruta</label>
                            <input type="text" name="codigo" placeholder="ej: R-101" 
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                                   value="{{ old('codigo') }}" required>
                            @error('codigo')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nombre -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nombre de la Ruta</label>
                            <input type="text" name="nombre" placeholder="ej: Chía - Centro Bogotá" 
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                                   value="{{ old('nombre') }}" required>
                            @error('nombre')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Origen -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Origen</label>
                            <input type="text" name="origen" placeholder="ej: Terminal Chía" 
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                                   value="{{ old('origen') }}">
                        </div>

                        <!-- Destino -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Destino</label>
                            <input type="text" name="destino" placeholder="ej: Centro Comercial Bogotá" 
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                                   value="{{ old('destino') }}">
                        </div>

                        <!-- Distancia -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Distancia (km)</label>
                            <input type="number" name="distancia_km" placeholder="ej: 25.5" step="0.1"
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                                   value="{{ old('distancia_km') }}">
                        </div>

                        <!-- Duración -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Duración Estimada</label>
                            <input type="text" name="duracion_estimada" placeholder="ej: 45 min" 
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                                   value="{{ old('duracion_estimada') }}">
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="mt-6">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Descripción</label>
                        <textarea name="descripcion" rows="3" placeholder="Describe la ruta..."
                                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">{{ old('descripcion') }}</textarea>
                    </div>
                </div>

                <!-- Mapa para agregar paradas -->
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h3 class="text-2xl font-bold mb-6 text-slate-800">🗺️ Agregar Paradas en el Mapa</h3>
                    
                    <p class="text-slate-600 mb-4 text-sm">
                        <strong>Instrucciones:</strong> Haz clic en el mapa para agregar paradas. Puedes moverlas y eliminarlas.
                    </p>

                    <!-- Mapa - CONTENEDOR CON ALTURA FIJA -->
                    <div style="height: 400px; width: 100%; border: 2px solid #e2e8f0; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                        <div id="map" style="width: 100%; height: 100%;"></div>
                    </div>

                    <!-- Lista de Paradas -->
                    <div class="mt-6">
                        <h4 class="font-bold text-slate-800 mb-3">Paradas Agregadas:</h4>
                        <div id="paradas-list" class="space-y-2 max-h-48 overflow-y-auto">
                            <p class="text-slate-500 text-sm">No hay paradas aún. Haz clic en el mapa para agregar.</p>
                        </div>
                    </div>

                    <!-- Input oculto para guardar paradas -->
                    <input type="hidden" name="paradas" id="paradas-input" value="[]">
                </div>

                <!-- Botones -->
                <div class="flex gap-4 justify-end">
                    <a href="{{ route('admin.rutas.index') }}" 
                       class="px-6 py-3 border-2 border-slate-300 text-slate-700 rounded-lg font-bold hover:bg-slate-50 transition">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-cyan-600 text-white rounded-lg font-bold hover:bg-cyan-700 transition">
                        ✅ Crear Ruta
                    </button>
                </div>

            </form>

        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

    <script>
        // Inicializar mapa (centrado en Chía, Cundinamarca)
        const map = L.map('map').setView([4.8604, -74.0447], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap',
            maxZoom: 19
        }).addTo(map);

        let paradas = [];
        let markers = [];

        // Agregar parada al hacer clic en el mapa
        map.on('click', function(e) {
            const { lat, lng } = e.latlng;
            const numero = paradas.length + 1;

            // Crear parada
            // Dentro de map.on('click', function(e) { ... })

            const parada = {
                nombre: `Parada ${numero}`,
                lat: lat.toFixed(6),
                lng: lng.toFixed(6),
                tarifa: 0,
                // CAMBIO CRUCIAL: 'regular' no existe en tu CHECK de Postgres
                // La primera es salida, las demás intermedias (lógica simple para evitar errores)
                tipo: (paradas.length === 0) ? 'salida' : 'intermedia', 
                ubicacion: '', 
                descripcion: '',
                radio: 50,
                obligatoria: false
            };

            paradas.push(parada);

            // Crear marcador
            const marker = L.marker([lat, lng], {
                draggable: true
            })
            .bindPopup(`
    <div class="text-sm p-2">
        <p class="font-bold mb-2">${parada.nombre}</p>
        <label class="block text-xs font-bold text-slate-600">Tipo de Parada:</label>
        <select onchange="cambiarTipo(${numero - 1}, this.value)" class="w-full px-2 py-1 border rounded mb-2 text-xs">
            <option value="intermedia">Intermedia</option>
            <option value="salida">Salida</option>
            <option value="destino">Destino</option>
        </select>
        <button onclick="eliminarParada(${numero - 1})" class="w-full bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600 transition">
            Eliminar Parada
        </button>
    </div>
`)
            .addTo(map);

            // Actualizar posición al mover
            marker.on('dragend', function() {
                const newLat = marker.getLatLng().lat.toFixed(6);
                const newLng = marker.getLatLng().lng.toFixed(6);
                paradas[paradas.length - 1].lat = newLat;
                paradas[paradas.length - 1].lng = newLng;
                actualizarInput();
            });

            markers.push(marker);
            actualizarLista();
        });

        function actualizarLista() {
            const listDiv = document.getElementById('paradas-list');
            
            if (paradas.length === 0) {
                listDiv.innerHTML = '<p class="text-slate-500 text-sm">No hay paradas aún.</p>';
                return;
            }

            listDiv.innerHTML = paradas.map((p, i) => `
                <div class="flex items-center justify-between bg-slate-100 p-3 rounded-lg">
                    <div class="flex-1">
                        <p class="font-bold text-sm text-slate-800">${i + 1}. ${p.nombre}</p>
                        <p class="text-xs text-slate-600">📍 ${p.lat}, ${p.lng}</p>
                    </div>
                    <button type="button" onclick="eliminarParada(${i})" class="bg-red-500 text-white px-3 py-1 rounded text-xs">
                        ✕
                    </button>
                </div>
            `).join('');

            actualizarInput();
        }

        function eliminarParada(index) {
            paradas.splice(index, 1);
            markers[index].removeFrom(map);
            markers.splice(index, 1);
            actualizarLista();
        }

        function actualizarInput() {
            document.getElementById('paradas-input').value = JSON.stringify(paradas);
        }
        function cambiarTipo(index, nuevoTipo) {
            paradas[index].tipo = nuevoTipo;
            actualizarInput();
        }
    </script>

</x-app-layout>