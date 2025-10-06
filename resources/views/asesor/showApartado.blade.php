@extends('asesor.navbar')

@section('title', 'Nelva Bienes Raíces - Detalles del Apartado')

@push('styles')
<link href="{{ asset('css/showApartado.css') }}" rel="stylesheet">
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
                <h2 class="reservation-title">Apartado #<span id="reservation-id">1</span></h2>
                <div class="reservation-badge badge-verbal">
                    <i class="fas fa-comment"></i>
                    Apartado de Palabra
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
                    <div class="detail-value" id="client-name">Jose Alberto Reyes Gonzalez</div>
                </div>
                
                <div class="detail-card">
                    <div class="detail-label">
                        <i class="fas fa-calendar-alt"></i>
                        Fecha de apartado
                    </div>
                    <div class="detail-value" id="reservation-date">25 de septiembre de 2025</div>
                </div>
                
                <div class="detail-card">
                    <div class="detail-label">
                        <i class="fas fa-user-tie"></i>
                        Vendedor
                    </div>
                    <div class="detail-value" id="salesperson">Emmanuel Rojas</div>
                </div>
                
                <div class="detail-card">
                    <div class="detail-label">
                        <i class="fas fa-hourglass-end"></i>
                        Fecha de vencimiento
                    </div>
                    <div class="detail-value" id="expiration-date">28 de septiembre de 2025</div>
                </div>
            </div>

            <!-- Sección de lotes -->
            <div class="lot-section">
                <h3 class="section-title">
                    <i class="fas fa-map-marked-alt"></i>
                    Lote Apartado
                </h3>
                
                <div class="lot-card">
                    <div class="lot-header">
                        <h4 class="lot-title" id="lot-name">OCEANICA-1-1</h4>
                        <div class="lot-status status-apartado">Apartado</div>
                    </div>
                    
                    <div class="lot-details">
                        <div>
                            <div class="detail-label">
                                <i class="fas fa-hashtag"></i>
                                Número de lote
                            </div>
                            <div class="detail-value" id="lot-number">1</div>
                        </div>
                        
                        <div>
                            <div class="detail-label">
                                <i class="fas fa-building"></i>
                                Fraccionamiento
                            </div>
                            <div class="detail-value" id="subdivision">Oceánica</div>
                        </div>
                        
                        <div>
                            <div class="detail-label">
                                <i class="fas fa-map-marker-alt"></i>
                                Ubicación
                            </div>
                            <div class="detail-value" id="location">Guapinole, Sta María Tonameca, Oaxaca</div>
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
            </div>

            <!-- Sección de depósito -->
            <div class="deposit-section">
                <h3 class="section-title">
                    <i class="fas fa-file-invoice-dollar"></i>
                    Comprobante de Depósito
                </h3>
                
                <div class="file-upload-card" id="upload-area">
                    <div class="file-upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <p class="file-upload-text">Arrastra tu comprobante aquí o haz clic para seleccionar</p>
                    <button type="button" class="btn-upload" id="upload-btn">
                        <i class="fas fa-upload"></i>
                        Seleccionar archivo
                    </button>
                    <input type="file" id="file-input" class="file-input" accept=".pdf,.jpg,.jpeg,.png">
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
                
                <button type="button" class="btn-save" id="save-btn" style="display: none;">
                    <i class="fas fa-save"></i>
                    Guardar comprobante
                </button>
            </div>

            <!-- Estado y observaciones -->
            <div class="status-section">
                <h3 class="section-title">
                    <i class="fas fa-clipboard-check"></i>
                    Estado del Apartado
                </h3>
                
                <div class="status-badge badge-pending">
                    <i class="fas fa-clock"></i>
                    Pendiente de aprobación
                </div>
                
                <div class="observations-card">
                    <h4 class="observations-title">
                        <i class="fas fa-comment-alt"></i>
                        Observaciones
                    </h4>
                    <p id="observations-text">Aún no se han registrado observaciones para este apartado.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Verificar que los datos existan y tengan la estructura esperada
        console.log('Datos del apartado:', @json($apartado));

        // Datos del apartado con validación
        const apartadoData = @json($apartado) || {};
        
        // Función para formatear fechas
        function formatDate(dateString) {
            if (!dateString) return 'Fecha no disponible';
            
            try {
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                return new Date(dateString).toLocaleDateString('es-ES', options);
            } catch (error) {
                console.error('Error formateando fecha:', error);
                return 'Fecha inválida';
            }
        }

        // Función para calcular tiempo restante
        function calculateTimeRemaining(endDate) {
            if (!endDate) {
                return { days: 0, hours: 0, minutes: 0, seconds: 0, totalSeconds: 0 };
            }
            
            const now = new Date();
            const end = new Date(endDate);
            const diffTime = end - now;
            
            if (diffTime <= 0) {
                return { days: 0, hours: 0, minutes: 0, seconds: 0, totalSeconds: 0 };
            }
            
            const days = Math.floor(diffTime / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diffTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diffTime % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diffTime % (1000 * 60)) / 1000);
            
            return { days, hours, minutes, seconds, totalSeconds: diffTime / 1000 };
        }

        // Función para formatear el tiempo restante
        function formatTimeRemaining(timeObj) {
            if (timeObj.totalSeconds <= 0) {
                return "Vencido";
            }
            
            if (timeObj.days > 0) {
                return `${timeObj.days} día${timeObj.days !== 1 ? 's' : ''}, ${timeObj.hours} hora${timeObj.hours !== 1 ? 's' : ''}`;
            } else if (timeObj.hours > 0) {
                return `${timeObj.hours} hora${timeObj.hours !== 1 ? 's' : ''}, ${timeObj.minutes} minuto${timeObj.minutes !== 1 ? 's' : ''}`;
            } else {
                return `${timeObj.minutes} minuto${timeObj.minutes !== 1 ? 's' : ''}, ${timeObj.seconds} segundo${timeObj.seconds !== 1 ? 's' : ''}`;
            }
        }

        // Función para actualizar el contador de tiempo
        function updateTimeCounter() {
            const endDate = apartadoData.fechaVencimiento;
            const timeRemaining = calculateTimeRemaining(endDate);
            
            const timeDisplay = document.getElementById('time-display');
            const fixedTimeDisplay = document.getElementById('fixed-time-display');
            const timeCounter = document.getElementById('time-counter');
            const fixedTimeCounter = document.getElementById('fixed-time-counter');
            
            if (!timeDisplay || !fixedTimeDisplay) {
                console.error('Elementos del cronómetro no encontrados');
                return;
            }
            
            let displayText = '';
            let timeClass = '';
            let fixedTimeClass = '';
            
            if (!endDate) {
                displayText = 'Fecha no disponible';
                timeClass = 'time-normal';
                fixedTimeClass = 'fixed-time-normal';
            } else if (timeRemaining.totalSeconds <= 0) {
                displayText = 'Vencido';
                timeClass = 'time-danger';
                fixedTimeClass = 'fixed-time-danger';
            } else if (timeRemaining.days === 0 && timeRemaining.hours < 24) {
                displayText = `Vence en: ${formatTimeRemaining(timeRemaining)}`;
                timeClass = 'time-danger';
                fixedTimeClass = 'fixed-time-danger';
            } else if (timeRemaining.days <= 3) {
                displayText = `Tiempo restante: ${formatTimeRemaining(timeRemaining)}`;
                timeClass = 'time-warning';
                fixedTimeClass = 'fixed-time-warning';
            } else {
                displayText = `Tiempo restante: ${formatTimeRemaining(timeRemaining)}`;
                timeClass = 'time-normal';
                fixedTimeClass = 'fixed-time-normal';
            }
            
            timeDisplay.textContent = displayText;
            fixedTimeDisplay.textContent = displayText;
            
            // Actualizar clases
            if (timeCounter) timeCounter.className = `time-counter ${timeClass}`;
            if (fixedTimeCounter) fixedTimeCounter.className = `fixed-time-counter ${fixedTimeClass}`;
        }

        // Función para cargar los datos en la vista
        function loadReservationData() {
            // Información básica
            document.getElementById('reservation-id').textContent = apartadoData.id_apartado || 'N/A';
            document.getElementById('client-name').textContent = apartadoData.cliente_nombre && apartadoData.cliente_apellidos 
                ? `${apartadoData.cliente_nombre} ${apartadoData.cliente_apellidos}`
                : 'Cliente no disponible';
            document.getElementById('reservation-date').textContent = formatDate(apartadoData.fechaApartado);
            document.getElementById('expiration-date').textContent = formatDate(apartadoData.fechaVencimiento);
            document.getElementById('salesperson').textContent = apartadoData.usuario?.nombre || 'Vendedor no disponible';
            
            // Información del lote
            if (apartadoData.lotes_apartados && apartadoData.lotes_apartados.length > 0) {
                const lote = apartadoData.lotes_apartados[0].lote;
                const fraccionamiento = lote?.fraccionamiento;
                
                document.getElementById('lot-name').textContent = lote?.id_lote || 'N/A';
                document.getElementById('lot-number').textContent = lote?.numeroLote || 'N/A';
                document.getElementById('subdivision').textContent = fraccionamiento?.nombre || 'N/A';
                document.getElementById('location').textContent = fraccionamiento?.ubicacion || 'Ubicación no disponible';
            }
            
            // Actualizar contador de tiempo
            updateTimeCounter();
        }

        // Funcionalidad para subir archivos
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM cargado, inicializando...');
            
            // Cargar datos primero
            loadReservationData();
            
            // Elementos del file upload
            const fileInput = document.getElementById('file-input');
            const uploadBtn = document.getElementById('upload-btn');
            const uploadArea = document.getElementById('upload-area');
            const filePreview = document.getElementById('file-preview');
            const previewImage = document.getElementById('preview-image');
            const fileLink = document.getElementById('file-link');
            const changeFileBtn = document.getElementById('change-file-btn');
            const saveBtn = document.getElementById('save-btn');
            
            if (!fileInput) {
                console.error('Elemento file-input no encontrado');
                return;
            }
            
            console.log('Elementos de file upload encontrados');
            
            // Abrir selector de archivos al hacer clic en el botón o área
            uploadBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                fileInput.click();
            });
            
            uploadArea.addEventListener('click', (e) => {
                if (e.target === uploadArea || e.target.classList.contains('file-upload-text')) {
                    fileInput.click();
                }
            });
            
            // Prevenir que el drag and drop recargue la página
            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadArea.classList.add('drag-over');
            });
            
            uploadArea.addEventListener('dragleave', () => {
                uploadArea.classList.remove('drag-over');
            });
            
            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadArea.classList.remove('drag-over');
                if (e.dataTransfer.files.length) {
                    fileInput.files = e.dataTransfer.files;
                    handleFileSelection();
                }
            });
            
            // Cambiar archivo
            changeFileBtn.addEventListener('click', () => fileInput.click());
            
            // Manejar selección de archivo
            fileInput.addEventListener('change', handleFileSelection);
            
            function handleFileSelection() {
                if (fileInput.files && fileInput.files[0]) {
                    const file = fileInput.files[0];
                    const fileName = file.name;
                    const fileType = file.type;
                    
                    console.log('Archivo seleccionado:', fileName, fileType);
                    
                    // Mostrar vista previa para imágenes
                    if (fileType.match('image.*')) {
                        const reader = new FileReader();
                        
                        reader.onload = function(e) {
                            previewImage.src = e.target.result;
                            previewImage.style.display = 'block';
                            fileLink.style.display = 'none';
                        }
                        
                        reader.readAsDataURL(file);
                    } else {
                        previewImage.style.display = 'none';
                        fileLink.style.display = 'block';
                        fileLink.textContent = fileName;
                        fileLink.href = URL.createObjectURL(file);
                    }
                    
                    // Mostrar área de vista previa y botón de guardar
                    uploadArea.style.display = 'none';
                    filePreview.style.display = 'block';
                    saveBtn.style.display = 'block';
                }
            }
            
            // Guardar archivo (simulación)
            saveBtn.addEventListener('click', function() {
                alert('Comprobante guardado exitosamente. Será revisado por el administrador.');
                // Aquí iría la lógica para enviar el archivo al servidor
            });
            
            // Actualizar el contador cada segundo
            setInterval(updateTimeCounter, 1000);
            
            console.log('Inicialización completada');
        });
    </script>
</body>
</html>
@endsection