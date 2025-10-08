@extends('asesor.navbar')

@section('title', 'Nelva Bienes Raíces - Detalle de Venta')

@push('styles')
<link href="{{ asset('css/showVenta.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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