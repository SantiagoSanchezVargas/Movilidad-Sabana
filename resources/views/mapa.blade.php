<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MovSabana PRO</title>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<style>

*{
    box-sizing:border-box;
}

body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:#eef2f7;
    overflow:hidden;
}

.wrapper{
    display:grid;
    grid-template-columns:380px 1fr;
    height:100vh;
}

/* ==========================================
   SIDEBAR
========================================== */

.sidebar{
    background:linear-gradient(180deg,#0f172a,#1e293b);
    color:white;
    padding:24px;
    overflow-y:auto;
    display:flex;
    flex-direction:column;
}

.sidebar::-webkit-scrollbar{
    width:8px;
}

.sidebar::-webkit-scrollbar-thumb{
    background:#334155;
    border-radius:10px;
}

.logo{
    margin-bottom:20px;
}

.logo h1{
    margin:0;
    font-size:30px;
    font-weight:800;
}

.logo p{
    margin-top:6px;
    color:#cbd5e1;
    font-size:14px;
}

/* ==========================================
   STATUS
========================================== */

.status{
    padding:14px;
    border-radius:14px;
    font-weight:600;
    margin-bottom:18px;
    background:#1e40af;
    color:white;
    box-shadow:0 8px 20px rgba(0,0,0,.18);
}

/* ==========================================
   BOTÓN
========================================== */

.btn{
    width:100%;
    border:none;
    padding:15px;
    border-radius:14px;
    cursor:pointer;
    font-size:16px;
    font-weight:700;
    background:linear-gradient(135deg,#3b82f6,#2563eb);
    color:white;
    margin-bottom:20px;
    transition:.25s;
    box-shadow:0 8px 18px rgba(37,99,235,.35);
}

.btn:hover{
    transform:translateY(-2px);
    box-shadow:0 12px 22px rgba(37,99,235,.45);
}

/* ==========================================
   CARDS RUTAS
========================================== */

.route-card{
    background:white;
    color:#111827;
    border-radius:18px;
    padding:18px;
    margin-bottom:16px;
    cursor:pointer;
    transition:.25s;
    border:2px solid transparent;
    box-shadow:0 6px 14px rgba(0,0,0,.08);
}

.route-card:hover{
    transform:translateY(-3px);
    box-shadow:0 14px 30px rgba(0,0,0,.14);
}

.route-card.best{
    border:2px solid #22c55e;
    background:#f0fdf4;
}

.route-name{
    font-weight:800;
    font-size:18px;
    margin-bottom:10px;
}

.route-distance{
    color:#475569;
    font-size:14px;
    margin-bottom:10px;
}

.route-meta{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    margin-top:8px;
}

.badge{
    background:#eff6ff;
    color:#1d4ed8;
    padding:8px 12px;
    border-radius:999px;
    font-size:13px;
    font-weight:700;
}

.badge.green{
    background:#dcfce7;
    color:#166534;
}

.best-label{
    margin-top:12px;
    color:#16a34a;
    font-weight:800;
    font-size:14px;
}

/* ==========================================
   MAPA
========================================== */

#map{
    width:100%;
    height:100%;
    z-index:1;
}

/* ==========================================
   PANEL DETALLE
========================================== */

.route-details{
    margin-top:20px;
    background:rgba(255,255,255,.08);
    border:1px solid rgba(255,255,255,.1);
    padding:16px;
    border-radius:18px;
    backdrop-filter:blur(10px);
}

.route-details h3{
    margin-top:0;
    font-size:18px;
}

.paradas{
    margin-top:10px;
    max-height:180px;
    overflow:auto;
}

.parada-item{
    padding:10px;
    border-radius:10px;
    background:rgba(255,255,255,.06);
    margin-bottom:8px;
    font-size:14px;
}

/* ==========================================
   RESPONSIVE
========================================== */

@media(max-width:900px){

    .wrapper{
        grid-template-columns:1fr;
    }

    #map{
        height:55vh;
    }

    .sidebar{
        height:45vh;
    }
}

</style>
</head>

<body>

<div class="wrapper">

    <div class="sidebar">

       <div class="logo">
            <h1>🚌 MovSabana</h1>
            <p>Movilidad inteligente para la Sabana de Bogotá</p>
        </div>
        <div id="status" class="status">
            Presiona buscar rutas.
        </div>

        <button class="btn" onclick="geolocalizar()">
            🎯 Buscar rutas cercanas
        </button>

        <div id="routesList"></div>

    </div>

    <div id="map"></div>

</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>

/* ==========================================
   VARIABLES
========================================== */
let map;
let userMarker = null;
let routeLine = null;
let routeMarkers = [];
let rutasEncontradas = [];

/* ==========================================
   ICONOS
========================================== */
const blueIcon = new L.Icon({
    iconUrl:'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
    iconSize:[25,41],
    iconAnchor:[12,41]
});

const greenIcon = new L.Icon({
    iconUrl:'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
    iconSize:[25,41],
    iconAnchor:[12,41]
});

const redIcon = new L.Icon({
    iconUrl:'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
    iconSize:[25,41],
    iconAnchor:[12,41]
});

/* ==========================================
   INICIO
========================================== */
document.addEventListener('DOMContentLoaded',()=>{

    map = L.map('map').setView([4.8621,-74.0335],11);

    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            attribution:'© OpenStreetMap'
        }
    ).addTo(map);

});

/* ==========================================
   STATUS
========================================== */
function setStatus(msg,color='info'){
    const box = document.getElementById('status');
    box.innerHTML = msg;

    if(color==='error'){
        box.style.background='#ffebee';
        box.style.color='#b71c1c';
    }else if(color==='success'){
        box.style.background='#e8f5e9';
        box.style.color='#1b5e20';
    }else{
        box.style.background='#e3f2fd';
        box.style.color='#0d47a1';
    }
}

/* ==========================================
   GEOLOCALIZAR
========================================== */
function geolocalizar(){

    if(!navigator.geolocation){
        setStatus('Tu navegador no soporta GPS','error');
        return;
    }

    setStatus('📍 Obteniendo ubicación...');

    navigator.geolocation.getCurrentPosition(
        async(pos)=>{

            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;

            if(userMarker){
                userMarker.setLatLng([lat,lng]);
            }else{
                userMarker = L.marker([lat,lng],{icon:blueIcon})
                    .addTo(map)
                    .bindPopup('Tu ubicación');
            }

            map.setView([lat,lng],14);

            await buscarRutas(lat,lng);

        },
        err=>{
            setStatus(err.message,'error');
        },
        {
            enableHighAccuracy:true,
            timeout:10000
        }
    );
}

/* ==========================================
   BUSCAR RUTAS
========================================== */
async function buscarRutas(lat,lng){

    try{

        setStatus('🔍 Buscando rutas cercanas...');

        const url = new URL('/rutas/cercanas',window.location.origin);
        url.searchParams.set('lat',lat);
        url.searchParams.set('lng',lng);
        url.searchParams.set('radio_km',5);

        const res = await fetch(url);
        const json = await res.json();

        if(!res.ok || !json.success){
            throw new Error(json.error || 'Error servidor');
        }

        rutasEncontradas = json.data;

        renderLista();

        if(rutasEncontradas.length>0){

            const mejor = rutasEncontradas.find(r=>r.mejor_opcion);

            if(mejor){

                const distancia = parseFloat(mejor.distancia_km || 0).toFixed(2);

                setStatus(
                    `⭐ Mejor ruta: ${mejor.nombre}
                    <br>📍 ${distancia} km 
                    | ⏱️ ${mejor.tiempo_estimado || '-'} min 
                    | 💰 $${mejor.precio || '-'}`,
                    'success'
                );

            }else{
                setStatus(`✅ ${rutasEncontradas.length} rutas encontradas`,'success');
            }

        }else{
            setStatus('No se encontraron rutas');
        }

    }catch(error){
        setStatus(error.message,'error');
    }
}

/* ==========================================
   LISTA DE RUTAS
========================================== */
function renderLista(){

    const cont = document.getElementById('routesList');
    cont.innerHTML = '';

    rutasEncontradas.forEach(ruta=>{

        const distancia = parseFloat(ruta.distancia_km || 0);

        const texto =
            distancia < 1
            ? Math.round(distancia*1000)+' m'
            : distancia.toFixed(2)+' km';

        const tiempo = ruta.tiempo_estimado 
            ? `⏱️ ${ruta.tiempo_estimado} min` 
            : '';

        const precio = ruta.precio 
            ? `💰 $${ruta.precio}` 
            : '';

        const div = document.createElement('div');
        div.className = ruta.mejor_opcion
        ? 'route-card best'
        : 'route-card';

        if(ruta.mejor_opcion){
            div.style.border = '2px solid #1a73e8';
            div.style.background = '#e8f0fe';
        }

        div.innerHTML = `
    <div class="route-name">
        ${ruta.nombre}
    </div>

    <div class="route-distance">
        📍 ${texto}
    </div>

    <div class="route-meta">

        <div class="badge">
            ⏱️ ${ruta.tiempo_estimado || '-'} min
        </div>

        <div class="badge green">
            💰 $${ruta.precio || '-'}
        </div>

    </div>

    ${
        ruta.mejor_opcion
        ? '<div class="best-label">⭐ Mejor ruta recomendada</div>'
        : ''
    }
`;
        div.onclick = ()=>dibujarRuta(ruta);

        cont.appendChild(div);

    });
}

/* ==========================================
   DIBUJAR RUTA REAL
========================================== */
async function dibujarRuta(ruta){
    
    try{
        limpiarRuta();

        // 🔥 FIX: convertir JSON si viene como string
        if(typeof ruta.paradas === "string"){
            ruta.paradas = JSON.parse(ruta.paradas);
        }

        // 🔥 VALIDACIÓN
        if(!Array.isArray(ruta.paradas) || ruta.paradas.length < 2){
            setStatus('❌ Ruta sin paradas suficientes','error');
            return;
        }

        // 🔥 ORDENAR (MUY IMPORTANTE)
        ruta.paradas.sort((a,b)=>(a.numero_orden || 0) - (b.numero_orden || 0));

        setStatus('🛣️ Calculando ruta real...');

        // 🔥 MARCAR PARADAS (validando coordenadas)
        ruta.paradas.forEach((p,i)=>{

            if(!p.lat || !p.lng) return; // evita errores

            const marker = L.circleMarker(
                [parseFloat(p.lat), parseFloat(p.lng)],
                {
                    radius:6,
                    color: i === 0 
                        ? 'green' 
                        : i === ruta.paradas.length-1 
                            ? 'red' 
                            : 'orange'
                }
            )
            .addTo(map)
            .bindPopup(p.nombre || 'Parada');

            routeMarkers.push(marker);
        });

        // 🔥 COORDS LIMPIAS
        const coords = ruta.paradas
            .filter(p => p.lat && p.lng)
            .map(p => `${p.lng},${p.lat}`)
            .join(';');

        if(!coords){
            setStatus('❌ No hay coordenadas válidas','error');
            return;
        }

        // 🔥 OSRM
        const url =
        `https://router.project-osrm.org/route/v1/driving/`+
        coords +
        `?overview=full&geometries=geojson`;

        const res = await fetch(url);
        const data = await res.json();

        if(!data.routes || !data.routes.length){
            throw new Error('No se pudo calcular ruta');
        }

        const poly = data.routes[0].geometry.coordinates.map(
            c => [c[1],c[0]]
        );

        routeLine = L.polyline(poly,{
            color: ruta.mejor_opcion ? '#16a34a' : '#1a73e8',
            weight:6,
            opacity:0.9
        }).addTo(map);

        map.fitBounds(routeLine.getBounds(),{padding:[40,40]});

        const km = (data.routes[0].distance/1000).toFixed(2);
        const min = Math.round(data.routes[0].duration/60);

        setStatus(
            `✅ ${ruta.nombre}<br>🛣️ ${km} km | ⏱️ ${min} min`,
            'success'
        );

    }catch(error){
        setStatus(error.message,'error');
    }
}
/* ==========================================
   LIMPIAR
========================================== */
function limpiarRuta(){

    if(routeLine){
        map.removeLayer(routeLine);
        routeLine = null;
    }

    routeMarkers.forEach(m=>map.removeLayer(m));
    routeMarkers = [];
}

</script>

</body>
</html>