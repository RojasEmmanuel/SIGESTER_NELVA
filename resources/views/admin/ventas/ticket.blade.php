@extends('admin.navbar')
@section('title', 'Validación de Ticket - Ventas')
@push('styles')
    <link href="{{ asset('css/ventas-style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<div class="ventas-content">
    <div class="ticket-container">
        <!-- Header -->
        <div class="ticket-header">
            <div class="breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('admin.ventas.index') }}">Ventas</a>
                </div>
                <div class="breadcrumb-item active">Validación de Ticket</div>
            </div>
        </div>

        <div class="ticket-card">
            <div class="ticket-card-header">
                <h5>
                    <i class="fas fa-receipt"></i> Validación de Ticket - Venta #{{ $venta->id_venta }}
                </h5>
            </div>
            <div class="ticket-card-body">
                <div class="ticket-grid">
                    <!-- Información de la Venta -->
                    <div class="ticket-info-card">
                        <h6>Información de la Venta</h6>
                        <div class="info-table">
                            <table>
                                <tr>
                                    <th>Cliente:</th>
                                    <td>{{ $venta->clienteVenta->nombres }} {{ $venta->clienteVenta->apellidos }}</td>
                                </tr>
                                <tr>
                                    <th>Fecha Solicitud:</th>
                                    <td>{{ $venta->fechaSolicitud->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Estatus Venta:</th>
                                    <td>
                                        <span class="badge badge-{{ $venta->estatus === 'pagos' ? 'warning' : ($venta->estatus === 'liquidado' ? 'success' : 'info') }}">
                                            {{ ucfirst($venta->estatus) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Estatus Ticket:</th>
                                    <td>
                                        <span class="badge badge-{{ $venta->ticket_estatus === 'aceptado' ? 'success' : ($venta->ticket_estatus === 'rechazado' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($venta->ticket_estatus) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Lotes Asociados -->
                        <h6>Lotes en Venta</h6>
                        <div class="lotes-container">
                            @foreach($venta->apartado->lotesApartados as $loteApartado)
                                <div class="lote-item">
                                    <strong>Lote:</strong> {{ $loteApartado->lote->nombre }}<br>
                                    <strong>Fraccionamiento:</strong> {{ $loteApartado->lote->fraccionamiento->nombre }}<br>
                                    <strong>Precio:</strong> ${{ number_format($loteApartado->lote->precio, 2) }}
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Ticket y Acciones -->
                    <div class="ticket-actions-card">
                        <!-- Ticket -->
                        <!-- Ticket -->
                        <h6>Ticket de Enganche</h6>
                        <div class="ticket-image-container">
                            @if($ticketExists && $venta->ticket_path)
                                <img src="{{ asset('storage/' . $venta->ticket_path) }}" 
                                    alt="Ticket de enganche" 
                                    class="ticket-image">
                                <a href="{{ asset('storage/' . $venta->ticket_path) }}" 
                                target="_blank" 
                                class="btn btn-outline btn-sm">
                                    <i class="fas fa-expand"></i> Ver en tamaño completo
                                </a>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    No se encontró el ticket de enganche.
                                </div>
                            @endif
                        </div>
                        

                        <!-- Acciones de Validación -->
                        <h6>Acciones de Validación</h6>
                        <form id="ticketStatusForm">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">Cambiar Estatus del Ticket:</label>
                                <div class="status-buttons">
                                    <button type="button" 
                                            class="status-btn solicitud {{ $venta->ticket_estatus === 'solicitud' ? 'active' : '' }}"
                                            data-status="solicitud">
                                        <i class="fas fa-clock"></i> Solicitud
                                    </button>
                                    <button type="button" 
                                            class="status-btn rechazado {{ $venta->ticket_estatus === 'rechazado' ? 'active' : '' }}"
                                            data-status="rechazado">
                                        <i class="fas fa-times"></i> Rechazar
                                    </button>
                                    <button type="button" 
                                            class="status-btn aceptado {{ $venta->ticket_estatus === 'aceptado' ? 'active' : '' }}"
                                            data-status="aceptado">
                                        <i class="fas fa-check"></i> Aceptar
                                    </button>
                                </div>
                            </div>

                            <div id="rechazoMotivo" class="form-group hidden">
                                <label for="motivo" class="form-label">Motivo del rechazo:</label>
                                <textarea class="textarea-field" id="motivo" rows="3" placeholder="Especifica el motivo del rechazo..."></textarea>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-full" id="submitBtn">
                                    <i class="fas fa-save"></i> Actualizar Estatus
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación -->
<div class="ventas-modal-overlay" id="confirmModal">
    <div class="ventas-modal-content">
        <div class="ventas-modal-header">
            <h5 class="ventas-modal-title">Confirmar Cambio de Estatus</h5>
            <button type="button" class="ventas-modal-close">&times;</button>
        </div>
        <div class="ventas-modal-body">
            <p id="confirmMessage"></p>
            <div id="aceptadoInfo" class="alert alert-info hidden">
                <i class="fas fa-info-circle"></i> Al aceptar el ticket, el estatus de la venta cambiará a "Pagos" y los lotes se marcarán como vendidos.
            </div>
        </div>
        <div class="ventas-modal-footer">
            <button type="button" class="btn btn-secondary modal-cancel">Cancelar</button>
            <button type="button" class="btn btn-primary" id="confirmChange">Confirmar</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let selectedStatus = '{{ $venta->ticket_estatus }}';
    
    // Manejar selección de estatus
    document.querySelectorAll('.status-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.status-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            selectedStatus = this.dataset.status;
            
            // Mostrar/ocultar campo de motivo para rechazo
            const motivoField = document.getElementById('rechazoMotivo');
            if (selectedStatus === 'rechazado') {
                motivoField.classList.remove('hidden');
            } else {
                motivoField.classList.add('hidden');
            }
        });
    });

    // Enviar formulario
    document.getElementById('ticketStatusForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const statusLabels = {
            'solicitud': 'Solicitud',
            'rechazado': 'Rechazado', 
            'aceptado': 'Aceptado'
        };

        let message = `¿Estás seguro de que deseas cambiar el estatus del ticket a "${statusLabels[selectedStatus]}"?`;
        
        // Información adicional para aceptación
        const aceptadoInfo = document.getElementById('aceptadoInfo');
        if (selectedStatus === 'aceptado') {
            aceptadoInfo.classList.remove('hidden');
        } else {
            aceptadoInfo.classList.add('hidden');
        }

        document.getElementById('confirmMessage').textContent = message;
        document.getElementById('confirmModal').classList.add('active');
    });

    // Confirmar cambio
    document.getElementById('confirmChange').addEventListener('click', function() {
        document.getElementById('confirmModal').classList.remove('active');
        
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
                
                // Actualizar la página después de un momento
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showNotification('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Error al actualizar el estatus');
        });
    });

    // Cerrar modales
    document.querySelectorAll('.ventas-modal-close, .modal-cancel').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('confirmModal').classList.remove('active');
        });
    });

    // Cerrar modal al hacer click fuera
    document.getElementById('confirmModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
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