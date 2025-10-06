document.addEventListener('DOMContentLoaded', function () {
    /* ===========================
       VERIFICACIÓN INICIAL DEL CONTENEDOR
       =========================== */
    const mapContainer = document.getElementById('mapPlano');
    
    if (!mapContainer) {
        console.warn('❌ Contenedor de mapa no encontrado (#mapPlano)');
        return;
    }

    /* ===========================
       VALIDAR AppConfig
       =========================== */
    if (!window.AppConfig || window.AppConfig.fraccionamientoId === null || !window.AppConfig.fraccionamientoNombre) {
        console.error('❌ Configuración de fraccionamiento no disponible, cargando datos de prueba');
        initializeMap();
        addLotesToMap(generateSampleData());
        return;
    }

    /* ===========================
       VARIABLES GLOBALES DEL MAPA
       =========================== */
    let map = null;
    let currentFilter = 'all';
    let lotesData = null;
    let is3DMode = false;
    let mapLayersLoaded = false;
    const mapStyles = {
        'satellite-streets': 'mapbox://styles/mapbox/satellite-streets-v12',
        'outdoors': 'mapbox://styles/mapbox/outdoors-v12',
        'streets': 'mapbox://styles/mapbox/streets-v12',
        'light': 'mapbox://styles/mapbox/light-v11'
    };

    /* ===========================
       UTIL / MAPA - STATUS MAPS
       =========================== */
    const STATUS_CLASS_MAP = {
        'disponible': 'status-disponible',
        'apartadoPalabra': 'status-apartado',
        'apartadoVendido': 'status-apartado',
        'vendido': 'status-vendido',
        'no disponible': 'status-no-disponible'
    };

    const STATUS_LABEL_MAP = {
        'disponible': 'Disponible',
        'apartadoPalabra': 'Apartado (Palabra)',
        'apartadoVendido': 'Apartado (Vendido)',
        'vendido': 'Vendido',
        'no disponible': 'No Disponible'
    };

    function getStatusClass(status) {
        return STATUS_CLASS_MAP[status] || 'status-no-disponible';
    }

    function formatStatus(status) {
        return STATUS_LABEL_MAP[status] || status || 'No Disponible';
    }

    /* ===========================
       BOTONES DE FILTRO
       =========================== */
    function initFilterButtons() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        console.log('🔄 Inicializando botones de filtro:', filterButtons.length);
        
        filterButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                console.log('🎯 Botón de filtro clickeado:', this.getAttribute('data-filter'));
                
                filterButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                currentFilter = this.getAttribute('data-filter');
                console.log('🔄 Aplicando filtro:', currentFilter);
                
                filterLotesByStatus(currentFilter);
            });
        });
    }

    function filterLotesByStatus(status) {
        if (!map || !mapLayersLoaded) {
            console.warn('⚠️ Mapa o capas no cargadas, no se puede aplicar filtro');
            return;
        }

        console.log('🎯 Filtrando lotes por estado:', status);

        try {
            if (status === 'all') {
                map.setFilter('lotes-fill', null);
                map.setFilter('lotes-borders', null);
                map.setFilter('lotes-labels', null);
                console.log('✅ Mostrando todos los lotes');
            } else if (status === 'apartado') {
                const filter = [
                    'any',
                    ['==', ['get', 'estatus'], 'apartadoPalabra'],
                    ['==', ['get', 'estatus'], 'apartadoVendido']
                ];
                map.setFilter('lotes-fill', filter);
                map.setFilter('lotes-borders', filter);
                map.setFilter('lotes-labels', filter);
                console.log('✅ Filtrando lotes apartados');
            } else {
                const filter = ['==', ['get', 'estatus'], status];
                map.setFilter('lotes-fill', filter);
                map.setFilter('lotes-borders', filter);
                map.setFilter('lotes-labels', filter);
                console.log('✅ Filtrando lotes con estado:', status);
            }
        } catch (error) {
            console.error('❌ Error aplicando filtro:', error);
        }
    }

    /* ===========================
       BOTONES DE ESTILO DE MAPA
       =========================== */
    function initStyleButtons() {
        const styleButtons = document.querySelectorAll('.style-btn');
        console.log('🔄 Inicializando botones de estilo:', styleButtons.length);
        
        styleButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                console.log('🎯 Botón de estilo clickeado:', this.getAttribute('data-style'));
                
                styleButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const style = this.getAttribute('data-style');
                console.log('🔄 Cambiando a estilo:', style);
                
                changeMapStyle(style);
            });
        });
    }

    function changeMapStyle(style) {
        if (!map) {
            console.error('❌ Mapa no inicializado');
            return;
        }

        if (!mapStyles[style]) {
            console.error('❌ Estilo no válido:', style);
            return;
        }

        console.log('🎨 Cambiando estilo del mapa a:', style);

        try {
            map.setStyle(mapStyles[style]);

            map.once('style.load', () => {
                console.log('✅ Estilo cargado, restaurando lotes...');
                
                if (lotesData) {
                    setTimeout(() => {
                        addLotesToMap(lotesData);
                    }, 500);
                }
                
                if (currentFilter && mapLayersLoaded) {
                    setTimeout(() => {
                        filterLotesByStatus(currentFilter);
                    }, 1000);
                }
            });

        } catch (error) {
            console.error('❌ Error cambiando estilo del mapa:', error);
        }
    }

    /* ===========================
       BOTÓN PANTALLA COMPLETA
       =========================== */
    function initFullscreenButton() {
        const fullscreenBtn = document.getElementById('fullscreenBtn');
        
        if (!fullscreenBtn) {
            console.error('❌ Botón de pantalla completa no encontrado');
            return;
        }

        fullscreenBtn.addEventListener('click', toggleFullscreenMap);
        console.log('✅ Botón de pantalla completa inicializado');
    }

    function toggleFullscreenMap() {
        const container = document.getElementById('planContainer');
        const fullscreenBtn = document.getElementById('fullscreenBtn');
        
        if (!container) {
            console.error('❌ Contenedor de mapa no encontrado');
            return;
        }

        console.log('🔄 Alternando pantalla completa');

        if (!document.fullscreenElement) {
            if (container.requestFullscreen) {
                container.requestFullscreen().catch(err => {
                    console.error('❌ Error al activar pantalla completa:', err);
                });
            } else if (container.webkitRequestFullscreen) {
                container.webkitRequestFullscreen();
            } else if (container.msRequestFullscreen) {
                container.msRequestFullscreen();
            }
            
            if (fullscreenBtn) {
                fullscreenBtn.innerHTML = '<i class="fas fa-compress"></i> Salir de Pantalla Completa';
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
            
            if (fullscreenBtn) {
                fullscreenBtn.innerHTML = '<i class="fas fa-expand"></i> Pantalla Completa';
            }
        }
    }

    document.addEventListener('fullscreenchange', handleFullscreenChange);
    document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
    document.addEventListener('msfullscreenchange', handleFullscreenChange);

    function handleFullscreenChange() {
        const fullscreenBtn = document.getElementById('fullscreenBtn');
        if (!fullscreenBtn) return;
        
        if (document.fullscreenElement) {
            fullscreenBtn.innerHTML = '<i class="fas fa-compress"></i> Salir de Pantalla Completa';
            console.log('📺 Modo pantalla completa activado');
            
            setTimeout(() => {
                if (map) {
                    map.resize();
                    console.log('✅ Mapa redimensionado para pantalla completa');
                }
            }, 300);
        } else {
            fullscreenBtn.innerHTML = '<i class="fas fa-expand"></i> Pantalla Completa';
            console.log('📺 Modo pantalla completa desactivado');
            
            setTimeout(() => {
                if (map) {
                    map.resize();
                    console.log('✅ Mapa redimensionado después de pantalla completa');
                }
            }, 300);
        }
    }

    /* ===========================
       INICIALIZACIÓN DEL MAPA
       =========================== */
    function initializeMap() {
        if (typeof mapboxgl === 'undefined') {
            console.error('❌ Mapbox GL JS no está cargado');
            return;
        }

        if (!mapContainer || mapContainer.offsetParent === null) {
            console.warn('❌ Contenedor de mapa no visible o no existe');
            return;
        }

        try {
            mapboxgl.accessToken = 'pk.eyJ1Ijoicm9qYXNkZXYiLCJhIjoiY21leDF4N2JtMTI0NTJrcHlsdjBiN2Y3YiJ9.RB87H34djrYH3WrRa-12Pg';
        } catch (err) {
            console.error('❌ Error con accessToken de Mapbox:', err);
            return;
        }

        try {
            map = new mapboxgl.Map({
                container: 'mapPlano',
                style: 'mapbox://styles/mapbox/satellite-streets-v12',
                center: [-99.1332, 19.4326],
                zoom: 10,
                pitch: 0,
                bearing: 0,
                antialias: true
            });

            map.on('load', () => {
                console.log('✅ Mapa cargado correctamente');
                initMapControls();
                initFilterButtons();
                initStyleButtons();
                initFullscreenButton();
                loadGeoJSONFromPublic();
            });

            map.on('error', (e) => {
                console.error('❌ Error en el mapa:', e.error);
            });

        } catch (error) {
            console.error('❌ Error creando el mapa:', error);
        }
    }

    /* ===========================
       CARGAR GEOJSON DESDE CARPETA PÚBLICA
       =========================== */
    async function loadGeoJSONFromPublic() {
        if (!map) {
            console.error('❌ Mapa no inicializado');
            return;
        }

        try {
            const fraccionamientoNombre = window.AppConfig.fraccionamientoNombre.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/\s+/g, '_');
            const geoJsonUrl = `/geojson/${fraccionamientoNombre}.geojson`;
            
            console.log('🔄 Cargando GeoJSON desde:', geoJsonUrl);
            
            const response = await fetch(geoJsonUrl);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status} - No se pudo cargar el archivo GeoJSON`);
            }
            
            const geoJsonData = await response.json();
            console.log('✅ GeoJSON cargado exitosamente');
            
            processGeoJSONData(geoJsonData);
            
        } catch (error) {
            console.error('❌ Error cargando GeoJSON:', error);
            addLotesToMap(generateSampleData());
        }
    }

    function processGeoJSONData(geoJsonData) {
        if (!geoJsonData || !geoJsonData.features || !Array.isArray(geoJsonData.features)) {
            throw new Error('Formato de GeoJSON inválido');
        }

        console.log('📊 Total de features en GeoJSON:', geoJsonData.features.length);
        
        const lotesFeatures = geoJsonData.features.filter((feature, index) => {
            if (index === 0) {
                console.log('✅ Excluyendo polígono del fraccionamiento:', feature.properties?.lote);
                return false;
            }
            return true;
        });

        console.log('🏠 Lotes a mostrar:', lotesFeatures.length);

        enrichGeoJSONWithServerData({
            ...geoJsonData,
            features: lotesFeatures
        });
    }

    async function enrichGeoJSONWithServerData(filteredGeoJsonData) {
        try {
            const fraccionamientoId = window.AppConfig.fraccionamientoId;
            const response = await fetch(`/asesor/fraccionamiento/${fraccionamientoId}/lotes`);
            
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            const serverData = await response.json();
            
            if (serverData.success && serverData.lotes) {
                const lotesMap = {};
                serverData.lotes.forEach(lote => {
                    lotesMap[lote.numeroLote] = lote;
                });

                filteredGeoJsonData.features.forEach(feature => {
                    const loteNumber = feature.properties.lote;
                    const serverLote = lotesMap[loteNumber];
                    
                    if (serverLote) {
                        feature.properties = {
                            ...feature.properties,
                            id: serverLote.id_lote,
                            lote: serverLote.numeroLote,
                            estatus: serverLote.estatus,
                            manzana: serverLote.manzana || 'N/A',
                            area: serverLote.area_total || 'N/A',
                            norte: serverLote.medidas?.norte || 'N/A',
                            sur: serverLote.medidas?.sur || 'N/A',
                            oriente: serverLote.medidas?.oriente || 'N/A',
                            poniente: serverLote.medidas?.poniente || 'N/A',
                            area_metros: serverLote.medidas?.area_metros || 'N/A'
                        };
                    } else {
                        feature.properties = {
                            ...feature.properties,
                            id: feature.properties.id || null,
                            lote: loteNumber,
                            estatus: 'disponible',
                            manzana: feature.properties.manzana || 'N/A',
                            area: 'N/A',
                            norte: 'N/A',
                            sur: 'N/A',
                            oriente: 'N/A',
                            poniente: 'N/A',
                            area_metros: 'N/A'
                        };
                    }
                });

                lotesData = filteredGeoJsonData;
                addLotesToMap(filteredGeoJsonData);
                
            } else {
                throw new Error(serverData.message || 'Error en los datos del servidor');
            }
        } catch (error) {
            console.error('❌ Error enriqueciendo GeoJSON:', error);
            
            filteredGeoJsonData.features.forEach(feature => {
                feature.properties = {
                    ...feature.properties,
                    id: feature.properties.id || null,
                    lote: feature.properties.lote || 'N/A',
                    estatus: feature.properties.estatus || 'disponible',
                    manzana: feature.properties.manzana || 'N/A',
                    area: 'N/A',
                    norte: 'N/A',
                    sur: 'N/A',
                    oriente: 'N/A',
                    poniente: 'N/A',
                    area_metros: 'N/A'
                };
            });
            
            lotesData = filteredGeoJsonData;
            addLotesToMap(filteredGeoJsonData);
        }
    }

    function generateSampleData() {
        return {
            type: "FeatureCollection",
            features: [
                {
                    type: "Feature",
                    properties: {
                        id: 1,
                        lote: "1",
                        estatus: "disponible",
                        manzana: "A",
                        area_metros: "250",
                        norte: "25",
                        sur: "25",
                        oriente: "10",
                        poniente: "10"
                    },
                    geometry: {
                        type: "Polygon",
                        coordinates: [[
                            [-96.778, 15.7346],
                            [-96.7778, 15.7346],
                            [-96.7778, 15.7348],
                            [-96.778, 15.7348],
                            [-96.778, 15.7346]
                        ]]
                    }
                },
                {
                    type: "Feature",
                    properties: {
                        id: 2,
                        lote: "2",
                        estatus: "vendido",
                        manzana: "A",
                        area_metros: "300",
                        norte: "30",
                        sur: "30",
                        oriente: "10",
                        poniente: "10"
                    },
                    geometry: {
                        type: "Polygon",
                        coordinates: [[
                            [-96.7778, 15.7346],
                            [-96.7776, 15.7346],
                            [-96.7776, 15.7348],
                            [-96.7778, 15.7348],
                            [-96.7778, 15.7346]
                        ]]
                    }
                },
                {
                    type: "Feature",
                    properties: {
                        id: 3,
                        lote: "3",
                        estatus: "apartadoPalabra",
                        manzana: "B",
                        area_metros: "280",
                        norte: "28",
                        sur: "28",
                        oriente: "10",
                        poniente: "10"
                    },
                    geometry: {
                        type: "Polygon",
                        coordinates: [[
                            [-96.778, 15.7344],
                            [-96.7778, 15.7344],
                            [-96.7778, 15.7346],
                            [-96.778, 15.7346],
                            [-96.778, 15.7344]
                        ]]
                    }
                }
            ]
        };
    }

    /* ===========================
       AJUSTAR MAPA A LOS LOTES
       =========================== */
    function fitMapToLotes(data) {
        if (!map || !data || !data.features || data.features.length === 0) {
            console.warn('⚠️ No hay datos para ajustar el mapa');
            return;
        }

        console.log('🗺️ Ajustando mapa a los lotes:', data.features.length, 'lotes');

        const bounds = new mapboxgl.LngLatBounds();

        data.features.forEach(feature => {
            if (!feature.geometry || !feature.geometry.coordinates) return;
            
            const coords = feature.geometry.coordinates;

            if (feature.geometry.type === 'Polygon' && Array.isArray(coords[0])) {
                coords[0].forEach(coord => {
                    if (Array.isArray(coord) && coord.length >= 2) {
                        bounds.extend(coord);
                    }
                });
            } else if (feature.geometry.type === 'MultiPolygon') {
                coords.forEach(polygon => {
                    polygon[0].forEach(coord => {
                        if (Array.isArray(coord) && coord.length >= 2) {
                            bounds.extend(coord);
                        }
                    });
                });
            }
        });

        if (!bounds.isEmpty()) {
            console.log('📍 Bounds calculados:', bounds);
            
            map.fitBounds(bounds, {
                padding: { top: 50, bottom: 50, left: 50, right: 50 },
                duration: 1500,
                maxZoom: 18
            });
            
            console.log('✅ Mapa ajustado a los lotes correctamente');
        } else {
            console.warn('⚠️ No se pudieron calcular bounds válidos');
        }
    }

    function addLotesToMap(data) {
        if (!map) return;

        try {
            if (map.getSource('lotes')) {
                if (map.getLayer('lotes-labels')) map.removeLayer('lotes-labels');
                if (map.getLayer('lotes-borders')) map.removeLayer('lotes-borders');
                if (map.getLayer('lotes-fill')) map.removeLayer('lotes-fill');
                map.removeSource('lotes');
            }

            map.addSource('lotes', {
                type: 'geojson',
                data: data
            });

            map.addLayer({
                id: 'lotes-fill',
                type: 'fill',
                source: 'lotes',
                paint: {
                    'fill-color': [
                        'match',
                        ['get', 'estatus'],
                        'disponible', '#16a34a',
                        'vendido', '#dc2626',
                        'apartadoPalabra', '#ea580c',
                        'apartadoVendido', '#ea580c',
                        '#6b7280'
                    ],
                    'fill-opacity': 0.7,
                    'fill-outline-color': '#ffffff'
                }
            });

            map.addLayer({
                id: 'lotes-borders',
                type: 'line',
                source: 'lotes',
                paint: {
                    'line-color': '#ffffff',
                    'line-width': 2,
                    'line-opacity': 0.9
                }
            });

            map.addLayer({
                id: 'lotes-labels',
                type: 'symbol',
                source: 'lotes',
                layout: {
                    'text-field': ['get', 'lote'],
                    'text-size': 14,
                    'text-font': ['Open Sans Bold', 'Arial Unicode MS Bold']
                },
                paint: {
                    'text-color': '#ffffff',
                    'text-halo-color': '#000000',
                    'text-halo-width': 1
                }
            });

            mapLayersLoaded = true;
            console.log('✅ Capas de lotes cargadas correctamente');

            setTimeout(() => {
                fitMapToLotes(data);
            }, 500);

            setupMapInteractions();

            if (currentFilter) {
                setTimeout(() => {
                    filterLotesByStatus(currentFilter);
                }, 1000);
            }

        } catch (error) {
            console.error('❌ Error agregando lotes al mapa:', error);
        }
    }

    /* ===========================
       INTERACCIONES DEL MAPA
       =========================== */
    function setupMapInteractions() {
        if (!map.getLayer('lotes-fill')) {
            console.warn('⚠️ Capa de lotes no disponible para interacciones');
            return;
        }

        console.log('🎯 Configurando interacciones del mapa');

        const popup = new mapboxgl.Popup({
            closeButton: true,
            closeOnClick: true,
            maxWidth: '280px',
            className: 'lote-popup-compact'
        });

        map.on('mouseenter', 'lotes-fill', () => {
            map.getCanvas().style.cursor = 'pointer';
        });

        map.on('mouseleave', 'lotes-fill', () => {
            map.getCanvas().style.cursor = '';
        });

        map.on('click', 'lotes-fill', (e) => {
            const properties = e.features[0].properties;
            console.log('📍 Lote clickeado:', properties.lote);
            
            popup.remove();
            
            popup.setLngLat(e.lngLat)
                 .setHTML(createCompactPopupContent(properties))
                 .addTo(map);
        });

        console.log('✅ Interacciones del mapa configuradas correctamente');
    }

    function createCompactPopupContent(properties) {
        const statusClass = getStatusClass(properties.estatus);
        const statusText = formatStatus(properties.estatus);
        
        return `
            <div class="popup-compact-container">
                <div class="popup-compact-header">
                    <div class="popup-compact-title">
                        <span class="lote-number">Lote ${properties.lote}</span>
                        <span class="popup-status ${statusClass}">${statusText}</span>
                    </div>
                    <div class="popup-compact-subtitle">
                        <i class="fas fa-layer-group"></i>
                        Manzana ${properties.manzana}
                    </div>
                </div>

                <div class="popup-compact-info">
                    <div class="compact-info-item">
                        <i class="fas fa-vector-square"></i>
                        <span>${properties.area_metros} m²</span>
                    </div>
                </div>

                <div class="popup-compact-measures">
                    <div class="compact-measure-row">
                        <div class="measure-compact">
                            <span class="measure-direction">N</span>
                            <span class="measure-value">${properties.norte}m</span>
                        </div>
                        <div class="measure-compact">
                            <span class="measure-direction">S</span>
                            <span class="measure-value">${properties.sur}m</span>
                        </div>
                    </div>
                    <div class="compact-measure-row">
                        <div class="measure-compact">
                            <span class="measure-direction">E</span>
                            <span class="measure-value">${properties.oriente}m</span>
                        </div>
                        <div class="measure-compact">
                            <span class="measure-direction">O</span>
                            <span class="measure-value">${properties.poniente}m</span>
                        </div>
                    </div>
                </div>

                <div class="popup-compact-actions">
                    <button class="btn-compact btn-calculate" onclick="window.openCalculationForLote('${properties.lote}')">
                        <i class="fas fa-calculator"></i>
                    </button>
                    <button class="btn-compact btn-reserve" onclick="window.openReservationForLote('${properties.lote}')">
                        <i class="fas fa-handshake"></i>
                    </button>
                </div>
            </div>
        `;
    }

    /* ===========================
       CONTROLES DEL MAPA
       =========================== */
    function initMapControls() {
        const customControls = document.createElement('div');
        customControls.className = 'custom-map-controls';
        customControls.style.position = 'absolute';
        customControls.style.top = '50%';
        customControls.style.right = '20px';
        customControls.style.transform = 'translateY(-50%)';
        customControls.style.zIndex = '10';
        customControls.style.display = 'flex';
        customControls.style.flexDirection = 'column';
        customControls.style.gap = '10px';

        const navControl = document.createElement('div');
        navControl.className = 'custom-nav-control';
        navControl.innerHTML = `
            <button class="custom-control-btn zoom-in" title="Acercar">
                <i class="fas fa-plus"></i>
            </button>
            <button class="custom-control-btn zoom-out" title="Alejar">
                <i class="fas fa-minus"></i>
            </button>
            <button class="custom-control-btn compass" title="Restablecer norte">
                <i class="fas fa-compass"></i>
            </button>
        `;

        const toggle3DButton = document.createElement('button');
        toggle3DButton.className = 'custom-control-btn toggle-3d';
        toggle3DButton.title = 'Activar/Desactivar Vista 3D';
        toggle3DButton.innerHTML = '<i class="fas fa-cube"></i>';

        const rotateControl = document.createElement('div');
        rotateControl.className = 'custom-rotate-control';
        rotateControl.innerHTML = `
            <button class="custom-control-btn rotate-left" title="Rotar izquierda">
                <i class="fas fa-undo"></i>
            </button>
            <button class="custom-control-btn rotate-right" title="Rotar derecha">
                <i class="fas fa-redo"></i>
            </button>
        `;

        customControls.appendChild(navControl);
        customControls.appendChild(toggle3DButton);
        customControls.appendChild(rotateControl);

        mapContainer.appendChild(customControls);

        setupCustomControls(customControls);
    }

    function setupCustomControls(controlsContainer) {
        controlsContainer.querySelector('.zoom-in').addEventListener('click', () => {
            map.zoomIn();
        });

        controlsContainer.querySelector('.zoom-out').addEventListener('click', () => {
            map.zoomOut();
        });

        controlsContainer.querySelector('.compass').addEventListener('click', () => {
            map.easeTo({
                bearing: 0,
                pitch: 0,
                duration: 1000
            });
        });

        controlsContainer.querySelector('.toggle-3d').addEventListener('click', toggle3DMode);

        controlsContainer.querySelector('.rotate-left').addEventListener('click', () => {
            map.easeTo({
                bearing: map.getBearing() - 45,
                duration: 500
            });
        });

        controlsContainer.querySelector('.rotate-right').addEventListener('click', () => {
            map.easeTo({
                bearing: map.getBearing() + 45,
                duration: 500
            });
        });
    }

    function toggle3DMode() {
        is3DMode = !is3DMode;
        const toggle3DButton = document.querySelector('.toggle-3d');
        
        if (is3DMode) {
            if (!map.getSource('mapbox-dem')) {
                map.addSource('mapbox-dem', {
                    'type': 'raster-dem',
                    'url': 'mapbox://mapbox.mapbox-terrain-dem-v1',
                    'tileSize': 512,
                    'maxzoom': 14
                });
            }
            
            map.once('idle', () => {
                map.setTerrain({ 'source': 'mapbox-dem', 'exaggeration': 1.5 });
            });
            
            map.easeTo({
                pitch: 60,
                bearing: -17,
                duration: 1000
            });
            
            toggle3DButton.innerHTML = '<i class="fas fa-cube" style="color: #3b82f6;"></i>';
            toggle3DButton.style.background = '#e0f2fe';
        } else {
            map.setTerrain(null);
            
            map.easeTo({
                pitch: 0,
                bearing: 0,
                duration: 1000
            });
            
            toggle3DButton.innerHTML = '<i class="fas fa-cube"></i>';
            toggle3DButton.style.background = '';
        }
    }

    /* ===========================
       INICIALIZACIÓN FINAL
       =========================== */
    setTimeout(() => {
        initializeMap();
    }, 100);

    console.log('✅ Script de mapa cargado correctamente');
});