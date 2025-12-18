@extends('cobranza.navbar')
@section('title', 'Detalle de Venta')
@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/contratos.css') }}">
@endpush

@section('content')
<div class="ventas-content">
    <div class="detail-container">
        <!-- Header -->
        <div class="page-header">
            <div class="header-title">
                <h1>Detalle de Venta</h1>
                <p class="header-subtitle">
                    Información completa de la venta <strong>#{{ $venta->id_venta }}</strong>
                </p>
            </div>
            
            <a href="{{ route('cobranza.ventas.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                <span>Volver al listado</span>
            </a>
        </div>

        <div class="detail-card">
            <div class="detail-card-header">
                <div>
                    <h3 class="detail-card-title">Resumen de la transacción</h3>
                    <div style="margin-top: 8px; display: flex; align-items: center; gap: 12px;">
                        <span class="status-badge status-{{ $venta->estatus }}">
                            {{ ucfirst($venta->estatus) }}
                        </span>
                        <span style="color: var(--text-light); font-size: 14px;">
                            <i class="far fa-calendar"></i> {{ $venta->fechaSolicitud->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
                
                <div style="text-align: right;">
                    <div style="font-size: 24px; font-weight: 700; color: var(--primary);">
                        ${{ number_format($venta->total, 2) }}
                    </div>
                    <div style="font-size: 14px; color: var(--text-light); margin-top: 4px;">
                        Total de la venta
                    </div>
                </div>
            </div>
            
            <div class="detail-grid">
                <!-- Información General -->
                <div class="info-section">
                    <h6><i class="fas fa-info-circle"></i> Información General</h6>
                    <div class="info-table">
                        <table>
                            <tr>
                                <th>ID Venta:</th>
                                <td><strong>#{{ $venta->id_venta }}</strong></td>
                            </tr>
                            <tr>
                                <th>Fecha Solicitud:</th>
                                <td>{{ $venta->fechaSolicitud->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Asesor:</th>
                                <td>{{ $venta->apartado->usuario->nombre }} {{ $venta->apartado->usuario->apellidos }}</td>
                            </tr>
                            <tr>
                                <th>Total Venta:</th>
                                <td>
                                    <span class="monto-destacado">
                                        ${{ number_format($venta->total, 2) }} MXN
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Enganche:</th>
                                <td>
                                    <span class="monto-destacado">
                                        ${{ number_format($venta->enganche, 2) }} MXN
                                    </span>
                                </td>
                            </tr>
                            @if($venta->credito)
                            <tr>
                                <th>Modalidad Pago:</th>
                                <td>
                                    {{ $venta->credito->modalidad_pago ?? 'Contado' }}
                                    @if($venta->credito->plazo_financiamiento)
                                        <span style="color: var(--text-light);">({{ $venta->credito->plazo_financiamiento }} meses)</span>
                                    @endif
                                </td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <!-- Información del Cliente -->
                <div class="info-section">
                    <h6><i class="fas fa-user"></i> Información del Cliente</h6>
                    <div class="info-table">
                        <table>
                            <tr>
                                <th>Nombre completo:</th>
                                <td><strong>{{ $venta->clienteVenta->nombres }} {{ $venta->clienteVenta->apellidos }}</strong></td>
                            </tr>
                            <tr>
                                <th>Información personal:</th>
                                <td>
                                    @if($venta->clienteVenta->edad)
                                        {{ $venta->clienteVenta->edad }} años
                                    @endif
                                    @if($venta->clienteVenta->estado_civil)
                                        • {{ $venta->clienteVenta->estado_civil }}
                                    @endif
                                    @if($venta->clienteVenta->ocupacion)
                                        • {{ $venta->clienteVenta->ocupacion }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Identificación:</th>
                                <td>{{ $venta->clienteVenta->clave_elector ?? 'No especificado' }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $venta->clienteVenta->contacto->email ?? 'No especificado' }}</td>
                            </tr>
                            <tr>
                                <th>Teléfono:</th>
                                <td>{{ $venta->clienteVenta->contacto->telefono ?? 'No especificado' }}</td>
                            </tr>
                            <tr>
                                <th>Domicilio:</th>
                                <td>
                                    {{ $venta->clienteVenta->direccion->nacionalidad ?? '' }}, 
                                    {{ $venta->clienteVenta->direccion->estado ?? '' }}, 
                                    {{ $venta->clienteVenta->direccion->municipio ?? '' }}, 
                                    {{ $venta->clienteVenta->direccion->localidad ?? '' }}
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Fotos INE del Cliente -->
                    <div class="ine-container">
                        <h6 style="font-size: 14px; margin-bottom: 12px;"><i class="fas fa-id-card"></i> INE del Cliente</h6>
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
                            <div class="alert">
                                <i class="fas fa-exclamation-triangle"></i> 
                                <span>No se encontraron imágenes de INE del cliente.</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Beneficiario -->
                @if($venta->beneficiario)
                <div class="info-section">
                    <h6><i class="fas fa-user-friends"></i> Información del Beneficiario</h6>
                    <div class="info-table">
                        <table>
                            <tr>
                                <th>Nombre completo:</th>
                                <td><strong>{{ $venta->beneficiario->nombres }} {{ $venta->beneficiario->apellidos }}</strong></td>
                            </tr>
                            <tr>
                                <th>Teléfono:</th>
                                <td>{{ $venta->beneficiario->telefono ?? 'No especificado' }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Fotos INE del Beneficiario -->
                    <div class="ine-container">
                        <h6 style="font-size: 14px; margin-bottom: 12px;"><i class="fas fa-id-card"></i> INE del Beneficiario</h6>
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
                            <div class="alert">
                                <i class="fas fa-exclamation-triangle"></i> 
                                <span>No se encontraron imágenes de INE del beneficiario.</span>
                            </div>
                        @endif
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
                                <th>Fecha de inicio:</th>
                                <td>{{ \Carbon\Carbon::parse($venta->credito->fecha_inicio)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Plazo financiero:</th>
                                <td>{{ $venta->credito->plazo_financiamiento ?? 'No especificado' }} meses</td>
                            </tr>
                            <tr>
                                <th>Modalidad de pago:</th>
                                <td>{{ $venta->credito->modalidad_pago ?? 'No especificado' }}</td>
                            </tr>
                            <tr>
                                <th>Formas de pago:</th>
                                <td>{{ $venta->credito->formas_pago ?? 'No especificado' }}</td>
                            </tr>
                            <tr>
                                <th>Día de pago:</th>
                                <td>{{ $venta->credito->dia_pago ?? 'No especificado' }}</td>
                            </tr>
                            <tr>
                                <th>Monto de pago:</th>
                                <td>
                                    <span class="monto-destacado">
                                        {{ $venta->credito->pagos ?? 'No especificado' }}
                                    </span>
                                </td>
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
                                <h6><i class="fas fa-tag"></i> Lote: {{ $loteApartado->lote->nombre }}</h6>
                                <div class="info-table">
                                    <table>
                                        <tr>
                                            <th>Fraccionamiento:</th>
                                            <td>{{ $loteApartado->lote->fraccionamiento->nombre }}</td>
                                        </tr>
                                        <tr>
                                            <th>Número de lote:</th>
                                            <td>{{ $loteApartado->lote->numeroLote }}</td>
                                        </tr>
                                        <tr>
                                            <th>Estatus del lote:</th>
                                            <td>
                                                <span class="status-badge status-{{ $loteApartado->lote->estatus === 'vendido' ? 'liquidado' : 'pagos' }}">
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
            </div>
        </div>
    </div>
</div>
@endsection