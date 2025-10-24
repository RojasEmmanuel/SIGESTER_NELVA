@extends('cobranza.navbar')

@section('title', 'Nelva Bienes Raíces - Contratos Pendientes')
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
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--border);
    }

    .page-title {
        font-size: 28px;
        font-weight: 500;
        color: var(--primary);
        margin: 0;
    }

    .card {
        background-color: var(--white);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .card-header {
        padding: 16px 24px;
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
    }

    .card-content {
        padding: 24px;
    }

    .table-responsive {
        overflow-x: auto;
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
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        text-transform: uppercase;
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
        padding: 8px 16px;
        border-radius: var(--radius-sm);
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        gap: 8px;
    }

    .btn-primary {
        background-color: var(--primary);
        color: var(--white);
    }

    .btn-primary:hover {
        background-color: var(--primary-light);
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

    .btn-icon {
        padding: 8px;
        border-radius: 50%;
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

    .pagination-container {
        display: flex;
        justify-content: center;
        margin-top: 24px;
    }

    .pagination {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        border-radius: var(--radius-sm);
        overflow: hidden;
        box-shadow: var(--shadow);
    }

    .pagination li {
        margin: 0;
    }

    .pagination li a,
    .pagination li span {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 8px 16px;
        background-color: var(--white);
        color: var(--text);
        text-decoration: none;
        border-right: 1px solid var(--border);
        transition: all 0.2s ease;
    }

    .pagination li:last-child a,
    .pagination li:last-child span {
        border-right: none;
    }

    .pagination li a:hover {
        background-color: var(--bg-light);
    }

    .pagination li.active span {
        background-color: var(--primary);
        color: var(--white);
    }

    .alert {
        padding: 12px 16px;
        border-radius: var(--radius-sm);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-error {
        background-color: rgba(239, 68, 68, 0.1);
        color: var(--danger);
        border-left: 4px solid var(--danger);
    }

    .alert-icon {
        font-size: 20px;
    }

    .filters {
        display: flex;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .filter-label {
        font-size: 14px;
        color: var(--text-light);
        font-weight: 500;
    }

    .filter-select {
        padding: 10px 12px;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        background-color: var(--white);
        font-size: 14px;
        color: var(--text);
        min-width: 180px;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }

        .filters {
            flex-direction: column;
        }

        .filter-select {
            min-width: 100%;
        }

        .data-table th,
        .data-table td {
            padding: 12px 8px;
        }
    }
</style>
@endpush

@section('content')
<body>
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Contratos Pendientes</h1>
            <div class="actions">
                <button class="btn btn-outline">
                    <span class="material-icons">file_download</span>
                    Exportar
                </button>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-error">
                <span class="material-icons alert-icon">error</span>
                {{ session('error') }}
            </div>
        @endif

        <div class="filters">
            <div class="filter-group">
                <label class="filter-label">Filtrar por estatus</label>
                <select class="filter-select">
                    <option value="all">Todos los estatus</option>
                    <option value="pagos">Pagos</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="rechazado">Rechazado</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Ordenar por</label>
                <select class="filter-select">
                    <option value="fecha">Fecha de solicitud</option>
                    <option value="cliente">Cliente</option>
                    <option value="total">Total</option>
                </select>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Listado de Ventas</h2>
                <span class="material-icons">receipt_long</span>
            </div>
            <div class="card-content">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID Venta</th>
                                <th>Cliente</th>
                                <th>Fecha Solicitud</th>
                                <th>Enganche</th>
                                <th>Total</th>
                                <th>Estatus</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ventas as $venta)
                                <tr>
                                    <td>{{ $venta->id_venta }}</td>
                                    <td>{{ $venta->clienteVenta->nombres }} {{ $venta->clienteVenta->apellidos }}</td>
                                    <td>{{ $venta->fechaSolicitud->format('d/m/Y') }}</td>
                                    <td>$ {{ number_format($venta->enganche, 2) }}</td>
                                    <td>$ {{ number_format($venta->total, 2) }}</td>
                                    <td>
                                        <span class="status-badge status-{{ strtolower($venta->estatus) }}">
                                            {{ $venta->estatus }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('cobranza.ventas.show', $venta->id_venta) }}" class="btn btn-primary">
                                            <span class="material-icons">visibility</span>
                                            Ver Detalles
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <span class="material-icons empty-state-icon">inventory_2</span>
                                            <p class="empty-state-text">No hay ventas disponibles con estatus 'pagos' y ticket 'aceptado'.</p>
                                            <button class="btn btn-primary">
                                                <span class="material-icons">refresh</span>
                                                Actualizar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($ventas->count() > 0)
                <div class="pagination-container">
                    {{ $ventas->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</body>
@endsection