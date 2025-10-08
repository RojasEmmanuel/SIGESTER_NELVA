@extends('asesor.navbar')

@section('title', 'Nelva Bienes Raíces - Apartados')

@push('styles')
<!-- Agregar Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
<link href="{{ asset('css/ApartadoAsesor.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container">
    <!-- Header Simple -->
    <div class="page-header">
        <div class="header-main">
            <h1 class="page-title">
                <i class="bi bi-journal-text"></i>
                Mis Apartados
            </h1>
            <p class="page-subtitle">Gestiona tus apartados activos y vencidos</p>
        </div>
    </div>

    @if($apartados->count() > 0)
    <!-- Filtros Simples -->
    <div class="filters-bar">
        <div class="filter-group">
            <label>Estado:</label>
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="todos">Todos</button>
                <button class="filter-btn" data-filter="activo">Activos</button>
                <button class="filter-btn" data-filter="vencido">Vencidos</button>
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
        @if(isset($apartados) && $apartados->count() > 0)
            @foreach($apartados as $apartado)
                @php
                    $hoy = \Carbon\Carbon::now();
                    $vencimiento = \Carbon\Carbon::parse($apartado->fechaVencimiento);
                    $estaVencido = $vencimiento->lt($hoy);
                    $estadoClass = $estaVencido ? 'vencido' : 'activo';
                    
                    // SOLUCIÓN CORREGIDA: Calcular días, horas y minutos exactos
                    if ($estaVencido) {
                        $tiempoRestante = 'Vencido';
                        $tiempoClass = 'text-danger';
                        $diasRestantes = 0;
                        $horasRestantes = 0;
                        $minutosRestantes = 0;
                    } else {
                        // Calcular la diferencia completa
                        $diferencia = $vencimiento->diff($hoy);
                        
                        $diasRestantes = $diferencia->days;
                        $horasRestantes = $diferencia->h;
                        $minutosRestantes = $diferencia->i;
                        
                        // Formatear el tiempo restante de manera compacta
                        if ($diasRestantes > 0) {
                            $tiempoRestante = $diasRestantes . 'd ' . $horasRestantes . 'h';
                        } elseif ($horasRestantes > 0) {
                            $tiempoRestante = $horasRestantes . 'h ' . $minutosRestantes . 'm';
                        } else {
                            $tiempoRestante = $minutosRestantes . 'm';
                        }
                        
                        // Determinar la clase de color según el tiempo restante
                        $totalHorasRestantes = ($diasRestantes * 24) + $horasRestantes;
                        if ($totalHorasRestantes <= 24) {
                            $tiempoClass = 'text-danger'; // Menos de 24 horas - ROJO
                        } elseif ($totalHorasRestantes <= 72) {
                            $tiempoClass = 'text-warning'; // 1-3 días - AMARILLO
                        } else {
                            $tiempoClass = 'text-success'; // Más de 3 días - VERDE
                        }
                    }
                    
                    $fechaApartado = \Carbon\Carbon::parse($apartado->fechaApartado)->isoFormat('D MMM YYYY');
                    $fechaVencimiento = \Carbon\Carbon::parse($apartado->fechaVencimiento)->isoFormat('D MMM YYYY');
                @endphp
                
                <div class="apartado-card {{ $estadoClass }}" data-estado="{{ $estadoClass }}" data-tipo="{{ $apartado->tipoApartado }}">
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
                                        {{ $fechaApartado }}
                                    </span>
                                    <span class="meta-item">
                                        <i class="bi bi-calendar-x {{ $estaVencido ? 'text-danger' : 'text-success' }}"></i>
                                        {{ $fechaVencimiento }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="header-badges">
                            <span class="type-badge {{ $apartado->tipoApartado }}">
                                {{ $apartado->tipoApartado == 'palabra' ? 'Palabra' : 'Depósito' }}
                            </span>
                            <span class="status-badge {{ $estadoClass }}">
                                <i class="bi bi-{{ $estaVencido ? 'x-circle' : 'check-circle' }}"></i>
                                {{ $estaVencido ? 'Vencido' : 'Activo' }}
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
                // Remover active de todos los botones del mismo grupo
                const group = this.closest('.filter-buttons');
                group.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                
                // Agregar active al botón clickeado
                this.classList.add('active');
                applyFilters();
            });
        });

        applyFilters();
    }
});
</script>
@endsection