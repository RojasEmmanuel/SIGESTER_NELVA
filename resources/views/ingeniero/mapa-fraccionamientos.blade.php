{{-- resources/views/ing/mapa-fraccionamientos.blade.php --}}
@extends('ingeniero.navbar')

@section('title', 'Mapa de Fraccionamientos')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-map-marked-alt me-2"></i> Mapa de Fraccionamientos</h4>
                </div>

                <div class="card-body p-0">
                    <div class="p-4 bg-light border-bottom">
                        <div class="row align-items-end">
                            <div class="col-md-6">
                                <label for="fraccionamientoSelect" class="form-label fw-bold">Selecciona un fraccionamiento</label>
                                <select id="fraccionamientoSelect" class="form-select form-select-lg">
                                    <option value=""> Elige un fraccionamiento </option>
                                    @foreach($fraccionamientos as $f)
                                        <option value="{{ $f->id_fraccionamiento }}">{{ $f->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="text-muted small"><strong id="lotesCount">0</strong> lotes cargados</div>
                            </div>
                        </div>
                    </div>

                    <div id="mapContainer" style="position: relative; height: 75vh;">
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
@endsection

@push('styles')
<link href="https://api.mapbox.com/mapbox-gl-js/v3.7.0/mapbox-gl.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

<style>
    /* Material Design global */
    body {
        font-family: 'Roboto', sans-serif;
        background: #f5f5f5;
        color: #3c4043;
    }

    /* Card estilo Material */
    .card.shadow-lg {
        border-radius: 16px !important;
        overflow: hidden;
        margin: 24px auto;
        max-width: 1200px;
    }

    /* Header estilo Material */
    .card-header {
        background: #1e478a !important;  /* Google Blue 500 */
        padding: 24px !important;
        border-bottom: none;
        margin-bottom: 20px;
        color: white !important;
    }
    .card-header h4 {
        font-size: 1.5rem;
        font-weight: 500;
        letter-spacing: 0.0125em;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .card-header i {
        font-size: 28px;
    }

    /* Selector estilo Material */
    #fraccionamientoSelect {
        height: 56px;
        border-radius: 8px;
        border: 1px solid #dadce0;
        background: white;
        padding:  10px;
        font-size: 1rem;
        font-weight: 500;
        color: #3c4043;
        box-shadow: 0 1px 2px 0 rgba(60,64,67,0.3), 0 1px 3px 1px rgba(60,64,67,0.15);
        transition: all 0.2s ease;
    }
    #fraccionamientoSelect:focus {
        border-color: #1a73e8;
        box-shadow: 0 0 0 3px rgba(26,115,232,0.2), 0 4px 8px rgba(26,115,232,0.15);
        outline: none;
    }

    /* Contador de lotes estilo chip */
    #lotesCount {
        font-size: 2rem;
        font-weight: 700;
        color: #1a73e8;
        background: #e8f0fe;
        padding: 8px 20px;
        border-radius: 32px;
        display: inline-block;
        min-width: 120px;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Área del mapa con márgenes y padding Material */
    #mapContainer {
        height: 78vh;
        margin: 24px;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 8px -2px rgba(0,0,0,0.2), 0 8px 16px 0 rgba(0,0,0,0.14), 0 3px 12px 2px rgba(0,0,0,0.12);
        position: relative;
    }
    #mapPlano {
        width: 100%;
        height: 100%;
        border-radius: 16px;
    }

    /* Loading overlay estilo Material */
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

    /* Popup de Mapbox estilo Material Card */
    .mapboxgl-popup {
        z-index: 10;
    }
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

    /* Controles flotantes estilo FAB (Floating Action Button) */
    .map-controls .ctrl-btn {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: white;
        box-shadow: 0 4px 8px -2px rgba(0,0,0,0.3), 0 8px 16px 0 rgba(0,0,0,0.24);
        font-size: 24px;
        color: #5f6368;
    }
    .map-controls .ctrl-btn:hover {
        box-shadow: 0 8px 16px -4px rgba(0,0,0,0.3), 0 12px 24px 0 rgba(0,0,0,0.28);
        background: #f1f3f4;
    }
    .toggle-3d.active {
        background: #1a73e8 !important;
        color: white !important;
    }

    /* Responsive perfecto */
    @media (max-width: 992px) {
        .card.shadow-lg { margin: 16px; }
        #mapContainer { margin: 16px; height: 75vh; }
    }
    @media (max-width: 600px) {
        .card-header h4 { font-size: 1.25rem; }
        #lotesCount { font-size: 1.5rem; padding: 6px 16px; }
        #mapContainer { margin: 12px; height: 70vh; border-radius: 12px; }
        .map-controls .ctrl-btn { width: 48px; height: 48px; font-size: 20px; }
    }
</style>
@endpush
@push('scripts')
<script src="https://api.mapbox.com/mapbox-gl-js/v3.7.0/mapbox-gl.js"></script>

@include('ingeniero.partials.map-script')

<script>
    const select = document.getElementById('fraccionamientoSelect');
    const loading = document.getElementById('loadingOverlay');
    const countEl = document.getElementById('lotesCount');
    let currentId = null;

    select.addEventListener('change', function() {
        const id = this.value;
        if (!id) { if (map) map.remove(); countEl.textContent = '0'; return; }
        if (currentId === id) return;

        loading.style.display = 'block';
        currentId = id;

        fetch(`/ing/fraccionamiento/${id}/geojson-data`)
            .then(r => r.json())
            .then(data => {
                if (!data.success) throw new Error('No encontrado');

                window.AppConfig = {
                    fraccionamientoId: data.fraccionamiento.id,
                    fraccionamientoNombre: data.fraccionamiento.nombre
                };

                initializeMap();           // YA EXISTE
                map.on('load', () => {
                    processGeoJSONData(data.geojson);  // YA EXISTE
                    countEl.textContent = (data.geojson.features.length - 1) || 0;
                });
            })
            .catch(err => {
                alert('Error: ' + err.message);
                select.value = '';
            })
            .finally(() => loading.style.display = 'none');
    });
</script>
@endpush