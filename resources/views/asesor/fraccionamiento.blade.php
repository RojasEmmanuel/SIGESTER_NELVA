@extends('asesor.navbar')

@section('title', 'Detalles del Fraccionamiento')

@push('styles')
<link href="{{ asset('css/fraccionamiento.css') }}" rel="stylesheet">
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
                <button class="btn btn-outline" onclick="window.location.href='ventas'">
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
                <i class="fas fa-calculator"></i> Calcular Costo
            </button>
        </div>

        <!-- Development Plan -->
        @if($planos->count() > 0)
        <div class="development-plan">
            <h3 class="info-title">
                <i class="fas fa-map"></i>
                <span>Plano del Fraccionamiento</span>
            </h3>
            
            <div class="plan-container" id="planContainer">
                <button class="fullscreen-btn" id="fullscreenBtn">
                    <i class="fas fa-expand"></i> Pantalla Completa
                </button>
                
                <!-- Imagen del plano -->
                <img src="{{ asset('storage/' . $planos->first()['plano_path']) }}" alt="Plano del fraccionamiento {{ $datosFraccionamiento['nombre'] }}" class="plan-image" id="planImage">
            </div>
            
            <div class="plan-actions">
                <a href="{{ route('asesor.fraccionamiento.download-plano', ['idFraccionamiento' => $datosFraccionamiento['id'], 'idPlano' => $planos->first()['id']]) }}" class="btn btn-outline">
                    <i class="fas fa-download"></i> Descargar Plano
                </a>
            </div>
        </div>
        @endif

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

    <!-- Image Viewer Modal -->
    <div class="image-viewer-modal" id="imageViewerModal">
        <span class="close-viewer" id="closeViewer">&times;</span>
        <div class="image-viewer-content">
            <img src="" alt="" class="image-viewer-img" id="viewerImage">
        </div>
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
                
                <div id="lotDetails" style="display: none;">
                    <div class="info-section">
                        <h3 class="info-title">
                            <i class="fas fa-info-circle"></i>
                            <span>Detalles del Lote</span>
                        </h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">ID del Lote</div>
                                <div class="info-value" id="lotId">-</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Estatus</div>
                                <div class="info-value" id="lotStatus">
                                    <span class="status-badge" id="statusBadge">-</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Manzana</div>
                                <div class="info-value" id="lotBlock">-</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Área total</div>
                                <div class="info-value" id="lotArea">- m²</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Norte</div>
                                <div class="info-value" id="lotNorth">- m</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Sur</div>
                                <div class="info-value" id="lotSouth">- m</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Oriente</div>
                                <div class="info-value" id="lotEast">- m</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Poniente</div>
                                <div class="info-value" id="lotWest">- m</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-section">
                        <h3 class="info-title">
                            <i class="fas fa-dollar-sign"></i>
                            <span>Cálculo de Costo</span>
                        </h3>
                        <div class="info-item">
                            <div class="info-label">Precio por m²</div>
                            <div class="info-value">${{ number_format($datosFraccionamiento['precio_metro_cuadrado'] ?? 0, 2) }} MXN</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Costo total</div>
                            <div class="info-value highlight" id="totalCost">$0 MXN</div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* Estilos para el badge de estatus */
        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-disponible {
            background-color: #e8f5e8;
            color: #2e7d32;
        }
        
        .status-apartado {
            background-color: #fff3e0;
            color: #ef6c00;
        }
        
        .status-vendido {
            background-color: #ffebee;
            color: #c62828;
        }
        
        .status-no-disponible {
            background-color: #f5f5f5;
            color: #757575;
        }

        /* Estilos para el mapa */
        .map-container {
            width: 100%;
            height: 400px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 1rem;
        }

        .map-iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .development-map {
            margin-top: 2rem;
            padding: 1.5rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>

    <script>
        // Modal de apartado
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

        // Modal de cálculo
        const calculationModal = document.getElementById('calculationModal');
        const closeCalculationModal = document.getElementById('closeCalculationModal');
        const openCalculationModal = document.getElementById('openCalculationModal');
        const calculateBtn = document.getElementById('calculateBtn');
        const lotDetails = document.getElementById('lotDetails');

        // Función para agregar nuevo campo de lote
        function addLotField() {
            const newLotItem = document.createElement('div');
            newLotItem.className = 'lot-item';
            newLotItem.innerHTML = `
                <input type="text" class="form-control lot-number" required placeholder="Ej. 12, 5, etc.">
                <button type="button" class="remove-lot">
                    <i class="fas fa-times"></i>
                </button>
            `;
            lotList.appendChild(newLotItem);
            
            // Agregar evento al botón de eliminar
            const removeBtn = newLotItem.querySelector('.remove-lot');
            removeBtn.addEventListener('click', function() {
                removeLotField(newLotItem);
            });
        }

        // Función para eliminar campo de lote
        function removeLotField(lotItem) {
            if (lotList.children.length > 1) {
                lotList.removeChild(lotItem);
            } else {
                alert('Debe especificar al menos un lote');
            }
        }

        // Agregar evento al botón de agregar lote
        addLotBtn.addEventListener('click', addLotField);

        // Agregar eventos a los botones de eliminar existentes
        document.querySelectorAll('.remove-lot').forEach(btn => {
            btn.addEventListener('click', function() {
                const lotItem = this.closest('.lot-item');
                removeLotField(lotItem);
            });
        });

        // Abrir modal de apartado
        openReservationModal.addEventListener('click', function() {
            reservationModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });

        // Abrir modal de cálculo
        openCalculationModal.addEventListener('click', function() {
            calculationModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });

        // Cerrar modales
        closeModal.addEventListener('click', function() {
            reservationModal.style.display = 'none';
            document.body.style.overflow = 'auto';
            resetReservationForm();
        });

        closeCalculationModal.addEventListener('click', function() {
            calculationModal.style.display = 'none';
            document.body.style.overflow = 'auto';
            resetCalculationForm();
        });

        // Cerrar al hacer clic fuera del modal
        reservationModal.addEventListener('click', function(e) {
            if (e.target === reservationModal) {
                reservationModal.style.display = 'none';
                document.body.style.overflow = 'auto';
                resetReservationForm();
            }
        });

        calculationModal.addEventListener('click', function(e) {
            if (e.target === calculationModal) {
                calculationModal.style.display = 'none';
                document.body.style.overflow = 'auto';
                resetCalculationForm();
            }
        });

        // Cerrar con tecla ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                reservationModal.style.display = 'none';
                calculationModal.style.display = 'none';
                document.body.style.overflow = 'auto';
                resetReservationForm();
                resetCalculationForm();
            }
        });

        // Mostrar/ocultar campos de depósito según selección
        document.querySelectorAll('input[name="reservationType"]').forEach(radio => {
            radio.addEventListener('change', function() {
                depositFields.style.display = this.value === 'deposit' ? 'block' : 'none';
            });
        });

        // Resetear formulario de apartado
        function resetReservationForm() {
            reservationForm.reset();
            reservationForm.style.display = 'block';
            verbalReceipt.style.display = 'none';
            depositReceipt.style.display = 'none';
            depositFields.style.display = 'none';
            
            // Mantener solo un campo de lote
            while (lotList.children.length > 1) {
                lotList.removeChild(lotList.lastChild);
            }
            // Limpiar el primer campo de lote
            document.querySelector('.lot-number').value = '';
        }

        // Resetear formulario de cálculo
        function resetCalculationForm() {
            document.getElementById('calculationForm').reset();
            lotDetails.style.display = 'none';
        }

        // Función para obtener la clase CSS según el estatus del lote
        function getStatusClass(status) {
            const statusMap = {
                'disponible': 'status-disponible',
                'apartadoPalabra': 'status-apartado',
                'apartadoVendido': 'status-apartado', 
                'vendido': 'status-vendido',
                'no disponible': 'status-no-disponible'
            };
            
            return statusMap[status] || 'status-no-disponible';
        }

        // Función para formatear el estatus del lote
        function formatStatus(status) {
            const statusMap = {
                'disponible': 'Disponible',
                'apartadoPalabra': 'Apartado (Palabra)',
                'apartadoVendido': 'Apartado (Vendido)',
                'vendido': 'Vendido',
                'no disponible': 'No Disponible'
            };
            
            return statusMap[status] || status;
        }

        // Función para calcular el costo del lote
        calculateBtn.addEventListener('click', async function() {
            const lotNumber = document.getElementById('lotNumber').value.trim();
            const lotError = document.getElementById('lotError');
            
            // Validación básica
            if (!lotNumber) {
                lotError.textContent = 'Por favor ingrese un número de lote';
                lotError.style.display = 'block';
                return;
            }

            // Validar que sea un número válido
            if (!/^\d+$/.test(lotNumber)) {
                lotError.textContent = 'Por favor ingrese un número de lote válido';
                lotError.style.display = 'block';
                return;
            }

            try {
                // Mostrar loading
                calculateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Calculando...';
                calculateBtn.disabled = true;
                lotError.style.display = 'none';

                const fraccionamientoId = {{ $datosFraccionamiento['id'] }};
                const url = `/asesor/fraccionamiento/${fraccionamientoId}/lote/${encodeURIComponent(lotNumber)}`;
                
                console.log('Realizando petición a:', url);

                // Hacer la petición al servidor con timeout
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 10000); // 10 segundos timeout

                const response = await fetch(url, {
                    signal: controller.signal,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                clearTimeout(timeoutId);

                // Verificar si la respuesta es JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Respuesta del servidor no es JSON');
                }

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || `Error ${response.status}`);
                }

                // En la función calculateBtn.addEventListener('click', ...)
                if (data.success) {
                    const lote = data.lote;
                    console.log('Datos recibidos del backend:', lote); // Para depuración
                    
                    // Mostrar detalles del lote - CORREGIDO
                    document.getElementById('lotId').textContent = lote.id || 'N/A';
                    
                    // Mostrar estatus con badge
                    const status = lote.estatus || 'no disponible';
                    const statusBadge = document.getElementById('statusBadge');
                    statusBadge.textContent = formatStatus(status);
                    statusBadge.className = 'status-badge ' + getStatusClass(status);
                    
                    document.getElementById('lotBlock').textContent = lote.manzana || 'N/A';
                    document.getElementById('lotArea').textContent = `${lote.area_total ? lote.area_total.toLocaleString('es-MX') : '0'} m²`;
                    
                    // Mostrar medidas - CORREGIDO (usar lote.medidas)
                    if (lote.medidas) {
                        document.getElementById('lotNorth').textContent = `${lote.medidas.norte || '0'} m`;
                        document.getElementById('lotSouth').textContent = `${lote.medidas.sur || '0'} m`;
                        document.getElementById('lotEast').textContent = `${lote.medidas.oriente || '0'} m`;
                        document.getElementById('lotWest').textContent = `${lote.medidas.poniente || '0'} m`;
                    } else {
                        document.getElementById('lotNorth').textContent = 'No disponible';
                        document.getElementById('lotSouth').textContent = 'No disponible';
                        document.getElementById('lotEast').textContent = 'No disponible';
                        document.getElementById('lotWest').textContent = 'No disponible';
                    }

                    // Calcular costo total - USAR EL COSTO QUE YA CALCULÓ EL BACKEND
                    const totalCost = lote.costo_total || 0;
                    document.getElementById('totalCost').textContent = `$${totalCost.toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})} MXN`;
                    
                    // Mostrar sección de detalles
                    lotDetails.style.display = 'block';
                } else {
                    throw new Error(data.message || 'Lote no encontrado');
                }

            } catch (error) {
                console.error('Error en cálculo:', error);
                
                if (error.name === 'AbortError') {
                    lotError.textContent = 'La solicitud tardó demasiado tiempo. Intente nuevamente.';
                } else if (error.message.includes('JSON')) {
                    lotError.textContent = 'Error en la respuesta del servidor.';
                } else {
                    lotError.textContent = error.message || 'Error al calcular el costo del lote.';
                }
                
                lotError.style.display = 'block';
                lotDetails.style.display = 'none';
            } finally {
                // Restaurar botón
                calculateBtn.innerHTML = '<i class="fas fa-calculator"></i> Calcular';
                calculateBtn.disabled = false;
            }
        });

        // Resto del código JavaScript para el envío de formularios...
        // (mantener el código existente para el envío de formularios)
    </script>
@endsection