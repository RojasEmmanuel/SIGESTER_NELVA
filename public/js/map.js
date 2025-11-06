document.addEventListener('DOMContentLoaded', function () {
    const mapContainer = document.getElementById('mapPlano');
    if (!mapContainer) return;

    let map = null;
    let currentFilter = 'all';
    let lotesData = null;
    let fraccionamientoFeature = null;
    let is3DMode = false;
    let mapLayersLoaded = false;
    let styleChanging = false;

    const mapStyles = {
        'satellite-streets': 'mapbox://styles/mapbox/satellite-streets-v12',
        'streets': 'mapbox://styles/mapbox/streets-v12',
        'light': 'mapbox://styles/mapbox/light-v11',
        'dark': 'mapbox://styles/mapbox/dark-v11',
        'standard': 'mapbox://styles/mapbox/navigation-day-v1',
        'tourist': 'mapbox://styles/mapbox/navigation-night-v1'
    };

    const STATUS_CLASS_MAP = {
        'disponible': 'status-disponible',
        'apartadoPalabra': 'status-apartado',
        'apartadoDeposito': 'status-apartado',
        'vendido': 'status-vendido'
    };

    const STATUS_LABEL_MAP = {
        'disponible': 'Disponible',
        'apartadoPalabra': 'Apartado',
        'apartadoDeposito': 'Apartado',
        'vendido': 'Vendido'
    };

    const ZONA_STYLES = {
        'zona oro': { 
            color: '#ffd700', 
            dash: ['literal', [6, 3]], 
            name: 'Oro',
            gradient: 'linear-gradient(135deg, #fff9c4, #ffd700)',
            icon: '👑'
        },
        'zona plata': { 
            color: '#c0c0c0', 
            dash: ['literal', [4, 4]], 
            name: 'Plata',
            gradient: 'linear-gradient(135deg, #f5f5f5, #c0c0c0)',
            icon: '⚪'
        },
        'zona bronce': { 
            color: '#cd7f32', 
            dash: ['literal', [8, 2, 2, 2]], 
            name: 'Bronce',
            gradient: 'linear-gradient(135deg, #ffe0b2, #cd7f32)',
            icon: '🟤'
        },
        'zona premium': { 
            color: '#9c27b0', 
            dash: ['literal', [10, 3]], 
            name: 'Premium',
            gradient: 'linear-gradient(135deg, #e1bee7, #9c27b0)',
            icon: '💎'
        },
        'zona estandar': { 
            color: '#757575', 
            dash: ['literal', [3, 3]], 
            name: 'Estándar',
            gradient: 'linear-gradient(135deg, #f5f5f5, #757575)',
            icon: '🏠'
        }
    };

    // Añadir estilos CSS dinámicamente
    const style = document.createElement('style');
    style.textContent = `
        .modern-lote-popup {
            max-width: 300px !important;
            font-family: 'Roboto', -apple-system, BlinkMacSystemFont, sans-serif;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
            border: none;
            overflow: hidden;
        }

        .popup-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        .popup-header {
            padding: 14px 14px 10px;
            background: linear-gradient(135deg, #185cdd 0%, #4facfe 50%, #90ceff 100%);
            color: white;
            position: relative;
        }

        .popup-title {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 6px;
            color: white;
        }

        .lote-number {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .status-badge {
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            backdrop-filter: blur(10px);
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
        }

        .status-disponible { background: rgba(76, 175, 80, 0.9) !important; }
        .status-apartado { background: rgba(255, 152, 0, 0.9) !important; }
        .status-vendido { background: rgba(244, 67, 54, 0.9) !important; }

        .popup-zona {
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 600;
            margin: 6px 0;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .zona-icon {
            font-size: 10px;
        }

        .popup-subtitle {
            font-size: 11px;
            opacity: 0.9;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .popup-content {
            padding: 12px;
        }

        .popup-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-bottom: 12px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .info-item.compact {
            grid-column: 1 / -1;
            background: #f8f9fa;
            padding: 8px;
            border-radius: 6px;
            border-left: 3px solid #2196f3;
        }

        .info-item .icon {
            font-size: 10px;
            color: #666;
            font-weight: 500;
        }

        .info-item strong {
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }

        .info-item.compact strong {
            color: #1976d2;
            font-size: 14px;
        }

        .measures-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5px;
            margin-top: 8px;
        }

        .measure {
            padding: 4px 6px;
            background: #f5f5f5;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 500;
            text-align: center;
            color: #555;
        }

        .reserve-btn {
            width: 100%;
            margin-top: 10px;
            padding: 10px 16px;
            background: linear-gradient(135deg, #4caf50, #45a049);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(76, 175, 80, 0.3);
        }

        .reserve-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(76, 175, 80, 0.4);
        }

        .reserve-btn:active {
            transform: translateY(0);
        }

        .sold-notice {
            width: 100%;
            margin-top: 10px;
            padding: 10px 12px;
            background: #ffebee;
            color: #c62828;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
            border: 1px solid #ffcdd2;
        }

        /* Map controls en centro-derecha */
        .map-controls {
            position: absolute;
            top: 50%;
            right: 16px;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 6px;
            z-index: 2;
        }

        .ctrl-btn {
            width: 40px;
            height: 40px;
            background: white;
            border: none;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 3px 12px rgba(0,0,0,0.15);
            transition: all 0.2s ease;
            color: #333;
            font-size: 14px;
        }

        .ctrl-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            background: #f8f9fa;
        }

        .ctrl-btn:active {
            transform: translateY(0);
        }

        .toggle-3d.active {
            background: #2196f3;
            color: white;
        }

        /* Responsive para móviles */
        @media (max-width: 768px) {
            .modern-lote-popup {
                max-width: 260px !important;
            }

            .popup-header {
                padding: 12px 12px 8px;
            }

            .lote-number {
                font-size: 16px;
            }

            .popup-grid {
                grid-template-columns: 1fr;
                gap: 6px;
            }

            .map-controls {
                right: 12px;
                gap: 5px;
            }

            .ctrl-btn {
                width: 36px;
                height: 36px;
                font-size: 12px;
                border-radius: 8px;
            }
        }

        @media (max-width: 480px) {
            .modern-lote-popup {
                max-width: 240px !important;
            }

            .popup-card {
                border-radius: 10px;
            }

            .measures-grid {
                grid-template-columns: 1fr;
            }

            .reserve-btn,
            .sold-notice {
                padding: 8px 12px;
                font-size: 12px;
            }

            .map-controls {
                right: 8px;
            }

            .ctrl-btn {
                width: 32px;
                height: 32px;
                font-size: 11px;
            }
        }

        /* Estados de los botones de control */
        .ctrl-btn.zoom-in:active,
        .ctrl-btn.zoom-out:active,
        .ctrl-btn.compass:active,
        .ctrl-btn.rotate-left:active,
        .ctrl-btn.rotate-right:active {
            background: #e3f2fd;
            color: #2196f3;
        }
    `;
    document.head.appendChild(style);

    function getZonaBorderStyle(zonaNombre) {
        if (!zonaNombre) return null;
        const key = zonaNombre.toLowerCase().trim();
        return ZONA_STYLES[key] || null;
    }

    function getStatusClass(status) {
        return STATUS_CLASS_MAP[status] || 'status-no-disponible';
    }

    function formatStatus(status) {
        return STATUS_LABEL_MAP[status] || status || 'No Disponible';
    }

    function isLoteAvailable(status) {
        return status === 'disponible';
    }

    /* ===========================
       FILTROS
       =========================== */
    function initFilterButtons() {
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentFilter = this.getAttribute('data-filter');
                filterLotesByStatus(currentFilter);
            });
        });
    }

    function filterLotesByStatus(status) {
        if (!map || !mapLayersLoaded || styleChanging) return;

        try {
            if (status === 'all') {
                ['lotes-fill', 'lotes-borders', 'lotes-labels'].forEach(l => {
                    if (map.getLayer(l)) map.setFilter(l, null);
                });
            } else if (status === 'apartado-palabra-deposito') {
                const filter = ['any', ['==', ['get', 'estatus'], 'apartadoPalabra'], ['==', ['get', 'estatus'], 'apartadoDeposito']];
                ['lotes-fill', 'lotes-borders', 'lotes-labels'].forEach(l => {
                    if (map.getLayer(l)) map.setFilter(l, filter);
                });
            } else {
                const filter = ['==', ['get', 'estatus'], status];
                ['lotes-fill', 'lotes-borders', 'lotes-labels'].forEach(l => {
                    if (map.getLayer(l)) map.setFilter(l, filter);
                });
            }
        } catch (e) {
            console.warn('Error filtering lots:', e);
        }
    }

    /* ===========================
       ESTILO DEL MAPA
       =========================== */
    function initStyleButtons() {
        document.querySelectorAll('.style-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const newStyle = this.getAttribute('data-style');
                // Si el botón ya está activo, no hacer nada
                if (this.classList.contains('active')) {
                    return;
                }
                document.querySelectorAll('.style-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                changeMapStyle(newStyle);
            });
        });
    }

    function changeMapStyle(styleKey) {
        if (!map || !mapStyles[styleKey] || styleChanging) return;
        styleChanging = true;

        const currentLotesData = window.lotesData;
        const currentFraccionamiento = fraccionamientoFeature;
        const currentFilterState = currentFilter;

        map.setStyle(mapStyles[styleKey]);

        map.once('style.load', () => {
            // Asegurarse de que las fuentes estén cargadas
            map.once('sourcedata', () => {
                // Primero agregar el perímetro del fraccionamiento
                if (currentFraccionamiento) {
                    addFraccionamientoPerimeter(currentFraccionamiento);
                }
                
                // Luego agregar los lotes
                if (currentLotesData) {
                    addLotesToMap(currentLotesData);
                }
                
                // Finalmente aplicar los filtros si existen
                if (currentFilterState && currentFilterState !== 'all') {
                    filterLotesByStatus(currentFilterState);
                }
                
                styleChanging = false;
            });
        });
    }

    /* ===========================
       PANTALLA COMPLETA
       =========================== */
    function initFullscreenButton() {
        const btn = document.getElementById('fullscreenBtn');
        if (!btn) return;
        btn.addEventListener('click', toggleFullscreenMap);
    }

    function toggleFullscreenMap() {
        const container = document.getElementById('planContainer');
        const btn = document.getElementById('fullscreenBtn');
        if (!container || !btn) return;

        if (!document.fullscreenElement) {
            container.requestFullscreen?.() || container.webkitRequestFullscreen?.() || container.msRequestFullscreen?.();
            btn.innerHTML = '<i class="fas fa-compress"></i>';
        } else {
            document.exitFullscreen?.() || document.webkitExitFullscreen?.() || document.msExitFullscreen?.();
            btn.innerHTML = '<i class="fas fa-expand"></i>';
        }
    }

    ['fullscreenchange', 'webkitfullscreenchange', 'msfullscreenchange'].forEach(ev => {
        document.addEventListener(ev, () => {
            const btn = document.getElementById('fullscreenBtn');
            if (!btn) return;
            btn.innerHTML = document.fullscreenElement
                ? '<i class="fas fa-compress"></i>'
                : '<i class="fas fa-expand"></i>';
            setTimeout(() => map?.resize(), 300);
        });
    });

    /* ===========================
       INICIALIZAR MAPA
       =========================== */
    function initializeMap() {
        if (typeof mapboxgl === 'undefined' || !mapContainer.offsetParent) return;

        mapboxgl.accessToken = 'pk.eyJ1Ijoicm9qYXNkZXYiLCJhIjoiY21leDF4N2JtMTI0NTJrcHlsdjBiN2Y3YiJ9.RB87H34djrYH3WrRa-12Pg';

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
            initMapControls();
            initFilterButtons();
            initStyleButtons();
            initFullscreenButton();
            loadGeoJSONFromPublic();
        });
    }

    /* ===========================
       CARGAR GEOJSON
       =========================== */
    async function loadGeoJSONFromPublic() {
        if (!map) return;
        let geoJsonData = null;

        if (window.AppConfig?.fraccionamientoNombre) {
            try {
                const name = window.AppConfig.fraccionamientoNombre.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/\s+/g, '_');
                const res = await fetch(`/geojson/${name}.geojson`);
                if (res.ok) geoJsonData = await res.json();
            } catch (e) {
                console.warn('Error loading GeoJSON:', e);
            }
        }

        processGeoJSONData(geoJsonData || { type: "FeatureCollection", features: [] });
    }

    function processGeoJSONData(geoJsonData) {
        if (!geoJsonData?.features?.length) return;

        fraccionamientoFeature = geoJsonData.features[0];
        if (fraccionamientoFeature?.properties?.lote === "Fraccionamiento") {
            addFraccionamientoPerimeter(fraccionamientoFeature);
            geoJsonData.features.shift();
        }

        enrichGeoJSONWithServerData({ ...geoJsonData, features: geoJsonData.features });
    }

    function addFraccionamientoPerimeter(feature) {
        if (!map || !feature) return;
        const sid = 'fraccionamiento-source', fid = 'fraccionamiento-fill', bid = 'fraccionamiento-border';

        [fid, bid].forEach(l => map.getLayer(l) && map.removeLayer(l));
        map.getSource(sid) && map.removeSource(sid);

        map.addSource(sid, { type: 'geojson', data: feature });
        map.addLayer({ 
            id: fid, 
            type: 'fill', 
            source: sid, 
            paint: { 
                'fill-color': '#1f2937', 
                'fill-opacity': 0.85 
            } 
        });
        map.addLayer({ 
            id: bid, 
            type: 'line', 
            source: sid, 
            paint: { 
                'line-color': '#ffffff', 
                'line-width': 2.5, 
                'line-opacity': 0.9 
            } 
        });
    }

    /* ===========================
       ENRIQUECER DATOS
       =========================== */
    async function enrichGeoJSONWithServerData(filteredGeoJsonData) {
        try {
            const id = window.AppConfig.fraccionamientoId;
            const lotesRes = await fetch(`/asesor/fraccionamiento/${id}/lotes`);
            if (!lotesRes.ok) throw new Error();

            const lotesData = await lotesRes.json();
            if (!lotesData.success) throw new Error();

            const lotesMap = {};
            lotesData.lotes.forEach(l => lotesMap[l.numeroLote] = l);

            filteredGeoJsonData.features.forEach(f => {
                const serverLote = lotesMap[f.properties.lote];
                if (!serverLote) {
                    f.properties.zona = null;
                    f.properties.precio_m2 = '0.00';
                    f.properties.costo_total = '0.00';
                    return;
                }

                const zonaNombre = serverLote.zona?.nombre?.toLowerCase().trim();

                f.properties = {
                    ...f.properties,
                    id: serverLote.id_lote,
                    lote: serverLote.numeroLote,
                    estatus: serverLote.estatus,
                    manzana: serverLote.manzana || 'N/A',
                    norte: serverLote.medidas?.norte || 'N/A',
                    sur: serverLote.medidas?.sur || 'N/A',
                    oriente: serverLote.medidas?.oriente || 'N/A',
                    poniente: serverLote.medidas?.poniente || 'N/A',
                    area_metros: parseFloat(serverLote.medidas?.area_metros || 0).toFixed(2),
                    zona: zonaNombre,
                    precio_m2: parseFloat(serverLote.precio_m2).toFixed(2),
                    costo_total: parseFloat(serverLote.costo_total).toFixed(2)
                };
            });

            window.lotesData = filteredGeoJsonData;
            addLotesToMap(filteredGeoJsonData);
        } catch (e) {
            console.error('Error enriching data:', e);
            filteredGeoJsonData.features.forEach(f => {
                f.properties.zona = null;
                f.properties.precio_m2 = '0.00';
                f.properties.costo_total = '0.00';
            });
            window.lotesData = filteredGeoJsonData;
            addLotesToMap(filteredGeoJsonData);
        }
    }

    /* ===========================
       AÑADIR LOTES
       =========================== */
    function addLotesToMap(data) {
        if (!map || !data) return;

        ['lotes-fill', 'lotes-borders', 'lotes-labels'].forEach(l => {
            if (map.getLayer(l)) map.removeLayer(l);
        });
        if (map.getSource('lotes')) map.removeSource('lotes');

        map.addSource('lotes', { type: 'geojson', data: data });

        // Relleno
        map.addLayer({
            id: 'lotes-fill',
            type: 'fill',
            source: 'lotes',
            paint: {
                'fill-color': [
                    'match', 
                    ['get', 'estatus'], 
                    'disponible', '#4caf50', 
                    'vendido', '#f44336', 
                    'apartadoPalabra', '#ff9800', 
                    'apartadoDeposito', '#ff9800', 
                    '#9e9e9e'
                ],
                'fill-opacity': 0.75
            }
        });

        // Bordes por zona
        map.addLayer({
            id: 'lotes-borders',
            type: 'line',
            source: 'lotes',
            paint: {
                'line-color': [
                    'case',
                    ['==', ['get', 'zona'], 'zona oro'], '#ffd700',
                    ['==', ['get', 'zona'], 'zona plata'], '#c0c0c0',
                    ['==', ['get', 'zona'], 'zona bronce'], '#cd7f32',
                    ['==', ['get', 'zona'], 'zona premium'], '#9c27b0',
                    ['==', ['get', 'zona'], 'zona estandar'], '#757575',
                    '#ffffff'
                ],
                'line-width': 3,
                'line-opacity': 1,
                'line-dasharray': [
                    'case',
                    ['==', ['get', 'zona'], 'zona oro'], ZONA_STYLES['zona oro'].dash,
                    ['==', ['get', 'zona'], 'zona plata'], ZONA_STYLES['zona plata'].dash,
                    ['==', ['get', 'zona'], 'zona bronce'], ZONA_STYLES['zona bronce'].dash,
                    ['==', ['get', 'zona'], 'zona premium'], ZONA_STYLES['zona premium'].dash,
                    ['==', ['get', 'zona'], 'zona estandar'], ZONA_STYLES['zona estandar'].dash,
                    ['literal', [1, 0]]
                ]
            }
        });

        // Etiquetas compactas
        map.addLayer({
            id: 'lotes-labels',
            type: 'symbol',
            source: 'lotes',
            layout: {
                'text-field': ['to-string', ['get', 'lote']],
                'text-size': 13,
                'text-font': ['Open Sans Bold', 'Arial Unicode MS Bold']
            },
            paint: {
                'text-color': '#ffffff',
                'text-halo-color': '#000000',
                'text-halo-width': 2
            }
        });

        ['fraccionamiento-fill', 'fraccionamiento-border'].forEach(l => {
            if (map.getLayer(l)) {
                map.moveLayer(l, 'lotes-fill');
            }
        });

        mapLayersLoaded = true;
        setTimeout(() => fitMapToLotes(data), 300);
        setupMapInteractions();
        if (currentFilter) setTimeout(() => filterLotesByStatus(currentFilter), 500);
    }

    /* ===========================
       POPUP ULTRA COMPACTO
       =========================== */
    function setupMapInteractions() {
        if (!map.getLayer('lotes-fill')) return;

        const popup = new mapboxgl.Popup({
            closeButton: true,
            closeOnClick: true,
            maxWidth: '300px',
            className: 'modern-lote-popup',
            anchor: 'left'
        });

        map.on('mouseenter', 'lotes-fill', () => map.getCanvas().style.cursor = 'pointer');
        map.on('mouseleave', 'lotes-fill', () => map.getCanvas().style.cursor = '');

        map.on('click', 'lotes-fill', e => {
            const p = e.features[0].properties;
            popup.setLngLat(e.lngLat).setHTML(createModernPopup(p)).addTo(map);
        });
    }

    function createModernPopup(p) {
        const statusClass = getStatusClass(p.estatus);
        const zonaStyle = p.zona ? getZonaBorderStyle(p.zona) : null;
        const isAvailable = isLoteAvailable(p.estatus);

        const zonaTag = zonaStyle ? `
            <div class="popup-zona" style="background: ${zonaStyle.gradient}; color: #1f2937; border: 1px solid ${zonaStyle.color};">
                Zona ${zonaStyle.name}
            </div>` : '';

        const reserveButton = isAvailable ? `
            <button class="reserve-btn" onclick="window.openReservationForLote('${p.lote}')">
                <i class="fas fa-calendar-plus"></i>
                Reservar Lote
            </button>` : `
            <div class="sold-notice">
                <i class="fas fa-info-circle"></i>
                No disponible
            </div>`;

        return `
            <div class="popup-card">
                <div class="popup-header">
                    <div class="popup-title">
                        <span class="lote-number" style="color:white;">Lote ${p.lote}</span>
                        <span class="status-badge ${statusClass}" style="color:white;">
                            <i class="fas fa-${isAvailable ? 'check-circle' : 'lock'}"></i>
                            ${formatStatus(p.estatus)}
                        </span>
                    </div>
                    
                    <div class="zonaManzana" style="display: flex; gap:6px;">
                        ${zonaTag}
                        <div class="popup-subtitle">
                            <i class="fas fa-layer-group"></i>
                            Manzana ${p.manzana}
                        </div>
                    </div>
                </div>

                <div class="popup-content">
                    <div class="popup-grid">
                        <div class="info-item">
                            <span class="icon">Área</span>
                            <strong>${p.area_metros} m²</strong>
                        </div>
                        <div class="info-item">
                            <span class="icon"> Precio m²</span>
                            <strong>$${p.precio_m2}</strong>
                        </div>
                        <div class="info-item compact">
                            <span class="icon"> Total</span>
                            <strong>$${parseFloat(p.costo_total).toLocaleString('es-MX')}</strong>
                        </div>
                    </div>

                    <div class="measures-grid">
                        <div class="measure north">N ${p.norte}m</div>
                        <div class="measure south">S ${p.sur}m</div>
                        <div class="measure east">E ${p.oriente}m</div>
                        <div class="measure west">O ${p.poniente}m</div>
                    </div>

                    ${reserveButton}
                </div>
            </div>
        `;
    }

    /* ===========================
       CONTROLES EN CENTRO-DERECHA
       =========================== */
    function initMapControls() {
        const controls = document.createElement('div');
        controls.className = 'map-controls';
        controls.innerHTML = `
            <button class="ctrl-btn zoom-in" title="Acercar"><i class="fas fa-plus"></i></button>
            <button class="ctrl-btn zoom-out" title="Alejar"><i class="fas fa-minus"></i></button>
            <button class="ctrl-btn compass" title="Norte"><i class="fas fa-compass"></i></button>
            <button class="ctrl-btn toggle-3d" title="3D"><i class="fas fa-cube"></i></button>
            <button class="ctrl-btn rotate-left" title="Izquierda"><i class="fas fa-undo"></i></button>
            <button class="ctrl-btn rotate-right" title="Derecha"><i class="fas fa-redo"></i></button>
        `;

        mapContainer.appendChild(controls);
        setupCustomControls(controls);
    }

    function setupCustomControls(container) {
        container.querySelector('.zoom-in').onclick = () => map.zoomIn();
        container.querySelector('.zoom-out').onclick = () => map.zoomOut();
        container.querySelector('.compass').onclick = () => map.easeTo({ bearing: 0, pitch: 0, duration: 1000 });
        container.querySelector('.toggle-3d').onclick = toggle3DMode;
        container.querySelector('.rotate-left').onclick = () => map.easeTo({ bearing: map.getBearing() - 45, duration: 500 });
        container.querySelector('.rotate-right').onclick = () => map.easeTo({ bearing: map.getBearing() + 45, duration: 500 });
    }

    function toggle3DMode() {
        is3DMode = !is3DMode;
        const btn = document.querySelector('.toggle-3d');
        if (is3DMode) {
            if (!map.getSource('mapbox-dem')) {
                map.addSource('mapbox-dem', { type: 'raster-dem', url: 'mapbox://mapbox.mapbox-terrain-dem-v1' });
            }
            map.once('idle', () => map.setTerrain({ source: 'mapbox-dem', exaggeration: 1.5 }));
            map.easeTo({ pitch: 60, bearing: -17, duration: 1000 });
            btn.classList.add('active');
        } else {
            map.setTerrain(null);
            map.easeTo({ pitch: 0, bearing: 0, duration: 1000 });
            btn.classList.remove('active');
        }
    }

    function fitMapToLotes(data) {
        if (!map || !data?.features?.length) return;
        const bounds = new mapboxgl.LngLatBounds();
        data.features.forEach(f => {
            if (f.geometry?.coordinates) {
                const coords = f.geometry.type === 'Polygon' ? f.geometry.coordinates[0] : f.geometry.coordinates.flat(2);
                coords.forEach(c => c.length >= 2 && bounds.extend(c));
            }
        });
        if (!bounds.isEmpty()) {
            map.fitBounds(bounds, { padding: 40, duration: 1500, maxZoom: 20 });
        }
    }

    setTimeout(initializeMap, 100);
});