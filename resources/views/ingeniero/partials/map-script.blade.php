{{-- resources/views/ing/partials/map-script.blade.php --}}
<script>
let map = null;
let currentFilter = 'all';
let mapLayersLoaded = false;

const mapContainer = document.getElementById('mapPlano');
mapboxgl.accessToken = 'pk.eyJ1Ijoicm9qYXNkZXYiLCJhIjoiY21leDF4N2JtMTI0NTJrcHlsdjBiN2Y3YiJ9.RB87H34djrYH3WrRa-12Pg';

// ===========================================
// ESTILOS COMPLETOS (actualizados para vértices)
// ===========================================
const style = document.createElement('style');
style.textContent = `
    .modern-lote-popup {max-width:300px!important;font-family:'Roboto',sans-serif;border-radius:12px;box-shadow:0 8px 32px rgba(0,0,0,.2);border:none;overflow:hidden}
    .popup-card {background:white;border-radius:12px;overflow:hidden}
    .popup-header {padding:14px 14px 10px;background:linear-gradient(135deg,#185cdd 0%,#4facfe 50%,#90ceff 100%);color:white;position:relative}
    .lote-number {font-size:18px;font-weight:700;letter-spacing:-0.5px}
    .status-badge {padding:3px 8px;border-radius:10px;font-size:9px;font-weight:600;text-transform:uppercase;backdrop-filter:blur(10px);background:rgba(255,255,255,.2);border:1px solid rgba(255,255,255,.3)}
    .status-disponible {background:rgba(76,175,80,.9)!important}
    .status-apartado {background:rgba(255,152,0,.9)!important}
    .status-vendido {background:rgba(244,67,54,.9)!important}
    .popup-zona {padding:4px 8px;border-radius:6px;font-size:10px;font-weight:600;margin:6px 0;display:inline-flex;align-items:center;gap:4px;box-shadow:0 2px 6px rgba(0,0,0,.1)}
    .popup-subtitle {font-size:11px;opacity:0.9;display:flex;align-items:center;gap:5px}
    .popup-content {padding:12px}
    .popup-grid {display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:12px}
    .info-item {display:flex;flex-direction:column;gap:2px}
    .info-item.compact {grid-column:1/-1;background:#f8f9fa;padding:8px;border-radius:6px;border-left:3px solid #2196f3}
    .info-item strong {font-size:13px;font-weight:600;color:#333}
    .info-item.compact strong {color:#1976d2;font-size:14px}
    .measures-grid {display:grid;grid-template-columns:1fr 1fr;gap:5px;margin-top:8px}
    .measure {padding:4px 6px;background:#f5f5f5;border-radius:4px;font-size:10px;font-weight:500;text-align:center;color:#555}
    .vertex-info {margin-top:12px;padding:8px;background:#f8f9fa;border-radius:6px;border-left:3px solid #ff9800}
    .vertex-list {max-height:120px;overflow-y:auto;margin-top:6px}
    .vertex-item {display:flex;justify-content:space-between;padding:4px 6px;font-size:10px;border-bottom:1px solid #e0e0e0}
    .vertex-item:last-child {border-bottom:none}
    .vertex-coords {font-family:'Courier New',monospace;color:#666}
    .map-controls {position:absolute;top:50%;right:16px;transform:translateY(-50%);display:flex;flex-direction:column;gap:6px;z-index:2}
    .ctrl-btn {width:40px;height:40px;background:white;border:none;border-radius:10px;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 3px 12px rgba(0,0,0,.15);transition:all .2s;font-size:14px}
    .ctrl-btn:hover {transform:translateY(-1px);box-shadow:0 5px 15px rgba(0,0,0,.2)}
    .toggle-3d.active {background:#2196f3;color:white}
    .toggle-vertices.active {background:#4caf50;color:white}
    @media (max-width:768px) {.modern-lote-popup{max-width:260px!important}.popup-grid{grid-template-columns:1fr}}
`;
document.head.appendChild(style);

// ===========================================
// CONSTANTES DE ZONAS (con iconos)
// ===========================================
const ZONA_STYLES = {
    'zona oro':     { color: '#ffd700', dash: [6,3],   name: 'Oro',     gradient: 'linear-gradient(135deg,#fff9c4,#ffd700)', icon: '' },
    'zona plata':   { color: '#c0c0c0', dash: [4,4],   name: 'Plata',   gradient: 'linear-gradient(135deg,#f5f5f5,#c0c0c0)', icon: ' ' },
    'zona bronce':  { color: '#cd7f32', dash: [8,2,2,2], name: 'Bronce', gradient: 'linear-gradient(135deg,#ffe0b2,#cd7f32)', icon: ' ' },
    'zona premium': { color: '#9c27b0', dash: [10,3],  name: 'Premium', gradient: 'linear-gradient(135deg,#e1bee7,#9c27b0)', icon: '' },
    'zona estandar':{ color: '#757575', dash: [3,3],   name: 'Estándar',gradient: 'linear-gradient(135deg,#f5f5f5,#757575)', icon: '' }
};

// ===========================================
// INICIALIZAR MAPA
// ===========================================
window.initializeMap = function() {
    if (map) map.remove();
    map = new mapboxgl.Map({
        container: 'mapPlano',
        style: 'mapbox://styles/mapbox/satellite-streets-v12',
        center: [-96.778, 15.7345],
        zoom: 18,
        pitch: 0,
        bearing: 0,
        antialias: true
    });

    map.on('load', () => {
        map.resize();
        initMapControls();
    });

    map.on('style.load', () => map.resize());
};

// ===========================================
// PROCESAR GEOJSON + ENRIQUECER + DIBUJAR LOTES
// ===========================================
window.processGeoJSONData = function(geoJsonData) {
    if (!map || !geoJsonData?.features?.length) return;

    let features = [...geoJsonData.features];

    // Perímetro del fraccionamiento
    if (features[0]?.properties?.lote === "Fraccionamiento") {
        const perimetro = features.shift();
        map.addSource('frac-source', {type:'geojson', data: perimetro});
        map.addLayer({id:'frac-fill',type:'fill',source:'frac-source',paint:{'fill-color':'#1f2937','fill-opacity':0.85}});
        map.addLayer({id:'frac-border',type:'line',source:'frac-source',paint:{'line-color':'#fff','line-width':3}});
    }

    // Enriquecer con datos del servidor
    fetch(`/asesor/fraccionamiento/${window.AppConfig.fraccionamientoId}/lotes`)
        .then(r => r.json())
        .then(res => {
            const lotesMap = {};
            res.lotes.forEach(l => lotesMap[l.numeroLote] = l);

            features.forEach(f => {
                const sl = lotesMap[f.properties.lote] || {};
                const zona = (sl.zona?.nombre || '').toLowerCase().trim();
                Object.assign(f.properties, {
                    estatus: sl.estatus || 'disponible',
                    manzana: sl.manzana || 'N/A',
                    area_metros: parseFloat(sl.medidas?.area_metros || 0).toFixed(2),
                    precio_m2: parseFloat(sl.precio_m2 || 0).toFixed(2),
                    costo_total: parseFloat(sl.costo_total || 0).toFixed(2),
                    norte: sl.medidas?.norte || 'N/A',
                    sur: sl.medidas?.sur || 'N/A',
                    oriente: sl.medidas?.oriente || 'N/A',
                    poniente: sl.medidas?.poniente || 'N/A',
                    zona
                });
            });

            addLotesToMap({type:"FeatureCollection", features});
        })
        .catch(() => addLotesToMap({type:"FeatureCollection", features})); // fallback
};

// ===========================================
// AÑADIR LOTES AL MAPA (con vértices en lugar de relleno)
// ===========================================
function addLotesToMap(data) {
    if (!map) return;

    // Limpiar capas anteriores
    ['lotes-vertices','lotes-borders','lotes-labels','lotes-centers'].forEach(id => {
        if (map.getLayer(id)) map.removeLayer(id);
    });
    if (map.getSource('lotes')) map.removeSource('lotes');

    map.addSource('lotes', {type:'geojson', data});

    // Crear capa de vértices (puntos en las esquinas de los lotes)
    map.addLayer({
        id: 'lotes-vertices',
        type: 'circle',
        source: 'lotes',
        paint: {
            'circle-radius': 6,
            'circle-color': '#ff6b35',
            'circle-stroke-width': 2,
            'circle-stroke-color': '#ffffff'
        }
    });

    // Bordes por zona (con dasharray personalizado)
    map.addLayer({
        id: 'lotes-borders',
        type: 'line',
        source: 'lotes',
        paint: {
            'line-color': ['case',
                ['==',['get','zona'],'zona oro'],'#ffd700',
                ['==',['get','zona'],'zona plata'],'#c0c0c0',
                ['==',['get','zona'],'zona bronce'],'#cd7f32',
                ['==',['get','zona'],'zona premium'],'#9c27b0',
                ['==',['get','zona'],'zona estandar'],'#757575',
                '#ffffff'
            ],
            'line-width': 3.5,
            'line-dasharray': ['case',
                ['==',['get','zona'],'zona oro'],     ['literal', ZONA_STYLES['zona oro'].dash],
                ['==',['get','zona'],'zona plata'],   ['literal', ZONA_STYLES['zona plata'].dash],
                ['==',['get','zona'],'zona bronce'],  ['literal', ZONA_STYLES['zona bronce'].dash],
                ['==',['get','zona'],'zona premium'], ['literal', ZONA_STYLES['zona premium'].dash],
                ['==',['get','zona'],'zona estandar'],['literal', ZONA_STYLES['zona estandar'].dash],
                ['literal',[1,0]]
            ]
        }
    });

    // Etiquetas en el centro de cada lote
    map.addLayer({
        id: 'lotes-labels',
        type: 'symbol',
        source: 'lotes',
        layout: {
            'text-field':['get','lote'],
            'text-size':14,
            'text-font':['Open Sans Bold','Arial Unicode MS Bold'],
            'text-allow-overlap': true
        },
        paint: {
            'text-color':'#fff',
            'text-halo-color':'#000',
            'text-halo-width':2
        }
    });

    // Punto central de cada lote (opcional, para mejor interacción)
    map.addLayer({
        id: 'lotes-centers',
        type: 'circle',
        source: 'lotes',
        paint: {
            'circle-radius': 0, // Invisible pero clickeable
            'circle-opacity': 0
        }
    });

    mapLayersLoaded = true;

    // Zoom automático
    const bounds = new mapboxgl.LngLatBounds();
    data.features.forEach(f => {
        // Para polígonos, usar todas las coordenadas
        if (f.geometry.type === 'Polygon') {
            f.geometry.coordinates[0].forEach(c => bounds.extend(c));
        }
        // Para MultiPolygon, usar todas las coordenadas de todos los polígonos
        else if (f.geometry.type === 'MultiPolygon') {
            f.geometry.coordinates.forEach(polygon => {
                polygon[0].forEach(c => bounds.extend(c));
            });
        }
    });
    map.fitBounds(bounds, {padding:60, duration:1500, maxZoom:20});

    // Popup mejorado con información de vértices
    const popup = new mapboxgl.Popup({closeButton:true, closeOnClick:false, className:'modern-lote-popup', anchor:'left'});
    
    // Click en el área central del lote
    map.on('click', 'lotes-centers', e => {
        const p = e.features[0].properties;
        const geometry = e.features[0].geometry;
        
        // Extraer coordenadas de vértices
        let vertices = [];
        if (geometry.type === 'Polygon') {
            vertices = geometry.coordinates[0].slice(0, -1); // Excluir el último punto (igual al primero)
        } else if (geometry.type === 'MultiPolygon') {
            vertices = geometry.coordinates[0][0].slice(0, -1);
        }
        
        const zona = ZONA_STYLES[p.zona] || null;
        const zonaTag = zona ? `<div class="popup-zona" style="background:${zona.gradient};color:#1f2937;border:1px solid ${zona.color}">${zona.icon} Zona ${zona.name}</div>` : '';

        // Generar lista de vértices
        const verticesList = vertices.map((vertex, index) => `
            <div class="vertex-item">
                <span>Vértice ${index + 1}</span>
                <span class="vertex-coords">${vertex[1].toFixed(6)}, ${vertex[0].toFixed(6)}</span>
            </div>
        `).join('');

        popup.setLngLat(e.lngLat).setHTML(`
            <div class="popup-card">
                <div class="popup-header">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start">
                        <span class="lote-number">Lote ${p.lote}</span>
                        <span class="status-badge ${p.estatus==='disponible'?'status-disponible':p.estatus==='vendido'?'status-vendido':'status-apartado'}">
                            ${p.estatus==='disponible'?'Disponible':p.estatus==='vendido'?'Vendido':'Apartado'}
                        </span>
                    </div>
                    ${zonaTag}
                    <div class="popup-subtitle">Manzana ${p.manzana}</div>
                </div>
                <div class="popup-content">
                    <div class="popup-grid">
                        <div class="info-item"><span class="icon">Área</span><strong>${p.area_metros} m²</strong></div>
                        <div class="info-item"><span class="icon">Precio m²</span><strong>$${p.precio_m2}</strong></div>
                        <div class="info-item compact"><strong>$${parseFloat(p.costo_total).toLocaleString('es-MX')}</strong><span class="icon">Total</span></div>
                    </div>
                    <div class="measures-grid">
                        <div class="measure north">N ${p.norte}m</div>
                        <div class="measure south">S ${p.sur}m</div>
                        <div class="measure east">E ${p.oriente}m</div>
                        <div class="measure west">O ${p.poniente}m</div>
                    </div>
                    <div class="vertex-info">
                        <strong>Vértices del Lote (${vertices.length} puntos)</strong>
                        <div class="vertex-list">
                            ${verticesList}
                        </div>
                    </div>
                </div>
            </div>
        `).addTo(map);
    });

    // Interacción con los vértices
    map.on('click', 'lotes-vertices', e => {
        const p = e.features[0].properties;
        const coordinates = e.lngLat;
        
        popup.setLngLat(coordinates).setHTML(`
            <div class="popup-card">
                <div class="popup-header">
                    <span class="lote-number">Vértice Lote ${p.lote}</span>
                </div>
                <div class="popup-content">
                    <div class="info-item compact">
                        <strong>Coordenadas del Vértice</strong>
                        <span>Lat: ${coordinates.lat.toFixed(6)}</span>
                        <span>Lng: ${coordinates.lng.toFixed(6)}</span>
                    </div>
                </div>
            </div>
        `).addTo(map);
    });

    map.on('mouseenter', ['lotes-centers', 'lotes-vertices'], () => map.getCanvas().style.cursor = 'pointer');
    map.on('mouseleave', ['lotes-centers', 'lotes-vertices'], () => map.getCanvas().style.cursor = '');
}

// ===========================================
// CONTROLES FLOTANTES (3D, zoom, vértices)
// ===========================================
function initMapControls() {
    const controls = document.createElement('div');
    controls.className = 'map-controls';
    controls.innerHTML = `
        <button class="ctrl-btn zoom-in" title="Acercar">
            <span class="material-icons">zoom_in</span>
        </button>
        <button class="ctrl-btn zoom-out" title="Alejar">
            <span class="material-icons">zoom_out</span>
        </button>
        <button class="ctrl-btn compass" title="Norte arriba">
            <span class="material-icons">explore</span>
        </button>
        <button class="ctrl-btn toggle-3d" title="Vista 3D">
            <span class="material-icons">3d_rotation</span>
        </button>
        <button class="ctrl-btn toggle-vertices" title="Mostrar/ocultar vértices">
            <span class="material-icons">location_on</span>
        </button>
    `;
    mapContainer.appendChild(controls);

    controls.querySelector('.zoom-in').onclick = () => map.zoomIn();
    controls.querySelector('.zoom-out').onclick = () => map.zoomOut();
    controls.querySelector('.compass').onclick = () => map.easeTo({bearing:0,pitch:0,duration:1000});
    
    controls.querySelector('.toggle-3d').onclick = () => {
        const btn = controls.querySelector('.toggle-3d');
        if (btn.querySelector('span').textContent === '3d_rotation') {
            if (!map.getSource('mapbox-dem')) {
                map.addSource('mapbox-dem', {type:'raster-dem', url:'mapbox://mapbox.mapbox-terrain-dem-v1'});
            }
            map.setTerrain({source:'mapbox-dem', exaggeration:1.5});
            map.easeTo({pitch:60, bearing:-17, duration:1200});
            btn.querySelector('span').textContent = 'landscape';
            btn.classList.add('active');
        } else {
            map.setTerrain(null);
            map.easeTo({pitch:0, bearing:0, duration:1200});
            btn.querySelector('span').textContent = '3d_rotation';
            btn.classList.remove('active');
        }
    };

    // Control para mostrar/ocultar vértices
    controls.querySelector('.toggle-vertices').onclick = () => {
        const btn = controls.querySelector('.toggle-vertices');
        const isVisible = map.getLayoutProperty('lotes-vertices', 'visibility') !== 'none';
        
        if (isVisible) {
            map.setLayoutProperty('lotes-vertices', 'visibility', 'none');
            btn.classList.remove('active');
        } else {
            map.setLayoutProperty('lotes-vertices', 'visibility', 'visible');
            btn.classList.add('active');
        }
    };
}
</script>