@php
    $navbarMap = [
        'Administrador' => 'admin.navbar',
        'Asesor' => 'asesor.navbar',
        'Cobranza' => 'cobranza.navbar',
        'Ingeniero' => 'ingeniero.navbar',
    ];

    // Obtener el usuario autenticado directamente (asumiendo Auth::user() es instancia de App\Models\Usuario)
    $usuario = Auth::user();
    
    // Cargar la relación 'tipo' si no está ya cargada para evitar errores
    if (! $usuario->relationLoaded('tipo')) {
        $usuario->load('tipo');
    }
    
    $tipoNombre = $usuario->tipo->tipo ?? 'Asesor'; // Fallback a Asesor si no hay tipo
    $navbar = $navbarMap[$tipoNombre] ?? 'asesor.navbar';

    // Verificar si el usuario es administrador
    $esAdministrador = $tipoNombre === 'Administrador';
@endphp

@extends($navbar)


@section('title', 'Nelva Bienes Raíces - Fraccionamiento')

@push('styles')
<link href="{{ asset('css/fraccionamientoAsesor.css') }}" rel="stylesheet">
<link href='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css' rel='stylesheet' />

@endpush

@section('content')
    <!-- Main Content -->
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-map-marked-alt"></i>
                <span>Detalles del Fraccionamiento</span>
            </h1>
            <div class="page-actions">
                @if($esAdministrador)
                <button class="btn btn-primary" onclick="window.location.href='{{ route('admin.fraccionamiento.show', $datosFraccionamiento['id']) }}'">
                    <i class="fas fa-edit"></i> Editar
                </button>
                @endif
                <button class="btn btn-outline" onclick="window.history.back()">
                    <i class="fas fa-arrow-left"></i> Volver
                </button>
            </div>
        </div>

        <!-- Development Header -->
        <div class="development-header">
            <h2 class="development-name">{{ $datosFraccionamiento['nombre'] }}</h2>
            <div class="development-location">
                <i class="fas fa-map-marker-alt"></i>
                <span>{{ $datosFraccionamiento['ubicacion'] }}</span>
            </div>
            @if(isset($datosFraccionamiento['descripcion']))
            <p class="development-description">
                {{ $datosFraccionamiento['descripcion'] }}
            </p>
            @endif
        </div>

        <!-- Estadísticas Minimalistas -->
        <div class="stats-section">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ $totalLotes }}</div>
                    <div class="stat-label">Total</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $lotesDisponibles }}</div>
                    <div class="stat-label">Disponibles</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number">{{ $lotesApartados }}</div>
                    <div class="stat-label">Apartados</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number">{{ $lotesVendidos }}</div>
                    <div class="stat-label">Vendidos</div>
                </div>            
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <button class="btn btn-primary btn-lg" id="openReservationModal">
                <i class="fas fa-handshake"></i> Apartar Lote
            </button>
            <button class="btn btn-outline btn-lg" id="openCalculationModal">
                <i class="fas fa-calculator"></i> Consultar Lote
            </button>
        </div>

        <!-- Development Plan -->
        <div class="development-plan">
            <h3 class="info-title">
                <i class="fas fa-map"></i>
                <span>Plano Interactivo del Fraccionamiento</span>
            </h3>
            
            <div class="plan-container" id="planContainer">
                <button class="fullscreen-btn" id="fullscreenBtn">
                    <i class="fas fa-expand"></i> Pantalla Completa
                </button>
                <div id="mapPlano" style="width: 100%; height: 600px; border-radius: 12px;"></div>
            </div>

            <!-- Controles del Mapa -->
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
                            <button class="style-btn" data-style="standard">
                                <i class="fas fa-map-marked-alt"></i> Estándar
                            </button>
                            <button class="style-btn" data-style="tourist">
                                <i class="fas fa-landmark"></i> Lugares Turísticos
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
                                Apartado Palabra/Depósito
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Panel de información del lote -->
                <div class="info-panel-map hidden" id="infoPanelMap">
                    <div class="info-header">
                        <div class="info-title">Información del Lote</div>
                        <button class="info-close" id="infoCloseMap">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="lote-info-content" id="loteInfoContent">
                        <!-- La información del lote se cargará aquí dinámicamente -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Development Info -->
        <div class="development-info">
            <div class="info-section">
                <h3 class="info-title">
                    <i class="fas fa-info-circle"></i>
                    <span>Información General</span>
                </h3>
                <div class="info-grid">
                    @if(isset($datosFraccionamiento['tipo_propiedad']))
                    <div class="info-item">
                        <div class="info-label">Tipo de Propiedad</div>
                        <div class="info-value highlight">{{ $datosFraccionamiento['tipo_propiedad'] }}</div>
                    </div>
                    @endif
                    
                    @if(isset($datosFraccionamiento['precio_metro_cuadrado']) && $datosFraccionamiento['precio_metro_cuadrado'] > 0)
                    <div class="info-item">
                        <div class="info-label">Precio por m²</div>
                        <div class="info-value highlight">${{ number_format($datosFraccionamiento['precio_metro_cuadrado'], 2) }} MXN</div>
                    </div>
                    @endif
                    
                    @if(isset($datosFraccionamiento['precioGeneral']) && $datosFraccionamiento['precioGeneral'] > 0)
                    <div class="info-item">
                        <div class="info-label">Precio general</div>
                        <div class="info-value highlight">${{ number_format($datosFraccionamiento['precioGeneral'], 2) }} MXN</div>
                    </div>
                    @endif
                    
                    <div class="info-item">
                        <div class="info-label">Total de lotes</div>
                        <div class="info-value">{{ $totalLotes }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Estatus</div>
                        <div class="info-value {{ $datosFraccionamiento['estatus'] ? 'highlight' : '' }}">
                            {{ $datosFraccionamiento['estatus'] ? 'Activo' : 'Inactivo' }}
                        </div>
                    </div>
                </div>
            </div>

            @if($amenidades->count() > 0)
            <div class="info-section">
                <h3 class="info-title">
                    <i class="fas fa-bolt"></i>
                    <span>Servicios y Amenidades</span>
                </h3>
                <div class="services-list">
                    @foreach($amenidades as $amenidad)
                    <span class="service-tag">{{ $amenidad['nombre'] }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            @if($zonas->count() > 0)
            <div class="info-section">
                <h3 class="info-title">
                    <i class="fas fa-map-marked-alt"></i>  
                    <span>Zonas del Fraccionamiento</span>
                </h3>
                <div class="info-grid"
">
                    @foreach($zonas as $zona)
                    <div class="info-item">
                        <div class="info-label">{{ $zona['nombre'] }}</div>
                        <div class="info-value highlight
                            ">Precio por m²: ${{ number_format($zona['precio_m2'], 2) }} MXN
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>
            @endif

            <!-- GALERÍA SIMPLIFICADA -->
            <div class="info-section gallery-section">
                <div class="gallery-header">
                    <h3 class="info-title">
                        <i class="fas fa-images"></i>
                        <span>Galería del Fraccionamiento</span>
                    </h3>
                    @if($galeria->count() > 0)
                    <div class="gallery-count">
                        {{ $galeria->count() }} {{ $galeria->count() === 1 ? 'foto' : 'fotos' }}
                    </div>
                    @endif
                </div>

                @if($galeria->count() > 0)
                <div class="gallery-grid">
                    @foreach($galeria as $foto)
                    <div class="gallery-card">
                        <div class="gallery-image">
                            <img src="{{ asset('storage/' . $foto['fotografia_path']) }}" 
                                 alt="{{ $foto['nombre'] ?? 'Foto del fraccionamiento' }}">
                        </div>
                        <div class="gallery-info">
                            <h4 class="gallery-title">{{ $foto['nombre'] ?? 'Sin título' }}</h4>
                            <div class="gallery-meta">
                                <div class="gallery-date">
                                    <i class="fas fa-calendar"></i>
                                    {{ \Carbon\Carbon::parse($foto['fecha_subida'])->format('d/m/Y') }}
                                </div>
                                <a href="{{ asset('storage/' . $foto['fotografia_path']) }}" 
                                   class="gallery-download" 
                                   download="{{ $foto['nombre'] ?? 'foto_fraccionamiento' }}">
                                    <i class="fas fa-download"></i> Descargar
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="gallery-empty">
                    <i class="fas fa-images"></i>
                    <h4>No hay fotos en la galería</h4>
                    <p>Actualmente no hay imágenes disponibles para este fraccionamiento.</p>
                </div>
                @endif
            </div>

            <!-- ARCHIVOS SIMPLIFICADOS -->
            <div class="info-section files-section">
                <div class="files-header">
                    <h3 class="info-title">
                        <i class="fas fa-file-download"></i>
                        <span>Documentos del Fraccionamiento</span>
                    </h3>
                    @if($archivos->count() > 0)
                    <div class="files-count">
                        {{ $archivos->count() }} {{ $archivos->count() === 1 ? 'archivo' : 'archivos' }}
                    </div>
                    @endif
                </div>

                @if($archivos->count() > 0)
                <div class="files-grid">
                    @foreach($archivos as $archivo)
                    <div class="file-card">
                        <div class="file-header">
                            <div class="file-icon">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="file-info">
                                <h4 class="file-title">{{ $archivo['nombre_archivo'] ?? 'Documento sin título' }}</h4>
                                <div class="file-meta">
                                    <div class="file-date">
                                        <i class="fas fa-calendar"></i>
                                        {{ \Carbon\Carbon::parse($archivo['fecha_subida'])->format('d/m/Y') }}
                                    </div>
                                    <span class="file-size">PDF</span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('asesor.fraccionamiento.download-archivo', [
                            'idFraccionamiento' => $datosFraccionamiento['id'],
                            'idArchivo' => $archivo['id']
                        ]) }}" 
                           class="file-download"
                           target="_blank">
                            <i class="fas fa-download"></i> Descargar
                        </a>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="files-empty">
                    <i class="fas fa-file-download"></i>
                    <h4>No hay archivos disponibles</h4>
                    <p>Actualmente no hay documentos para descargar de este fraccionamiento.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- SECCIÓN DE PROMOCIONES (OPCIONAL) -->
        @if($promocionesActivas->count() > 0)
        <div class="oceanica-promociones-section">
            <div class="oceanica-promociones-title">
                <i class="fas fa-gift"></i>
                <h3>Promociones Activas</h3>
            </div>
            
            <div class="oceanica-promociones-grid">
                @foreach($promocionesActivas as $promo)
                @php
                    // Detectar si la imagen es vertical u horizontal
                    $esVertical = false;
                    if ($promo['imagen_path']) {
                        try {
                            $rutaImagen = storage_path('app/public/' . $promo['imagen_path']);
                            if (file_exists($rutaImagen)) {
                                list($ancho, $alto) = getimagesize($rutaImagen);
                                $esVertical = $alto > $ancho;
                            }
                        } catch (Exception $e) {
                            // En caso de error, asumir horizontal
                            $esVertical = false;
                        }
                    }
                    
                    // Formatear fechas en formato simple dd/mm/yyyy
                    $fechaInicioFormateada = \Carbon\Carbon::parse($promo['fecha_inicio'])->format('d/m/Y');
                    
                    if ($promo['fecha_fin'] !== 'Indefinida') {
                        $fechaFinFormateada = \Carbon\Carbon::parse($promo['fecha_fin'])->format('d/m/Y');
                    } else {
                        $fechaFinFormateada = 'Indefinida';
                    }
                @endphp
                
                <div class="oceanica-promo-card">
                    @if($promo['imagen_path'])
                    <div class="oceanica-promo-image-container">
                        <img src="{{ asset('storage/' . $promo['imagen_path']) }}" 
                            alt="{{ $promo['titulo'] }}"
                            title="Haz clic para ver en tamaño completo"
                            class="oceanica-promo-image {{ $esVertical ? 'vertical' : 'horizontal' }}"
                            onload="this.classList.remove('oceanica-image-loading')"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        
                        <!-- Fallback si la imagen no carga -->
                        <div class="oceanica-promo-image-container" style="display: none; background: var(--accent-color);">
                            <i class="fas fa-image" style="font-size: 3rem; color: var(--primary-color);"></i>
                        </div>
                        
                        <div class="oceanica-promo-overlay">
                            <a href="{{ asset('storage/' . $promo['imagen_path']) }}" 
                            download="promocion-{{ Str::slug($promo['titulo']) }}.jpg" 
                            class="oceanica-download-btn">
                                <i class="fas fa-download"></i>
                                Descargar Flyer
                            </a>
                        </div>
                        
                        <div class="oceanica-promo-badge">Activa</div>
                        <div class="oceanica-flyer-tag">Flyer</div>
                    </div>
                    @else
                    <div class="oceanica-promo-image-container" style="background: var(--accent-color);">
                        <i class="fas fa-gift" style="font-size: 3rem; color: var(--primary-color);"></i>
                        <div class="oceanica-promo-badge">Activa</div>
                    </div>
                    @endif
                    
                    <div class="oceanica-promo-content">
                        <h4 class="oceanica-promo-title">{{ $promo['titulo'] }}</h4>
                        
                        @if($promo['descripcion'])
                        <div class="oceanica-promo-description 
                            {{ strlen($promo['descripcion']) > 200 ? 'oceanica-promo-description-with-scroll' : '' }}">
                            {!! nl2br(e($promo['descripcion'])) !!}
                        </div>
                        @endif
                        
                        <div class="oceanica-promo-dates">
                            <div class="oceanica-date-item">
                                <i class="fas fa-play-circle oceanica-date-icon"></i>
                                <div class="oceanica-date-content">
                                    <div class="oceanica-date-label">Inicia: </div>
                                    <div class="oceanica-date-value">
                                        {{ $fechaInicioFormateada }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="oceanica-date-item">
                                <i class="fas fa-flag-checkered oceanica-date-icon"></i>
                                <div class="oceanica-date-content">
                                    <div class="oceanica-date-label">Finaliza: </div>
                                    <div class="oceanica-date-value 
                                        {{ $fechaFinFormateada === 'Indefinida' ? 'oceanica-indefinida' : '' }}">
                                        @if($fechaFinFormateada !== 'Indefinida')
                                            {{ $fechaFinFormateada }}
                                        @else
                                            <span class="oceanica-indefinida">Indefinida</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <!-- Estado vacío opcional -->
        <div class="oceanica-promociones-section">
            <div class="oceanica-promociones-empty">
                <i class="fas fa-gift"></i>
                <h4>No hay promociones activas</h4>
                <p>Actualmente no contamos con promociones vigentes.</p>
            </div>
        </div>
        @endif

        <!-- Development Map -->
        @if(isset($datosFraccionamiento['ubicacionMaps']) && !empty($datosFraccionamiento['ubicacionMaps']))
        <div class="development-map">
            <h3 class="info-title">
                <i class="fas fa-map-marked-alt"></i>
                <span>Google Maps</span>
            </hh3>
            <div class="map-container">
                <iframe 
                    class="map-iframe" 
                    src="{{ $datosFraccionamiento['ubicacionMaps'] }}" 
                    width="600" 
                    height="450" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
        @endif
    </div>

    <!-- Modal de Apartado -->
    <div class="modal-fraccionamiento" id="reservationModal">
        <div class="modal-content-fraccionamiento">
            <button class="close-modal-fraccionamiento" id="closeModal">&times;</button>
            <h2 class="modal-title-fraccionamiento">Apartar Lote(s)</h2>
            
            <form id="reservationForm">
                <div class="form-group">
                    <label class="form-label">Tipo de apartado</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" id="verbalReservation" name="reservationType" value="verbal" class="radio-input" checked>
                            <label for="verbalReservation" class="radio-label">
                                <i class="fas fa-handshake"></i> De palabra
                            </label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="depositReservation" name="reservationType" value="deposit" class="radio-input">
                            <label for="depositReservation" class="radio-label">
                                <i class="fas fa-money-bill-wave"></i> Con depósito
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Lote(s) a apartar</label>
                    <div class="lot-list" id="lotList">
                        <div class="lot-item">
                            <input type="text" class="form-control lot-number" required placeholder="Ej. 12, 5, etc.">
                            <button type="button" class="remove-lot">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="add-lot-btn" id="addLotBtn">
                        <i class="fas fa-plus-circle"></i> Agregar otro lote
                    </button>
                </div>
                
                <div class="form-group">
                    <label for="firstName" class="form-label">Nombres</label>
                    <input type="text" id="firstName" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="lastName" class="form-label">Apellidos</label>
                    <input type="text" id="lastName" class="form-control" required>
                </div>
                
                <div id="depositFields" class="deposit-fields">
                    <div class="form-group">
                        <label for="amount" class="form-label">Cantidad total a depositar (MXN)</label>
                        <input type="number" id="amount" class="form-control" min="1000">
                    </div>
                </div>
                
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                        <i class="fas fa-paper-plane"></i> Enviar Solicitud
                    </button>
                </div>
            </form>
            
            <div id="verbalReceipt" class="receipt">
                <h3 class="receipt-title">Resumen de Apartado</h3>
                <div class="receipt-info">
                    <p><strong>Nombre:</strong> <span id="verbalName"></span></p>
                    <p><strong>Lote(s):</strong> <span id="verbalLots"></span></p>
                    <p><strong>Tipo:</strong> Apartado de palabra</p>
                    <p><strong>Fecha y hora límite:</strong> <span id="deadlineDate"></span></p>
                    <p class="time-limit"><i class="fas fa-clock"></i> Vence exactamente en 2 días</p>
                    <p><strong>Fraccionamiento:</strong> {{ $datosFraccionamiento['nombre'] }}</p>
                </div>
                <div class="text-center">
                    <p>Una vez finalizado el tiempo límite se cancelará el apartado.</p>
                    <a href="#" class="share-btn verbal-share-btn" id="verbalWhatsappShare">
                        <i class="fab fa-whatsapp"></i> Notificar
                    </a>
                    <button class="btn btn-outline mt-3" id="closeAfterVerbal" onclick="window.location.reload();">
                        <i class="fas fa-check"></i> Aceptar
                    </button>
                </div>
            </div>
            
            <div id="depositReceipt" class="receipt">
                <h3 class="receipt-title">Datos para Depósito</h3>
                <div class="receipt-info">
                    <p><strong>Nombre:</strong> <span id="depositName"></span></p>
                    <p><strong>Lote(s):</strong> <span id="depositLots"></span></p>
                    <p><strong>Tipo:</strong> Apartado con depósito</p>
                    <p><strong>Monto total a depositar:</strong> $<span id="depositAmount"></span> MXN</p>
                    <p><strong>Fraccionamiento:</strong> {{ $datosFraccionamiento['nombre'] }}</p>
                </div>
                
                <div class="bank-details">
                    <h4 class="bank-details-title">Datos Bancarios</h4>
                    <p><strong>Banco:</strong> BBVA</p>
                    <p><strong>Nombre:</strong> Nelva Bienes Raíces S.A. de C.V.</p>
                    <p><strong>Cuenta:</strong> 0123 4567 8910 1112</p>
                    <p><strong>CLABE:</strong> 012 320 0123 4567 8910 11</p>
                </div>
                
                <div class="text-center">
                    <p>Una vez realizado el depósito, adjunte el comprobante para validar el pago.</p>
                    <a href="#" class="share-btn" id="whatsappShare">
                        <i class="fab fa-whatsapp"></i> Notificar
                    </a>
                    <button class="btn btn-outline mt-3" id="closeAfterVerbal" onclick="window.location.reload();">
                        <i class="fas fa-check"></i> Aceptar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Cálculo de Costo Mejorado -->
    <div class="modal-fraccionamiento" id="calculationModal">
        <div class="modal-content-fraccionamiento">
            <button class="close-modal-fraccionamiento" id="closeCalculationModal">&times;</button>
            <h2 class="modal-title-fraccionamiento">Calcular Costo del Lote</h2>
            
            <form id="calculationForm">
                <div class="form-group">
                    <label for="lotNumber" class="form-label">Número de Lote</label>
                    <input type="text" id="lotNumber" class="form-control" required placeholder="Ej. 12, 5, etc.">
                    <div id="lotError" class="error-message" style="display: none; color: var(--danger-color); margin-top: 0.5rem;"></div>
                </div>
                
                <div class="form-group">
                    <button type="button" class="btn btn-primary" id="calculateBtn" style="width: 100%;">
                        <i class="fas fa-calculator"></i> Calcular
                    </button>
                </div>
                
                <div id="lotDetails" class="lot-details-container" style="display: none;">
                    <div class="lot-details-content">
                        <h3 class="info-title">
                            <i class="fas fa-info-circle"></i>
                            <span>Detalles del Lote</span>
                        </h3>
                        <div class="lot-details-grid">
                            <div class="lot-detail-item">
                                <div class="lot-detail-label">ID del Lote</div>
                                <div class="lot-detail-value" id="lotId">-</div>
                            </div>
                            <div class="lot-detail-item">
                                <div class="lot-detail-label">Estatus</div>
                                <div class="lot-detail-value" id="lotStatus">
                                    <span class="status-badge" id="statusBadge">-</span>
                                </div>
                            </div>
                            <div class="lot-detail-item">
                                <div class="lot-detail-label">Manzana</div>
                                <div class="lot-detail-value" id="lotBlock">-</div>
                            </div>
                            <div class="lot-detail-item">
                                <div class="lot-detail-label">Área total</div>
                                <div class="lot-detail-value" id="lotArea">- m²</div>
                            </div>
                            <div class="lot-detail-item">
                                <div class="lot-detail-label">Norte</div>
                                <div class="lot-detail-value" id="lotNorth">- m</div>
                            </div>
                            <div class="lot-detail-item">
                                <div class="lot-detail-label">Sur</div>
                                <div class="lot-detail-value" id="lotSouth">- m</div>
                            </div>
                            <div class="lot-detail-item">
                                <div class="lot-detail-label">Oriente</div>
                                <div class="lot-detail-value" id="lotEast">- m</div>
                            </div>
                            <div class="lot-detail-item">
                                <div class="lot-detail-label">Poniente</div>
                                <div class="lot-detail-value" id="lotWest">- m</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="calculation-result">
                        <h3 class="info-title">
                            <i class="fas fa-dollar-sign"></i>
                            <span>Cálculo de Costo</span>
                        </h3>
                        <div class="calculation-item">
                            <div class="calculation-label">Precio por m²</div>
                            <div class="calculation-value">${{ number_format($datosFraccionamiento['precio_metro_cuadrado'] ?? 0, 2) }} MXN</div>
                        </div>
                        <div class="calculation-item highlight">
                            <div class="calculation-label">Costo total</div>
                            <div class="calculation-value" id="totalCost">$0 MXN</div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    


    @include('app_config')

    <script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
    <script src="{{ asset('js/modals.js') }}"></script>
    <script src="{{ asset('js/map.js') }}"></script>

    <script>
        // Efectos hover mejorados para tarjetas
        document.addEventListener('DOMContentLoaded', function() {
            const galleryCards = document.querySelectorAll('.gallery-card');
            const fileCards = document.querySelectorAll('.file-card');

            // Efecto para tarjetas de galería
            galleryCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-8px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Efecto para tarjetas de archivos
            fileCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
@endsection