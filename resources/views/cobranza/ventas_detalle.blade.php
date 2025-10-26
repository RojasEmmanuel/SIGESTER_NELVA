@extends('admin.navbar')

@section('title', 'Nelva Bienes Raíces - Detalle de Contrato')

@push('styles')
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<style>
    :root {
        --primary: #1e478a;
        --primary-light: #3d86df;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --text: #334155;
        --text-light: #64748b;
        --border: #e2e8f0;
        --bg-light: #f8fafc;
        --white: #ffffff;
        --shadow: 0 2px 8px rgba(0,0,0,0.1);
        --shadow-hover: 0 4px 12px rgba(0,0,0,0.15);
        --radius: 12px;
        --radius-sm: 8px;
    }

    body {
        font-family: 'Roboto', sans-serif;
        background-color: var(--bg-light);
        color: var(--text);
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 24px 16px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--border);
    }

    .page-title {
        font-size: 28px;
        font-weight: 500;
        color: var(--primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-title .material-icons {
        font-size: 32px;
    }

    .contract-id {
        background-color: var(--primary);
        color: var(--white);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
    }

    .card {
        background-color: var(--white);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .card-header {
        padding: 20px 24px;
        background-color: var(--primary);
        color: var(--white);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-title {
        font-size: 20px;
        font-weight: 500;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .card-content {
        padding: 24px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .info-label {
        font-size: 14px;
        color: var(--text-light);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 16px;
        color: var(--text);
        font-weight: 400;
    }

    .info-value.highlight {
        color: var(--primary);
        font-weight: 500;
        font-size: 18px;
    }

    .table-responsive {
        overflow-x: auto;
        margin-top: 16px;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        background-color: var(--bg-light);
        color: var(--text);
        font-weight: 500;
        text-align: left;
        padding: 16px;
        border-bottom: 2px solid var(--border);
    }

    .data-table td {
        padding: 16px;
        border-bottom: 1px solid var(--border);
    }

    .data-table tr:last-child td {
        border-bottom: none;
    }

    .data-table tr:hover {
        background-color: rgba(30, 71, 138, 0.03);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        text-transform: uppercase;
        gap: 4px;
    }

    .status-pagos {
        background-color: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .status-pendiente {
        background-color: rgba(245, 158, 11, 0.1);
        color: var(--warning);
    }

    .status-rechazado {
        background-color: rgba(239, 68, 68, 0.1);
        color: var(--danger);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 24px;
        border-radius: var(--radius-sm);
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        gap: 8px;
        min-width: 140px;
    }

    .btn-primary {
        background-color: var(--primary);
        color: var(--white);
    }

    .btn-primary:hover {
        background-color: var(--primary-light);
        box-shadow: var(--shadow-hover);
    }

    .btn-success {
        background-color: var(--success);
        color: var(--white);
    }

    .btn-success:hover {
        background-color: #0da271;
        box-shadow: var(--shadow-hover);
    }

    .btn-outline {
        background-color: transparent;
        color: var(--primary);
        border: 1px solid var(--primary);
    }

    .btn-outline:hover {
        background-color: rgba(30, 71, 138, 0.05);
    }

    .actions-container {
        display: flex;
        gap: 16px;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid var(--border);
        flex-wrap: wrap;
    }

    .alert {
        padding: 16px 20px;
        border-radius: var(--radius-sm);
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        background-color: rgba(239, 68, 68, 0.1);
        color: var(--danger);
        border-left: 4px solid var(--danger);
    }

    .alert-icon {
        font-size: 20px;
    }

    .section-divider {
        height: 1px;
        background-color: var(--border);
        margin: 24px 0;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-light);
    }

    .empty-state-icon {
        font-size: 48px;
        color: var(--border);
        margin-bottom: 16px;
    }

    .empty-state-text {
        font-size: 16px;
        margin-bottom: 24px;
    }

    .amount-display {
        font-size: 24px;
        font-weight: 600;
        color: var(--primary);
        margin: 8px 0;
    }

    .amount-label {
        font-size: 14px;
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .actions-container {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .data-table th,
        .data-table td {
            padding: 12px 8px;
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        .container {
            padding: 16px 12px;
        }

        .card-content {
            padding: 16px;
        }

        .page-title {
            font-size: 24px;
        }
    }
</style>
@endpush

@section('content')
<body>
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">
                <span class="material-icons">description</span>
                Contrato de Compraventa
                <span class="contract-id">#{{ $venta->id_venta }}</span>
            </h1>
        </div>

        @if (session('error'))
            <div class="alert">
                <span class="material-icons alert-icon">error</span>
                {{ session('error') }}
            </div>
        @endif

        <!-- Datos del Cliente -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <span class="material-icons">person</span>
                    Datos del Cliente
                </h2>
            </div>
            <div class="card-content">
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Nombre Completo</span>
                        <span class="info-value highlight">{{ $venta->clienteVenta->nombres }} {{ $venta->clienteVenta->apellidos }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Edad</span>
                        <span class="info-value">{{ $venta->clienteVenta->edad }} años</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Estado Civil</span>
                        <span class="info-value">{{ $venta->clienteVenta->estado_civil }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Ocupación</span>
                        <span class="info-value">{{ $venta->clienteVenta->ocupacion }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Lugar de Origen</span>
                        <span class="info-value">{{ $venta->clienteVenta->lugar_origen }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Teléfono</span>
                        <span class="info-value">{{ $venta->clienteVenta->contacto->telefono ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $venta->clienteVenta->contacto->email ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Dirección</span>
                        <span class="info-value">
                            {{ $venta->clienteVenta->direccion ? 
                                "{$venta->clienteVenta->direccion->nacionalidad}, {$venta->clienteVenta->direccion->estado}, {$venta->clienteVenta->direccion->municipio}, {$venta->clienteVenta->direccion->localidad}" : 
                                'N/A' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Datos del Beneficiario -->
        @if ($venta->beneficiario)
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        <span class="material-icons">person_outline</span>
                        Datos del Beneficiario
                    </h2>
                </div>
                <div class="card-content">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Nombre Completo</span>
                            <span class="info-value highlight">{{ $venta->beneficiario->nombres }} {{ $venta->beneficiario->apellidos }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Teléfono</span>
                            <span class="info-value">{{ $venta->beneficiario->telefono }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Datos de la Venta -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <span class="material-icons">receipt_long</span>
                    Datos de la Venta
                </h2>
            </div>
            <div class="card-content">
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">ID Venta</span>
                        <span class="info-value">{{ $venta->id_venta }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Fecha de Solicitud</span>
                        <span class="info-value">{{ $venta->fechaSolicitud->format('d/m/Y') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Estatus</span>
                        <span class="status-badge status-{{ strtolower($venta->estatus) }}">
                            <span class="material-icons" style="font-size: 14px;">
                                @if(strtolower($venta->estatus) === 'pagos') check_circle
                                @elseif(strtolower($venta->estatus) === 'pendiente') schedule
                                @elseif(strtolower($venta->estatus) === 'rechazado') cancel
                                @else info
                                @endif
                            </span>
                            {{ $venta->estatus }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Enganche</span>
                        <div class="amount-display">$ {{ number_format($venta->enganche, 2) }}</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Total del Contrato</span>
                        <div class="amount-display">$ {{ number_format($venta->total, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Datos del Crédito -->
        @if ($venta->credito)
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        <span class="material-icons">credit_card</span>
                        Datos del Crédito
                    </h2>
                </div>
                <div class="card-content">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Plazo de Financiamiento</span>
                            <span class="info-value">{{ $venta->credito->plazo_financiamiento }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Modalidad de Pago</span>
                            <span class="info-value">{{ $venta->credito->modalidad_pago }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Día de Pago</span>
                            <span class="info-value">{{ $venta->credito->dia_pago }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Fecha de Inicio</span>
                            <span class="info-value">{{ $venta->credito->fecha_inicio->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Lotes -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <span class="material-icons">map</span>
                    Lotes del Contrato
                </h2>
            </div>
            <div class="card-content">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Número de Lote</th>
                                <th>Fraccionamiento</th>
                                <th>Área (m²)</th>
                                <th>Precio por m²</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($venta->apartado->lotesApartados as $loteApartado)
                                <tr>
                                    <td>
                                        <strong>{{ $loteApartado->lote->numeroLote }}</strong>
                                    </td>
                                    <td>{{ $loteApartado->lote->fraccionamiento->nombre ?? 'N/A' }}</td>
                                    <td>{{ $loteApartado->lote->loteMedida->area_total ?? 'N/A' }} m²</td>
                                    <td>$ {{ number_format($loteApartado->lote->precio_m2 ?? 0, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Datos del Asesor -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <span class="material-icons">badge</span>
                    Datos del Asesor
                </h2>
            </div>
            <div class="card-content">
                <div class="info-item">
                    <span class="info-label">Asesor Responsable</span>
                    <span class="info-value highlight">{{ $venta->apartado->usuario->nombre }} {{ $venta->apartado->usuario->apellidos }}</span>
                </div>
            </div>
        </div>

        <!-- Acciones -->
        <div class="actions-container">
            <a href="{{ route('cobranza.ventas.contrato', $venta->id_venta) }}" class="btn btn-success">
                <span class="material-icons">picture_as_pdf</span>
                Generar PDF
            </a>
            <a href="{{ route('cobranza.ventas.index') }}" class="btn btn-primary">
                <span class="material-icons">arrow_back</span>
                Volver al Listado
            </a>
        </div>
    </div>
</body>
@endsection