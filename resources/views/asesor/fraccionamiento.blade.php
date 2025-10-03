@extends('asesor.navbar')

@section('title', 'Detalles del Fraccionamiento')

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
                <button class="btn btn-outline" onclick="window.location.href='/asesor/inicio'">
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

        
        {{-- Development Plan --}}
        
        <div class="development-plan">
            <h3 class="info-title">
                <i class="fas fa-map"></i>
                <span>Plano Interactivo del Fraccionamiento</span>
            </h3>
            
            <div class="plan-container" id="planContainer">
                <button class="fullscreen-btn" id="fullscreenBtn">
                    <i class="fas fa-expand"></i> Pantalla Completa
                </button>

                {{-- Contenedor del mapa de Mapbox --}}
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
                            <button class="style-btn" data-style="outdoors">
                                <i class="fas fa-mountain"></i> Relieve
                            </button>
                            <button class="style-btn" data-style="streets">
                                <i class="fas fa-road"></i> Calles
                            </button>
                            <button class="style-btn" data-style="light">
                                <i class="fas fa-map"></i> Light
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
                            <button class="filter-btn" data-filter="apartado">
                                <div class="color-indicator apartado-indicator"></div>
                                Apartados
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

            <div class="plan-actions mt-3">
                <h5><i class="fas fa-file-download"></i> Planos disponibles:</h5>
                @foreach($planos as $plano)
                    <a href="{{ route('asesor.fraccionamiento.download-plano', [
                        'idFraccionamiento' => $datosFraccionamiento['id'],
                        'idPlano' => $plano['id']
                    ]) }}"
                    class="btn btn-outline m-1" target="_blank">
                    <i class="fas fa-download"></i> {{ $plano['nombre'] }}
                    </a>
                @endforeach
            </div>
        </div>
        
         {{-- Development Plan --}}
        
        

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

            <div class="info-section">
                <h3 class="info-title">
                    <i class="fas fa-map-marked-alt"></i>
                    <span>Ubicación</span>
                </h3>
                <div class="info-item">
                    <div class="info-value">• {{ $datosFraccionamiento['ubicacion'] }}</div>
                </div>
            </div>
        </div>

         <!-- Development Map -->
        @if(isset($datosFraccionamiento['ubicacionMaps']) && !empty($datosFraccionamiento['ubicacionMaps']))
        <div class="development-map">
            <h3 class="info-title">
                <i class="fas fa-map-marked-alt"></i>
                <span>Ubicación en Mapa</span>
            </h3>
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
                    <p>Una vez finalizado el tiempo limite se cancelará el apartado.</p>
                    <a href="#" class="share-btn verbal-share-btn" id="verbalWhatsappShare">
                        <i class="fab fa-whatsapp"></i> Notificar
                    </a>
                    <button class="btn btn-outline mt-3" id="closeAfterVerbal">
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
                    <p><strong>Referencia:</strong> {{ substr($datosFraccionamiento['nombre'], 0, 3) }}-<span id="referenceNumber"></span></p>
                </div>
                
                <div class="text-center">
                    <p>Una vez realizado el depósito, adjunte el comprobante para validar el pago.</p>
                    <a href="#" class="share-btn" id="whatsappShare">
                        <i class="fab fa-whatsapp"></i> Notificar
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de Cálculo de Costo Mejorado -->
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
<script>
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
       ELEMENTOS DOM
       =========================== */
    const reservationModal = document.getElementById('reservationModal');
    const closeModal = document.getElementById('closeModal');
    const openReservationModal = document.getElementById('openReservationModal');
    const reservationForm = document.getElementById('reservationForm');
    const depositFields = document.getElementById('depositFields');
    const verbalReceipt = document.getElementById('verbalReceipt');
    const depositReceipt = document.getElementById('depositReceipt');
    const verbalName = document.getElementById('verbalName');
    const depositName = document.getElementById('depositName');
    const verbalLots = document.getElementById('verbalLots');
    const depositLots = document.getElementById('depositLots');
    const depositAmount = document.getElementById('depositAmount');
    const deadlineDate = document.getElementById('deadlineDate');
    const referenceNumber = document.getElementById('referenceNumber');
    const closeAfterVerbal = document.getElementById('closeAfterVerbal');
    const whatsappShare = document.getElementById('whatsappShare');
    const verbalWhatsappShare = document.getElementById('verbalWhatsappShare');
    const lotList = document.getElementById('lotList');
    const addLotBtn = document.getElementById('addLotBtn');

    const calculationModal = document.getElementById('calculationModal');
    const closeCalculationModal = document.getElementById('closeCalculationModal');
    const openCalculationModal = document.getElementById('openCalculationModal');
    const calculateBtn = document.getElementById('calculateBtn');
    const lotDetails = document.getElementById('lotDetails');

    /* ===========================
       VARIABLES GLOBALES DEL MAPA
       =========================== */
    let map = null;
    let currentFilter = 'all';
    let lotesData = null;
    let pendingFilter = null;

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
       CAMPOS DINÁMICOS LOTES
       =========================== */
    function addLotField() {
        if (!lotList) return;
        const newLotItem = document.createElement('div');
        newLotItem.className = 'lot-item';
        newLotItem.innerHTML = `
            <input type="text" class="form-control lot-number" required placeholder="Ej. 12, 5, etc.">
            <button type="button" class="remove-lot">
                <i class="fas fa-times"></i>
            </button>
        `;
        lotList.appendChild(newLotItem);

        const removeBtn = newLotItem.querySelector('.remove-lot');
        if (removeBtn) {
            removeBtn.addEventListener('click', function () {
                removeLotField(newLotItem);
            });
        }
    }

    function removeLotField(lotItem) {
        if (!lotList || !lotItem) return;
        if (lotList.children.length > 1) {
            lotList.removeChild(lotItem);
        } else {
            alert('Debe especificar al menos un lote');
        }
    }

    if (addLotBtn) addLotBtn.addEventListener('click', addLotField);

    document.querySelectorAll('.remove-lot').forEach(btn => {
        btn.addEventListener('click', function () {
            const lotItem = this.closest('.lot-item');
            removeLotField(lotItem);
        });
    });

    /* ===========================
       MODALES: abrir/cerrar y reset
       =========================== */
    if (openReservationModal) {
        openReservationModal.addEventListener('click', function () {
            if (!reservationModal) return;
            reservationModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
    }

    if (openCalculationModal) {
        openCalculationModal.addEventListener('click', function () {
            if (!calculationModal) return;
            calculationModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
    }

    if (closeModal) {
        closeModal.addEventListener('click', function () {
            if (!reservationModal) return;
            reservationModal.style.display = 'none';
            document.body.style.overflow = 'auto';
            resetReservationForm();
        });
    }

    if (closeCalculationModal) {
        closeCalculationModal.addEventListener('click', function () {
            if (!calculationModal) return;
            calculationModal.style.display = 'none';
            document.body.style.overflow = 'auto';
            resetCalculationForm();
        });
    }

    if (reservationModal) {
        reservationModal.addEventListener('click', function (e) {
            if (e.target === reservationModal) {
                reservationModal.style.display = 'none';
                document.body.style.overflow = 'auto';
                resetReservationForm();
            }
        });
    }

    if (calculationModal) {
        calculationModal.addEventListener('click', function (e) {
            if (e.target === calculationModal) {
                calculationModal.style.display = 'none';
                document.body.style.overflow = 'auto';
                resetCalculationForm();
            }
        });
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            if (reservationModal) reservationModal.style.display = 'none';
            if (calculationModal) calculationModal.style.display = 'none';
            document.body.style.overflow = 'auto';
            resetReservationForm();
            resetCalculationForm();
        }
    });

    document.querySelectorAll('input[name="reservationType"]').forEach(radio => {
        radio.addEventListener('change', function () {
            if (depositFields) depositFields.style.display = this.value === 'deposit' ? 'block' : 'none';
        });
    });

    function resetReservationForm() {
        if (!reservationForm) return;
        reservationForm.reset();
        reservationForm.style.display = 'block';
        if (verbalReceipt) verbalReceipt.style.display = 'none';
        if (depositReceipt) depositReceipt.style.display = 'none';
        if (depositFields) depositFields.style.display = 'none';

        if (lotList) {
            while (lotList.children.length > 1) {
                lotList.removeChild(lotList.lastChild);
            }
            const first = document.querySelector('.lot-number');
            if (first) first.value = '';
        }
    }

    function resetCalculationForm() {
        const calcForm = document.getElementById('calculationForm');
        if (calcForm) calcForm.reset();
        if (lotDetails) lotDetails.style.display = 'none';
    }

    /* ===========================
       CÁLCULO DE COSTO
       =========================== */
    if (calculateBtn) {
        calculateBtn.addEventListener('click', async function () {
            const lotNumberInput = document.getElementById('lotNumber');
            const lotError = document.getElementById('lotError');
            if (!lotNumberInput) return;
            const lotNumber = lotNumberInput.value.trim();

            if (!lotNumber) {
                if (lotError) { lotError.textContent = 'Por favor ingrese un número de lote'; lotError.style.display = 'block'; }
                return;
            }
            if (!/^\d+$/.test(lotNumber)) {
                if (lotError) { lotError.textContent = 'Por favor ingrese un número de lote válido'; lotError.style.display = 'block'; }
                return;
            }

            try {
                calculateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Calculando...';
                calculateBtn.disabled = true;
                if (lotError) lotError.style.display = 'none';

                const fraccionamientoId = {{ $datosFraccionamiento['id'] }};
                const url = `/asesor/fraccionamiento/${fraccionamientoId}/lote/${encodeURIComponent(lotNumber)}`;

                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 10000);

                const response = await fetch(url, {
                    signal: controller.signal,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                clearTimeout(timeoutId);

                const contentType = response.headers.get('content-type') || '';
                if (!contentType.includes('application/json')) {
                    throw new Error('Respuesta del servidor no es JSON');
                }

                const data = await response.json();
                if (!response.ok) throw new Error(data.message || `Error ${response.status}`);

                if (data.success) {
                    const lote = data.lote || {};
                    const status = lote.estatus || 'no disponible';
                    const statusBadge = document.getElementById('statusBadge');
                    if (statusBadge) {
                        statusBadge.textContent = formatStatus(status);
                        statusBadge.className = 'status-badge ' + getStatusClass(status);
                    }

                    const el = id => document.getElementById(id);
                    if (el('lotId')) el('lotId').textContent = lote.id || 'N/A';
                    if (el('lotBlock')) el('lotBlock').textContent = lote.manzana || 'N/A';
                    if (el('lotArea')) el('lotArea').textContent = `${lote.area_total ? lote.area_total.toLocaleString('es-MX') : '0'} m²`;

                    if (lote.medidas) {
                        if (el('lotNorth')) el('lotNorth').textContent = `${lote.medidas.norte || '0'} m`;
                        if (el('lotSouth')) el('lotSouth').textContent = `${lote.medidas.sur || '0'} m`;
                        if (el('lotEast')) el('lotEast').textContent = `${lote.medidas.oriente || '0'} m`;
                        if (el('lotWest')) el('lotWest').textContent = `${lote.medidas.poniente || '0'} m`;
                    } else {
                        if (el('lotNorth')) el('lotNorth').textContent = 'No disponible';
                        if (el('lotSouth')) el('lotSouth').textContent = 'No disponible';
                        if (el('lotEast')) el('lotEast').textContent = 'No disponible';
                        if (el('lotWest')) el('lotWest').textContent = 'No disponible';
                    }

                    const totalCost = lote.costo_total || 0;
                    if (el('totalCost')) el('totalCost').textContent = `$${totalCost.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} MXN`;

                    if (lotDetails) lotDetails.style.display = 'block';
                } else {
                    throw new Error(data.message || 'Lote no encontrado');
                }
            } catch (error) {
                console.error('Error en cálculo:', error);
                const lotError = document.getElementById('lotError');
                if (lotError) {
                    if (error.name === 'AbortError') {
                        lotError.textContent = 'La solicitud tardó demasiado tiempo. Intente nuevamente.';
                    } else if (error.message.includes('JSON')) {
                        lotError.textContent = 'Error en la respuesta del servidor.';
                    } else {
                        lotError.textContent = error.message || 'Error al calcular el costo del lote.';
                    }
                    lotError.style.display = 'block';
                }
                if (lotDetails) lotDetails.style.display = 'none';
            } finally {
                calculateBtn.innerHTML = '<i class="fas fa-calculator"></i> Calcular';
                calculateBtn.disabled = false;
            }
        });
    }

    /* ===========================
       ENVÍO FORMULARIO APARTADO
       =========================== */
    if (reservationForm) {
        reservationForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const reservationType = document.querySelector('input[name="reservationType"]:checked')?.value || 'verbal';
            const firstName = (document.getElementById('firstName')?.value || '').trim();
            const lastName = (document.getElementById('lastName')?.value || '').trim();
            const lotNumbers = Array.from(document.querySelectorAll('.lot-number'))
                .map(input => input.value.trim())
                .filter(v => v !== '');

            if (!firstName || !lastName) {
                alert('Por favor complete todos los campos requeridos');
                return;
            }
            if (lotNumbers.length === 0) {
                alert('Por favor ingrese al menos un número de lote');
                return;
            }

            const randomRef = Math.floor(1000 + Math.random() * 9000);

            if (reservationType === 'verbal') {
                if (verbalName) verbalName.textContent = `${firstName} ${lastName}`;
                if (verbalLots) verbalLots.textContent = lotNumbers.join(', ');
                const deadline = new Date();
                deadline.setDate(deadline.getDate() + 2);
                if (deadlineDate) deadlineDate.textContent = deadline.toLocaleString('es-MX', {
                    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'
                });

                const verbalMessage = `Hola ${firstName}, tu apartado de palabra para el lote(s) ${lotNumbers.join(', ')} en {{ $datosFraccionamiento['nombre'] }} ha sido registrado. Tienes hasta el ${deadline.toLocaleDateString('es-MX')} para confirmar.`;
                if (verbalWhatsappShare) verbalWhatsappShare.href = `https://wa.me/?text=${encodeURIComponent(verbalMessage)}`;

                reservationForm.style.display = 'none';
                if (verbalReceipt) verbalReceipt.style.display = 'block';
            } else {
                const amount = document.getElementById('amount')?.value;
                if (!amount || amount < 1000) {
                    alert('Por favor ingrese un monto válido (mínimo $1,000 MXN)');
                    return;
                }
                if (depositName) depositName.textContent = `${firstName} ${lastName}`;
                if (depositLots) depositLots.textContent = lotNumbers.join(', ');
                if (depositAmount) depositAmount.textContent = parseFloat(amount).toLocaleString('es-MX', { minimumFractionDigits: 2 });

                if (referenceNumber) referenceNumber.textContent = randomRef;

                const depositMessage = `Hola ${firstName}, para apartar el lote(s) ${lotNumbers.join(', ')} en {{ $datosFraccionamiento['nombre'] }} realiza un depósito de $${amount} MXN a la cuenta BBVA. Referencia: {{ substr($datosFraccionamiento['nombre'], 0, 3) }}-${randomRef}`;
                if (whatsappShare) whatsappShare.href = `https://wa.me/?text=${encodeURIComponent(depositMessage)}`;

                reservationForm.style.display = 'none';
                if (depositReceipt) depositReceipt.style.display = 'block';
            }
        });
    }

    if (closeAfterVerbal) {
        closeAfterVerbal.addEventListener('click', function () {
            if (reservationModal) reservationModal.style.display = 'none';
            document.body.style.overflow = 'auto';
            resetReservationForm();
        });
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

        const mapStyles = {
            'satellite-streets': 'mapbox://styles/mapbox/satellite-streets-v12',
            'outdoors': 'mapbox://styles/mapbox/outdoors-v12',
            'streets': 'mapbox://styles/mapbox/streets-v12',
            'light': 'mapbox://styles/mapbox/light-v11'
        };

        try {
            map = new mapboxgl.Map({
                container: 'mapPlano',
                style: mapStyles['satellite-streets'],
                center: [-96.7779, 15.7345],
                zoom: 17,
                pitch: 45,
                bearing: -17,
                antialias: true
            });

            map.on('load', () => {
                console.log('✅ Mapa cargado correctamente');
                initMapControls();
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
            const fraccionamientoNombre = '{{ $datosFraccionamiento["nombre"] }}'.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/\s+/g, '_');
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
            loadLotesFromServer();
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
            const fraccionamientoId = {{ $datosFraccionamiento['id'] }};
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

    async function loadLotesFromServer() {
        try {
            const fraccionamientoId = {{ $datosFraccionamiento['id'] }};
            const response = await fetch(`/asesor/fraccionamiento/${fraccionamientoId}/lotes`);
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            const data = await response.json();

            if (data.success && data.lotes) {
                processLotesData(data.lotes);
            } else {
                throw new Error(data.message || 'Error en los datos recibidos');
            }
        } catch (error) {
            console.error('Error cargando lotes:', error);
        }
    }

    function processLotesData(lotes) {
        const geojson = {
            type: "FeatureCollection",
            features: lotes.map(lote => ({
                type: "Feature",
                properties: {
                    id: lote.id_lote,
                    lote: lote.numeroLote,
                    estatus: lote.estatus,
                    manzana: lote.manzana || 'N/A',
                    area: lote.area_total || 'N/A',
                    norte: lote.medidas?.norte || 'N/A',
                    sur: lote.medidas?.sur || 'N/A',
                    oriente: lote.medidas?.oriente || 'N/A',
                    poniente: lote.medidas?.poniente || 'N/A',
                    area_metros: lote.medidas?.area_metros || 'N/A'
                },
                geometry: generateLoteGeometry(lote)
            }))
        };

        lotesData = geojson;
        if (map) addLotesToMap(geojson);
    }

    function generateLoteGeometry(lote) {
        const baseLng = -96.7779;
        const baseLat = 15.7345;
        const loteNum = parseInt(lote.numeroLote) || 1;

        const offsetX = (loteNum % 6) * 0.00028;
        const offsetY = Math.floor(loteNum / 6) * 0.00028;

        return {
            type: "Polygon",
            coordinates: [[
                [baseLng + offsetX, baseLat + offsetY],
                [baseLng + offsetX + 0.00022, baseLat + offsetY],
                [baseLng + offsetX + 0.00022, baseLat + offsetY + 0.00022],
                [baseLng + offsetX, baseLat + offsetY + 0.00022],
                [baseLng + offsetX, baseLat + offsetY]
            ]]
        };
    }

    function safeRemoveLayer(id) {
        if (!map) return;
        try {
            if (map.getLayer(id)) map.removeLayer(id);
        } catch (e) { /* ignore */ }
    }
    
    function safeRemoveSource(id) {
        if (!map) return;
        try {
            if (map.getSource(id)) map.removeSource(id);
        } catch (e) { /* ignore */ }
    }

    function addLotesToMap(data) {
        if (!map) return;

        safeRemoveLayer('lotes-fill');
        safeRemoveLayer('lotes-borders');
        safeRemoveLayer('lotes-labels');
        safeRemoveSource('lotes');

        try {
            map.addSource('lotes', { type: 'geojson', data });

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

            addFraccionamientoLabel();
            map.addControl(new mapboxgl.NavigationControl(), 'top-right');
            setupMapInteractions();

            if (pendingFilter) {
                filterLotesByStatus(pendingFilter);
                pendingFilter = null;
            }

            fitMapToLotes(data);

        } catch (error) {
            console.error('❌ Error agregando lotes al mapa:', error);
        }
    }

    function addFraccionamientoLabel() {
        const fraccionamientoNombre = '{{ $datosFraccionamiento["nombre"] }}';
        const center = map.getCenter();
        
        new mapboxgl.Popup({ 
            closeButton: false, 
            closeOnClick: false,
            className: 'fraccionamiento-label-popup'
        })
        .setLngLat(center)
        .setHTML(`
            <div class="fraccionamiento-center-label">
                <h3>${fraccionamientoNombre}</h3>
            </div>
        `)
        .addTo(map);
    }

    function fitMapToLotes(data) {
        if (!map || !data || !data.features || data.features.length === 0) return;
        const bounds = new mapboxgl.LngLatBounds();

        data.features.forEach(feature => {
            if (!feature.geometry || !feature.geometry.coordinates) return;
            const coords = feature.geometry.coordinates;

            if (feature.geometry.type === 'Polygon' && Array.isArray(coords[0])) {
                coords[0].forEach(c => {
                    if (Array.isArray(c) && c.length >= 2) bounds.extend(c);
                });
            } else if (feature.geometry.type === 'MultiPolygon') {
                coords.forEach(polygon => {
                    polygon[0].forEach(c => { if (Array.isArray(c) && c.length >= 2) bounds.extend(c); });
                });
            }
        });

        if (!bounds.isEmpty()) {
            map.fitBounds(bounds, { padding: 50, duration: 1000 });
        }
    }

    function setupMapInteractions() {
        if (!map.getLayer('lotes-fill')) return;

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
            popup.remove();
            popup.setLngLat(e.lngLat)
                 .setHTML(createCompactPopupContent(properties))
                 .addTo(map);
        });
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
                    <button class="btn-compact btn-calculate" onclick="openCalculationForLote('${properties.lote}')">
                        <i class="fas fa-calculator"></i>
                    </button>
                    <button class="btn-compact btn-reserve" onclick="openReservationForLote('${properties.lote}')">
                        <i class="fas fa-handshake"></i>
                    </button>
                </div>
            </div>
        `;
    }

    function showLoteInfo(properties) {
        const infoPanel = document.getElementById('infoPanelMap');
        const content = document.getElementById('loteInfoContent');
        if (!content || !infoPanel) return;

        const statusClass = getStatusClass(properties.estatus);
        const statusText = formatStatus(properties.estatus);

        content.innerHTML = `
            <div class="lote-header">
                <div class="lote-numero">Lote ${properties.lote}</div>
                <span class="estatus-badge ${statusClass}">${statusText}</span>
            </div>
            <div class="lote-details">
                <div class="detail-section">
                    <h5>Información General</h5>
                    <div class="lote-detail-row"><span class="lote-detail-label">Manzana:</span><span class="lote-detail-value">${properties.manzana}</span></div>
                    <div class="lote-detail-row"><span class="lote-detail-label">Área total:</span><span class="lote-detail-value">${properties.area_metros} m²</span></div>
                </div>
                <div class="detail-section">
                    <h5>Medidas</h5>
                    <div class="lote-detail-row"><span class="lote-detail-label">Norte:</span><span class="lote-detail-value">${properties.norte} m</span></div>
                    <div class="lote-detail-row"><span class="lote-detail-label">Sur:</span><span class="lote-detail-value">${properties.sur} m</span></div>
                    <div class="lote-detail-row"><span class="lote-detail-label">Oriente:</span><span class="lote-detail-value">${properties.oriente} m</span></div>
                    <div class="lote-detail-row"><span class="lote-detail-label">Poniente:</span><span class="lote-detail-value">${properties.poniente} m</span></div>
                </div>
            </div>
            <div class="lote-actions">
                <button class="btn btn-primary btn-sm" onclick="openCalculationForLote('${properties.lote}')"><i class="fas fa-calculator"></i> Calcular Costo</button>
                <button class="btn btn-outline btn-sm" onclick="openReservationForLote('${properties.lote}')"><i class="fas fa-handshake"></i> Apartar</button>
            </div>
        `;
        infoPanel.classList.remove('hidden');
    }

    /* ===========================
       CONTROLES DEL MAPA
       =========================== */
    function initMapControls() {
        document.querySelectorAll('.style-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.style-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const style = this.getAttribute('data-style');
                if (!mapStyles[style]) return;
                map.setStyle(mapStyles[style]);

                map.once('style.load', () => {
                    if (lotesData) addLotesToMap(lotesData);
                    if (pendingFilter) { filterLotesByStatus(pendingFilter); pendingFilter = null; }
                });
            });
        });

        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                currentFilter = this.getAttribute('data-filter');
                filterLotesByStatus(currentFilter);
            });
        });

        const infoClose = document.getElementById('infoCloseMap');
        if (infoClose) infoClose.addEventListener('click', () => document.getElementById('infoPanelMap')?.classList.add('hidden'));

        const fullscreenBtn = document.getElementById('fullscreenBtn');
        if (fullscreenBtn) fullscreenBtn.addEventListener('click', toggleFullscreenMap);
    }

    function filterLotesByStatus(status) {
        if (!map) return;
        if (!map.getLayer('lotes-fill')) {
            pendingFilter = status;
            return;
        }

        if (status === 'all') {
            map.setFilter('lotes-fill', null);
            map.setFilter('lotes-borders', null);
            map.setFilter('lotes-labels', null);
        } else if (status === 'apartado') {
            map.setFilter('lotes-fill', [
                'any',
                ['==', ['get', 'estatus'], 'apartadoPalabra'],
                ['==', ['get', 'estatus'], 'apartadoVendido']
            ]);
            map.setFilter('lotes-borders', [
                'any',
                ['==', ['get', 'estatus'], 'apartadoPalabra'],
                ['==', ['get', 'estatus'], 'apartadoVendido']
            ]);
            map.setFilter('lotes-labels', [
                'any',
                ['==', ['get', 'estatus'], 'apartadoPalabra'],
                ['==', ['get', 'estatus'], 'apartadoVendido']
            ]);
        } else {
            map.setFilter('lotes-fill', ['==', ['get', 'estatus'], status]);
            map.setFilter('lotes-borders', ['==', ['get', 'estatus'], status]);
            map.setFilter('lotes-labels', ['==', ['get', 'estatus'], status]);
        }
    }

    function toggleFullscreenMap() {
        const container = document.getElementById('planContainer');
        if (!container) return;
        if (!document.fullscreenElement) {
            container.requestFullscreen().catch(err => console.error('Error al activar pantalla completa:', err));
        } else {
            document.exitFullscreen();
        }
    }

    /* ===========================
       FUNCIONES GLOBALES
       =========================== */
    window.openCalculationForLote = function (loteNumber) {
        const lotInput = document.getElementById('lotNumber');
        if (lotInput) lotInput.value = loteNumber;
        if (calculationModal) calculationModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        setTimeout(() => document.getElementById('calculateBtn')?.click(), 500);
    };

    window.openReservationForLote = function (loteNumber) {
        if (!lotList) return;
        while (lotList.children.length > 1) lotList.removeChild(lotList.lastChild);
        const first = document.querySelector('.lot-number');
        if (first) first.value = loteNumber;

        if (reservationModal) reservationModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    };

    /* ===========================
       INICIALIZACIÓN FINAL
       =========================== */
    setTimeout(() => {
        initializeMap();
    }, 100);

    window.addEventListener('beforeunload', function() {
        if (map) {
            try {
                map.remove();
            } catch (e) {
                console.log('⚠️ Error limpiando mapa:', e.message);
            }
        }
    });

    console.log('✅ Script de fraccionamiento cargado correctamente');
});
</script>


<script src='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js'></script>
@endsection

