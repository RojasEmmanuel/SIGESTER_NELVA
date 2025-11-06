document.addEventListener('DOMContentLoaded', function () {
    /* ===========================
       VERIFICACIÓN INICIAL DEL CONTENEDOR
       =========================== */
    const mapContainer = document.getElementById('mapPlano');
    
    if (!mapContainer) {
        return;
    }

    /* ===========================
       VALIDAR AppConfig
       =========================== */
    if (!window.AppConfig || window.AppConfig.fraccionamientoId === null || !window.AppConfig.fraccionamientoNombre) {
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
    let fraccionamientoFeature = null; // Nuevo: para el perímetro del fraccionamiento
    let is3DMode = false;
    let mapLayersLoaded = false;
    const mapStyles = {
        'satellite-streets': 'mapbox://styles/mapbox/satellite-streets-v12',
        'streets': 'mapbox://styles/mapbox/streets-v12',
        'light': 'mapbox://styles/mapbox/light-v11',
        'dark': 'mapbox://styles/mapbox/dark-v11',
        'standard': 'mapbox://styles/mapbox/standard-v1',
        'tourist': 'mapbox://styles/your-username/custom-tourist-style' // Reemplaza con tu ID de estilo personalizado
    };

    /* ===========================
       UTIL / MAPA - STATUS MAPS
       =========================== */
    const STATUS_CLASS_MAP = {
        'disponible': 'status-disponible',
        'apartadoPalabra': 'status-apartado',
        'apartadoDeposito': 'status-apartado',
        'vendido': 'status-vendido'
    };

    const STATUS_LABEL_MAP = {
        'disponible': 'Disponible',
        'apartadoPalabra': 'Apartado (Palabra)',
        'apartadoDeposito': 'Apartado (Depósito)',
        'vendido': 'Vendido'
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
        
        filterButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                
                filterButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                currentFilter = this.getAttribute('data-filter');
                
                filterLotesByStatus(currentFilter);
            });
        });
    }

    function filterLotesByStatus(status) {
        if (!map || !mapLayersLoaded) {
            return;
        }

        console.log('🎯 Filtrando lotes por estado:', status);

        try {
            if (status === 'all') {
                map.setFilter('lotes-fill', null);
                map.setFilter('lotes-borders', null);
                map.setFilter('lotes-labels', null);
            } else if (status === 'apartado-palabra-deposito') {
                const filter = [
                    'any',
                    ['==', ['get', 'estatus'], 'apartadoPalabra'],
                    ['==', ['get', 'estatus'], 'apartadoDeposito']
                ];
                map.setFilter('lotes-fill', filter);
                map.setFilter('lotes-borders', filter);
                map.setFilter('lotes-labels', filter);
            } else {
                const filter = ['==', ['get', 'estatus'], status];
                map.setFilter('lotes-fill', filter);
                map.setFilter('lotes-borders', filter);
                map.setFilter('lotes-labels', filter);
            }
        } catch (error) {
        }
    }

    /* ===========================
       BOTONES DE ESTILO DE MAPA
       =========================== */
    function initStyleButtons() {
        const styleButtons = document.querySelectorAll('.style-btn');
        
        styleButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                
                styleButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const style = this.getAttribute('data-style');
                
                changeMapStyle(style);
            });
        });
    }

    function changeMapStyle(style) {
        if (!map) {
            return;
        }

        if (!mapStyles[style]) {
            return;
        }


        try {
            map.setStyle(mapStyles[style]);

            map.once('style.load', () => {
                
                // Restaurar perímetro del fraccionamiento si existe (agregar sin before, se moverá después)
                if (fraccionamientoFeature) {
                    setTimeout(() => {
                        addFraccionamientoPerimeter(fraccionamientoFeature);
                    }, 500);
                }
                
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
            return;
        }

        fullscreenBtn.addEventListener('click', toggleFullscreenMap);
    }

    function toggleFullscreenMap() {
        const container = document.getElementById('planContainer');
        const fullscreenBtn = document.getElementById('fullscreenBtn');
        
        if (!container) {
            return;
        }


        if (!document.fullscreenElement) {
            if (container.requestFullscreen) {
                container.requestFullscreen().catch(err => {
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
            
            setTimeout(() => {
                if (map) {
                    map.resize();
                }
            }, 300);
        } else {
            fullscreenBtn.innerHTML = '<i class="fas fa-expand"></i> Pantalla Completa';
            
            setTimeout(() => {
                if (map) {
                    map.resize();
                }
            }, 300);
        }
    }

    /* ===========================
       INICIALIZACIÓN DEL MAPA
       =========================== */
    function initializeMap() {
        if (typeof mapboxgl === 'undefined') {
            return;
        }

        if (!mapContainer || mapContainer.offsetParent === null) {
            return;
        }

        try {
            mapboxgl.accessToken = 'pk.eyJ1Ijoicm9qYXNkZXYiLCJhIjoiY21leDF4N2JtMTI0NTJrcHlsdjBiN2Y3YiJ9.RB87H34djrYH3WrRa-12Pg';
        } catch (err) {
            return;
        }

        try {
            map = new mapboxgl.Map({
                container: 'mapPlano',
                style: 'mapbox://styles/mapbox/satellite-streets-v12',
                center: [-96.778, 15.7345], // Centro ajustado aproximado al área del GeoJSON proporcionado
                zoom: 18, // Zoom más cercano para ver detalles de lotes pequeños
                pitch: 0,
                bearing: 0,
                antialias: true
            });

            map.on('load', () => {
                initMapControls();
                initFilterButtons();
                initStyleButtons();
                initFullscreenButton();
                loadGeoJSONFromPublic(); // Ahora usa el GeoJSON hardcodeado o fallback
            });

            map.on('error', (e) => {
            });

        } catch (error) {
        }
    }

    /* ===========================
       CARGAR GEOJSON DESDE CARPETA PÚBLICA (MODIFICADO PARA HARDcode SI FALLA)
       =========================== */
    async function loadGeoJSONFromPublic() {
        if (!map) {
            return;
        }

        let geoJsonData = null;

        // Intentar cargar desde servidor
        if (window.AppConfig && window.AppConfig.fraccionamientoNombre) {
            try {
                const fraccionamientoNombre = window.AppConfig.fraccionamientoNombre.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/\s+/g, '_');
                const geoJsonUrl = `/geojson/${fraccionamientoNombre}.geojson`;
                
                
                const response = await fetch(geoJsonUrl);
                if (response.ok) {
                    geoJsonData = await response.json();
                }
            } catch (error) {
            }
        }

        // Si no se cargó, usar el proporcionado hardcodeado
        
        
        processGeoJSONData(geoJsonData);
    }

    function processGeoJSONData(geoJsonData) {
        if (!geoJsonData || !geoJsonData.features || !Array.isArray(geoJsonData.features)) {
            throw new Error('Formato de GeoJSON inválido');
        }

        
        // Separar el perímetro del fraccionamiento (primer feature con lote: "Fraccionamiento")
        fraccionamientoFeature = geoJsonData.features[0];
        if (fraccionamientoFeature.properties.lote === "Fraccionamiento") {
            addFraccionamientoPerimeter(fraccionamientoFeature);
            geoJsonData.features.shift(); // Remover del array principal
        }

        const lotesFeatures = geoJsonData.features; // Los restantes son lotes

        console.log('🏠 Lotes a mostrar:', lotesFeatures.length);

        enrichGeoJSONWithServerData({
            ...geoJsonData,
            features: lotesFeatures
        });
    }

    // Nueva función para agregar el perímetro (agregar sin before para evitar errores, se moverá después)
    function addFraccionamientoPerimeter(feature) {
        if (!map || !feature) return;

        const sourceId = 'fraccionamiento-source';
        const fillLayerId = 'fraccionamiento-fill';
        const borderLayerId = 'fraccionamiento-border';

        // Remover si existe
        if (map.getLayer(fillLayerId)) map.removeLayer(fillLayerId);
        if (map.getLayer(borderLayerId)) map.removeLayer(borderLayerId);
        if (map.getSource(sourceId)) map.removeSource(sourceId);

        map.addSource(sourceId, {
            type: 'geojson',
            data: feature
        });

        // Fondo con opacidad para ver el satélite debajo
        map.addLayer({
            id: fillLayerId,
            type: 'fill',
            source: sourceId,
            paint: {
                'fill-color': 'rgb(91,91,91)', // Gris suave para fondo
                'fill-opacity': 0.9 // Baja opacidad para ver el fondo
            }
        });

        // Borde destacado
        map.addLayer({
            id: borderLayerId,
            type: 'line',
            source: sourceId,
            paint: {
                'line-color': 'rgb(255,255,255)',
                'line-width': 1,
                'line-opacity': 1
            }
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
        // Mantener fallback, pero ahora usa el hardcodeado arriba
        return lotesData || {
            type: "FeatureCollection",
            features: [] // Vacío ya que usamos hardcode
        };
    }

    /* ===========================
       AJUSTAR MAPA A LOS LOTES (INCLUYE PERÍMETRO SI EXISTE)
       =========================== */
    function fitMapToLotes(data) {
        if (!map || !data || !data.features || data.features.length === 0) {
            console.warn('⚠️ No hay datos para ajustar el mapa');
            return;
        }

        console.log('🗺️ Ajustando mapa a los lotes:', data.features.length, 'lotes');

        const bounds = new mapboxgl.LngLatBounds();

        // Extender con lotes
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

        // Extender con perímetro si existe
        if (fraccionamientoFeature && fraccionamientoFeature.geometry.coordinates) {
            fraccionamientoFeature.geometry.coordinates[0].forEach(coord => {
                if (Array.isArray(coord) && coord.length >= 2) {
                    bounds.extend(coord);
                }
            });
        }

        if (!bounds.isEmpty()) {
            
            map.fitBounds(bounds, {
                padding: { top: 50, bottom: 50, left: 50, right: 50 },
                duration: 1500,
                maxZoom: 20 // Aumentado para lotes pequeños
            });
            
        } else {
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

            // Añadir capas de lotes
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
                        'apartadoDeposito', '#ea580c',
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
                    'text-field': ['to-string', ['get', 'lote']], // Convertir a string por si es número
                    'text-size': 14,
                    'text-font': ['Open Sans Bold', 'Arial Unicode MS Bold']
                },
                paint: {
                    'text-color': '#ffffff',
                    'text-halo-color': '#000000',
                    'text-halo-width': 1
                }
            });

            // Mover el perímetro detrás si existe (usando moveLayer con beforeId 'lotes-fill')
            if (map.getLayer('fraccionamiento-fill')) {
                try {
                    map.moveLayer('fraccionamiento-fill', 'lotes-fill');
                    map.moveLayer('fraccionamiento-border', 'lotes-fill');
                } catch (moveError) {
                }
            }

            mapLayersLoaded = true;

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
        }
    }

    /* ===========================
       INTERACCIONES DEL MAPA (IGNORAR PERÍMETRO EN CLICK Y POPUP)
       =========================== */
    function setupMapInteractions() {
        if (!map.getLayer('lotes-fill')) {
            return;
        }


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

        // No agregar interacciones al perímetro (no cursor, no click)

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

});