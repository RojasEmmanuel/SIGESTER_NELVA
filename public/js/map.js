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
    let zonasData = {}; // Objeto para almacenar los datos de zonas

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
        
        /* POPUP NELVA - 100% IGUAL A TU FOTO Y PERFECTO EN MÓVILES */
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
            padding: 10px 16px;
            font-size: 13px;
            color: #000;
            line-height: 1.5;
            text-align: center;
            border-top: 1px solid #eee;
        }

        .medidas-2x2 div {
            display: inline-block;
            width: 100%;
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
            .no-disponible, .btn-reservar { font-size: 12px; padding: 8px; margin: 5px 12px 12px;
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

            .lote-number {
                font-size: 16px;
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

            // Cargar datos de zonas
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

   /* ===========================
   CARGAR DATOS DE ZONAS
   =========================== */
    async function loadZonasData(fraccionamientoId) {
        try {
            const zonasRes = await fetch(`/asesor/fraccionamiento/${fraccionamientoId}/zonas`);
            if (zonasRes.ok) {
                const zonasResponse = await zonasRes.json();
                if (zonasResponse.success && zonasResponse.zonas) {
                    // Limpiar y cargar datos de zonas
                    zonasData = {};
                    zonasResponse.zonas.forEach(zona => {
                        const key = zona.nombre.toLowerCase().trim();
                        // Validar que el color exista y sea válido
                        if (zona.color && zona.color !== 'undefined' && zona.color !== 'null') {
                            zonasData[key] = {
                                nombre: zona.nombre,
                                color: zona.color
                            };
                        } else {
                            console.warn(`Zona "${zona.nombre}" no tiene color válido:`, zona.color);
                        }
                    });
                    console.log('Zonas cargadas:', zonasData);
                }
            }
        } catch (e) {
            console.error('Error loading zones data:', e);
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

        // Construir la expresión de color para bordes dinámicamente
        const zonasKeys = Object.keys(zonasData);
        let lineColorExpression;
        
        if (zonasKeys.length > 0) {
            // Si hay zonas cargadas, crear expresión case
            // Filtrar zonas que tengan color definido
            const caseExpressions = zonasKeys.flatMap(key => {
                const color = zonasData[key].color;
                // Solo incluir si el color está definido y no es null/undefined
                if (color && color !== 'undefined' && color !== 'null') {
                    return [
                        ['==', ['get', 'zona'], key],
                        color
                    ];
                }
                return []; // Excluir zonas sin color
            }).filter(expr => expr.length > 0); // Filtrar arrays vacíos
            
            // Si hay expresiones válidas, crear el case, sino usar color por defecto
            if (caseExpressions.length > 0) {
                lineColorExpression = [
                    'case',
                    ...caseExpressions,
                    '#ffffff' // Color por defecto si no coincide con ninguna zona
                ];
            } else {
                lineColorExpression = '#ffffff';
            }
        } else {
            // Si no hay zonas, usar color blanco por defecto
            lineColorExpression = '#ffffff';
        }

        // Bordes por zona - usando colores de la BD
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

        // Medidas de 2 en 2
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