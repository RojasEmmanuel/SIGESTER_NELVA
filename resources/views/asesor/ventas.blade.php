@extends('asesor.navbar')

@section('title', 'Nelva Bienes Raíces - Ventas')

@push('styles')
<link href="{{ asset('css/ventasAsesor.css') }}" rel="stylesheet">
@endpush

@section('content')
    <!-- Main Content -->
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-history"></i>
                <span>Historial de Ventas</span>
            </h1>
            <a href="{{ route('ventas.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Crear Nueva Venta
            </a>
        </div>

        <!-- Stats Overview -->
        <div class="stats-container">
            <div class="stat-card total">
                <h3 class="stat-card-title">
                    <i class="fas fa-chart-bar"></i>
                    <span>Total Ventas</span>
                </h3>
                <p class="stat-card-value">{{ $totalVentas }}</p>
                <p class="stat-card-description">Transacciones</p>
            </div>
            <div class="stat-card completed">
                <h3 class="stat-card-title">
                    <i class="fas fa-check-circle"></i>
                    <span>Liquidados</span>
                </h3>
                <p class="stat-card-value">{{ $liquidadas }}</p>
                <p class="stat-card-description">{{ $porcentajeLiquidadas }}% del total</p>
            </div>
            <div class="stat-card pending">
                <h3 class="stat-card-title">
                    <i class="fas fa-clock"></i>
                    <span>En Pagos</span>
                </h3>
                <p class="stat-card-value">{{ $enPagos }}</p>
                <p class="stat-card-description">{{ $porcentajeEnPagos }}% del total</p>
            </div>
            <div class="stat-card cancelled">
                <h3 class="stat-card-title">
                    <i class="fas fa-times-circle"></i>
                    <span>Cancelados</span>
                </h3>
                <p class="stat-card-value">{{ $canceladas }}</p>
                <p class="stat-card-description">{{ $porcentajeCanceladas }}% del total</p>
            </div>
        </div>

        <!-- Sales Grid -->
        <div class="sales-grid">
            @foreach ($ventas as $venta)
                <div class="sale-card">
                    <div class="sale-header">
                        <h3 class="sale-title">
                            <i class="fas fa-home"></i>
                            <span>
                                @foreach ($venta->apartado->lotesApartados as $lote)
                                    {{ $lote->id_lote }}{{ $loop->last ? '' : ', ' }}
                                @endforeach
                            </span>
                        </h3>
                        <span class="sale-status status-{{ strtolower($venta->estatus) }}">{{ ucfirst($venta->estatus) }}</span>
                    </div>
                    <div class="sale-body">
                        <div class="sale-details">
                            <div class="detail-group">
                                <span class="detail-label">Asesor</span>
                                <span class="detail-value highlight">{{ $venta->apartado->usuario ? $venta->apartado->usuario->nombre : 'N/A' }}</span>
                            </div>
                            <div class="detail-group">
                                <span class="detail-label">Cliente</span>
                                <span class="detail-value">{{ $venta->clienteVenta->nombres ?? 'N/A' }} {{ $venta->clienteVenta->apellidos ?? '' }}</span>
                            </div>
                            <div class="detail-group">
                                <span class="detail-label">Precio</span>
                                <span class="detail-value">${{ number_format($venta->total, 2) }} MXN</span>
                            </div>
                            <div class="detail-group">
                                <span class="detail-label">Método de pago</span>
                                <span class="detail-value">
                                    {{ $venta->credito->modalidad_pago ?? 'Contado' }}
                                    @if ($venta->credito && $venta->credito->plazo_financiamiento)
                                        ({{ $venta->credito->plazo_financiamiento }} meses)
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="sale-footer">
                        <span class="sale-date">
                            <i class="far fa-calendar-alt"></i>
                            <span>{{ \Carbon\Carbon::parse($venta->fechaSolicitud)->format('d/m/Y') }}</span>
                        </span>
                        <button class="btn btn-primary btn-sm" onclick="window.location.href='{{ route('ventas.show', $venta->id_venta) }}'">
                            <i class="fas fa-eye"></i> Detalles
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="pagination">
            {{ $ventas->links() }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterSelects = document.querySelectorAll('.filter-select');
            
            filterSelects.forEach(select => {
                select.addEventListener('change', function() {
                    const status = this.value;
                    const cards = document.querySelectorAll('.sale-card');
                    
                    cards.forEach(card => {
                        const cardStatus = card.querySelector('.sale-status').textContent.toLowerCase();
                        if (status === '' || cardStatus === status) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>

@endsection