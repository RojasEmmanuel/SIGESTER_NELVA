@extends('admin.navbar')
@section('title', 'Detalle de Venta')
@push('styles')
    <link href="{{ asset('css/ventas-style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<div class="ventas-content">
    <div class="detail-container">
        <!-- Header -->
        <div class="ticket-header">
            <div class="breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('admin.ventas.index') }}">Ventas</a>
                </div>
                <div class="breadcrumb-item active">Detalle #{{ $venta->id_venta }}</div>
            </div>
        </div>

        <div class="detail-card">
            <div class="detail-card-header">
                <h5>
                    <i class="fas fa-file-invoice"></i> Venta #{{ $venta->id_venta }}
                </h5>
                <div>
                    @if($venta->ticket_estatus === 'solicitud')
                        <a href="{{ route('admin.ventas.ticket', $venta->id_venta) }}" 
                           class="btn btn-warning btn-sm">
                            <i class="fas fa-receipt"></i> Validar Ticket
                        </a>
                    @endif
                </div>
            </div>
            <div class="detail-card-body">
                <div class="detail-grid">
                    <!-- Información General -->
                    <div class="info-section">
                        <h6><i class="fas fa-info-circle"></i> Información General</h6>
                        <div class="info-table">
                            <table>
                                <tr>
                                    <th>Estatus Venta:</th>
                                    <td>
                                        <span class="badge badge-{{ $venta->estatus === 'liquidado' ? 'success' : ($venta->estatus === 'pagos' ? 'warning' : ($venta->estatus === 'retraso' ? 'danger' : ($venta->estatus === 'cancelado' ? 'secondary' : 'info'))) }}">
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
                                <tr>
                                    <th>Fecha Solicitud:</th>
                                    <td>{{ $venta->fechaSolicitud->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Vendedor:</th>
                                    <td>{{ $venta->apartado->usuario->nombre }} {{ $venta->apartado->usuario->apellidos }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Información del Cliente -->
                    <div class="info-section">
                        <h6><i class="fas fa-user"></i> Cliente</h6>
                        <div class="info-table">
                            <table>
                                <tr>
                                    <th>Nombre:</th>
                                    <td>{{ $venta->clienteVenta->nombres }} {{ $venta->clienteVenta->apellidos }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $venta->clienteVenta->contacto->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Teléfono:</th>
                                    <td>{{ $venta->clienteVenta->contacto->telefono ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Lotes -->
                    <div class="info-section">
                        <h6><i class="fas fa-map-marker-alt"></i> Lotes Vendidos</h6>
                        <div class="lotes-container">
                            @foreach($venta->apartado->lotesApartados as $loteApartado)
                                <div class="lote-detail">
                                    <h6>Lote: {{ $loteApartado->lote->nombre }}</h6>
                                    <div class="info-table">
                                        <table>
                                            <tr>
                                                <th>Fraccionamiento:</th>
                                                <td>{{ $loteApartado->lote->fraccionamiento->nombre }}</td>
                                            </tr>
                                             <tr>
                                                <th>Lote:</th>
                                                <td>{{ $loteApartado->lote->numeroLote }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total:</th>
                                                <td>${{ number_format($venta->total, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Estatus Lote:</th>
                                                <td>
                                                    <span class="badge badge-{{ $loteApartado->lote->estatus === 'vendido' ? 'success' : 'primary' }}">
                                                        {{ ucfirst($loteApartado->lote->estatus) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Ticket -->
                    <div class="info-section">
                        <h6><i class="fas fa-receipt"></i> Ticket de Enganche</h6>
                        <div class="ticket-preview">
                            @if($ticketExists && $venta->ticket_path)
                                <img src="{{ asset('storage/' . $venta->ticket_path) }}" 
                                    alt="Ticket de enganche" 
                                    class="ticket-preview-img">
                                <br>
                                <a href="{{ asset('storage/' . $venta->ticket_path) }}" 
                                target="_blank" 
                                class="btn btn-outline btn-sm">
                                    <i class="fas fa-expand"></i> Ver completo
                                </a>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    No se encontró el ticket de enganche.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
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