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

@section('title', 'Nelva Bienes Raíces - Apartados')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
<link href="{{ asset('css/ApartadoAsesor.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container">
    <!-- Header con estadísticas -->
    <div class="page-header">
        <div class="header-main">
            <h1 class="page-title">
                <i class="bi bi-journal-text"></i>
                Mis Apartados
            </h1>
            <p class="page-subtitle">Gestiona tus apartados activos, vencidos y vendidos</p>
        </div>
        <div class="header-stats">
            <span class="stat-item">Total: {{ $totalApartados }}</span>
            <span class="stat-item">En curso: {{ $enCurso }}</span>
            <span class="stat-item">Vencidos: {{ $vencidos }}</span>
            <span class="stat-item">Vendidos: {{ $vendidos }}</span>
        </div>
    </div>

    @if($apartados->count() > 0)
    <!-- Filtros -->
    <div class="filters-bar">
        <div class="filter-group">
            <label>Estado:</label>
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="todos">Todos</button>
                <button class="filter-btn" data-filter="en curso">En curso</button>
                <button class="filter-btn" data-filter="vencido">Vencidos</button>
                <button class="filter-btn" data-filter="venta">Vendidos</button>
            </div>
        </div>
        <div class="filter-group">
            <label>Tipo:</label>
            <div class="filter-buttons">
                <button class="filter-btn active" data-type="todos">Todos</button>
                <button class="filter-btn" data-type="palabra">Palabra</button>
                <button class="filter-btn" data-type="deposito">Depósito</button>
            </div>
        </div>
    </div>
    @endif

    <!-- Grid de Tarjetas Horizontal -->
    <div class="cards-container">
        @if($apartados->count() > 0)
            @foreach($apartados as $apartado)
                @php
                    try {
                        $hoy = \Carbon\Carbon::now('America/Mexico_City');
                        $vencimiento = !empty($apartado->fechaVencimiento) ? \Carbon\Carbon::parse($apartado->fechaVencimiento) : null;
                        $fechaApartado = !empty($apartado->fechaApartado) ? \Carbon\Carbon::parse($apartado->fechaApartado) : null;

                        $estadoClass = match ($apartado->estatus) {
                            'en curso' => 'activo',
                            'vencido' => 'vencido',
                            'venta' => 'vendido',
                            default => 'error',
                        };

                        $estadoDisplay = match ($apartado->estatus) {
                            'en curso' => 'En curso',
                            'vencido' => 'Vencido',
                            'venta' => 'Vendido',
                            default => 'Error',
                        };

                        if ($apartado->estatus === 'en curso' && $vencimiento) {
                            $diferencia = $vencimiento->diff($hoy);
                            $diasRestantes = $diferencia->days;
                            $horasRestantes = $diferencia->h;
                            $minutosRestantes = $diferencia->i;
                            $segundosRestantes = $diferencia->s;

                            $partes = [];
                            if ($diasRestantes > 0) {
                                $partes[] = $diasRestantes . 'd';
                            }
                            if ($horasRestantes > 0 || $diasRestantes > 0) {
                                $partes[] = $horasRestantes . 'h';
                            }
                            $partes[] = $minutosRestantes . 'm';

                            if ($diasRestantes == 0 && $horasRestantes == 0 && $minutosRestantes < 5) {
                                $partes[] = $segundosRestantes . 's';
                            }

                            $tiempoRestante = implode(' ', $partes);
                            if (empty($tiempoRestante)) {
                                $tiempoRestante = 'Menos de 1m';
                            }

                            $totalHorasRestantes = ($diasRestantes * 24) + $horasRestantes + ($minutosRestantes / 60);
                            if ($totalHorasRestantes <= 12) {
                                $tiempoClass = 'text-danger';
                            } elseif ($totalHorasRestantes <= 48) {
                                $tiempoClass = 'text-warning';
                            } elseif ($totalHorasRestantes <= 120) {
                                $tiempoClass = 'text-warning-light';
                            } else {
                                $tiempoClass = 'text-success';
                            }
                        } else {
                            $tiempoRestante = $apartado->estatus === 'vencido' ? 'Vencido' : 'Vendido';
                            $tiempoClass = $apartado->estatus === 'vencido' ? 'text-danger' : 'text-success';
                        }

                        $fechaApartadoFormatted = $fechaApartado ? $fechaApartado->isoFormat('D MMM YYYY, h:mm:ss A') : 'N/A';
                        $fechaVencimientoFormatted = $vencimiento ? $vencimiento->isoFormat('D MMM YYYY, h:mm:ss A') : 'N/A';
                    } catch (\Exception $e) {
                        $tiempoRestante = 'Error en fecha';
                        $tiempoClass = 'text-danger';
                        $estadoClass = 'error';
                        $estadoDisplay = 'Error';
                        $fechaApartadoFormatted = 'N/A';
                        $fechaVencimientoFormatted = 'N/A';
                    }
                @endphp

                <div class="apartado-card {{ $estadoClass }}" data-estado="{{ $apartado->estatus }}" data-tipo="{{ $apartado->tipoApartado }}">
                    <!-- Header compacto -->
                    <div class="card-header-compact">
                        <div class="client-info-compact">
                            <div class="client-avatar-mini">
                                <i class="bi bi-person"></i>
                            </div>
                            <div class="client-details">
                                <h3 class="client-name-compact">{{ $apartado->cliente_nombre }} {{ $apartado->cliente_apellidos }}</h3>
                                <div class="client-meta">
                                    <span class="meta-item">
                                        <i class="bi bi-calendar-check"></i>
                                        {{ $fechaApartadoFormatted }}
                                    </span>
                                    <span class="meta-item">
                                        <i class="bi bi-calendar-x {{ $apartado->estatus === 'vencido' ? 'text-danger' : ($apartado->estatus === 'venta' ? 'text-success' : 'text-success') }}"></i>
                                        {{ $fechaVencimientoFormatted }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="header-badges">
                            <span class="type-badge {{ $apartado->tipoApartado }}">
                                {{ $apartado->tipoApartado == 'palabra' ? 'Palabra' : 'Depósito' }}
                            </span>
                            <span class="status-badge {{ $estadoClass }}">
                                <i class="bi bi-{{ $apartado->estatus === 'vencido' ? 'x-circle' : ($apartado->estatus === 'venta' ? 'check-circle-fill' : 'check-circle') }}"></i>
                                {{ $estadoDisplay }}
                            </span>
                        </div>
                    </div>

                    <!-- Contenido horizontal -->
                    <div class="card-content-horizontal">
                        <div class="time-section">
                            <div class="time-display {{ $tiempoClass }}">
                                <i class="bi bi-clock"></i>
                                <span class="time-value">{{ $tiempoRestante }}</span>
                            </div>
                            <div class="time-label">Tiempo restante</div>
                        </div>

                        <div class="divider"></div>

                        <div class="lotes-section-compact">
                            <div class="lotes-header">
                                <i class="bi bi-map"></i>
                                <span>Lotes ({{ $apartado->lotesApartados && $apartado->lotesApartados->count() > 0 ? $apartado->lotesApartados->count() : 0 }})</span>
                            </div>
                            <div class="lotes-list-compact">
                                @if($apartado->lotesApartados && $apartado->lotesApartados->count() > 0)
                                    @foreach($apartado->lotesApartados->take(2) as $lote)
                                        <div class="lote-item-compact">
                                            <i class="bi bi-geo-alt"></i>
                                            <div class="lote-info-compact">
                                                <span class="lote-number">Lote {{ $lote->lote->numeroLote ?? 'N/A' }}</span>
                                                <span class="fraccionamiento-name">{{ $lote->lote->fraccionamiento->nombre ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($apartado->lotesApartados->count() > 2)
                                        <div class="lote-more">+{{ $apartado->lotesApartados->count() - 2 }} más</div>
                                    @endif
                                @else
                                    <div class="no-lotes-compact">Sin lotes</div>
                                @endif
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div class="actions-section">
                            <div class="asesor-info">
                                <i class="bi bi-person-badge"></i>
                                <span>{{ $apartado->usuario->nombre ?? 'N/A' }}</span>
                            </div>
                            <a href="{{ route('asesor.apartados.show', $apartado->id_apartado) }}" class="view-btn-compact">
                                <i class="bi bi-eye"></i>
                                Detalles
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-inbox"></i>
                </div>
                <h3>No hay apartados</h3>
                <p>No se encontraron apartados registrados</p>
            </div>
        @endif
    </div>

    <div id="no-results" class="empty-state" style="display: none;">
        <div class="empty-icon">
            <i class="bi bi-search"></i>
        </div>
        <h3>Sin resultados</h3>
        <p>Prueba con otros filtros</p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.apartado-card');
    
    if (cards.length > 0) {
        const filterBtns = document.querySelectorAll('.filter-btn');
        const noResults = document.getElementById('no-results');

        function applyFilters() {
            const estadoFilter = document.querySelector('.filter-btn[data-filter].active')?.dataset.filter || 'todos';
            const tipoFilter = document.querySelector('.filter-btn[data-type].active')?.dataset.type || 'todos';

            let visibleCount = 0;

            cards.forEach(card => {
                const cardEstado = card.dataset.estado;
                const cardTipo = card.dataset.tipo;

                const matchEstado = estadoFilter === 'todos' || estadoFilter === cardEstado;
                const matchTipo = tipoFilter === 'todos' || tipoFilter === cardTipo;

                if (matchEstado && matchTipo) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            noResults.style.display = visibleCount === 0 ? 'flex' : 'none';
        }

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const group = this.closest('.filter-buttons');
                group.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                applyFilters();
            });
        });

        applyFilters();
    }
});
</script>
@endsection