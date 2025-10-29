<?= view('templates/navbar', ['title' => $datosFraccionamiento['nombre'] . ' - Nelva Bienes Raíces']) ?>

<!-- Agregar CSS de Mapbox -->
<link href='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css' rel='stylesheet' />

<link href="{{ asset('css/pagina/fraccionamiento.css') }}" rel="stylesheet">
<!-- Configuración global para los scripts -->
<script>
    // Configuración global que necesita map.js
    window.AppConfig = {
        fraccionamientoId: {{ $datosFraccionamiento['id'] }},
        fraccionamientoNombre: "{{ addslashes($datosFraccionamiento['nombre']) }}"
    };
    
    // Funciones globales que necesita map.js
    window.openCalculationForLote = function(loteNumber) {
        const modal = document.getElementById('calculationModal');
        const input = document.getElementById('lotNumber');
        if (modal && input) {
            input.value = loteNumber;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                const calculateBtn = document.getElementById('calculateBtn');
                if (calculateBtn) calculateBtn.click();
            }, 500);
        }
    };

    window.openReservationForLote = function(loteNumber) {
        window.openCalculationForLote(loteNumber);
    };
</script>

<a href="https://wa.me/5219581362522?text=Hola,%20me%20interesa%20saber%20más%20sobre%20{{ urlencode($datosFraccionamiento['nombre']) }}" class="whatsapp-float" target="_blank">
    <i class="fab fa-whatsapp"></i>
</a>

<!-- Hero Section -->
<section class="hero">
    @if(isset($datosFraccionamiento['hero_image']) && !empty($datosFraccionamiento['hero_image']))
    <div class="hero-bg" style="background-image: url('{{ asset('storage/' . $datosFraccionamiento['hero_image']) }}')"></div>
    @else
    <div class="hero-bg" style="background-image: url('{{ asset('images/default-hero.jpg') }}')"></div>
    @endif
    <div class="hero-content">
        <h1>{{ $datosFraccionamiento['nombre'] }}</h1>
        <div class="hero-location">
            <i class="fas fa-map-marker-alt"></i>
            <span>{{ $datosFraccionamiento['ubicacion'] }}</span>
        </div>
        @if(isset($datosFraccionamiento['descripcion']))
        <p>{{ $datosFraccionamiento['descripcion'] }}</p>
        @endif
        
        @if(isset($datosFraccionamiento['precioGeneral']) && $datosFraccionamiento['precioGeneral'] > 0)
        <span class="price">${{ number_format($datosFraccionamiento['precioGeneral'], 2) }} MXN</span>
        @endif
        
        <div class="hero-buttons">
            <button class="btn btn-secondary" id="openCalculationModal">
                <i class="fas fa-calculator"></i> Consultar Lotes
            </button>
            <a href="#gallery" class="btn">
                <i class="fas fa-images"></i> Ver Galería
            </a>
        </div>
    </div>
</section>

<!-- Quick Stats -->
<section class="section">
    <div class="container">
        <div class="quick-stats">
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="stat-number">{{ $totalLotes }}</div>
                <div class="stat-label">Total de Lotes</div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number">{{ $lotesDisponibles }}</div>
                <div class="stat-label">Disponibles</div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number">{{ $lotesApartados }}</div>
                <div class="stat-label">Apartados</div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-home"></i>
                </div>
                <div class="stat-number">{{ $lotesVendidos }}</div>
                <div class="stat-label">Vendidos</div>
            </div>
        </div>
    </div>
</section>

<!-- Información General Mejorada -->
<section class="section info-general-section">
    <div class="container info-general-container">
        <div class="info-general-card fade-in-up">
            <div class="info-general-header">
                <div class="info-general-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <h2 class="info-general-title">Información General</h2>
                <p class="amenidades-subtitle">Conoce todos los detalles y características principales de este exclusivo fraccionamiento</p>
            </div>
            
            <div class="info-grid-modern">
                @if(isset($datosFraccionamiento['tipo_propiedad']))
                <div class="info-item-modern fade-in-up">
                    <div class="info-item-header">
                        <div class="info-item-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <h3 class="info-item-title">Tipo de Propiedad</h3>
                    </div>
                    <div class="info-item-value">{{ $datosFraccionamiento['tipo_propiedad'] }}</div>
                    <p class="info-item-description">Clasificación y categoría del desarrollo</p>
                </div>
                @endif
                
                @if(isset($datosFraccionamiento['precio_metro_cuadrado']) && $datosFraccionamiento['precio_metro_cuadrado'] > 0)
                <div class="info-item-modern fade-in-up">
                    <div class="info-item-header">
                        <div class="info-item-icon">
                            <i class="fas fa-ruler-combined"></i>
                        </div>
                        <h3 class="info-item-title">Precio por m²</h3>
                    </div>
                    <div class="info-item-value highlight">${{ number_format($datosFraccionamiento['precio_metro_cuadrado'], 2) }} MXN</div>
                    <p class="info-item-description">Costo por metro cuadrado de terreno</p>
                </div>
                @endif
                
                @if(isset($datosFraccionamiento['precioGeneral']) && $datosFraccionamiento['precioGeneral'] > 0)
                <div class="info-item-modern fade-in-up">
                    <div class="info-item-header">
                        <div class="info-item-icon">
                            <i class="fas fa-tag"></i>
                        </div>
                        <h3 class="info-item-title">Precio General</h3>
                    </div>
                    <div class="info-item-value highlight">${{ number_format($datosFraccionamiento['precioGeneral'], 2) }} MXN</div>
                    <p class="info-item-description">Precio de referencia para el desarrollo</p>
                </div>
                @endif
                
                <div class="info-item-modern fade-in-up">
                    <div class="info-item-header">
                        <div class="info-item-icon">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <h3 class="info-item-title">Total de Lotes</h3>
                    </div>
                    <div class="info-item-value">{{ $totalLotes }}</div>
                    <p class="info-item-description">Número total de lotes en el fraccionamiento</p>
                </div>
                
                <div class="info-item-modern fade-in-up">
                    <div class="info-item-header">
                        <div class="info-item-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="info-item-title">Disponibilidad</h3>
                    </div>
                    <div class="info-item-value">{{ $lotesDisponibles }} disponibles</div>
                    <p class="info-item-description">Lotes listos para ser adquiridos</p>
                </div>
                
                <div class="info-item-modern fade-in-up">
                    <div class="info-item-header">
                        <div class="info-item-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h3 class="info-item-title">Estatus del Proyecto</h3>
                    </div>
                    <div class="info-item-value">
                        <span class="status-badge-modern {{ $datosFraccionamiento['estatus'] ? 'status-active-modern' : 'status-inactive-modern' }}">
                            {{ $datosFraccionamiento['estatus'] ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                    <p class="info-item-description">Estado actual del desarrollo</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Servicios y Amenidades Mejorada -->
@if($amenidades->count() > 0)
<section class="section amenidades-section" id="amenidades">
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    
    <div class="container amenidades-container">
        <div class="amenidades-card fade-in-up">
            <div class="amenidades-header">
                <div class="amenidades-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h2 class="amenidades-title">Servicios y Amenidades</h2>
                <p class="amenidades-subtitle">Disfruta de una amplia gama de servicios y comodidades diseñadas para tu máximo confort</p>
            </div>

            @php
                $tiposAmenidades = $amenidades->pluck('tipo')->unique()->filter();
            @endphp
            
            
            <div class="amenidades-grid-modern">
                @foreach($amenidades as $index => $amenidad)
                <div class="amenidad-item-modern fade-in-up" 
                     data-category="{{ Str::slug($amenidad['tipo'] ?? 'general') }}"
                     style="animation-delay: {{ $index * 0.1 }}s">
                    <div class="amenidad-icon-modern">
                        @switch($amenidad['tipo'] ?? 'general')
                            @case('Seguridad')
                                <i class="fas fa-shield-alt"></i>
                                @break
                            @case('Recreación')
                                <i class="fas fa-gamepad"></i>
                                @break
                            @case('Áreas Comunes')
                                <i class="fas fa-tree"></i>
                                @break
                            @case('Servicios Básicos')
                                <i class="fas fa-tools"></i>
                                @break
                            @default
                                <i class="fas fa-star"></i>
                        @endswitch
                    </div>
                    <div class="amenidad-content">
                        <h3 class="amenidad-title">{{ $amenidad['nombre'] }}</h3>
                        @if(isset($amenidad['descripcion']) && $amenidad['descripcion'])
                        <p class="amenidad-description">{{ $amenidad['descripcion'] }}</p>
                        @endif
                        @if(isset($amenidad['tipo']) && $amenidad['tipo'])
                        <span class="amenidad-badge">{{ $amenidad['tipo'] }}</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<!-- Development Plan con Mapa -->
<section class="section development-plan">
    <div class="container">
        <h2 class="section-title">Plano Interactivo del Fraccionamiento</h2>
        <p class="text-center mb-30" style="max-width: 800px; margin-left: auto; margin-right: auto;">
            Explora el diseño urbanístico del fraccionamiento y consulta información detallada de cada lote disponible.
        </p>

        <div class="plan-container" id="planContainer">
            <button class="fullscreen-btn" id="fullscreenBtn">
                <i class="fas fa-expand"></i> Pantalla Completa
            </button>
            
            <div class="map-controls-overlay">
                <div class="control-panel-map">
                    <div class="control-section">
                        <div class="control-title"><i class="fas fa-layer-group"></i> Estilo del Mapa</div>
                        <div class="style-buttons">
                            <button class="style-btn active" data-style="satellite-streets">
                                <i class="fas fa-satellite"></i> Satélite
                            </button>
                            <button class="style-btn" data-style="streets">
                                <i class="fas fa-road"></i> Calles
                            </button>
                            <button class="style-btn" data-style="light">
                                <i class="fas fa-map"></i> Claro
                            </button>
                            <button class="style-btn" data-style="dark">
                                <i class="fas fa-moon"></i> Oscuro
                            </button>
                        </div>
                    </div>
                    
                    <div class="control-section">
                        <div class="control-title"><i class="fas fa-filter"></i> Filtros</div>
                        <div class="filter-buttons">
                            <button class="filter-btn active" data-filter="all">
                                <div class="color-indicator" style="background: conic-gradient(#16a34a 0% 33%, #dc2626 33% 66%, #ea580c 66% 100%);"></div>
                                Todos los lotes
                            </button>
                            <button class="filter-btn" data-filter="disponible">
                                <div class="color-indicator disponible-indicator"></div>
                                Disponibles
                            </button>
                            <button class="filter-btn" data-filter="vendido">
                                <div class="color-indicator vendido-indicator"></div>
                                Vendidos
                            </button>
                            <button class="filter-btn" data-filter="apartado-palabra-deposito">
                                <div class="color-indicator palabra-deposito-indicator"></div>
                                Apartados
                            </button>
                        </div>
                    </div>
                </div>

                <div class="info-panel-map hidden" id="infoPanelMap">
                    <div class="info-header">
                        <div class="info-title">Información del Lote</div>
                        <button class="info-close" id="infoCloseMap">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="lote-info-content" id="loteInfoContent">
                        <!-- La información del lote se cargará aquí dinámicamente por map.js -->
                    </div>
                    <div class="lote-actions">
                        <button class="btn btn-outline" onclick="window.openCalculationForLote(document.getElementById('loteInfoContent').dataset?.lotNumber || '')">
                            <i class="fas fa-calculator"></i> Calcular Costo
                        </button>
                    </div>
                </div>
            </div>

            <div id="mapPlano" style="width: 100%; height: 600px; border-radius: 12px;"></div>
        </div>
    </div>
</section>

<!-- Gallery Section Mejorada -->
<section class="section gallery-section" id="gallery">
    <div class="container">
        <h2 class="section-title">Galería del Fraccionamiento</h2>
        
        @if($galeria->count() > 0)
        <div class="gallery-grid">
            @foreach($galeria as $foto)
            <div class="gallery-item">
                <img src="{{ asset('storage/' . $foto['fotografia_path']) }}" 
                     alt="{{ $foto['nombre'] ?? 'Foto del fraccionamiento' }}"
                     data-src="{{ asset('storage/' . $foto['fotografia_path']) }}">
                <div class="gallery-overlay">
                    <div class="gallery-info">
                        <h4>{{ $foto['nombre'] ?? 'Sin título' }}</h4>
                        <div class="gallery-meta">
                            <i class="fas fa-calendar"></i>
                            {{ \Carbon\Carbon::parse($foto['fecha_subida'])->format('d/m/Y') }}
                        </div>
                    </div>
                    <div class="gallery-actions">
                        <a href="{{ asset('storage/' . $foto['fotografia_path']) }}" 
                           class="gallery-action-btn" 
                           target="_blank">
                            <i class="fas fa-expand"></i>
                        </a>
                        <a href="{{ asset('storage/' . $foto['fotografia_path']) }}" 
                           class="gallery-action-btn" 
                           download="{{ $foto['nombre'] ?? 'foto_fraccionamiento' }}">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-images"></i>
            </div>
            <h3>No hay fotos en la galería</h3>
            <p>Actualmente no hay imágenes disponibles para este fraccionamiento.</p>
        </div>
        @endif
    </div>
</section>

<!-- Documents Section Mejorada -->
<section class="section documents-section">
    <div class="container">
        <h2 class="section-title">Documentos del Fraccionamiento</h2>
        
        @if($archivos->count() > 0)
        <div class="documents-grid">
            @foreach($archivos as $archivo)
            <div class="document-card">
                <div class="document-icon">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <div class="document-info">
                    <h4 class="document-title">{{ $archivo['nombre_archivo'] ?? 'Documento sin título' }}</h4>
                    <div class="document-meta">
                        <div class="document-date">
                            <i class="fas fa-calendar"></i>
                            {{ \Carbon\Carbon::parse($archivo['fecha_subida'])->format('d/m/Y') }}
                        </div>
                        <span class="document-type">PDF</span>
                    </div>
                </div>
                <div class="document-actions">
                    <a href="{{ route('pagina.fraccionamiento.download-archivo', [
                        'idFraccionamiento' => $datosFraccionamiento['id'],
                        'idArchivo' => $archivo['id']
                    ]) }}" 
                    class="document-action-btn"
                    target="_blank">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('pagina.fraccionamiento.download-archivo', [
                        'idFraccionamiento' => $datosFraccionamiento['id'],
                        'idArchivo' => $archivo['id']
                    ]) }}" 
                    class="document-action-btn"
                    download>
                        <i class="fas fa-download"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-file-download"></i>
            </div>
            <h3>No hay archivos disponibles</h3>
            <p>Actualmente no hay documentos para descargar de este fraccionamiento.</p>
        </div>
        @endif
    </div>
</section>

<!-- Location Section Mejorada -->
@if(isset($datosFraccionamiento['ubicacionMaps']) && !empty($datosFraccionamiento['ubicacionMaps']))
<section class="section location-section">
    <div class="container">
        <h2 class="section-title" style="color: white;">Ubicación Estratégica</h2>
        <p class="text-center mb-30" style="color: rgba(255,255,255,0.9); max-width: 800px; margin-left: auto; margin-right: auto;">
            Descubre la ubicación privilegiada de nuestro fraccionamiento y su conexión con los principales puntos de interés.
        </p>
        
        <div class="location-container">
            <iframe 
                class="location-iframe" 
                src="{{ $datosFraccionamiento['ubicacionMaps'] }}" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</section>
@endif



<!-- Modal de Cálculo de Costo - VERSIÓN CORREGIDA -->
<div class="modal-fraccionamiento modal-compact" id="calculationModal">
    <div class="modal-content-fraccionamiento">
        <button class="close-modal-fraccionamiento" id="closeCalculationModal">✕</button>
        <h2 class="modal-title-fraccionamiento">Consultar Lote</h2>
        
        <form id="calculationForm">
            <div class="form-group">
                <input type="text" id="lotNumber" class="form-control" required placeholder="Ingresa número de lote (ej: 12)">
                <div id="lotError" class="error-message" style="display: none;"></div>
            </div>
            
            <div class="form-group">
                <button type="button" class="btn btn-primary" id="calculateBtn" style="width: 100%;">
                    Buscar Lote
                </button>
            </div>
            
            <div id="lotDetails" class="lot-details-container" style="display: none;">
                <!-- Información básica en grid 2x2 optimizado -->
                <div class="lot-details-content">
                    <div class="lot-details-title">
                        Detalles del Lote
                    </div>
                    
                    <div class="lot-details-grid">
                        <div class="lot-detail-item">
                            <div class="lot-detail-label">Lote #</div>
                            <div class="lot-detail-value" id="lotNumberDisplay">-</div>
                        </div>
                        <div class="lot-detail-item">
                            <div class="lot-detail-label">Estatus</div>
                            <div class="lot-detail-value">
                                <span class="status-badge" id="statusBadge">-</span>
                            </div>
                        </div>
                        <div class="lot-detail-item">
                            <div class="lot-detail-label">Manzana</div>
                            <div class="lot-detail-value" id="lotBlock">-</div>
                        </div>
                        <div class="lot-detail-item">
                            <div class="lot-detail-label">Área Total</div>
                            <div class="lot-detail-value" id="lotArea">- m²</div>
                        </div>
                    </div>
                    
                    <!-- Medidas ultra compactas - CORREGIDAS -->
                    <div class="measures-section">
                        <div class="measures-title">
                            Medidas
                        </div>
                        <div class="measures-grid">
                            <div class="measure-item">
                                <div class="lot-detail-label">Norte</div>
                                <div class="measure-value" id="lotNorth">- m</div>
                            </div>
                            <div class="measure-item">
                                <div class="lot-detail-label">Sur</div>
                                <div class="measure-value" id="lotSouth">- m</div>
                            </div>
                            <div class="measure-item">
                               <div class="lot-detail-label">Oriente</div>
                                <div class="measure-value" id="lotEast">- m</div>
                            </div>
                            <div class="measure-item">
                                <div class="lot-detail-label">Poniente</div>
                                <div class="measure-value" id="lotWest">- m</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Cálculo de costo ultra compacto -->
                <div class="calculation-result">
                    <div class="calculation-title">
                        Cálculo de Costo
                    </div>
                    <div class="calculation-item">
                        <div class="calculation-label">Precio m²</div>
                        <div class="calculation-value">${{ number_format($datosFraccionamiento['precio_metro_cuadrado'] ?? 0, 2) }}</div>
                    </div>
                    <div class="calculation-item">
                        <div class="calculation-label">Área total</div>
                        <div class="calculation-value" id="calculationArea">0 m²</div>
                    </div>
                    <div class="calculation-item highlight">
                        <div class="calculation-label">Total</div>
                        <div class="calculation-value" id="totalCost">$0 MXN</div>
                    </div>
                </div>
                
                <!-- Botones de acción mini - SIN ICONOS -->
                <div class="modal-actions">
                    <button type="button" class="btn btn-outline" id="closeCalculationResult">
                        Cerrar
                    </button>
                    <button type="button" class="btn btn-primary" id="consultAnotherLot">
                        Otro Lote
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Lightbox para Galería -->
<div class="lightbox" id="lightbox">
    <button class="lightbox-close" id="lightboxClose">
        <i class="fas fa-times"></i>
    </button>
    <button class="lightbox-nav lightbox-prev" id="lightboxPrev">
        <i class="fas fa-chevron-left"></i>
    </button>
    <button class="lightbox-nav lightbox-next" id="lightboxNext">
        <i class="fas fa-chevron-right"></i>
    </button>
    <div class="lightbox-content">
        <img id="lightboxImage" src="" alt="">
        <div class="lightbox-info">
            <h4 id="lightboxTitle"></h4>
            <div class="lightbox-meta">
                <span id="lightboxDate"></span>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

<?= view('templates/footer') ?>

<!-- Scripts en el orden correcto -->
<script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
<script src="{{ asset('js/map.js') }}"></script>

<script>
    // JavaScript específico para la vista cliente
    document.addEventListener('DOMContentLoaded', function() {
        // Funcionalidad del modal de cálculo para cliente
        const calculationModal = document.getElementById('calculationModal');
        const closeCalculationModal = document.getElementById('closeCalculationModal');
        const openCalculationModalBtn = document.getElementById('openCalculationModal');
        const closeCalculationResult = document.getElementById('closeCalculationResult');
        const consultAnotherLot = document.getElementById('consultAnotherLot');
        const calculateBtn = document.getElementById('calculateBtn');
        const lotError = document.getElementById('lotError');

        // Abrir modal
        if (openCalculationModalBtn) {
            openCalculationModalBtn.addEventListener('click', function() {
                if (calculationModal) {
                    calculationModal.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                }
            });
        }

        // Cerrar modal
        if (closeCalculationModal) {
            closeCalculationModal.addEventListener('click', function() {
                if (calculationModal) {
                    calculationModal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            });
        }

        if (closeCalculationResult) {
            closeCalculationResult.addEventListener('click', function() {
                if (calculationModal) {
                    calculationModal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            });
        }

        // Consultar otro lote
        if (consultAnotherLot) {
            consultAnotherLot.addEventListener('click', function() {
                const lotNumber = document.getElementById('lotNumber');
                const lotDetails = document.getElementById('lotDetails');
                const lotError = document.getElementById('lotError');
                
                if (lotNumber) lotNumber.value = '';
                if (lotDetails) lotDetails.style.display = 'none';
                if (lotError) lotError.style.display = 'none';
                if (lotNumber) lotNumber.focus();
            });
        }

        // Cálculo REAL para cliente - consulta datos reales del servidor
        if (calculateBtn) {
            calculateBtn.addEventListener('click', function() {
                const lotNumberInput = document.getElementById('lotNumber');
                
                if (!lotNumberInput) return;
                
                const lotNumber = lotNumberInput.value.trim();
                
                if (!lotNumber) {
                    if (lotError) {
                        lotError.textContent = 'Por favor ingrese un número de lote';
                        lotError.style.display = 'block';
                    }
                    return;
                }

                // Consulta REAL al servidor - RUTA CORREGIDA
                getRealLotData(lotNumber);
            });
        }

        // Función para obtener datos REALES del lote
        function getRealLotData(lotNumber) {
            const calculateBtn = document.getElementById('calculateBtn');
            const lotDetails = document.getElementById('lotDetails');
            const lotError = document.getElementById('lotError');
            
            if (!calculateBtn || !lotDetails) return;

            // Mostrar loading
            calculateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Consultando...';
            calculateBtn.disabled = true;
            
            if (lotError) lotError.style.display = 'none';

            // Hacer petición REAL al servidor - RUTA CORREGIDA
            const fraccionamientoId = window.AppConfig.fraccionamientoId;
            
            // CONSTRUIR LA URL MANUALMENTE (más seguro)
            const url = `/fraccionamiento/${fraccionamientoId}/lote/${encodeURIComponent(lotNumber)}`;

            console.log('🔍 Consultando lote:', lotNumber, 'en fraccionamiento:', fraccionamientoId);
            console.log('📡 URL:', url);

            fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            })
            .then(response => {
                console.log('📄 Respuesta del servidor:', response.status, response.statusText);
                if (!response.ok) {
                    // Si la respuesta no es exitosa, intentar leer el mensaje de error
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || `Error ${response.status}: ${response.statusText}`);
                    }).catch(() => {
                        throw new Error(`Error ${response.status}: ${response.statusText}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('✅ Datos recibidos:', data);
                if (data.success && data.lote) {
                    // Actualizar UI con datos REALES
                    updateLotDetails(data.lote);
                    if (lotDetails) lotDetails.style.display = 'block';
                } else {
                    throw new Error(data.message || 'Lote no encontrado en los datos');
                }
            })
            .catch(error => {
                console.error('❌ Error en la consulta:', error);
                if (lotError) {
                    lotError.textContent = error.message || 'Error al consultar el lote. Intente nuevamente.';
                    lotError.style.display = 'block';
                }
                if (lotDetails) lotDetails.style.display = 'none';
            })
            .finally(() => {
                calculateBtn.innerHTML = '<i class="fas fa-calculator"></i> Consultar Lote';
                calculateBtn.disabled = false;
            });
        }

        function updateLotDetails(lote) {
            console.log('🔄 Actualizando UI con datos del lote:', lote);
            
            // Actualizar información básica
            const elements = {
                'lotNumberDisplay': lote.numeroLote || lote.numero || 'N/A',
                'lotBlock': lote.manzana || 'N/A',
                'lotArea': `${lote.area_total || lote.area_metros || '0'} m²`,
                'lotNorth': `${lote.medidas?.norte || lote.norte || '0'} m`,
                'lotSouth': `${lote.medidas?.sur || lote.sur || '0'} m`,
                'lotEast': `${lote.medidas?.oriente || lote.oriente || '0'} m`,
                'lotWest': `${lote.medidas?.poniente || lote.poniente || '0'} m`
            };

            // Actualizar elementos
            Object.keys(elements).forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.textContent = elements[id];
                    console.log(`✅ Actualizado ${id}:`, elements[id]);
                }
            });

            // Actualizar estatus con datos REALES
            const statusBadge = document.getElementById('statusBadge');
            if (statusBadge) {
                const estatus = lote.estatus || 'disponible';
                let statusText = 'Disponible';
                let statusClass = 'status-disponible';
                
                switch(estatus) {
                    case 'vendido':
                        statusText = 'Vendido';
                        statusClass = 'status-vendido';
                        break;
                    case 'apartadoPalabra':
                        statusText = 'Apartado (Palabra)';
                        statusClass = 'status-apartado';
                        break;
                    case 'apartadoDeposito':
                        statusText = 'Apartado (Depósito)';
                        statusClass = 'status-apartado';
                        break;
                    default:
                        statusText = 'Disponible';
                        statusClass = 'status-disponible';
                }
                
                statusBadge.textContent = statusText;
                statusBadge.className = 'status-badge ' + statusClass;
                console.log('✅ Estatus actualizado:', statusText);
            }

            // Calcular costo total con datos REALES
            const pricePerM2 = {{ $datosFraccionamiento['precio_metro_cuadrado'] ?? 0 }};
            const areaTotal = parseFloat(lote.area_total || lote.area_metros || 0);
            const totalCost = areaTotal * pricePerM2;
            
            // Actualizar área en cálculo
            const calculationArea = document.getElementById('calculationArea');
            if (calculationArea) {
                calculationArea.textContent = `${areaTotal} m²`;
            }
            
            // Actualizar costo total
            const totalCostElement = document.getElementById('totalCost');
            if (totalCostElement) {
                totalCostElement.textContent = `$${totalCost.toLocaleString('es-MX', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                })} MXN`;
                console.log('✅ Costo calculado:', totalCostElement.textContent);
            }
        }

        // Cerrar modal al hacer clic fuera
        if (calculationModal) {
            calculationModal.addEventListener('click', function(event) {
                if (event.target === calculationModal) {
                    calculationModal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            });
        }

        // Lightbox para galería (mantener igual)
        const galleryImages = document.querySelectorAll('.gallery-item img');
        const lightbox = document.getElementById('lightbox');
        const lightboxImage = document.getElementById('lightboxImage');
        const lightboxTitle = document.getElementById('lightboxTitle');
        const lightboxDate = document.getElementById('lightboxDate');
        const lightboxClose = document.getElementById('lightboxClose');
        const lightboxPrev = document.getElementById('lightboxPrev');
        const lightboxNext = document.getElementById('lightboxNext');
        
        let currentImageIndex = 0;
        const images = Array.from(galleryImages);
        
        // Abrir lightbox
        galleryImages.forEach((img, index) => {
            img.addEventListener('click', function() {
                currentImageIndex = index;
                openLightbox();
            });
        });
        
        function openLightbox() {
            const currentImage = images[currentImageIndex];
            const galleryItem = currentImage.closest('.gallery-item');
            const title = galleryItem.querySelector('h4').textContent;
            const date = galleryItem.querySelector('.gallery-meta').textContent;
            
            lightboxImage.src = currentImage.dataset.src;
            lightboxTitle.textContent = title;
            lightboxDate.textContent = date;
            
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        // Cerrar lightbox
        lightboxClose.addEventListener('click', closeLightbox);
        lightbox.addEventListener('click', function(e) {
            if (e.target === lightbox) {
                closeLightbox();
            }
        });
        
        function closeLightbox() {
            lightbox.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
        
        // Navegación del lightbox
        lightboxPrev.addEventListener('click', function(e) {
            e.stopPropagation();
            currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
            openLightbox();
        });
        
        lightboxNext.addEventListener('click', function(e) {
            e.stopPropagation();
            currentImageIndex = (currentImageIndex + 1) % images.length;
            openLightbox();
        });
        
        // Navegación con teclado
        document.addEventListener('keydown', function(e) {
            if (!lightbox.classList.contains('active')) return;
            
            if (e.key === 'Escape') {
                closeLightbox();
            } else if (e.key === 'ArrowLeft') {
                currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
                openLightbox();
            } else if (e.key === 'ArrowRight') {
                currentImageIndex = (currentImageIndex + 1) % images.length;
                openLightbox();
            }
        });
    });

    // Función para manejar pantalla completa
    document.addEventListener('fullscreenchange', function() {
        const fullscreenBtn = document.getElementById('fullscreenBtn');
        if (!fullscreenBtn) return;
        
        if (document.fullscreenElement) {
            fullscreenBtn.innerHTML = '<i class="fas fa-compress"></i> Salir de Pantalla Completa';
            setTimeout(() => {
                if (window.map) {
                    window.map.resize();
                }
            }, 300);
        } else {
            fullscreenBtn.innerHTML = '<i class="fas fa-expand"></i> Pantalla Completa';
            setTimeout(() => {
                if (window.map) {
                    window.map.resize();
                }
            }, 300);
        }
    });
</script>