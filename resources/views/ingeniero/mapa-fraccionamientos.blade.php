{{-- resources/views/ing/mapa-fraccionamientos.blade.php --}}
@extends('ingeniero.navbar')

@section('title', 'Mapa de Fraccionamientos')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Creación de mapas interactivos</h4>
                    <div class="card-header-subtitle">
                        <span>Carga un archivo CSV con coordenadas en sistema decimal para generar el archivo GeoJSON del plano interactivo</span>
                    </div>
                    
                </div>

                <div class="card-body p-0">
                    <div class="p-4 bg-light border-bottom">
                        <div class="row align-items-end" style="display: flex; justify-content: space-between">
                            <div class="col-md-6" style="margin-left: 13px">
                                <label for="fraccionamientoSelect" class="form-label fw-bold" style="font-size: 1.1rem; color:#1557b0;">Selecciona un fraccionamiento</label>
                                <select id="fraccionamientoSelect" class="form-select form-select-lg">
                                    <option value=""> Elige </option>
                                    @foreach($fraccionamientos as $f)
                                        <option value="{{ $f->id_fraccionamiento }}">{{ $f->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="text-muted small" style="font-size: 1.1rem; color:#1557b0;">lotes cargados: <strong id="lotesCount">0</strong> </div>
                            </div>
                        </div>
                    </div>

                    <div id="mapContainer" style="position: relative; height: 70vh;">
                        <div id="mapPlano" style="width: 100%; height: 100%;"></div>
                        <div id="loadingOverlay" class="position-absolute top-50 start-50 translate-middle text-center bg-white p-4 rounded shadow" style="z-index: 10; display: none;">
                            <div class="spinner-border text-primary" role="status"></div>
                            <div class="mt-2">Cargando mapa...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===================== MODAL CON VISTA PREVIA (2 PASOS) ===================== -->
<div id="csvUploadModal" class="custom-modal">
    <div class="custom-modal-backdrop"></div>
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 id="modalTitle">Subir coordenadas del fraccionamiento</h5>
            <button type="button" class="custom-modal-close">×</button>
        </div>

        <!-- Paso 1 -->
        <div id="stepUpload" class="modal-step active">
            <div class="custom-modal-body">
                <div class="custom-alert">
                    <strong>Formato del CSV:</strong><br>
                    <code>lote,lat1,lng1,lat2,lng2,lat3,lng3,...</code><br><br>
                    • Primera fila → lote <code>0</code> (contorno del fraccionamiento)<br>
                    • Cada fila = un lote (polígono cerrado)<br>
                    • Sin comillas · Solo comas
                </div>

                <form id="csvForm">
                    @csrf
                    <input type="hidden" id="fracIdForCsv">
                    <input type="hidden" id="fracNombre">

                    <div class="custom-form-group">
                        <label class="custom-label">Archivo CSV</label>
                        <input type="file" id="csvFile" accept=".csv,text/csv" required class="custom-input-file">
                    </div>

                    <div class="custom-modal-actions">
                        <button type="button" class="custom-btn custom-btn-secondary" id="cancelCsv">Cancelar</button>
                        <button type="submit" class="custom-btn custom-btn-primary">Procesar CSV</button>
                    </div>
                </form>
            </div>

            <div class="custom-modal-footer" id="csvProcessing" style="display:none;">
                <div class="spinner"></div>
                <span>Procesando archivo...</span>
            </div>
        </div>

        <!-- Paso 2: Vista previa -->
        <div id="stepPreview" class="modal-step">
            <div id="previewMap" style="height:440px;"></div>
            <div class="custom-modal-body">
                <div class="custom-alert" style="background:#e6f4ea;border-left-color:#34a853;">
                    Se generaron <strong id="previewLotesCount">0</strong> lotes. ¿Está todo correcto?
                </div>
                <div class="custom-modal-actions">
                    <button type="button" class="custom-btn custom-btn-secondary" id="backToUpload">Volver</button>
                    <button type="button" class="custom-btn custom-btn-primary" id="confirmSave">Confirmar y Guardar .geojson</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://api.mapbox.com/mapbox-gl-js/v3.7.0/mapbox-gl.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

<style>
    /* ===================== TUS ESTILOS ORIGINALES (LÍNEA POR LÍNEA, SIN COMPRIMIR) ===================== */
    body {
        background: #f8fafc;
        color: #3c4043;
    }

    .card.shadow-lg {
        border-radius: 16px !important;
        overflow: hidden;
        margin: 24px auto;
        max-width: 1200px;
    }

    .card-header {
        padding: 15px !important;
        border-bottom: none;
        margin-bottom: 20px;
    }
    .card-header h4 {
        font-size: 1.8rem;
        font-weight: 600;
        letter-spacing: 0.0125em;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .card-header i {
        font-size: 28px;
    }

    .card-header-subtitle{
        margin-top: 13px;
    }

    #fraccionamientoSelect {
        height: 45px;
        border-radius: 8px;
        border: 1px solid #dadce0;
        background: white;
        padding: 10px;
        font-size: 0.9rem;
        font-weight: 500;
        color: #3c4043;
        box-shadow: 0 1px 2px 0 rgba(60,64,67,0.3), 0 1px 3px 1px rgba(60,64,67,0.15);
        transition: all 0.2s ease;
        margin-left: 20px;
    }
    #fraccionamientoSelect:focus {
        border-color: #1a73e8;
        box-shadow: 0 0 0 3px rgba(26,115,232,0.2), 0 4px 8px rgba(26,115,232,0.15);
        outline: none;
    }

    #lotesCount {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a73e8;
        display: inline-block;
        text-align: center;
    }

    #mapContainer {
        height: 75vh;
        margin: 24px;
        border-radius: 13px;
        overflow: hidden;
        position: relative;
    }
    #mapPlano {
        width: 100%;
        height: 100%;
        border-radius: 16px;
    }

    #loadingOverlay {
        background: white;
        border-radius: 16px;
        padding: 32px 48px;
        box-shadow: 0 8px 10px -5px rgba(0,0,0,0.2), 0 16px 24px 2px rgba(0,0,0,0.14), 0 6px 30px 5px rgba(0,0,0,0.12);
        min-width: 240px;
    }
    #loadingOverlay .spinner-border {
        width: 48px;
        height: 48px;
        border-width: 4px;
        color: #1a73e8;
    }

    .mapboxgl-popup { z-index: 10; }
    .mapboxgl-popup-content {
        border-radius: 16px !important;
        padding: 0 !important;
        box-shadow: 0 8px 10px -5px rgba(0,0,0,0.2), 0 16px 24px 2px rgba(0,0,0,0.14), 0 6px 30px 5px rgba(0,0,0,0.12) !important;
        overflow: hidden;
    }
    .mapboxgl-popup-tip {
        border-top-color: white !important;
        box-shadow: 0 -2px 4px rgba(0,0,0,0.1);
    }

    @media (max-width: 992px) {
        .card.shadow-lg { margin: 16px; }
        #mapContainer { margin: 16px; height: 75vh; }
    }
    @media (max-width: 600px) {
        .card-header h4 { font-size: 1.25rem; }
        #lotesCount { font-size: 1.5rem; padding: 6px 16px; }
        #mapContainer { margin: 12px; height: 70vh; border-radius: 12px; }
    }

    /* ===================== MODAL 100% TU ESTILO ORIGINAL ===================== */
    .custom-modal {
        position: fixed;
        inset: 0;
        z-index: 1050;
        display: none;
        align-items: center;
        justify-content: center;
    }
    .custom-modal.show {
        display: flex;
    }
    .custom-modal-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.2);
        backdrop-filter: blur(3px);
    }
    .custom-modal-content {
        position: relative;
        background: white;
        border-radius: 16px;
        box-shadow: 0 11px 15px -7px rgba(0,0,0,0.2),
                    0 24px 38px 3px rgba(0,0,0,0.14),
                    0 9px 46px 8px rgba(0,0,0,0.12);
        width: 90%;
        max-width: 750px;
        max-height: 95vh;
        overflow: hidden;
        animation: modalIn 0.3s ease-out;
    }
    @keyframes modalIn {
        from { opacity: 0; transform: translateY(-50px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .custom-modal-header {
        background: white;
        color: #1557b0;
        padding: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
         border-bottom: 2px solid #dadce0;
    }
    .custom-modal-header h5 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 600;
        margin-left: 20px;
       
    }
    .custom-modal-close {
        background: none;
        border: none;
        color: #1557b0;
        font-size: 36px;
        cursor: pointer;
        width: 48px; height:48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .custom-modal-close:hover { background: rgba(0,255,255,0.2); }

    .custom-modal-body { padding: 32px; max-height: 60vh; overflow-y: auto; }
    .custom-alert {
        background: #e8f0fe;
        border-left: 5px solid #1a73e8;
        padding: 16px;
        border-radius: 0 8px 8px 0;
        margin-bottom: 24px;
        font-size: 0.95rem;
        line-height: 1.5;
    }
    .custom-form-group { margin-bottom: 24px; }
    .custom-label {
        display: block;
        font-weight: 500;
        margin-bottom: 8px;
        color: #3c4043;
    }
    .custom-input-file {
        width: 100%;
        padding: 16px;
        border: 2px dashed #dadce0;
        border-radius: 12px;
        background: #f8f9fa;
        font-size: 1rem;
        transition: all 0.2s;
    }
    .custom-input-file:focus {
        border-color: #1a73e8;
        background: white;
        outline: none;
        box-shadow: 0 0 0 3px rgba(26,115,232,0.2);
    }
    .custom-modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 16px;
        margin-top: 32px;
    }
    .custom-btn {
        padding: 12px 28px;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        min-width: 120px;
        transition: all 0.2s;
    }
    .custom-btn-secondary {
        background: white;
        color: #5f6368;
        border: 1px solid #dadce0;
    }
    .custom-btn-secondary:hover { background: #f1f3f4; }
    .custom-btn-primary {
        background: #1a73e8;
        color: white;
        border: none;
    }
    .custom-btn-primary:hover { background: #1557b0; }

    .custom-modal-footer {
        padding: 20px;
        background: #f8f9fa;
        text-align: center;
        font-weight: 500;
    }
    .spinner {
        width: 32px;
        height: 32px;
        border: 4px solid #e3e3e3;
        border-top: 4px solid #1a73e8;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        display: inline-block;
        margin-right: 12px;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* Necesario para los dos pasos */
    .modal-step { display: none; }
    .modal-step.active { display: block; }
</style>
@endpush

@push('scripts')
<script src="https://api.mapbox.com/mapbox-gl-js/v3.7.0/mapbox-gl.js"></script>
@include('ingeniero.partials.map-script')

<script>
    // CAMBIA ESTO POR TU TOKEN REAL
    mapboxgl.accessToken = 'pk.eyJ1Ijoicm9qYXNkZXYiLCJhIjoiY21leDF4N2JtMTI0NTJrcHlsdjBiN2Y3YiJ9.RB87H34djrYH3WrRa-12Pg';

    const select = document.getElementById('fraccionamientoSelect');
    const loading = document.getElementById('loadingOverlay');
    const countEl = document.getElementById('lotesCount');
    let currentId = null;
    let currentNombre = '';
    let generatedGeoJSON = null;
    let previewMap = null;

    // === CONTROL MODAL ===
    function openCsvModal() {
        document.getElementById('csvUploadModal').classList.add('show');
        document.body.style.overflow = 'hidden';
        document.getElementById('stepUpload').classList.add('active');
        document.getElementById('stepPreview').classList.remove('active');
    }
    function closeCsvModal() {
        document.getElementById('csvUploadModal').classList.remove('show');
        document.body.style.overflow = '';
        if (previewMap) previewMap.remove();
        generatedGeoJSON = null;
    }

    document.querySelector('.custom-modal-close').onclick = closeCsvModal;
    document.querySelector('.custom-modal-backdrop').onclick = closeCsvModal;
    document.getElementById('cancelCsv').onclick = closeCsvModal;
    document.getElementById('backToUpload').onclick = () => {
        document.getElementById('stepPreview').classList.remove('active');
        document.getElementById('stepUpload').classList.add('active');
        if (previewMap) previewMap.remove();
    };

    // === CARGA DE FRACCIONAMIENTO ===
    select.addEventListener('change', function() {
        const id = this.value;
        if (!id) {
            if (window.map) window.map.remove();
            countEl.textContent = '0';
            return;
        }
        if (currentId === id) return;

        currentNombre = this.options[this.selectedIndex].textContent.trim();
        loading.style.display = 'block';
        currentId = id;

        fetch(`/ing/fraccionamiento/${id}/geojson-data?_=${Date.now()}`)
            .then(r => r.ok ? r.json() : Promise.reject())
            .then(data => {
                if (data.geojson && data.geojson.features?.length > 1) {
                    window.AppConfig = { fraccionamientoId: data.fraccionamiento.id, fraccionamientoNombre: data.fraccionamiento.nombre };
                    initializeMap();
                    map.on('load', () => {
                        processGeoJSONData(data.geojson);
                        countEl.textContent = (data.geojson.features.length - 1) || 0;
                    });
                } else {
                    document.getElementById('fracIdForCsv').value = id;
                    document.getElementById('fracNombre').value = currentNombre;
                    openCsvModal();
                }
            })
            .catch(() => {
                alert('Error al cargar');
                select.value = '';
            })
            .finally(() => loading.style.display = 'none');
    });

   
   // === PROCESAR CSV → VISTA PREVIA ===
    document.getElementById('csvForm').onsubmit = function(e) {
        e.preventDefault();
        const file = document.getElementById('csvFile').files[0];
        if (!file) return;

        document.getElementById('csvProcessing').style.display = 'block';
        const reader = new FileReader();
        reader.onload = function(e) {
            const lines = e.target.result.trim().split('\n');
            const features = [];

            lines.forEach(line => {
                const p = line.split(',').map(x => x.trim());
                if (p.length < 5) return; // mínimo 3 vértices + lote

                // CORRECCIÓN: Limpiar el valor del lote
                let lote = p[0];
                
                // Primero remover comillas si las tiene
                lote = lote.replace(/"/g, '');
                
                // Verificar si es el fraccionamiento (0 o "fraccionamiento" en cualquier caso)
                if (lote === '0' || lote.toLowerCase() === 'fraccionamiento') {
                    lote = 'Fraccionamiento'; // Siempre con F mayúscula
                } else {
                    // Convertir a número solo si es un número válido y NO es cero
                    if (!isNaN(lote) && lote !== '' && lote !== '0') {
                        lote = parseInt(lote);
                    }
                    // Si no es número, dejar el string original
                }

                const coords = [];
                for (let i = 1; i < p.length; i += 2) {
                    const lat = parseFloat(p[i]);
                    const lng = parseFloat(p[i + 1]);
                    if (!isNaN(lat) && !isNaN(lng)) {
                        coords.push([lng, lat]); // [lng, lat] → formato GeoJSON
                    }
                }

                if (coords.length >= 3) {
                    // Cerrar el polígono si no está cerrado
                    const first = coords[0];
                    const last = coords[coords.length - 1];
                    if (first[0] !== last[0] || first[1] !== last[1]) {
                        coords.push(first);
                    }

                    features.push({
                        type: "Feature",
                        properties: { lote }, // ← Ahora será número o string limpio
                        geometry: {
                            type: "Polygon",
                            coordinates: [coords]
                        }
                    });
                }
            });

            if (features.length === 0) {
                alert('No se encontraron polígonos válidos en el CSV');
                document.getElementById('csvProcessing').style.display = 'none';
                return;
            }

            generatedGeoJSON = { type: "FeatureCollection", features };
            document.getElementById('previewLotesCount').textContent = features.length - 1;

            // Resto del código igual...
            document.getElementById('csvProcessing').style.display = 'none';
            document.getElementById('stepUpload').classList.remove('active');
            document.getElementById('stepPreview').classList.add('active');
            document.getElementById('modalTitle').textContent = 'Vista previa del mapa';

            // Crear mapa de vista previa
            setTimeout(() => {
                previewMap = new mapboxgl.Map({
                    container: 'previewMap',
                    style: 'mapbox://styles/mapbox/satellite-streets-v12',
                    center: features[0].geometry.coordinates[0][0],
                    zoom: 17
                });

                previewMap.on('load', () => {
                    previewMap.addSource('prev', { type: 'geojson', data: generatedGeoJSON });
                    previewMap.addLayer({
                        id: 'fill',
                        type: 'fill',
                        source: 'prev',
                        paint: { 'fill-color': '#1a73e8', 'fill-opacity': 0.3 }
                    });
                    previewMap.addLayer({
                        id: 'line',
                        type: 'line',
                        source: 'prev',
                        paint: { 'line-color': '#1a73e8', 'line-width': 3 }
                    });
                });
            }, 100);
        };
        reader.readAsText(file);
    };

    // === CONFIRMAR Y GUARDAR ===
    document.getElementById('confirmSave').onclick = function() {
        if (!generatedGeoJSON) return;

        fetch('/ing/fraccionamiento/save-geojson', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
            body: JSON.stringify({
                geojson: generatedGeoJSON,
                nombre: document.getElementById('fracNombre').value
            })
        })
        .then(r => r.json())
        .then(r => {
            if (r.success) {
                alert('Guardado como ' + r.archivo);
                closeCsvModal();
                select.dispatchEvent(new Event('change'));
            }
        });
    };
</script>
@endpush