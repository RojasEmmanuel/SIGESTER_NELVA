@extends('admin.navbar')
@section('title', 'Validación de Ticket - Ventas')
@push('styles')
    <link href="{{ asset('css/ventas-style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<div class="ventas-content">
    <div class="ticket-container">
        <!-- Header Mejorado -->
        <div class="ticket-header">
            <div class="header-actions" style="justify-content: space-between;">
                
                <div class="header-title">
                    <h1 style="font-size: 1.8rem;">Validación de Ticket</h1>
                    <p>Revisión y aprobación de comprobante de pago</p>
                </div>

                <a href="{{ route('admin.ventas.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    <span>Volver</span>
                </a>
            </div>
            
        </div>

        <div class="ticket-card">
            <div class="ticket-card-header">
                <div class="header-content">
                    <div class="header-icon">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div class="header-text">
                        <h5>Venta #{{ $venta->id_venta }}</h5>
                        <p>{{ $venta->clienteVenta->nombres }} {{ $venta->clienteVenta->apellidos }}</p>
                    </div>
                    <div class="header-badge">
                        <span class="badge badge-{{ $venta->ticket_estatus === 'aceptado' ? 'success' : ($venta->ticket_estatus === 'rechazado' ? 'danger' : 'warning') }}">
                            <i class="fas fa-{{ $venta->ticket_estatus === 'aceptado' ? 'check' : ($venta->ticket_estatus === 'rechazado' ? 'times' : 'clock') }}"></i>
                            {{ ucfirst($venta->ticket_estatus) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="ticket-card-body">
                <div class="ticket-grid">
                    <!-- Información de la Venta -->
                    <div class="ticket-info-card">
                        <div class="card-section">
                            <h6><i class="fas fa-info-circle"></i> Información General</h6>
                            <div class="info-grid">
                                <div class="info-item">
                                    <label>Cliente:</label>
                                    <div class="info-value">
                                        <i class="fas fa-user"></i>
                                        {{ $venta->clienteVenta->nombres }} {{ $venta->clienteVenta->apellidos }}
                                    </div>
                                </div>
                                <div class="info-item">
                                    <label>Asesor:</label>
                                    <div class="info-value">
                                        <i class="fas fa-user-tie"></i>
                                        {{ $venta->apartado->usuario->nombre }}
                                    </div>
                                </div>
                                <div class="info-item">
                                    <label>Fecha Solicitud:</label>
                                    <div class="info-value">
                                        <i class="fas fa-calendar"></i>
                                        {{ $venta->fechaSolicitud->format('d/m/Y') }}
                                    </div>
                                </div>

                                <div class="info-item">
                                    <label>Enganche:</label>
                                    <div class="info-value">
                                        <i class="fas fa-dollar-sign"></i>
                                        ${{ number_format($venta->enganche, 2) }}
                                    </div>
                                </div>

                                <div class="info-item">
                                    <label>Total Venta:</label>
                                    <div class="info-value">
                                        <i class="fas fa-dollar-sign"></i>
                                        ${{ number_format($venta->total, 2) }}
                                    </div>
                                </div>

                                


                                <div class="info-item">
                                    <label>Estatus Venta:</label>
                                    <div class="info-value">
                                        <span class="badge badge-{{ $venta->estatus === 'pagos' ? 'warning' : ($venta->estatus === 'liquidado' ? 'success' : 'info') }}">
                                            <i class="fas fa-{{ $venta->estatus === 'pagos' ? 'money-bill-wave' : ($venta->estatus === 'liquidado' ? 'flag-checkered' : 'clock') }}"></i>
                                            {{ ucfirst($venta->estatus) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <label>Estatus Ticket:</label>
                                    <div class="info-value">
                                        <span class="badge badge-{{ $venta->ticket_estatus === 'aceptado' ? 'success' : ($venta->ticket_estatus === 'rechazado' ? 'danger' : 'warning') }}">
                                            <i class="fas fa-{{ $venta->ticket_estatus === 'aceptado' ? 'check' : ($venta->ticket_estatus === 'rechazado' ? 'times' : 'clock') }}"></i>
                                            {{ ucfirst($venta->ticket_estatus) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Lotes Asociados -->
                        <div class="card-section">
                            <h6><i class="fas fa-map-marker-alt"></i> Lotes en Venta</h6>
                            <div class="lotes-container">
                                @foreach($venta->apartado->lotesApartados as $loteApartado)
                                    <div class="lote-card">
                                        <div class="lote-header">
                                            <i class="fas fa-home"></i>
                                            <div class="lote-info">
                                                <strong class="lote-name">{{ $loteApartado->lote->nombre }}</strong>
                                                <span class="lote-fraccionamiento">{{ $loteApartado->lote->fraccionamiento->nombre }}</span>
                                            </div>
                                        </div>
                                        <div class="lote-id">
                                            <i class="fas fa-hashtag"></i>
                                            ID: {{ $loteApartado->lote->id_lote }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Ticket y Acciones -->
                    <div class="ticket-actions-card">
                        <!-- Ticket -->
                        <div class="card-section">
                            <h6><i class="fas fa-receipt"></i> Comprobante de Enganche</h6>
                            <div class="ticket-preview-container">
                                @if($ticketExists && $venta->ticket_path)
                                    <div class="ticket-image-wrapper">
                                        <img src="{{ asset('storage/' . $venta->ticket_path) }}" 
                                            alt="Ticket de enganche" 
                                            class="ticket-image"
                                            id="ticketImage">
                                        <div class="ticket-overlay">
                                            <div class="ticket-actions-grid">
                                                <button type="button" class="action-btn zoom-btn" id="zoomInBtn">
                                                    <div class="action-icon">
                                                        <i class="fas fa-search-plus"></i>
                                                    </div>
                                                </button>
                                                <a href="{{ asset('storage/' . $venta->ticket_path) }}" 
                                                target="_blank" 
                                                class="action-btn">
                                                    <div class="action-icon">
                                                        <i class="fas fa-expand-arrows-alt"></i>
                                                    </div>
                                                </a>
                                                <a href="{{ asset('storage/' . $venta->ticket_path) }}" 
                                                download 
                                                class="action-btn">
                                                    <div class="action-icon">
                                                        <i class="fas fa-download"></i>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ticket-hint">
                                        <i class="fas fa-mouse-pointer"></i>
                                        <span>Pasa el cursor sobre la imagen para ver las opciones</span>
                                    </div>
                                @else
                                    <div class="empty-ticket">
                                        <i class="fas fa-receipt"></i>
                                        <p>No se encontró el comprobante</p>
                                        <small>El cliente no ha subido el ticket de enganche</small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Acciones de Validación -->
                        <div class="card-section">
                            <h6><i class="fas fa-tasks"></i> Acciones de Validación</h6>
                            <form id="ticketStatusForm" class="validation-form">
                                @csrf
                                
                                <div class="form-group">
                                    <label class="form-label">Seleccionar estatus:</label>
                                    <div class="status-cards">
                                        <div class="status-card {{ $venta->ticket_estatus === 'solicitud' ? 'active' : '' }}" data-status="solicitud">
                                            <div class="status-icon">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                            <div class="status-content">
                                                <h4>Mantener Pendiente</h4>
                                                <p>Esperar más información</p>
                                            </div>
                                            <div class="status-check">
                                                <i class="fas fa-check"></i>
                                            </div>
                                        </div>
                                        
                                        <div class="status-card {{ $venta->ticket_estatus === 'rechazado' ? 'active' : '' }}" data-status="rechazado">
                                            <div class="status-icon rejected">
                                                <i class="fas fa-times"></i>
                                            </div>
                                            <div class="status-content">
                                                <h4>Rechazar</h4>
                                                <p>Comprobante no válido</p>
                                            </div>
                                            <div class="status-check">
                                                <i class="fas fa-check"></i>
                                            </div>
                                        </div>
                                        
                                        <div class="status-card {{ $venta->ticket_estatus === 'aceptado' ? 'active' : '' }}" data-status="aceptado">
                                            <div class="status-icon approved">
                                                <i class="fas fa-check"></i>
                                            </div>
                                            <div class="status-content">
                                                <h4>Aceptar</h4>
                                                <p>Comprobante verificado</p>
                                            </div>
                                            <div class="status-check">
                                                <i class="fas fa-check"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="button" class="btn btn-outline" id="cancelBtn">
                                        <i class="fas fa-arrow-left"></i> Regresar
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <span class="btn-text">
                                            <i class="fas fa-save"></i> Actualizar Estatus
                                        </span>
                                        <div class="btn-loading hidden">
                                            <div class="loading-spinner"></div>
                                            <span>Procesando...</span>
                                        </div>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal de Confirmación Mejorado -->
<div class="ventas-modal-overlay" id="confirmModal">
    <div class="ventas-modal-content modern-modal">
        <div class="modern-modal-header">
            
            <div class="modern-modal-title">
                <h5>Confirmar Cambio de Estatus</h5>
                <p id="confirmSubtitle">Revisa los detalles antes de continuar</p>
            </div>
            <button type="button" class="modern-modal-close">&times;</button>
        </div>
        <div class="modern-modal-body">
            <div class="confirmation-content">
                <div class="status-change-preview">
                    <div class="current-status">
                        <span class="status-label">Estatus actual:</span>
                        <span class="badge badge-{{ $venta->ticket_estatus === 'aceptado' ? 'success' : ($venta->ticket_estatus === 'rechazado' ? 'danger' : 'warning') }}">
                            {{ ucfirst($venta->ticket_estatus) }}
                        </span>
                    </div>
                    <div class="status-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                    <div class="new-status">
                        <span class="status-label">Nuevo estatus:</span>
                        <span id="newStatusBadge" class="badge"></span>
                    </div>
                </div>
                
                <div id="confirmMessage" class="confirm-message"></div>
            </div>
        </div>
        <div class="modern-modal-footer">
            <button type="button" class="btn btn-outline modal-cancel">
                <i class="fas fa-times"></i> Cancelar
            </button>
            <button type="button" class="btn btn-primary" id="confirmChange">
                <span class="btn-text">
                    <i class="fas fa-check"></i> Confirmar Cambio
                </span>
                <div class="btn-loading hidden">
                    <div class="loading-spinner"></div>
                    <span>Procesando...</span>
                </div>
            </button>
        </div>
    </div>
</div>


<!-- Modal para Zoom de Imagen Mejorado -->
<div class="ventas-modal-overlay" id="zoomModal">
    <div class="zoom-modal-content">
        <div class="zoom-modal-header">
            <div class="zoom-header-content">
                <div class="zoom-header-icon">
                    <i class="fas fa-search-plus"></i>
                </div>
                <div class="zoom-header-text">
                    <h5>Vista Previa del Comprobante</h5>
                    <p>Venta #{{ $venta->id_venta }} - {{ $venta->clienteVenta->nombres }} {{ $venta->clienteVenta->apellidos }}</p>
                </div>
            </div>
            <div class="zoom-controls">
                <div class="zoom-info">
                    <span id="zoomLevel">100%</span>
                </div>
                <div class="zoom-buttons">
                    <button type="button" class="btn btn-sm btn-outline" id="zoomOutBtn" title="Zoom Out">
                        <i class="fas fa-search-minus"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline" id="zoomResetBtn" title="Reset Zoom">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline" id="closeZoom">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="zoom-modal-body">
            <div class="zoom-container" id="zoomContainer">
                <img src="{{ $ticketExists && $venta->ticket_path ? asset('storage/' . $venta->ticket_path) : '' }}" 
                     alt="Ticket de enganche" 
                     class="zoomed-image"
                     id="zoomedImage">
            </div>
        </div>
        <div class="zoom-modal-footer">
            <div class="zoom-instructions">
                <div class="instruction-item">
                    <i class="fas fa-mouse"></i>
                    <span>Rueda del mouse para zoom</span>
                </div>
                <div class="instruction-item">
                    <i class="fas fa-arrows-alt"></i>
                    <span>Arrastra para mover</span>
                </div>
            </div>
            <div class="zoom-actions">
                <a href="{{ $ticketExists && $venta->ticket_path ? asset('storage/' . $venta->ticket_path) : '#' }}" 
                   target="_blank" 
                   class="btn btn-outline btn-sm">
                    <i class="fas fa-external-link-alt"></i> Abrir en nueva pestaña
                </a>
                <a href="{{ $ticketExists && $venta->ticket_path ? asset('storage/' . $venta->ticket_path) : '#' }}" 
                   download 
                   class="btn btn-primary btn-sm">
                    <i class="fas fa-download"></i> Descargar
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let selectedStatus = '{{ $venta->ticket_estatus }}';
    let zoomLevel = 1;
    let isDragging = false;
    let startX, startY, scrollLeft, scrollTop;
    
    // Botón de regresar
    document.getElementById('cancelBtn').addEventListener('click', function() {
        window.history.back();
    });
    
    // Manejar selección de estatus con cards
    document.querySelectorAll('.status-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.status-card').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            selectedStatus = this.dataset.status;
        });
    });

    // Enviar formulario
    document.getElementById('ticketStatusForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Configuración por estatus
        const configs = {
            'solicitud': { 
                label: 'Pendiente', 
                class: 'warning', 
                icon: 'clock',
                message: '¿Confirmas que deseas mantener el comprobante como pendiente?'
            },
            'rechazado': { 
                label: 'Rechazado', 
                class: 'danger', 
                icon: 'times',
                message: '¿Confirmas que deseas rechazar el comprobante?'
            },
            'aceptado': { 
                label: 'Aceptado', 
                class: 'success', 
                icon: 'check',
                message: '¿Confirmas que deseas aceptar el comprobante?'
            }
        };

        const config = configs[selectedStatus];
        
        // Actualizar elementos visuales
        const newStatusBadge = document.getElementById('newStatusBadge');
        newStatusBadge.className = `badge badge-${config.class}`;
        newStatusBadge.innerHTML = `<i class="fas fa-${config.icon}"></i> ${config.label}`;
        
        document.getElementById('confirmMessage').textContent = config.message;

        // Mostrar modal
        document.getElementById('confirmModal').classList.add('active');
    });

    // Confirmar cambio
    document.getElementById('confirmChange').addEventListener('click', function() {
        const confirmBtn = this;
        const btnText = confirmBtn.querySelector('.btn-text');
        const btnLoading = confirmBtn.querySelector('.btn-loading');
        const submitBtn = document.getElementById('submitBtn');
        const submitBtnText = submitBtn.querySelector('.btn-text');
        const submitBtnLoading = submitBtn.querySelector('.btn-loading');
        
        // Mostrar loading en ambos botones
        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');
        submitBtnText.classList.add('hidden');
        submitBtnLoading.classList.remove('hidden');
        confirmBtn.disabled = true;
        submitBtn.disabled = true;

        // Envío de datos
        fetch('{{ route("admin.ventas.update-ticket-estatus", $venta->id_venta) }}', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                ticket_estatus: selectedStatus
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('success', data.message);
                
                // Cerrar modal después de un breve delay
                setTimeout(() => {
                    document.getElementById('confirmModal').classList.remove('active');
                    // Recargar la página después de cerrar el modal
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }, 1500);
            } else {
                showNotification('error', data.message);
                resetButtons();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Error al actualizar el estatus');
            resetButtons();
        });
        
        function resetButtons() {
            btnText.classList.remove('hidden');
            btnLoading.classList.add('hidden');
            submitBtnText.classList.remove('hidden');
            submitBtnLoading.classList.add('hidden');
            confirmBtn.disabled = false;
            submitBtn.disabled = false;
        }
    });

    // Funcionalidad de Zoom
    const zoomModal = document.getElementById('zoomModal');
    const zoomedImage = document.getElementById('zoomedImage');
    const zoomContainer = document.getElementById('zoomContainer');
    const zoomLevelDisplay = document.getElementById('zoomLevel');
    
    document.getElementById('zoomInBtn')?.addEventListener('click', function() {
        zoomModal.classList.add('active');
        resetZoom();
    });
    
    document.getElementById('closeZoom')?.addEventListener('click', function() {
        zoomModal.classList.remove('active');
        resetZoom();
    });
    
    document.getElementById('zoomOutBtn')?.addEventListener('click', function() {
        if (zoomLevel > 1) {
            zoomLevel -= 0.25;
            updateZoom();
        }
    });
    
    document.getElementById('zoomResetBtn')?.addEventListener('click', function() {
        resetZoom();
    });
    
    // Zoom con rueda del mouse
    zoomContainer?.addEventListener('wheel', function(e) {
        e.preventDefault();
        if (e.deltaY < 0) {
            zoomLevel += 0.25;
        } else {
            zoomLevel = Math.max(1, zoomLevel - 0.25);
        }
        updateZoom();
    });
    
    // Arrastrar imagen
    zoomContainer?.addEventListener('mousedown', function(e) {
        if (zoomLevel > 1) {
            isDragging = true;
            startX = e.pageX - zoomContainer.offsetLeft;
            startY = e.pageY - zoomContainer.offsetTop;
            scrollLeft = zoomContainer.scrollLeft;
            scrollTop = zoomContainer.scrollTop;
            zoomContainer.style.cursor = 'grabbing';
        }
    });
    
    document.addEventListener('mouseup', function() {
        isDragging = false;
        if (zoomLevel > 1) {
            zoomContainer.style.cursor = 'grab';
        }
    });
    
    document.addEventListener('mousemove', function(e) {
        if (!isDragging || zoomLevel <= 1) return;
        e.preventDefault();
        const x = e.pageX - zoomContainer.offsetLeft;
        const y = e.pageY - zoomContainer.offsetTop;
        const walkX = (x - startX) * 2;
        const walkY = (y - startY) * 2;
        zoomContainer.scrollLeft = scrollLeft - walkX;
        zoomContainer.scrollTop = scrollTop - walkY;
    });
    
    function updateZoom() {
        zoomedImage.style.transform = `scale(${zoomLevel})`;
        zoomLevelDisplay.textContent = `${Math.round(zoomLevel * 100)}%`;
        
        if (zoomLevel > 1) {
            zoomContainer.style.cursor = 'grab';
            zoomContainer.style.overflow = 'auto';
        } else {
            zoomContainer.style.cursor = 'default';
            zoomContainer.style.overflow = 'hidden';
        }
    }
    
    function resetZoom() {
        zoomLevel = 1;
        zoomedImage.style.transform = 'scale(1)';
        zoomContainer.scrollLeft = 0;
        zoomContainer.scrollTop = 0;
        zoomContainer.style.cursor = 'default';
        zoomContainer.style.overflow = 'hidden';
        zoomLevelDisplay.textContent = '100%';
    }

    // Cerrar modales
    document.querySelectorAll('.modern-modal-close, .modal-cancel').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('confirmModal').classList.remove('active');
            document.getElementById('zoomModal').classList.remove('active');
        });
    });

    // Cerrar modal al hacer click fuera
    document.getElementById('confirmModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
        }
    });
    
    document.getElementById('zoomModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
            resetZoom();
        }
    });
    
    // Cerrar con tecla Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.getElementById('confirmModal').classList.remove('active');
            document.getElementById('zoomModal').classList.remove('active');
            resetZoom();
        }
    });
});

// Sistema de notificaciones
function showNotification(type, message) {
    const notification = document.createElement('div');
    notification.className = `ventas-notification ventas-notification-${type}`;
    notification.innerHTML = `
        <div class="ventas-notification-content">
            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 3000);
}
</script>
@endpush