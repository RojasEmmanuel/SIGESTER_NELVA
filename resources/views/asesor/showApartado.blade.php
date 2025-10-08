@extends('asesor.navbar')

@section('title', 'Nelva Bienes Raíces - Detalles del Apartado')

@push('styles')
<link href="{{ asset('css/showApartado.css') }}" rel="stylesheet">
<style>
    /* Estilos modernos para la sección de depósito */
    .deposit-status-card {
         background: linear-gradient(135deg, 
        #2563eb 0%, 
        #4facfe  50%, 
        #2563eb 100%);
        border-radius: 16px;
        padding: 25px;
        color: white;
        margin-bottom: 25px;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
    }

    .deposit-status-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.1);
        transform: rotate(30deg);
    }

    .status-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
        position: relative;
        z-index: 2;
    }

    .status-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .status-badge-modern {
        padding: 8px 16px;
        border-radius: 25px;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .badge-solicitud {
        background: rgba(255, 193, 7, 0.2);
        color: #FFC107;
    }

    .badge-aprobado {
        background: rgba(40, 167, 69, 0.2);
        color: #28a745;
    }

    .badge-rechazado {
        background: rgba(220, 53, 69, 0.2);
        color: #dc3545;
    }

    .badge-pendiente {
        background: rgba(255, 193, 7, 0.2);
        color: #FFC107;
    }

    .status-content {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        position: relative;
        z-index: 2;
    }

    .status-item {
        background: rgba(255, 255, 255, 0.1);
        padding: 15px;
        border-radius: 12px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .status-label {
        font-size: 0.875rem;
        opacity: 0.8;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .status-value {
        font-size: 1rem;
        font-weight: 600;
    }

    .file-link-modern {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: white;
        text-decoration: none;
        padding: 8px 16px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .file-link-modern:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
        color: white;
        text-decoration: none;
    }

    .observations-box {
        background: rgba(255, 255, 255, 0.1);
        padding: 15px;
        border-radius: 12px;
        margin-top: 15px;
        backdrop-filter: blur(10px);
        border-left: 4px solid #FFC107;
    }

    .observations-label {
        font-size: 0.875rem;
        opacity: 0.8;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .observations-text {
        margin: 0;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    /* Estilos para el formulario de subida moderno */
    .upload-section-modern {
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 16px;
        padding: 30px;
        text-align: center;
        transition: all 0.3s ease;
        margin-top: 20px;
    }

    .upload-section-modern:hover {
        border-color: #667eea;
        background: #f0f2ff;
    }

    .upload-section-modern.drag-over {
        border-color: #667eea;
        background: #e8ecff;
        transform: scale(1.02);
    }

    .upload-icon-modern {
        font-size: 3rem;
        color: #667eea;
        margin-bottom: 15px;
    }

    .upload-text-modern {
        color: #6c757d;
        margin-bottom: 20px;
        font-size: 1.1rem;
    }

    .btn-upload-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-upload-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        color: white;
    }

    /* Estilos para el botón Vender */
    .btn-sell-container {
        margin-top: 25px;
        padding-top: 25px;
        border-top: 2px solid #e9ecef;
        text-align: center;
    }

    .btn-sell {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border: none;
        padding: 14px 32px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    .btn-sell:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
        color: white;
    }

    .btn-sell:active {
        transform: translateY(-1px);
    }

    .btn-sell i {
        font-size: 1.2rem;
    }

    .btn-sell-disabled {
        background: linear-gradient(135deg, #6c757d 0%, #adb5bd 100%);
        cursor: not-allowed;
        opacity: 0.6;
    }

    .btn-sell-disabled:hover {
        transform: none;
        box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .status-header {
            flex-direction: column;
            gap: 15px;
        }
        
        .status-content {
            grid-template-columns: 1fr;
        }
        
        .deposit-status-card {
            padding: 20px;
        }
        
        .btn-sell {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<body>
    <!-- Cronómetro fijo -->
    <div id="fixed-time-counter" class="fixed-time-counter fixed-time-normal">
        <i class="fas fa-clock"></i>
        <span id="fixed-time-display">Tiempo restante: 3 días</span>
    </div>

    <div class="container">
        <!-- Encabezado de página -->
        <div class="page-header">
            <a href="#" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Volver a la lista de apartados
            </a>
            <h1 class="page-title">
                <i class="fas fa-file-contract"></i>
                Detalles del Apartado
            </h1>
            <p class="page-subtitle">Información completa del apartado y sus detalles</p>
        </div>

        <!-- Tarjeta principal del apartado -->
        <div class="reservation-card">
            <!-- Encabezado del apartado -->
            <div class="reservation-header">
                <h2 class="reservation-title">Apartado #<span id="reservation-id">{{ $apartado->id_apartado }}</span></h2>
                <div id="reservation-badge" class="reservation-badge">
                    @if($apartado->tipoApartado === 'deposito')
                        <i class="fas fa-money-bill-wave"></i> Apartado con Depósito
                    @elseif($apartado->tipoApartado === 'palabra')
                        <i class="fas fa-comment"></i> Apartado de Palabra
                    @else
                        <i class="fas fa-question-circle"></i> Tipo Desconocido
                    @endif
                </div>
            </div>

            <!-- CRONÓMETRO AÑADIDO AQUÍ -->
            <div id="time-counter" class="time-counter time-normal">
                <i class="fas fa-clock"></i>
                <span id="time-display">Tiempo restante: 3 días</span>
            </div>

            <!-- Detalles del apartado -->
            <div class="detail-grid">
                <div class="detail-card">
                    <div class="detail-label">
                        <i class="fas fa-user"></i>
                        Cliente
                    </div>
                    <div class="detail-value" id="client-name">
                        {{ $apartado->cliente_nombre }} {{ $apartado->cliente_apellidos }}
                    </div>
                </div>
                
                <div class="detail-card">
                    <div class="detail-label">
                        <i class="fas fa-calendar-alt"></i>
                        Fecha de apartado
                    </div>
                    <div class="detail-value" id="reservation-date">
                        {{ \Carbon\Carbon::parse($apartado->fechaApartado)->format('d/m/Y') }}
                    </div>
                </div>
                
                <div class="detail-card">
                    <div class="detail-label">
                        <i class="fas fa-user-tie"></i>
                        Vendedor
                    </div>
                    <div class="detail-value" id="salesperson">
                        {{ $apartado->usuario->nombre ?? 'N/A' }}
                    </div>
                </div>
                
                <div class="detail-card">
                    <div class="detail-label">
                        <i class="fas fa-hourglass-end"></i>
                        Fecha de vencimiento
                    </div>
                    <div class="detail-value" id="expiration-date">
                        {{ \Carbon\Carbon::parse($apartado->fechaVencimiento)->format('d/m/Y') }}
                    </div>
                </div>

                <div class="detail-card">
                    <div class="detail-label">
                        <i class="fas fa-money-bill-wave"></i>
                        Tipo de Apartado
                    </div>
                    <div class="detail-value" id="reservation-type">
                        @if($apartado->tipoApartado === 'deposito')
                            Apartado con Depósito
                        @elseif($apartado->tipoApartado === 'palabra')
                            Apartado de Palabra
                        @else
                            Tipo no especificado
                        @endif
                    </div>
                </div>

                <!-- Mostrar cantidad solo para depósito -->
                @if($apartado->tipoApartado === 'deposito' && $apartadoDeposito)
                <div class="detail-card">
                    <div class="detail-label">
                        <i class="fas fa-dollar-sign"></i>
                        Cantidad Depositada
                    </div>
                    <div class="detail-value" id="deposit-amount">
                        ${{ number_format($apartadoDeposito->cantidad, 2) }}
                    </div>
                </div>
                @endif
            </div>

            <!-- Sección de lotes -->
            <div class="lot-section">
                <h3 class="section-title">
                    <i class="fas fa-map-marked-alt"></i>
                    Lote Apartado
                </h3>
                
                @if($apartado->lotesApartados && count($apartado->lotesApartados) > 0)
                    @foreach($apartado->lotesApartados as $loteApartado)
                    <div class="lot-card">
                        <div class="lot-header">
                            <h4 class="lot-title" id="lot-name">{{ $loteApartado->lote->id_lote ?? 'N/A' }}</h4>
                            <div class="lot-status status-apartado">Apartado</div>
                        </div>
                        
                        <div class="lot-details">
                            <div>
                                <div class="detail-label">
                                    <i class="fas fa-hashtag"></i>
                                    Número de lote
                                </div>
                                <div class="detail-value" id="lot-number">{{ $loteApartado->lote->numeroLote ?? 'N/A' }}</div>
                            </div>
                            
                            <div>
                                <div class="detail-label">
                                    <i class="fas fa-building"></i>
                                    Fraccionamiento
                                </div>
                                <div class="detail-value" id="subdivision">{{ $loteApartado->lote->fraccionamiento->nombre ?? 'N/A' }}</div>
                            </div>
                            
                            <div>
                                <div class="detail-label">
                                    <i class="fas fa-map-marker-alt"></i>
                                    Ubicación
                                </div>
                                <div class="detail-value" id="location">{{ $loteApartado->lote->fraccionamiento->ubicacion ?? 'Ubicación no disponible' }}</div>
                            </div>
                            
                            <div>
                                <div class="detail-label">
                                    <i class="fas fa-info-circle"></i>
                                    Estado
                                </div>
                                <div class="detail-value" id="lot-status">Apartado</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="no-lots">
                        <p>No se encontraron lotes para este apartado.</p>
                    </div>
                @endif
            </div>

            <!-- Sección de depósito (SOLO para apartados con depósito) -->
            @if($apartado->tipoApartado === 'deposito')
            <div id="deposit-section" class="deposit-section">
                <h3 class="section-title">
                    <i class="fas fa-file-invoice-dollar"></i>
                    Estado del Depósito
                </h3>
                
                <!-- Tarjeta moderna de estado del depósito -->
                <div class="deposit-status-card">
                    <div class="status-header">
                        <h4 class="status-title">
                            <i class="fas fa-receipt"></i>
                            Comprobante de Depósito
                        </h4>
                        @if($apartadoDeposito)
                        <span class="status-badge-modern badge-{{ $apartadoDeposito->ticket_estatus ?? 'pendiente' }}">
                            {{ ucfirst($apartadoDeposito->ticket_estatus ?? 'pendiente') }}
                        </span>
                        @else
                        <span class="status-badge-modern badge-pendiente">
                            Pendiente
                        </span>
                        @endif
                    </div>

                    <div class="status-content">
                        @if($apartadoDeposito && $apartadoDeposito->path_ticket)
                        <div class="status-item">
                            <div class="status-label">
                                <i class="fas fa-file-pdf"></i>
                                Archivo actual
                            </div>
                            <div class="status-value">
                                <a href="{{ asset('storage/' . $apartadoDeposito->path_ticket) }}" target="_blank" class="file-link-modern">
                                    <i class="fas fa-external-link-alt"></i>
                                    Ver comprobante
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($apartadoDeposito && $apartadoDeposito->cantidad)
                        <div class="status-item">
                            <div class="status-label">
                                <i class="fas fa-money-bill-wave"></i>
                                Monto depositado
                            </div>
                            <div class="status-value">
                                ${{ number_format($apartadoDeposito->cantidad, 2) }}
                            </div>
                        </div>
                        @endif

                        <div class="status-item">
                            <div class="status-label">
                                <i class="fas fa-user-clock"></i>
                                Cliente
                            </div>
                            <div class="status-value">
                                {{ $apartado->cliente_nombre }} {{ $apartado->cliente_apellidos }}
                            </div>
                        </div>
                    </div>

                    @if($apartadoDeposito && $apartadoDeposito->observaciones)
                    <div class="observations-box">
                        <div class="observations-label">
                            <i class="fas fa-comment-alt"></i>
                            Observaciones
                        </div>
                        <p class="observations-text">{{ $apartadoDeposito->observaciones }}</p>
                    </div>
                    @elseif(!$apartadoDeposito || !$apartadoDeposito->path_ticket)
                    <div class="observations-box">
                        <div class="observations-label">
                            <i class="fas fa-info-circle"></i>
                            Información
                        </div>
                        <p class="observations-text">Depósito pendiente de cliente {{ $apartado->cliente_nombre }} {{ $apartado->cliente_apellidos }}</p>
                    </div>
                    @endif
                </div>

                <!-- Formulario de subida moderno -->
                @if(!$apartadoDeposito || !$apartadoDeposito->path_ticket)
                <form id="deposit-form" action="{{ route('asesor.apartados.upload-ticket', $apartado->id_apartado) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    
                    <div class="upload-section-modern" id="upload-area">
                        <div class="upload-icon-modern">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <p class="upload-text-modern">Arrastra tu comprobante aquí o haz clic para seleccionar</p>
                        <button type="button" class="btn-upload-modern" id="upload-btn">
                            <i class="fas fa-upload"></i>
                            Seleccionar archivo
                        </button>
                        <input type="file" id="file-input" name="ticket_file" class="file-input" accept=".pdf,.jpg,.jpeg,.png" required style="display: none;">
                    </div>
                    
                    <div id="file-preview" class="file-preview" style="display: none;">
                        <img id="preview-image" src="" alt="Vista previa del comprobante">
                        <a href="#" id="file-link" target="_blank">Ver comprobante</a>
                        <div class="file-actions">
                            <button type="button" class="btn-change-file" id="change-file-btn">
                                <i class="fas fa-exchange-alt"></i>
                                Cambiar archivo
                            </button>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-save" id="save-btn" style="display: none;">
                        <i class="fas fa-save"></i>
                        Guardar comprobante
                    </button>
                    
                    <!-- Mensajes de éxito/error -->
                    <div id="upload-message" style="display: none;"></div>
                </form>
                @endif
            </div>
            @endif

            <!-- BOTÓN VENDER - Solo aparece si el apartado está activo -->
            @php
                $fechaVencimiento = \Carbon\Carbon::parse($apartado->fechaVencimiento);
                $hoy = \Carbon\Carbon::now();
                $estaActivo = $hoy->lte($fechaVencimiento);
            @endphp

            @if($estaActivo)
            <div class="btn-sell-container">
                <button type="button" class="btn-sell" id="btn-vender" data-apartado-id="{{ $apartado->id_apartado }}">
                    <i class="fas fa-handshake"></i>
                    Proceder con la Venta
                </button>
            </div>
            @else
            <div class="btn-sell-container">
                <button type="button" class="btn-sell btn-sell-disabled" disabled title="El apartado ha vencido">
                    <i class="fas fa-clock"></i>
                    Apartado Vencido
                </button>
            </div>
            @endif
        </div>
    </div>

    <script>
        // Datos del apartado para JavaScript
        const apartadoData = {
            id_apartado: {{ $apartado->id_apartado }},
            tipoApartado: "{{ $apartado->tipoApartado }}",
            cliente_nombre: "{{ $apartado->cliente_nombre }}",
            cliente_apellidos: "{{ $apartado->cliente_apellidos }}",
            fechaApartado: "{{ $apartado->fechaApartado }}",
            fechaVencimiento: "{{ $apartado->fechaVencimiento }}",
            usuario: {
                nombre: "{{ $apartado->usuario->nombre ?? '' }}"
            },
            lotes_apartados: [
                @if($apartado->lotesApartados && count($apartado->lotesApartados) > 0)
                    @foreach($apartado->lotesApartados as $loteApartado)
                    {
                        lote: {
                            id_lote: "{{ $loteApartado->lote->id_lote ?? '' }}",
                            numeroLote: "{{ $loteApartado->lote->numeroLote ?? '' }}",
                            fraccionamiento: {
                                nombre: "{{ $loteApartado->lote->fraccionamiento->nombre ?? '' }}",
                                ubicacion: "{{ $loteApartado->lote->fraccionamiento->ubicacion ?? '' }}"
                            }
                        }
                    }@if(!$loop->last),@endif
                    @endforeach
                @endif
            ]
        };

        // Datos del depósito si existe
        const apartadoDepositoData = @json($apartadoDeposito);

        // Funcionalidad para subir archivos (solo para apartados con depósito)
        function setupFileUpload() {
            if (apartadoData.tipoApartado !== 'deposito') return;

            const fileInput = document.getElementById('file-input');
            const uploadBtn = document.getElementById('upload-btn');
            const uploadArea = document.getElementById('upload-area');
            const uploadSection = document.querySelector('.upload-section-modern');

            if (!fileInput || !uploadBtn || !uploadArea) return;

            // Abrir selector de archivos
            uploadBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                fileInput.click();
            });

            uploadArea.addEventListener('click', (e) => {
                if (e.target === uploadArea || e.target.classList.contains('upload-text-modern')) {
                    fileInput.click();
                }
            });

            // Drag and drop
            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadSection.classList.add('drag-over');
            });

            uploadArea.addEventListener('dragleave', () => {
                uploadSection.classList.remove('drag-over');
            });

            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadSection.classList.remove('drag-over');
                if (e.dataTransfer.files.length) {
                    fileInput.files = e.dataTransfer.files;
                    handleFileSelection();
                }
            });

            fileInput.addEventListener('change', handleFileSelection);

            function handleFileSelection() {
                if (fileInput.files && fileInput.files[0]) {
                    const file = fileInput.files[0];
                    // Aquí puedes agregar validaciones y mostrar vista previa
                    console.log('Archivo seleccionado:', file.name);
                    
                    // Mostrar botón de guardar
                    const saveBtn = document.getElementById('save-btn');
                    if (saveBtn) saveBtn.style.display = 'block';
                }
            }
        }

        // Funcionalidad del botón Vender
        function setupSellButton() {
            const btnVender = document.getElementById('btn-vender');
            
            if (btnVender) {
                btnVender.addEventListener('click', function() {
                    const apartadoId = this.getAttribute('data-apartado-id');
                    
                    // Confirmar antes de proceder con la venta
                    if (confirm('¿Estás seguro de que deseas proceder con la venta de este apartado? Esta acción convertirá el apartado en una venta formal.')) {
                        
                    }
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM cargado, inicializando...');
            
            // Configurar file upload solo si es apartado con depósito
            if (apartadoData.tipoApartado === 'deposito') {
                setupFileUpload();
            }
            
            // Configurar botón vender
            setupSellButton();
            
            console.log('Inicialización completada');
        });
    </script>
</body>
</html>
@endsection