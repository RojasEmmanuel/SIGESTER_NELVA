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

        <!-- ESTADÍSTICAS PRINCIPALES: ENFOCADAS EN PAGOS, LIQUIDADAS Y RETRASADAS -->
        <div class="stats-container primary-stats">
            <!-- EN PAGOS (PRIORIDAD 1) -->
            <div class="stat-card pending highlight">
                <h3 class="stat-card-title">
                    <i class="fas fa-clock"></i>
                    <span>En Pagos</span>
                </h3>
                <p class="stat-card-value large">{{ $enPagos }}</p>
            </div>

            

            <!-- RETRASADAS (PRIORIDAD 3) -->
            <div class="stat-card delayed highlight">
                <h3 class="stat-card-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Retrasadas</span>
                </h3>
                <p class="stat-card-value large">{{ $retrasadas }}</p>
                
            </div>

            <!-- LIQUIDADAS (PRIORIDAD 2) -->
            <div class="stat-card completed highlight">
                <h3 class="stat-card-title">
                    <i class="fas fa-check-circle"></i>
                    <span>Liquidadas</span>
                </h3>
                <p class="stat-card-value large">{{ $liquidadas }}</p>
            </div>

            <!-- TOTAL VENTAS (SECUNDARIO, COMO CONTEXTO) -->
            <div class="stat-card total secondary">
                <h3 class="stat-card-title">
                    <i class="fas fa-chart-bar"></i>
                    <span>Total Ventas</span>
                </h3>
                <p class="stat-card-value">{{ $totalVentas }}</p>
            </div>
        </div>

       

        <!-- Sales Grid (sin cambios, solo se muestra debajo) -->
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
                                <span class="detail-label">Cliente</span>
                                <span class="detail-value highlight">{{ $venta->clienteVenta->nombres ?? 'N/A' }} {{ $venta->clienteVenta->apellidos ?? '' }}</span>
                            </div>
                            
                            <div class="detail-group">
                                <span class="detail-label">Asesor</span>
                                <span class="detail-value">{{ $venta->apartado->usuario?->nombre ?? 'N/A' }}</span>
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
            @if ($ventas->onFirstPage())
                <span class="prev-btn disabled">
                    <i class="fas fa-chevron-left"></i> Anterior
                </span>
            @else
                <a href="{{ $ventas->previousPageUrl() }}" class="prev-btn">
                    <i class="fas fa-chevron-left"></i> Anterior
                </a>
            @endif

            @if ($ventas->hasMorePages())
                <a href="{{ $ventas->nextPageUrl() }}" class="next-btn">
                    Siguiente <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <span class="next-btn disabled">
                    Siguiente <i class="fas fa-chevron-right"></i>
                </span>
            @endif
        </div>
    </div>

    <!-- Script de filtrado (opcional: puedes mejorarlo para incluir "retrasadas") -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterSelects = document.querySelectorAll('.filter-select');
            
            filterSelects.forEach(select => {
                select.addEventListener('change', function() {
                    const status = this.value.toLowerCase();
                    const cards = document.querySelectorAll('.sale-card');
                    
                    cards.forEach(card => {
                        const cardStatus = card.querySelector('.sale-status').textContent.trim().toLowerCase();
                        if (status === '' || cardStatus === status || (status === 'retrasadas' && cardStatus === 'retrasada')) {
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