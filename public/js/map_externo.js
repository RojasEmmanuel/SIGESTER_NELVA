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
    let zonasData = {};

    const mapStyles = {
        'satellite-streets': 'mapbox://styles/mapbox/satellite-streets-v12',
        'streets': 'mapbox://styles/mapbox/streets-v12',
        'light': 'mapbox://styles/mapbox/light-v11',
        'dark': 'mapbox://styles/mapbox/dark-v11'
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

    const ZONA_DASH_PATTERNS = {
        'zona oro': ['literal', [6, 3]],
        'zona plata': ['literal', [4, 4]],
        'zona bronce': ['literal', [8, 2, 2, 2]],
        'zona premium': ['literal', [10, 3]],
        'zona estandar': ['literal', [3, 3]]
    };

    // Añadir estilos CSS dinámicamente
    const style = document.createElement('style');
    style.textContent = `
        /* CONTROLES MATERIAL DESIGN - DERECHA CENTRADA EN GRIS SIN HOVER */
        .map-controls-container {
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 12px;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 16px 12px;
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.12),
                0 2px 8px rgba(0, 0, 0, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        .map-control-btn {
            width: 52px;
            height: 52px;
            background: white;
            border: none;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 
                0 2px 8px rgba(0, 0, 0, 0.1),
                0 1px 4px rgba(0, 0, 0, 0.08);
            transition: all 0.2s ease;
            color: #5f6368;
            font-size: 20px;
            position: relative;
            overflow: hidden;
        }

        .map-control-btn:active {
            transform: scale(0.95);
            box-shadow: 
                0 1px 4px rgba(0, 0, 0, 0.1),
                0 1px 2px rgba(0, 0, 0, 0.08);
        }

        .map-control-btn.active {
            background: #5f6368;
            color: white;
            box-shadow: 
                0 2px 8px rgba(95, 99, 104, 0.3),
                0 1px 4px rgba(95, 99, 104, 0.2);
        }

        .map-control-btn i {
            position: relative;
            z-index: 1;
        }

        /* Efecto de onda al hacer click */
        .map-control-btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(95, 99, 104, 0.1);
            transform: translate(-50%, -50%);
            transition: width 0.2s, height 0.2s;
        }

        .map-control-btn:active::after {
            width: 100%;
            height: 100%;
        }

        /* Todos los botones en gris */
        .map-control-btn.zoom-in,
        .map-control-btn.zoom-out,
        .map-control-btn.compass,
        .map-control-btn.rotate-left,
        .map-control-btn.rotate-right,
        .map-control-btn.toggle-3d {
            color: #5f6368;
        }

        /* Indicador de rotación Material Design */
        .rotation-indicator {
            position: absolute;
            top: calc(50% + 120px);
            right: 20px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 12px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            color: #5f6368;
            box-shadow: 
                0 4px 16px rgba(0, 0, 0, 0.1),
                0 2px 8px rgba(0, 0, 0, 0.08);
            display: none;
            z-index: 1000;
            border: 1px solid rgba(255, 255, 255, 0.4);
            animation: slideInUp 0.3s ease;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Tooltips Material Design */
        .map-control-btn[title] {
            position: relative;
        }

        .map-control-btn[title]::before {
            content: attr(title);
            position: absolute;
            right: 100%;
            top: 50%;
            transform: translateY(-50%);
            margin-right: 12px;
            background: #323232;
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 500;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .map-control-btn[title]:hover::before {
            opacity: 1;
            margin-right: 16px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .map-controls-container {
                right: 12px;
                padding: 12px 8px;
                gap: 10px;
                border-radius: 20px;
            }

            .map-control-btn {
                width: 46px;
                height: 46px;
                font-size: 18px;
                border-radius: 14px;
            }

            .rotation-indicator {
                right: 12px;
                top: calc(50% + 110px);
                padding: 10px 14px;
                font-size: 12px;
            }

            .map-control-btn[title]::before {
                display: none; /* Ocultar tooltips en móvil */
            }
        }

        @media (max-width: 480px) {
            .map-controls-container {
                right: 8px;
                padding: 10px 6px;
                gap: 8px;
                border-radius: 18px;
            }

            .map-control-btn {
                width: 42px;
                height: 42px;
                font-size: 16px;
                border-radius: 12px;
            }

            .rotation-indicator {
                right: 8px;
                top: calc(50% + 100px);
                padding: 8px 12px;
                font-size: 11px;
            }
        }

        /* POPUP NELVA (mantener tu estilo existente) */
        .popup-nelva-final {
            width: 220px;
            max-width: 90vw;
            background: white;
            border-radius: 18px;
            overflow: hidden;
            font-family: 'Roboto', system-ui, sans-serif;
        }

        .header-nelva {
            background: linear-gradient(135deg, #0066cc, #00aaff);
            color: white;
            padding: 14px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .titulo {
            font-size: 19px;
            font-weight: 700;
        }

        .status {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .status-disponible { background: #4caf50; }
        .status-apartado   { background: #ff9800; }
        .status-vendido    { background: #f44336; }

        .manzana-zona {
            padding: 10px 16px 8px;
            font-size: 14.5px;
            color: #333;
            background: #f8fbff;
            font-weight: 500;
        }

        .info {
            padding: 12px 16px 10px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 9px;
            font-size: 14.5px;
        }

        .row.total {
            padding-top: 10px;
            border-top: 2px solid #e3f2fd;
            margin-top: 10px;
            font-size: 15px;
        }

        .row.total strong {
            font-size: 21px;
            color: #0066cc;
            font-weight: 700;
        }

        .medidas-2x2 {
            padding: 11px 16px;
            background: #f5f9ff;
            font-size: 13.2px;
            color: #444;
            text-align: center;
            border-top: 1px solid #e8efff;
            line-height: 1.6;
        }

        .medida-line {
            margin: 3px 0;
        }

        .btn-reservar {
            margin: 14px 16px 16px;
            padding: 13px;
            background: #4caf50;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
        }

        .no-disponible {
            margin: 14px 16px 16px;
            padding: 13px;
            background: #fff5f5;
            color: #c62828;
            border-radius: 12px;
            text-align: center;
            font-weight: 600;
            font-size: 15px;
            border: 1px solid #ffcdd2;
        }

        /* Perfecto en móviles pequeños */
        @media (max-width: 780px) {
            .popup-nelva-final { width: 150px; }
            .header-nelva { padding: 12px 14px; }
            .titulo { font-size: 14px; }
            .row.total strong { font-size: 16px;}
            .status { font-size: 9px; padding: 5px 12px;}
            .manzana-zona { font-size: 11px; padding: 6px 12px 4px;}
            .info { padding: 5px 7px 4px; }
            .info.row.span { font-size: 9px; margin-bottom: 3px; }
            .medidas-2x2 { font-size: 12px; padding: 4px 6px;}
            .no-disponible, .btn-reservar { font-size: 12px; padding: 8px; margin: 5px 12px 12px; }
        }
    `;
    document.head.appendChild(style);

    // ========== FUNCIONES DE CONTROLES DEL MAPA ==========
    function createMapControls() {
        const controlsContainer = document.createElement('div');
        controlsContainer.className = 'map-controls-container';
        controlsContainer.id = 'mapControlsContainer';

        const rotationIndicator = document.createElement('div');
        rotationIndicator.className = 'rotation-indicator';
        rotationIndicator.id = 'rotationIndicator';
        rotationIndicator.textContent = 'Norte: 0°';

        const controlsHTML = `
            <button class="map-control-btn zoom-in" title="Acercar (Ctrl + +)">
                <i class="fas fa-plus"></i>
            </button>
            <button class="map-control-btn zoom-out" title="Alejar (Ctrl + -)">
                <i class="fas fa-minus"></i>
            </button>
            <button class="map-control-btn compass" title="Restablecer al Norte">
                <i class="fas fa-compass"></i>
            </button>
            <button class="map-control-btn toggle-3d" title="Vista 3D / 2D">
                <i class="fas fa-cube"></i>
            </button>
            <button class="map-control-btn rotate-left" title="Rotar izquierda">
                <i class="fas fa-undo"></i>
            </button>
            <button class="map-control-btn rotate-right" title="Rotar derecha">
                <i class="fas fa-redo"></i>
            </button>
        `;

        controlsContainer.innerHTML = controlsHTML;
        mapContainer.appendChild(controlsContainer);
        mapContainer.appendChild(rotationIndicator);
        setupControlEvents(controlsContainer, rotationIndicator);
    }

    function setupControlEvents(container, rotationIndicator) {
        const zoomInBtn = container.querySelector('.zoom-in');
        const zoomOutBtn = container.querySelector('.zoom-out');
        const compassBtn = container.querySelector('.compass');
        const toggle3dBtn = container.querySelector('.toggle-3d');
        const rotateLeftBtn = container.querySelector('.rotate-left');
        const rotateRightBtn = container.querySelector('.rotate-right');

        zoomInBtn.addEventListener('click', () => {
            map.zoomTo(map.getZoom() + 1, { duration: 300 });
        });

        zoomOutBtn.addEventListener('click', () => {
            map.zoomTo(map.getZoom() - 1, { duration: 300 });
        });

        compassBtn.addEventListener('click', () => {
            map.easeTo({
                bearing: 0,
                pitch: 0,
                duration: 800
            });
            updateRotationIndicator(0);
        });

        toggle3dBtn.addEventListener('click', toggle3DMode);

        rotateLeftBtn.addEventListener('click', () => {
            const newBearing = map.getBearing() - 45;
            map.easeTo({ bearing: newBearing, duration: 400 });
            updateRotationIndicator(newBearing);
        });

        rotateRightBtn.addEventListener('click', () => {
            const newBearing = map.getBearing() + 45;
            map.easeTo({ bearing: newBearing, duration: 400 });
            updateRotationIndicator(newBearing);
        });

        map.on('rotate', () => {
            updateRotationIndicator(map.getBearing());
        });

        // Atajos de teclado
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey || e.metaKey) {
                switch (e.key) {
                    case '=':
                    case '+':
                        e.preventDefault();
                        map.zoomTo(map.getZoom() + 1, { duration: 250 });
                        break;
                    case '-':
                        e.preventDefault();
                        map.zoomTo(map.getZoom() - 1, { duration: 250 });
                        break;
                    case '0':
                        e.preventDefault();
                        map.easeTo({ bearing: 0, pitch: 0, duration: 600 });
                        updateRotationIndicator(0);
                        break;
                }
            }
        });
    }

    function updateRotationIndicator(bearing) {
        const indicator = document.getElementById('rotationIndicator');
        if (indicator) {
            const normalizedBearing = ((bearing % 360) + 360) % 360;
            const direction = getCardinalDirection(normalizedBearing);
            indicator.textContent = `${direction} (${Math.round(normalizedBearing)}°)`;
            indicator.style.display = 'block';
            
            clearTimeout(window.rotationIndicatorTimeout);
            window.rotationIndicatorTimeout = setTimeout(() => {
                indicator.style.display = 'none';
            }, 2000);
        }
    }

    function getCardinalDirection(bearing) {
        const directions = ['N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW'];
        const index = Math.round(bearing / 45) % 8;
        return directions[index];
    }

    function toggle3DMode() {
        const btn = document.querySelector('.toggle-3d');
        
        if (!is3DMode) {
            if (!map.getSource('mapbox-dem')) {
                map.addSource('mapbox-dem', {
                    type: 'raster-dem',
                    url: 'mapbox://mapbox.mapbox-terrain-dem-v1',
                    tileSize: 512
                });
            }
            
            map.once('idle', () => {
                map.setTerrain({ source: 'mapbox-dem', exaggeration: 1.5 });
            });
            
            map.easeTo({ pitch: 60, duration: 1200 });
            btn.classList.add('active');
            is3DMode = true;
        } else {
            map.setTerrain(null);
            map.easeTo({ pitch: 0, duration: 800 });
            btn.classList.remove('active');
            is3DMode = false;
        }
    }

    // ========== EL RESTO DE LAS FUNCIONES DEL MAPA PERMANECEN IGUAL ==========
    // ... (todas las demás funciones se mantienen exactamente igual)
    function getZonaStyle(zonaNombre) {
        if (!zonaNombre || !zonasData[zonaNombre] || !zonasData[zonaNombre].color) return null;
        return {
            color: zonasData[zonaNombre].color,
            name: zonasData[zonaNombre].nombre,
            gradient: `linear-gradient(135deg, ${zonasData[zonaNombre].color}20, ${zonasData[zonaNombre].color})`
        };
    }

    function getZonaDashPattern(zonaNombre) {
        return ZONA_DASH_PATTERNS[zonaNombre] || ['literal', [1, 0]];
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

    function initStyleButtons() {
        document.querySelectorAll('.style-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const newStyle = this.getAttribute('data-style');
                if (this.classList.contains('active')) return;
                
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
            map.once('sourcedata', () => {
                if (currentFraccionamiento) addFraccionamientoPerimeter(currentFraccionamiento);
                if (currentLotesData) addLotesToMap(currentLotesData);
                if (currentFilterState && currentFilterState !== 'all') filterLotesByStatus(currentFilterState);
                styleChanging = false;
            });
        });
    }

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
            createMapControls();
            initFilterButtons();
            initStyleButtons();
            initFullscreenButton();
            loadGeoJSONFromPublic();
        });
    }

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

    async function enrichGeoJSONWithServerData(filteredGeoJsonData) {
        try {
            const id = window.AppConfig.fraccionamientoId;
            const lotesRes = await fetch(`/fraccionamiento/${id}/lotes`);
            if (!lotesRes.ok) throw new Error();

            const lotesData = await lotesRes.json();
            if (!lotesData.success) throw new Error();

            await loadZonasData(id);

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

    async function loadZonasData(fraccionamientoId) {
        try {
            const zonasRes = await fetch(`/fraccionamiento/${fraccionamientoId}/zonas`);
            if (zonasRes.ok) {
                const zonasResponse = await zonasRes.json();
                if (zonasResponse.success && zonasResponse.zonas) {
                    zonasData = {};
                    zonasResponse.zonas.forEach(zona => {
                        const key = zona.nombre.toLowerCase().trim();
                        if (zona.color && zona.color !== 'undefined' && zona.color !== 'null') {
                            zonasData[key] = {
                                nombre: zona.nombre,
                                color: zona.color
                            };
                        }
                    });
                }
            }
        } catch (e) {
            console.error('Error loading zones data:', e);
        }
    }

    function addLotesToMap(data) {
        if (!map || !data) return;

        ['lotes-fill', 'lotes-borders', 'lotes-labels'].forEach(l => {
            if (map.getLayer(l)) map.removeLayer(l);
        });
        if (map.getSource('lotes')) map.removeSource('lotes');

        map.addSource('lotes', { type: 'geojson', data: data });

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

        const zonasKeys = Object.keys(zonasData);
        let lineColorExpression;
        
        if (zonasKeys.length > 0) {
            const caseExpressions = zonasKeys.flatMap(key => {
                const color = zonasData[key].color;
                if (color && color !== 'undefined' && color !== 'null') {
                    return [
                        ['==', ['get', 'zona'], key],
                        color
                    ];
                }
                return [];
            }).filter(expr => expr.length > 0);
            
            if (caseExpressions.length > 0) {
                lineColorExpression = ['case', ...caseExpressions, '#ffffff'];
            } else {
                lineColorExpression = '#ffffff';
            }
        } else {
            lineColorExpression = '#ffffff';
        }

        map.addLayer({
            id: 'lotes-borders',
            type: 'line',
            source: 'lotes',
            paint: {
                'line-color': lineColorExpression,
                'line-width': 3,
                'line-opacity': 1,
                'line-dasharray': [
                    'case',
                    ['==', ['get', 'zona'], 'zona oro'], ['literal', [6, 3]],
                    ['==', ['get', 'zona'], 'zona plata'], ['literal', [4, 4]],
                    ['==', ['get', 'zona'], 'zona bronce'], ['literal', [8, 2, 2, 2]],
                    ['==', ['get', 'zona'], 'zona premium'], ['literal', [10, 3]],
                    ['==', ['get', 'zona'], 'zona estandar'], ['literal', [3, 3]],
                    ['literal', [1, 0]]
                ]
            }
        });

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
            if (map.getLayer(l)) map.moveLayer(l, 'lotes-fill');
        });

        mapLayersLoaded = true;
        setTimeout(() => fitMapToLotes(data), 300);
        setupMapInteractions();
        if (currentFilter) setTimeout(() => filterLotesByStatus(currentFilter), 500);
    }

    function setupMapInteractions() {
        if (!map.getLayer('lotes-fill')) return;

        const popup = new mapboxgl.Popup({
            closeButton: true,
            closeOnClick: true,
            maxWidth: '150px',
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
        const isAvailable = isLoteAvailable(p.estatus);
        const zonaRaw = p.zona ? p.zona.replace('zona ', '') : '';
        const zonaTexto = zonaRaw ? zonaRaw.charAt(0).toUpperCase() + zonaRaw.slice(1) : '';

        let linea1 = '';
        let linea2 = '';

        if (p.norte && p.norte !== 'N/A') linea1 += `N: ${p.norte}m`;
        if (p.sur && p.sur !== 'N/A') linea1 += linea1 ? `   S: ${p.sur}m` : `S: ${p.sur}m`;

        if (p.oriente && p.oriente !== 'N/A') linea2 += `E: ${p.oriente}m`;
        if (p.poniente && p.poniente !== 'N/A') linea2 += linea2 ? `   O: ${p.poniente}m` : `O: ${p.poniente}m`;

        const medidasHTML = (linea1 || linea2) ? `
            <div class="medidas-2x2">
                ${linea1 ? `<div class="medida-line">${linea1}</div>` : ''}
                ${linea2 ? `<div class="medida-line">${linea2}</div>` : ''}
            </div>
        ` : '';

        return `
            <div class="popup-nelva-final">
                <div class="header-nelva">
                    <div class="titulo">Lote ${p.lote}</div>
                    <div class="status ${getStatusClass(p.estatus)}">
                        ${formatStatus(p.estatus)}
                    </div>
                </div>

                <div class="manzana-zona">
                    Manzana ${p.manzana}${zonaTexto ? ` • Zona ${zonaTexto}` : ''}
                </div>

                <div class="info">
                    <div class="row">
                        <span>Área</span>
                        <strong>${p.area_metros} m²</strong>
                    </div>
                    <div class="row">
                        <span>Precio m²</span>
                        <strong>$${p.precio_m2}</strong>
                    </div>
                    <div class="row total">
                        <span>Total</span>
                        <strong>$${parseFloat(p.costo_total).toLocaleString('es-MX')}</strong>
                    </div>
                </div>

                ${medidasHTML}

                ${isAvailable ? 
                    `<button class="btn-reservar" onclick="window.openReservationForLote('${p.lote}')">
                        Reservar
                    </button>` :
                    `<div class="no-disponible">No disponible</div>`
                }
            </div>
        `;
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

    // Inicializar el mapa
    setTimeout(initializeMap, 100);
});