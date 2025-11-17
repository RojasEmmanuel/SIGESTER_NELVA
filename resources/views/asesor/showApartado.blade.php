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
@endphp

@extends($navbar)

@section('title', 'Nelva Bienes Raíces - Apartado Detalles')

@push('styles')
<link href="{{ asset('css/showApartado.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container">
    <!-- Encabezado de página -->
    <div class="page-header" style="display:flex; justify-content: space-between; margin-bottom: 10px">
        
        
        <div class="headerLeft">
            <h1 class="page-title">
                Detalles del Apartado
            </h1>
            <p class="page-subtitle">Información completa del apartado y sus detalles</p>
        </div>
        <a href="{{ route('asesor.apartados.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver al Listado
        </a>
    </div>

    <!-- Cálculo del tiempo restante -->
    @php
        $errorMessage = 'Ninguno';
        try {
            $hoy = \Carbon\Carbon::now('America/Mexico_City');
            $vencimiento = !empty($apartado->fechaVencimiento) ? \Carbon\Carbon::parse($apartado->fechaVencimiento) : null;
            $fechaApartado = !empty($apartado->fechaApartado) ? \Carbon\Carbon::parse($apartado->fechaApartado) : null;

            if (!$vencimiento || !$fechaApartado) {
                throw new \Exception('Fecha de apartado o vencimiento inválida');
            }

            $estaVencido = $vencimiento->lt($hoy);
            $estadoClass = $estaVencido ? 'vencido' : 'activo';

            if ($estaVencido) {
                $tiempoRestante = 'Vencido';
                $tiempoClass = 'time-danger';
                $diasRestantes = 0;
                $horasRestantes = 0;
                $minutosRestantes = 0;
            } else {
                $diferencia = $vencimiento->diff($hoy);
                $diasRestantes = $diferencia->days;
                $horasRestantes = $diferencia->h;
                $minutosRestantes = $diferencia->i;

                $partes = [];
                if ($diasRestantes > 0) {
                    $partes[] = $diasRestantes . 'día(s)';
                }
                if ($horasRestantes > 0 || $diasRestantes > 0) {
                    $partes[] = $horasRestantes . 'hora(s)';
                }
                $partes[] = $minutosRestantes . 'minuto(s)';

                $tiempoRestante = implode(' ', $partes);
                if (empty($tiempoRestante)) {
                    $tiempoRestante = 'Menos de 1m';
                }

                $totalHorasRestantes = ($diasRestantes * 24) + $horasRestantes + ($minutosRestantes / 60);
                if ($totalHorasRestantes <= 12) {
                    $tiempoClass = 'time-danger';
                } elseif ($totalHorasRestantes <= 48) {
                    $tiempoClass = 'time-warning';
                } else {
                    $tiempoClass = 'time-normal';
                }
            }

            $fechaApartadoFormatted = $fechaApartado ? $fechaApartado->format('d/m/Y H:i:s') : 'N/A';
            $fechaVencimientoFormatted = $vencimiento ? $vencimiento->format('d/m/Y H:i:s') : 'N/A';
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $tiempoRestante = 'Error en fecha';
            $tiempoClass = 'time-danger';
            $estadoClass = 'error';
            $fechaApartadoFormatted = 'N/A';
            $fechaVencimientoFormatted = 'N/A';
        }
    @endphp

    <!-- Tarjeta principal del apartado -->
    <div class="reservation-card">
        <!-- Cronómetro dinámico -->
        <div id="time-counter" class="time-counter {{ $tiempoClass }}">
            <i class="fas fa-clock"></i>
            <span id="time-display">Tiempo restante: {{ $tiempoRestante }}</span>
        </div>

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
                    {{ $fechaApartadoFormatted }}
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
                    {{ $fechaVencimientoFormatted }}
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

            @if($apartado->tipoApartado === 'deposito' && isset($apartadoDeposito) && $apartadoDeposito)
            <div class="detail-card">
                <div class="detail-label">
                    <i class="fas fa-dollar-sign"></i>
                    Cantidad Depositada
                </div>
                <div class="detail-value" id="deposit-amount">
                    ${{ number_format($apartadoDeposito->cantidad ?? 0, 2) }}
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

        <!-- Sección de depósito -->
        @if($apartado->tipoApartado === 'deposito')
        <div id="deposit-section" class="deposit-section">
            <h3 class="section-title">
                <i class="fas fa-file-invoice-dollar"></i>
                Estado del Depósito
            </h3>
            
            <div class="deposit-status-card">
                <!-- Mostrar estado del depósito si existe -->
                @if(isset($apartadoDeposito) && $apartadoDeposito && $apartadoDeposito->path_ticket)
                    <div class="status-header">
                        <h4 class="status-title">
                            <i class="fas fa-file-invoice-dollar"></i>
                            Comprobante de Depósito
                        </h4>
                        <div class="status-badge 
                            @if($apartadoDeposito->ticket_estatus === 'aceptado') badge-aprobado
                            @elseif($apartadoDeposito->ticket_estatus === 'rechazado') badge-rechazado
                            @else badge-pendiente @endif">
                            @if($apartadoDeposito->ticket_estatus === 'aceptado')
                                <i class="fas fa-check-circle"></i>
                                Aceptado
                            @elseif($apartadoDeposito->ticket_estatus === 'rechazado')
                                <i class="fas fa-times-circle"></i>
                                Rechazado
                            @else
                                <i class="fas fa-clock"></i>
                                En Revisión
                            @endif
                        </div>
                    </div>
                    
                    <div class="status-content">
                        <div class="status-item">
                            <div class="status-label">
                                <i class="fas fa-info-circle"></i>
                                Estado del comprobante
                            </div>
                            <div class="status-value">
                                <span class="status-badge 
                                    @if($apartadoDeposito->ticket_estatus === 'aceptado') badge-aprobado
                                    @elseif($apartadoDeposito->ticket_estatus === 'rechazado') badge-rechazado
                                    @else badge-pendiente @endif">
                                    @if($apartadoDeposito->ticket_estatus === 'aceptado')
                                        <i class="fas fa-check"></i> Aceptado
                                    @elseif($apartadoDeposito->ticket_estatus === 'rechazado')
                                        <i class="fas fa-times"></i> Rechazado
                                    @else
                                        <i class="fas fa-clock"></i> En Revisión
                                    @endif
                                </span>
                            </div>
                        </div>
                        
                        <div class="status-item">
                            <div class="status-label">
                                <i class="fas fa-download"></i>
                                Acciones
                            </div>
                            <div class="status-value">
                                <a href="{{ Storage::url($apartadoDeposito->path_ticket) }}" target="_blank" class="file-link">
                                    <i class="fas fa-eye"></i>
                                    Ver Comprobante
                                </a>
                                <a href="{{ Storage::url($apartadoDeposito->path_ticket) }}" download class="file-link" style="margin-left: 0.5rem;">
                                    <i class="fas fa-download"></i>
                                    Descargar
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Mostrar mensaje específico según el estado -->
                    @if($apartadoDeposito->ticket_estatus === 'aceptado')
                    <div class="observations-box" style="border-left-color: var(--success-color);">
                        <div class="observations-label" style="color: var(--success-color);">
                            <i class="fas fa-check-circle"></i>
                            Comprobante Aceptado
                        </div>
                        <div class="observations-text">
                            El comprobante de depósito ha sido verificado y aceptado. El apartado está listo para proceder con la venta.
                        </div>
                    </div>
                    @elseif($apartadoDeposito->ticket_estatus === 'rechazado')
                    <div class="observations-box" style="border-left-color: var(--danger-color);">
                        <div class="observations-label" style="color: var(--danger-color);">
                            <i class="fas fa-exclamation-triangle"></i>
                            Comprobante Rechazado
                        </div>
                        <div class="observations-text">
                            {{ $apartadoDeposito->observaciones ?? 'El comprobante de depósito ha sido rechazado. Por favor, suba un nuevo comprobante.' }}
                        </div>
                    </div>
                    @else
                    <div class="observations-box" style="border-left-color: var(--warning-color);">
                        <div class="observations-label" style="color: var(--warning-color);">
                            <i class="fas fa-clock"></i>
                            En Proceso de Revisión
                        </div>
                        <div class="observations-text">
                            El comprobante de depósito está en proceso de revisión. Será verificado por el administrador en breve.
                        </div>
                    </div>
                    @endif

                @else
                    <div class="status-header">
                        <h4 class="status-title">
                            <i class="fas fa-clock"></i>
                            Comprobante de Depósito
                        </h4>
                        <div class="status-badge badge-pendiente">
                            <i class="fas fa-exclamation-circle"></i>
                            Pendiente de Subir
                        </div>
                    </div>
                    
                    <div class="status-content">
                        <div class="status-item">
                            <div class="status-label">
                                <i class="fas fa-info-circle"></i>
                                Estado actual
                            </div>
                            <div class="status-value">
                                Esperando comprobante de depósito
                            </div>
                        </div>
                    </div>

                    <div class="observations-box">
                        <div class="observations-label">
                            <i class="fas fa-info-circle"></i>
                            Información importante
                        </div>
                        <div class="observations-text">
                            Para completar el proceso de apartado, es necesario subir el comprobante de depósito. 
                            Asegúrese de que la imagen o PDF sea legible y muestre claramente los datos de la transacción.
                        </div>
                    </div>
                @endif
            </div>

            <!-- Mostrar formulario si no hay comprobante subido o si el ticket está rechazado -->
            @if($apartado->tipoApartado === 'deposito' && (!isset($apartadoDeposito) || !$apartadoDeposito || !$apartadoDeposito->path_ticket || $apartadoDeposito->ticket_estatus === 'rechazado'))
            <form id="deposit-form" action="{{ route('asesor.apartados.upload-ticket', $apartado->id_apartado) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                
                <input type="hidden" name="ticket_estatus" value="solicitud">
                
                <div class="upload-section" id="upload-area">
                    <div class="file-input-container">
                        <input type="file" id="file-input" name="ticket_file" class="file-input" accept=".pdf,.jpg,.jpeg,.png" required>
                        
                        <div class="upload-content">
                            <div class="upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <p class="upload-text">Arrastra tu comprobante aquí o haz clic para seleccionar</p>
                            <p class="upload-instructions">Formatos aceptados: PDF, JPG, PNG (Máx. 5MB)</p>
                            
                            <div class="upload-formats">
                                <span class="format-badge">PDF</span>
                                <span class="format-badge">JPG</span>
                                <span class="format-badge">PNG</span>
                            </div>
                            
                            <button type="button" class="btn-upload" id="upload-btn">
                                <i class="fas fa-upload"></i>
                                Seleccionar archivo
                            </button>
                        </div>
                    </div>
                </div>
                
                
                
                <div id="file-preview" class="file-preview" style="display: none;">
                    <div id="preview-content"></div>
                    <div class="file-actions">
                        <button type="button" class="btn-change-file" id="change-file-btn">
                            <i class="fas fa-exchange-alt"></i>
                            Cambiar archivo
                        </button>
                        <button type="submit" class="btn-save" id="save-btn">
                            <i class="fas fa-save"></i>
                            Guardar comprobante
                        </button>
                    </div>
                </div>
                
                <div id="upload-message" style="display: none;"></div>
            </form>
            @endif
        </div>
        @endif

        <!-- Botón Vender -->
        <div class="btn-sell-container">
            <button type="button" class="btn-sell {{ $estaVencido ? 'btn-sell-disabled' : '' }}" id="btn-vender" data-apartado-id="{{ $apartado->id_apartado }}" {{ $estaVencido ? 'disabled' : '' }} title="{{ $estaVencido ? 'El apartado ha vencido' : 'Proceder con la venta' }}">
                <i class="fas fa-handshake"></i>
                {{ $estaVencido ? 'Apartado Vencido' : 'Proceder con la Venta' }}
            </button>
        </div>
    </div>
</div>

<script>
    // Definir apartadoData para evitar errores
    const apartadoData = {
        tipoApartado: '{{ $apartado->tipoApartado ?? "palabra" }}'
    };

    // Configurar subida de archivos
    function setupFileUpload() {
        console.log('Iniciando configuración de subida de archivos...');

        const fileInput = document.getElementById('file-input');
        const uploadBtn = document.getElementById('upload-btn');
        const uploadArea = document.getElementById('upload-area');
        const filePreview = document.getElementById('file-preview');
        const previewContent = document.getElementById('preview-content');
        const changeFileBtn = document.getElementById('change-file-btn');

        // Verificar que todos los elementos existan
        if (!fileInput || !uploadBtn || !uploadArea || !filePreview || !previewContent || !changeFileBtn) {
            console.error('Faltan elementos del DOM:', {
                fileInput: !!fileInput,
                uploadBtn: !!uploadBtn,
                uploadArea: !!uploadArea,
                filePreview: !!filePreview,
                previewContent: !!previewContent,
                changeFileBtn: !!changeFileBtn
            });
            return;
        }

        // Configurar el botón para abrir el selector de archivos
        uploadBtn.addEventListener('click', () => {
            console.log('Clic en botón de subida, abriendo selector de archivos...');
            fileInput.click();
        });

        // Configurar el área de drag and drop
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            console.log('Archivo arrastrado sobre el área');
            uploadArea.classList.add('drag-over');
        });

        uploadArea.addEventListener('dragleave', (e) => {
            e.preventDefault();
            console.log('Archivo salió del área de arrastre');
            uploadArea.classList.remove('drag-over');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            console.log('Archivo soltado en el área');
            uploadArea.classList.remove('drag-over');
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                console.log('Archivo soltado:', e.dataTransfer.files[0].name);
                handleFileSelection(e.dataTransfer.files[0]);
            } else {
                console.warn('No se soltaron archivos válidos');
            }
        });

        // Configurar el evento change del input
        fileInput.addEventListener('change', (e) => {
            console.log('Evento change disparado en fileInput');
            if (e.target.files && e.target.files[0]) {
                console.log('Archivo seleccionado:', e.target.files[0].name, e.target.files[0].type, e.target.files[0].size);
                handleFileSelection(e.target.files[0]);
            } else {
                console.warn('No se seleccionó ningún archivo');
                showMessage('Por favor selecciona un archivo.', 'error');
            }
        });

        // Configurar el botón para cambiar archivo
        changeFileBtn.addEventListener('click', () => {
            console.log('Clic en cambiar archivo, reseteando input...');
            resetFileInput();
        });

        // Función para manejar la selección de archivo
        function handleFileSelection(file) {
            console.log('Procesando archivo:', {
                name: file.name,
                type: file.type,
                size: file.size
            });

            // Validar tipo de archivo
            const validTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
            const fileExtension = file.name.split('.').pop().toLowerCase();
            const validExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
            if (!validTypes.includes(file.type) && !validExtensions.includes(fileExtension)) {
                console.error('Tipo de archivo no válido:', file.type, fileExtension);
                showMessage('Tipo de archivo no válido. Solo se permiten PDF, JPG y PNG.', 'error');
                resetFileInput();
                return;
            }

            // Validar tamaño (5MB máximo)
            const maxSize = 5 * 1024 * 1024; // 5MB en bytes
            if (file.size > maxSize) {
                console.error('Archivo demasiado grande:', file.size);
                showMessage('El archivo es demasiado grande. Máximo 5MB.', 'error');
                resetFileInput();
                return;
            }

            // Mostrar previsualización y cambiar visibilidad
            console.log('Archivo válido, mostrando previsualización...');
            showFilePreview(file);
            uploadArea.style.display = 'none';
            filePreview.style.display = 'block';
        }

        // Función para mostrar la previsualización
        function showFilePreview(file) {
            console.log('Iniciando previsualización para:', file.name);
            if (!previewContent) {
                console.error('Elemento preview-content no encontrado');
                showMessage('Error interno: No se puede mostrar la previsualización.', 'error');
                return;
            }

            const fileType = file.type;
            const fileName = file.name;
            const fileSize = (file.size / (1024 * 1024)).toFixed(2); // MB
            const fileExtension = fileName.split('.').pop().toLowerCase();

            let previewHTML = '';

            if (fileType === 'application/pdf' || fileExtension === 'pdf') {
                console.log('Generando previsualización para PDF');
                previewHTML = `
                    <div class="file-preview-item">
                        <div class="file-icon">
                            <i class="fas fa-file-pdf fa-3x" style="color: #dc3545;"></i>
                        </div>
                        <div class="file-info">
                            <h4 class="file-name">${fileName}</h4>
                            <p class="file-size">${fileSize} MB</p>
                            <p class="file-type">Documento PDF</p>
                            <p class="file-status">Archivo listo para guardar</p>
                        </div>
                    </div>
                `;
                previewContent.innerHTML = previewHTML;
            } else if (fileType.startsWith('image/') || ['jpg', 'jpeg', 'png'].includes(fileExtension)) {
                console.log('Generando previsualización inicial para imagen');
                previewHTML = `
                    <div class="file-preview-item">
                        <div class="file-icon">
                            <i class="fas fa-file-image fa-3x" style="color: #28a745;"></i>
                        </div>
                        <div class="file-info">
                            <h4 class="file-name">${fileName}</h4>
                            <p class="file-size">${fileSize} MB</p>
                            <p class="file-type">Imagen ${fileExtension.toUpperCase()}</p>
                            <p class="file-status">Cargando vista previa...</p>
                        </div>
                    </div>
                `;
                previewContent.innerHTML = previewHTML;

                console.log('Iniciando FileReader para imagen');
                const reader = new FileReader();
                reader.onload = (e) => {
                    console.log('FileReader cargó la imagen correctamente');
                    previewHTML = `
                        <div class="file-preview-item">
                            <div class="file-icon">
                                <img src="${e.target.result}" alt="Vista previa" class="image-preview" style="max-width: 150px; max-height: 150px; border-radius: 4px;">
                            </div>
                            <div class="file-info">
                                <h4 class="file-name">${fileName}</h4>
                                <p class="file-size">${fileSize} MB</p>
                                <p class="file-type">Imagen ${fileExtension.toUpperCase()}</p>
                                <p class="file-status">Vista previa cargada</p>
                            </div>
                        </div>
                    `;
                    previewContent.innerHTML = previewHTML;
                };

                reader.onerror = (error) => {
                    console.error('Error en FileReader:', error);
                    previewHTML = `
                        <div class="file-preview-item">
                            <div class="file-icon">
                                <i class="fas fa-file-image fa-3x" style="color: #ffc107;"></i>
                            </div>
                            <div class="file-info">
                                <h4 class="file-name">${fileName}</h4>
                                <p class="file-size">${fileSize} MB</p>
                                <p class="file-type">Imagen ${fileExtension.toUpperCase()}</p>
                                <p class="file-status" style="color: #dc3545;">Error al cargar vista previa</p>
                            </div>
                        </div>
                    `;
                    previewContent.innerHTML = previewHTML;
                    showMessage('Error al cargar la vista previa de la imagen.', 'error');
                };

                reader.readAsDataURL(file);
            } else {
                console.error('Tipo de archivo no soportado:', fileType, fileExtension);
                showMessage('Tipo de archivo no soportado.', 'error');
            }
        }

        // Función para resetear el input
        function resetFileInput() {
            console.log('Reseteando input de archivo...');
            fileInput.value = '';
            uploadArea.style.display = 'block';
            filePreview.style.display = 'none';
            previewContent.innerHTML = '';
        }

        // Función para mostrar mensajes
        function showMessage(message, type) {
            console.log('Mostrando mensaje:', message, type);
            const messageDiv = document.getElementById('upload-message');
            if (messageDiv) {
                messageDiv.textContent = message;
                messageDiv.className = `message message-${type}`;
                messageDiv.style.display = 'block';
                setTimeout(() => {
                    messageDiv.style.display = 'none';
                }, 5000);
            } else {
                console.error('Elemento upload-message no encontrado');
                alert(message);
            }
        }
    }

    // Configurar botón de vender
    function setupSellButton() {
        console.log('Configurando botón de vender...');
        const btnVender = document.getElementById('btn-vender');
        if (btnVender) {
            btnVender.addEventListener('click', () => {
                const apartadoId = btnVender.getAttribute('data-apartado-id');
                console.log('Clic en botón vender, ID:', apartadoId);
                if (confirm('¿Estás seguro de que deseas proceder con la venta de este apartado? Esta acción convertirá el apartado en una venta formal.')) {
                    console.log('Procesando venta para apartado:', apartadoId);
                    // Descomentar para redirigir a la página de venta
                    // window.location.href = `/asesor/ventas/crear/${apartadoId}`;
                }
            });
        } else {
            console.error('Botón btn-vender no encontrado');
        }
    }

    // Configurar el envío del formulario
    function setupFormSubmission() {
        console.log('Configurando envío del formulario...');
        const depositForm = document.getElementById('deposit-form');
        if (depositForm) {
            depositForm.addEventListener('submit', (e) => {
                e.preventDefault();
                console.log('Formulario enviado');

                const fileInput = document.getElementById('file-input');
                if (!fileInput || !fileInput.files || !fileInput.files[0]) {
                    console.warn('No se seleccionó ningún archivo para enviar');
                    showMessage('Por favor selecciona un archivo antes de guardar.', 'error');
                    return;
                }

                const saveBtn = document.getElementById('save-btn');
                const originalText = saveBtn.innerHTML;
                saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
                saveBtn.disabled = true;

                const formData = new FormData(depositForm);
                console.log('Enviando formulario con archivo:', fileInput.files[0].name);
                console.log('URL de destino:', depositForm.action);
                console.log('CSRF Token:', document.querySelector('input[name="_token"]').value);

                fetch(depositForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    console.log('Respuesta del servidor:', response.status, response.statusText);
                    if (!response.ok) {
                        throw new Error(`Error HTTP: ${response.status} ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Datos del servidor:', data);
                    if (data.success) {
                        showMessage('Comprobante guardado exitosamente.', 'success');
                        setTimeout(() => {
                            console.log('Recargando página...');
                            location.reload();
                        }, 2000);
                    } else {
                        console.error('Error del servidor:', data.message);
                        showMessage('Error al guardar el comprobante: ' + (data.message || 'Error desconocido'), 'error');
                    }
                })
                .catch(error => {
                    console.error('Error en la solicitud:', error);
                    showMessage('Error al guardar el comprobante. Verifica la conexión o el formato del archivo.', 'error');
                })
                .finally(() => {
                    saveBtn.innerHTML = originalText;
                    saveBtn.disabled = false;
                    console.log('Envío del formulario finalizado');
                });
            });
        } else {
            console.error('Formulario deposit-form no encontrado');
        }
    }

    // Stub para el temporizador
    function setupTimer() {
        console.log('Configurando temporizador...');
        const timeDisplay = document.getElementById('time-display');
        if (timeDisplay) {
            console.log('Temporizador configurado (stub)');
        } else {
            console.error('Elemento time-display no encontrado');
        }
    }

    // Inicialización
    document.addEventListener('DOMContentLoaded', () => {
        console.log('=== INICIALIZANDO DETALLES DE APARTADO ===');

        // Verificar elementos críticos
        const criticalElements = [
            'time-counter',
            'time-display',
            'btn-vender',
            'file-input',
            'upload-btn',
            'upload-area',
            'file-preview',
            'preview-content',
            'change-file-btn',
            'deposit-form'
        ];

        criticalElements.forEach(id => {
            const element = document.getElementById(id);
            console.log(`Elemento ${id}:`, element ? 'ENCONTRADO' : 'NO ENCONTRADO');
        });

        // Verificar apartadoData
        if (typeof apartadoData === 'undefined') {
            console.error('apartadoData no está definido, usando valor por defecto');
            window.apartadoData = { tipoApartado: '{{ $apartado->tipoApartado ?? "palabra" }}' };
        }

        console.log('apartadoData:', apartadoData);

        if (apartadoData.tipoApartado === 'deposito') {
            console.log('Configurando subida de archivos para depósito...');
            setupFileUpload();
            setupFormSubmission();
        } else {
            console.log('No es apartado con depósito, omitiendo configuración de archivos');
        }

        setupSellButton();
        setupTimer();
        console.log('=== INICIALIZACIÓN COMPLETADA ===');
    });
</script>
@endsection