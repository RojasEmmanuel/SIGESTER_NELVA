@extends('admin.navbar')
@section('title', 'Detalle de Venta')
@push('styles')
    <link href="{{ asset('css/showVentaHistorial.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<div class="ventas-content">
    <div class="detail-container">
        <!-- Header con título y botón -->
        <div class="ticket-header">
            <div class="header-actions" style="justify-content: space-between;">
                <div class="header-title">
                    <h1>Detalles de la Venta</h1>
                    <p>Información completa de la venta #{{ $venta->id_venta }}</p>
                </div>
                <a href="{{ route('admin.ventas.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    <span>Volver</span>
                </a>
            </div>
        </div>

        <div class="detail-card">
            <div class="detail-card-header">
               
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
                                    <th>Asesor:</th>
                                    <td>{{ $venta->apartado->usuario->nombre }} {{ $venta->apartado->usuario->apellidos }}</td>
                                </tr>
                                <tr>
                                    <th>Total Venta:</th>
                                    <td>${{ number_format($venta->total, 2) }} MXN</td>
                                </tr>
                                <tr>
                                    <th>Enganche:</th>
                                    <td>${{ number_format($venta->enganche, 2) }} MXN</td>
                                </tr>
                                @if($venta->credito)
                                <tr>
                                    <th>Modalidad Pago:</th>
                                    <td>
                                        {{ $venta->credito->modalidad_pago ?? 'Contado' }}
                                        @if($venta->credito->plazo_financiamiento)
                                            ({{ $venta->credito->plazo_financiamiento }} meses)
                                        @endif
                                    </td>
                                </tr>
                                @endif
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
                                    <th>Información Personal:</th>
                                    <td>
                                        {{ $venta->clienteVenta->edad ?? 'N/A' }} años • 
                                        {{ $venta->clienteVenta->estado_civil ?? 'N/A' }} • 
                                        {{ $venta->clienteVenta->ocupacion ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Identificación:</th>
                                    <td>{{ $venta->clienteVenta->clave_elector ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $venta->clienteVenta->contacto->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Teléfono:</th>
                                    <td>{{ $venta->clienteVenta->contacto->telefono ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Domicilio:</th>
                                    <td>
                                        {{ $venta->clienteVenta->direccion->nacionalidad ?? 'N/A' }}, 
                                        {{ $venta->clienteVenta->direccion->estado ?? 'N/A' }}, 
                                        {{ $venta->clienteVenta->direccion->municipio ?? 'N/A' }}, 
                                        {{ $venta->clienteVenta->direccion->localidad ?? 'N/A' }}
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Fotos INE del Cliente -->
                        <div class="info-subsection">
                            <h6><i class="fas fa-id-card"></i> INE del Cliente</h6>
                            <div class="ine-container">
                                @if($venta->clienteVenta->ine_frente || $venta->clienteVenta->ine_reverso)
                                    <div class="ine-images">
                                        @if($venta->clienteVenta->ine_frente)
                                            <div class="ine-image">
                                                <img src="{{ Storage::url($venta->clienteVenta->ine_frente) }}" 
                                                     alt="INE Frente" 
                                                     class="ine-img">
                                                <div class="ine-label">Frente</div>
                                            </div>
                                        @endif
                                        @if($venta->clienteVenta->ine_reverso)
                                            <div class="ine-image">
                                                <img src="{{ Storage::url($venta->clienteVenta->ine_reverso) }}" 
                                                     alt="INE Reverso" 
                                                     class="ine-img">
                                                <div class="ine-label">Reverso</div>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i> 
                                        No se encontraron imágenes de INE del cliente.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Beneficiario -->
                    @if($venta->beneficiario)
                    <div class="info-section">
                        <h6><i class="fas fa-user-friends"></i> Beneficiario</h6>
                        <div class="info-table">
                            <table>
                                <tr>
                                    <th>Nombre:</th>
                                    <td>{{ $venta->beneficiario->nombres }} {{ $venta->beneficiario->apellidos }}</td>
                                </tr>
                                <tr>
                                    <th>Teléfono:</th>
                                    <td>{{ $venta->beneficiario->telefono ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Fotos INE del Beneficiario -->
                        <div class="info-subsection">
                            <h6><i class="fas fa-id-card"></i> INE del Beneficiario</h6>
                            <div class="ine-container">
                                @if($venta->beneficiario->ine_frente || $venta->beneficiario->ine_reverso)
                                    <div class="ine-images">
                                        @if($venta->beneficiario->ine_frente)
                                            <div class="ine-image">
                                                <img src="{{ Storage::url($venta->beneficiario->ine_frente) }}" 
                                                     alt="INE Frente Beneficiario" 
                                                     class="ine-img">
                                                <div class="ine-label">Frente</div>
                                            </div>
                                        @endif
                                        @if($venta->beneficiario->ine_reverso)
                                            <div class="ine-image">
                                                <img src="{{ Storage::url($venta->beneficiario->ine_reverso) }}" 
                                                     alt="INE Reverso Beneficiario" 
                                                     class="ine-img">
                                                <div class="ine-label">Reverso</div>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i> 
                                        No se encontraron imágenes de INE del beneficiario.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Términos del Crédito -->
                    @if($venta->credito)
                    <div class="info-section">
                        <h6><i class="fas fa-file-signature"></i> Términos del Crédito</h6>
                        <div class="info-table">
                            <table>
                                <tr>
                                    <th>Fecha Inicio:</th>
                                    <td>{{ \Carbon\Carbon::parse($venta->credito->fecha_inicio)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Plazo Financiamiento:</th>
                                    <td>{{ $venta->credito->plazo_financiamiento ?? 'N/A' }} meses</td>
                                </tr>
                                <tr>
                                    <th>Modalidad Pago:</th>
                                    <td>{{ $venta->credito->modalidad_pago ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Formas de Pago:</th>
                                    <td>{{ $venta->credito->formas_pago ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Día de Pago:</th>
                                    <td>{{ $venta->credito->dia_pago ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Monto de pago</th>
                                    <td>{{$venta->credito->pagos ?? 'N/A'}} </td>
                                </tr>
                                @if($venta->credito->observaciones)
                                <tr>
                                    <th>Observaciones:</th>
                                    <td>{{ $venta->credito->observaciones }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    @endif

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
                                @if(pathinfo($venta->ticket_path, PATHINFO_EXTENSION) === 'pdf')
                                    <div class="pdf-preview">
                                        <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                        <div class="pdf-label">Documento PDF</div>
                                    </div>
                                @else
                                    <img src="{{ asset('storage/' . $venta->ticket_path) }}" 
                                        alt="Ticket de enganche" 
                                        class="ticket-preview-img">
                                @endif
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