{{-- resources/views/ing/partials/map-script.blade.php --}}
<script>
let map = null;
let currentFilter = 'all';
let mapLayersLoaded = false;
let currentPopup = null; // Variable global para controlar el popup actual

const mapContainer = document.getElementById('mapPlano');
mapboxgl.accessToken = 'pk.eyJ1Ijoicm9qYXNkZXYiLCJhIjoiY21leDF4N2JtMTI0NTJrcHlsdjBiN2Y3YiJ9.RB87H34djrYH3WrRa-12Pg';

// ===========================================
// ESTILOS COMPLETOS (mejorados)
// ===========================================
const style = document.createElement('style');
style.textContent = `
    .modern-lote-popup {max-width:250px!important;font-family:'Roboto',sans-serif;border-radius:12px;box-shadow:0 8px 32px rgba(0,0,0,.2);border:none;overflow:hidden}
    .popup-card {background:white;border-radius:2px;overflow:hidden}
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
    
    /* Nuevos estilos para mejorar apariencia de lotes */
    .lote-fill-disponible {background:rgba(76,175,80,0.15)!important}
    .lote-fill-apartado {background:rgba(255,152,0,0.15)!important}
    .lote-fill-vendido {background:rgba(244,67,54,0.15)!important}
    .legend {position:absolute;bottom:20px;left:20px;background:white;border-radius:12px;padding:15px;box-shadow:0 4px 20px rgba(0,0,0,0.15);z-index:1;font-family:'Roboto',sans-serif;max-width:220px}
    .legend-title {font-size:14px;font-weight:600;margin-bottom:10px;color:#333;display:flex;align-items:center;gap:6px}
    .legend-item {display:flex;align-items:center;margin-bottom:8px;font-size:12px}
    .legend-color {width:16px;height:16px;border-radius:4px;margin-right:8px;border:1px solid rgba(0,0,0,0.1)}
    .legend-zona {display:flex;align-items:center;margin-bottom:6px;font-size:11px}
    .legend-line {width:20px;height:3px;margin-right:8px;border-radius:2px}
`;
document.head.appendChild(style);

// ===========================================
// INICIALIZAR MAPA (con estilo mejorado)
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
        antialias: true,
        maxZoom: 22,
        minZoom: 15
    });

    map.on('load', () => {
        map.resize();
        initMapControls();
        addLegend();
    });

    map.on('style.load', () => map.resize());
};

// ===========================================
// AÑADIR LEYENDA
// ===========================================
function addLegend() {
    const legend = document.createElement('div');
    legend.className = 'legend';
    legend.innerHTML = `
        <div class="legend-title">
            <span class="material-icons" style="font-size:16px">legend_toggle</span>
            Estado de Lotes
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background:#4CAF50;border-color:#4CAF50"></div>
            <span>Disponible</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background:#FF9800;border-color:#FF9800"></div>
            <span>Apartado</span>
        </div>
        <div class="legend-item">
            <div class="legend-color" style="background:#F44336;border-color:#F44336"></div>
            <span>Vendido</span>
        </div>
    `;
    mapContainer.appendChild(legend);
}

// ===========================================
// PROCESAR GEOJSON + ENRIQUECER + DIBUJAR LOTES
// ===========================================
window.processGeoJSONData = function(geoJsonData) {
    if (!map || !geoJsonData?.features?.length) return;

    let features = [...geoJsonData.features];

    // Perímetro del fraccionamiento - COLOR SÓLIDO
    if (features[0]?.properties?.lote === "Fraccionamiento") {
        const perimetro = features.shift();
        map.addSource('frac-source', {type:'geojson', data: perimetro});
        map.addLayer({
            id:'frac-fill',
            type:'fill',
            source:'frac-source',
            paint:{
                'fill-color':'#E8F5E8', // Verde claro sólido
                'fill-opacity':1, // Color sólido
                'fill-outline-color':'#2E7D32'
            }
        });
        map.addLayer({
            id:'frac-border',
            type:'line',
            source:'frac-source',
            paint:{
                'line-color':'#2E7D32',
                'line-width':3,
                'line-opacity':1
            }
        });
    }

    // Enriquecer con datos del servidor
    fetch(`/asesor/fraccionamiento/${window.AppConfig.fraccionamientoId}/lotes`)
        .then(r => r.json())
        .then(res => {
            const lotesMap = {};
            res.lotes.forEach(l => lotesMap[l.numeroLote] = l);

            features.forEach(f => {
                const sl = lotesMap[f.properties.lote] || {};
                Object.assign(f.properties, {
                    estatus: sl.estatus || 'disponible',
                    manzana: sl.manzana || 'N/A',
                    area_metros: parseFloat(sl.medidas?.area_metros || 0).toFixed(2),
                    precio_m2: parseFloat(sl.precio_m2 || 0).toFixed(2),
                    costo_total: parseFloat(sl.costo_total || 0).toFixed(2),
                    norte: sl.medidas?.norte || 'N/A',
                    sur: sl.medidas?.sur || 'N/A',
                    oriente: sl.medidas?.oriente || 'N/A',
                    poniente: sl.medidas?.poniente || 'N/A'
                });
            });

            addLotesToMap({type:"FeatureCollection", features});
        })
        .catch(() => addLotesToMap({type:"FeatureCollection", features})); // fallback
};

// ===========================================
// AÑADIR LOTES AL MAPA (apariencia mejorada)
// ===========================================
function addLotesToMap(data) {
    if (!map) return;

    // Limpiar capas anteriores
    ['lotes-fill','lotes-vertices','lotes-borders','lotes-labels','lotes-centers'].forEach(id => {
        if (map.getLayer(id)) map.removeLayer(id);
    });
    if (map.getSource('lotes')) map.removeSource('lotes');

    map.addSource('lotes', {type:'geojson', data});

    // Capa de relleno con colores SÓLIDOS según estado
    map.addLayer({
        id: 'lotes-fill',
        type: 'fill',
        source: 'lotes',
        paint: {
            'fill-color': [
                'case',
                ['==', ['get', 'estatus'], 'disponible'], '#4CAF50', // Verde sólido
                ['==', ['get', 'estatus'], 'apartado'], '#FF9800',   // Naranja sólido
                ['==', ['get', 'estatus'], 'vendido'], '#F44336',    // Rojo sólido
                '#9E9E9E' // Gris sólido
            ],
            'fill-opacity': 0.8, // Ligeramente transparente para ver detalles
            'fill-outline-color': '#1A237E'
        }
    });

    // Capa de vértices (puntos en las esquinas de los lotes) - RESTAURADA
    map.addLayer({
        id: 'lotes-vertices',
        type: 'circle',
        source: 'lotes',
        paint: {
            'circle-radius': 6,
            'circle-color': '#FF6D00',
            'circle-stroke-width': 2,
            'circle-stroke-color': '#FFFFFF',
            'circle-opacity': 1
        }
    });

    // Bordes de los lotes
    map.addLayer({
        id: 'lotes-borders',
        type: 'line',
        source: 'lotes',
        paint: {
            'line-color': '#1A237E',
            'line-width': 2,
            'line-opacity': 1
        }
    });

    // Etiquetas en el centro de cada lote (mejoradas)
    map.addLayer({
        id: 'lotes-labels',
        type: 'symbol',
        source: 'lotes',
        layout: {
            'text-field':['get','lote'],
            'text-size': 14,
            'text-font':['Open Sans Bold','Arial Unicode MS Bold'],
            'text-allow-overlap': false,
            'text-ignore-placement': false
        },
        paint: {
            'text-color':'#FFFFFF',
            'text-halo-color':'#000000',
            'text-halo-width':2,
            'text-opacity':1
        }
    });

    // Punto central de cada lote (para interacción)
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

    // Efectos de hover para mejor interactividad
    map.on('mouseenter', 'lotes-fill', () => {
        map.getCanvas().style.cursor = 'pointer';
        map.setPaintProperty('lotes-fill', 'fill-opacity', 1);
    });

    map.on('mouseleave', 'lotes-fill', () => {
        map.getCanvas().style.cursor = '';
        map.setPaintProperty('lotes-fill', 'fill-opacity', 0.8);
    });

    // Zoom automático mejorado
    const bounds = new mapboxgl.LngLatBounds();
    data.features.forEach(f => {
        if (f.geometry.type === 'Polygon') {
            f.geometry.coordinates[0].forEach(c => bounds.extend(c));
        } else if (f.geometry.type === 'MultiPolygon') {
            f.geometry.coordinates.forEach(polygon => {
                polygon[0].forEach(c => bounds.extend(c));
            });
        }
    });
    
    // Ajustar el zoom con márgenes adecuados
    map.fitBounds(bounds, {
        padding: {top: 60, bottom: 60, left: 60, right: 100},
        duration: 1500,
        maxZoom: 19
    });

    // Popup mejorado - CON CIERRE AL HACER CLICK FUERA
    const popup = new mapboxgl.Popup({
        closeButton: true, 
        closeOnClick: true, // IMPORTANTE: Permite cerrar al hacer click fuera
        className: 'modern-lote-popup', 
        anchor: 'left',
        maxWidth: '300px'
    });

    // Evento para cerrar popup al hacer click en el mapa
    map.on('click', (e) => {
        // Verificar si el click fue en un área sin features
        const features = map.queryRenderedFeatures(e.point, {
            layers: ['lotes-fill', 'lotes-vertices']
        });
        
        if (features.length === 0 && currentPopup) {
            currentPopup.remove();
            currentPopup = null;
        }
    });
    
    // Click en el área del lote
    map.on('click', 'lotes-fill', e => {
        // Cerrar popup anterior si existe
        if (currentPopup) {
            currentPopup.remove();
        }

        const p = e.features[0].properties;
        const geometry = e.features[0].geometry;
        
        // Extraer coordenadas de vértices
        let vertices = [];
        if (geometry.type === 'Polygon') {
            vertices = geometry.coordinates[0].slice(0, -1);
        } else if (geometry.type === 'MultiPolygon') {
            vertices = geometry.coordinates[0][0].slice(0, -1);
        }

        // Generar lista de vértices
        const verticesList = vertices.map((vertex, index) => `
            <div class="vertex-item">
                <span>Vértice ${index + 1}</span>
                <span class="vertex-coords">${vertex[1].toFixed(6)}, ${vertex[0].toFixed(6)}</span>
            </div>
        `).join('');

        currentPopup = new mapboxgl.Popup({
            closeButton: true, 
            closeOnClick: true, // Permite cerrar al hacer click fuera
            className: 'modern-lote-popup', 
            anchor: 'left',
            maxWidth: '300px'
        })
        .setLngLat(e.lngLat)
        .setHTML(`
            <div class="popup-card">
                <div class="popup-header">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start">
                        <span class="lote-number">Lote ${p.lote}</span>
                        <span class="status-badge ${p.estatus==='disponible'?'status-disponible':p.estatus==='vendido'?'status-vendido':'status-apartado'}">
                            ${p.estatus==='disponible'?'Disponible':p.estatus==='vendido'?'Vendido':'Apartado'}
                        </span>
                    </div>
                    <div class="popup-subtitle">Manzana ${p.manzana}</div>
                </div>
                <div class="popup-content">
                    <div class="popup-grid">
                        <div class="info-item"><span class="icon"></span><strong>Área: ${p.area_metros} m²</strong></div>
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
        `)
        .addTo(map);
    });

    // Interacción con los vértices - RESTAURADA
    map.on('click', 'lotes-vertices', e => {
        // Cerrar popup anterior si existe
        if (currentPopup) {
            currentPopup.remove();
        }

        const p = e.features[0].properties;
        const coordinates = e.lngLat;
        
        currentPopup = new mapboxgl.Popup({
            closeButton: true, 
            closeOnClick: true, // Permite cerrar al hacer click fuera
            className: 'modern-lote-popup', 
            anchor: 'left',
            maxWidth: '300px'
        })
        .setLngLat(coordinates)
        .setHTML(`
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
        `)
        .addTo(map);
    });

    map.on('mouseenter', ['lotes-fill', 'lotes-vertices'], () => map.getCanvas().style.cursor = 'pointer');
    map.on('mouseleave', ['lotes-fill', 'lotes-vertices'], () => map.getCanvas().style.cursor = '');
}

// ===========================================
// CONTROLES FLOTANTES (mejorados)
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
        <button class="ctrl-btn toggle-vertices" title="Mostrar/ocultar vértices">
            <span class="material-icons">location_on</span>
        </button>
    `;
    mapContainer.appendChild(controls);

    controls.querySelector('.zoom-in').onclick = () => map.zoomIn();
    controls.querySelector('.zoom-out').onclick = () => map.zoomOut();
    controls.querySelector('.compass').onclick = () => map.easeTo({bearing:0,pitch:0,duration:1000});

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