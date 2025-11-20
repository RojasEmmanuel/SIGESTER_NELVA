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

@section('title', 'Nelva Bienes Raíces - Detalle de Venta')

@push('styles')
<link href="{{ asset('css/showVenta.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
    .image-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }
    .image-item {
        max-width: 200px;
        border: 1px solid #ddd;
        border-radius: 5px;
        overflow: hidden;
    }
    .image-item img {
        width: 100%;
        height: auto;
        display: block;
    }
    .image-item .image-label {
        padding: 5px;
        text-align: center;
        background-color: #f8f9fa;
        font-size: 0.9em;
        color: #333;
    }
    .image-preview {
        cursor: pointer;
        transition: transform 0.2s;
    }
    .image-preview:hover {
        transform: scale(1.05);
    }
    .no-image {
        color: #888;
        font-style: italic;
    }
    .ticket-upload-form {
        margin-top: 10px;
    }
    .ticket-upload-form .form-group {
        margin-bottom: 15px;
    }
    .ticket-upload-form .form-control {
        width: 100%;
    }
    .ticket-upload-form .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }
    .ticket-upload-form .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }
</style>
@endpush

@section('content')
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-file-contract"></i>
                <span>Detalle de Venta</span>
            </h1>
            <a href="{{ route('ventas.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Listado
            </a>
        </div>

        <div class="sale-detail-container">
            <!-- Header -->
            <div class="sale-header">
                <div class="sale-id">Venta #{{ $venta->id_venta }}</div>
                <div class="sale-meta">
                    <span class="sale-status status-{{ strtolower($venta->estatus) }}">
                        <i class="fas fa-circle"></i>
                        {{ ucfirst($venta->estatus) }}
                    </span>
                    <span class="sale-date">
                        <i class="far fa-calendar"></i>
                        {{ \Carbon\Carbon::parse($venta->fechaSolicitud)->format('d/m/Y') }}
                    </span>
                </div>
            </div>

            <!-- Content -->
            <div class="sale-content">
                <!-- Información General -->
                <div class="section">
                    <div class="section-header">
                        <i class="fas fa-chart-bar"></i>
                        <h3 class="section-title">Resumen de la Venta</h3>
                    </div>
                    <div class="details-grid">
                        <div class="detail-item financial-highlight">
                            <span class="detail-label">Inversión Total</span>
                            <span class="detail-value highlight">${{ number_format($venta->total, 2) }} MXN</span>
                        </div>
                        <div class="detail-item financial-highlight">
                            <span class="detail-label">Enganche</span>
                            <span class="detail-value highlight">${{ number_format($venta->enganche, 2) }} MXN</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Lotes Incluidos</span>
                            <span class="detail-value">
                                @foreach ($venta->apartado->lotesApartados as $lote)
                                    <span class="lote-tag">#{{ $lote->id_lote }}</span>{{ $loop->last ? '' : ', ' }}
                                @endforeach
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Asesor Comercial</span>
                            <span class="detail-value highlight">{{ $venta->apartado->usuario ? $venta->apartado->usuario->nombre : 'N/A' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Modalidad de Pago</span>
                            <span class="detail-value">
                                {{ $venta->credito->modalidad_pago ?? 'Contado' }}
                                @if ($venta->credito && $venta->credito->plazo_financiamiento)
                                    ({{ $venta->credito->plazo_financiamiento }} meses)
                                @endif
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Ticket de Enganche</span>
                            <span class="detail-value">
                                @if ($venta->ticket_path)
                                    <div class="image-container">
                                        <div class="image-item">
                                            @if (pathinfo($venta->ticket_path, PATHINFO_EXTENSION) === 'pdf')
                                                <a href="{{ Storage::url($venta->ticket_path) }}" target="_blank" class="image-preview">
                                                    <i class="fas fa-file-pdf fa-3x"></i>
                                                </a>
                                                <div class="image-label">Ver PDF</div>
                                            @else
                                                <a href="{{ Storage::url($venta->ticket_path) }}" target="_blank" class="image-preview">
                                                    <img src="{{ Storage::url($venta->ticket_path) }}" alt="Ticket de Enganche">
                                                </a>
                                                <div class="image-label">Ticket</div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <span class="no-image">No disponible</span>
                                @endif
                                @if ($venta->ticket_estatus === 'rechazado')
                                    <form action="{{ route('ventas.updateTicket', $venta->id_venta) }}" method="POST" enctype="multipart/form-data" class="ticket-upload-form">
                                        @csrf
                                        @method('PATCH')
                                        <div class="form-group">
                                            <label for="new_ticket_path">Subir Nuevo Ticket <span class="required">*</span></label>
                                            <input type="file" name="new_ticket_path" id="new_ticket_path" class="form-control" accept=".pdf,.jpg,.png" required>
                                            <div class="helper-text">Formatos aceptados: PDF, JPG, PNG. Tamaño máximo: 5MB</div>
                                            @error('new_ticket_path')
                                                <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-upload"></i> Actualizar Ticket
                                        </button>
                                    </form>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Información del Cliente -->
                <div class="section">
                    <div class="section-header">
                        <i class="fas fa-user-tie"></i>
                        <h3 class="section-title">Datos del Cliente</h3>
                    </div>
                    <div class="details-grid">
                        <div class="detail-item">
                            <span class="detail-label">Nombre Completo</span>
                            <span class="detail-value highlight">{{ $venta->clienteVenta->nombres ?? 'N/A' }} {{ $venta->clienteVenta->apellidos ?? '' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Información Personal</span>
                            <span class="detail-value">
                                {{ $venta->clienteVenta->edad ?? 'N/A' }} años • 
                                {{ $venta->clienteVenta->estado_civil ?? 'N/A' }} • 
                                {{ $venta->clienteVenta->ocupacion ?? 'N/A' }}
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Identificación</span>
                            <span class="detail-value">{{ $venta->clienteVenta->clave_elector ?? 'N/A' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Contacto</span>
                            <span class="detail-value">
                                <i class="fas fa-phone"></i> {{ $venta->clienteVenta->contacto->telefono ?? 'N/A' }}<br>
                                <i class="fas fa-envelope"></i> {{ $venta->clienteVenta->contacto->email ?? 'N/A' }}
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Domicilio</span>
                            <span class="detail-value">
                                {{ $venta->clienteVenta->direccion->nacionalidad ?? 'N/A' }}, 
                                {{ $venta->clienteVenta->direccion->estado ?? 'N/A' }}, 
                                {{ $venta->clienteVenta->direccion->municipio ?? 'N/A' }}, 
                                {{ $venta->clienteVenta->direccion->localidad ?? 'N/A' }}
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Fotos INE</span>
                            <span class="detail-value">
                                <div class="image-container">
                                    @if ($venta->clienteVenta->ine_frente)
                                        <div class="image-item">
                                            <a href="{{ Storage::url($venta->clienteVenta->ine_frente) }}" target="_blank" class="image-preview">
                                                <img src="{{ Storage::url($venta->clienteVenta->ine_frente) }}" alt="INE Frente">
                                            </a>
                                            <div class="image-label">Frente</div>
                                        </div>
                                    @else
                                        <span class="no-image">INE Frente no disponible</span>
                                    @endif
                                    @if ($venta->clienteVenta->ine_reverso)
                                        <div class="image-item">
                                            <a href="{{ Storage::url($venta->clienteVenta->ine_reverso) }}" target="_blank" class="image-preview">
                                                <img src="{{ Storage::url($venta->clienteVenta->ine_reverso) }}" alt="INE Reverso">
                                            </a>
                                            <div class="image-label">Reverso</div>
                                        </div>
                                    @else
                                        <span class="no-image">INE Reverso no disponible</span>
                                    @endif
                                </div>
                            </span>
                        </div>
                    </div>
                </div>

                @if ($venta->beneficiario)
                <div class="section">
                    <div class="section-header">
                        <i class="fas fa-user-friends"></i>
                        <h3 class="section-title">Beneficiario Designado</h3>
                    </div>
                    <div class="details-grid">
                        <div class="detail-item">
                            <span class="detail-label">Nombre Completo</span>
                            <span class="detail-value highlight">{{ $venta->beneficiario->nombres ?? 'N/A' }} {{ $venta->beneficiario->apellidos ?? '' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Contacto</span>
                            <span class="detail-value">
                                <i class="fas fa-phone"></i> {{ $venta->beneficiario->telefono ?? 'N/A' }}
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Fotos INE</span>
                            <span class="detail-value">
                                <div class="image-container">
                                    @if ($venta->beneficiario->ine_frente)
                                        <div class="image-item">
                                            <a href="{{ Storage::url($venta->beneficiario->ine_frente) }}" target="_blank" class="image-preview">
                                                <img src="{{ Storage::url($venta->beneficiario->ine_frente) }}" alt="INE Frente Beneficiario">
                                            </a>
                                            <div class="image-label">Frente</div>
                                        </div>
                                    @else
                                        <span class="no-image">INE Frente no disponible</span>
                                    @endif
                                    @if ($venta->beneficiario->ine_reverso)
                                        <div class="image-item">
                                            <a href="{{ Storage::url($venta->beneficiario->ine_reverso) }}" target="_blank" class="image-preview">
                                                <img src="{{ Storage::url($venta->beneficiario->ine_reverso) }}" alt="INE Reverso Beneficiario">
                                            </a>
                                            <div class="image-label">Reverso</div>
                                        </div>
                                    @else
                                        <span class="no-image">INE Reverso no disponible</span>
                                    @endif
                                </div>
                            </span>
                        </div>
                    </div>
                </div>
                @endif

                @if ($venta->credito)
                <div class="section">
                    <div class="section-header">
                        <i class="fas fa-file-signature"></i>
                        <h3 class="section-title">Términos del Crédito</h3>
                    </div>
                    <div class="details-grid">
                        <div class="detail-item">
                            <span class="detail-label">Fecha de Inicio</span>
                            <span class="detail-value">{{ \Carbon\Carbon::parse($venta->credito->fecha_inicio)->format('d/m/Y') }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Plazo Financiamiento</span>
                            <span class="detail-value highlight">{{ $venta->credito->plazo_financiamiento ?? 'N/A' }} meses</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Modalidad de Pago</span>
                            <span class="detail-value">{{ $venta->credito->modalidad_pago ?? 'N/A' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Formas de Pago</span>
                            <span class="detail-value">{{ $venta->credito->formas_pago ?? 'N/A' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Día de Pago</span>
                            <span class="detail-value">{{ $venta->credito->dia_pago ?? 'N/A' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Monto a pagar</span>
                            <span class="detail value"> {{$venta->credito->pagos ?? 'N/A'}} </span>
                        </div>
                        @if($venta->credito->observaciones)
                        <div class="detail-item">
                            <span class="detail-label">Observaciones</span>
                            <span class="detail-value">{{ $venta->credito->observaciones }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection