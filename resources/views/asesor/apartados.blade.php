@extends('asesor.navbar')

@section('title', 'Nelva Bienes Raíces - Apartados')

@push('styles')
<link href="{{ asset('css/ApartadoAsesor.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container mt-4">
    <!-- Header -->
    <div class="page-header">
        <div class="header-content">
            <h2 class="page-title"><i class="bi bi-journal-text"></i> Mis Apartados</h2>
            <p class="page-subtitle">Consulta tus apartados activos y vencidos</p>
        </div>
        <div class="header-stats">
            <span class="stat-badge active">{{ $apartados->where('estado', 'activo')->count() }} Activos</span>
            <span class="stat-badge vencido">{{ $apartados->where('estado', 'vencido')->count() }} Vencidos</span>
        </div>
    </div>

    @if($apartados->count() > 0)
    <!-- Filtros -->
    <div class="filters-section">
        <div class="filter-group">
            <label class="filter-label">Estado:</label>
            <div class="btn-group">
                <button type="button" class="btn-filter active" data-filter="todos">Todos</button>
                <button type="button" class="btn-filter" data-filter="activo">Activo</button>
                <button type="button" class="btn-filter" data-filter="vencido">Vencido</button>
            </div>
        </div>
        <div class="filter-group">
            <label class="filter-label">Tipo:</label>
            <div class="btn-group">
                <button type="button" class="btn-filter active" data-type="todos">Todos</button>
                <button type="button" class="btn-filter" data-type="palabra">Palabra</button>
                <button type="button" class="btn-filter" data-type="deposito">Depósito</button>
            </div>
        </div>
    </div>
    @endif

    <!-- Tarjetas con mejor espaciado -->
    <div class="cards-grid">
        @if(isset($apartados) && $apartados->count() > 0)
            @foreach($apartados as $apartado)
                @php
                    $hoy = \Carbon\Carbon::now();
                    $vencimiento = \Carbon\Carbon::parse($apartado->fechaVencimiento);
                    $estaVencido = $vencimiento->lt($hoy);
                    $estadoClass = $estaVencido ? 'vencido' : 'activo';
                    $diasRestantes = $hoy->diffInDays($vencimiento, false);
                    
                    // Formato amigable para fechas
                    $fechaApartado = \Carbon\Carbon::parse($apartado->fechaApartado);
                    $fechaVencimiento = \Carbon\Carbon::parse($apartado->fechaVencimiento);
                    
                    $fechaApartadoFormateada = $fechaApartado->isoFormat('D [de] MMM [de] YYYY');
                    $fechaVencimientoFormateada = $fechaVencimiento->isoFormat('D [de] MMM [de] YYYY');
                @endphp
                
                <div class="apartado-card {{ $estadoClass }}" data-estado="{{ $estadoClass }}" data-tipo="{{ $apartado->tipoApartado }}">
                    <div class="card-header">
                        <div class="client-info">
                            <span class="client-name">
                                <i class="bi bi-person"></i> 
                                {{ $apartado->cliente_nombre }} {{ $apartado->cliente_apellidos }}
                            </span>
                            <div class="card-badges">
                                <span class="badge {{ $apartado->tipoApartado == 'palabra' ? 'badge-palabra' : 'badge-deposito' }}">
                                    {{ $apartado->tipoApartado == 'palabra' ? 'Palabra' : 'Depósito' }}
                                </span>
                                @if($diasRestantes <= 3 && !$estaVencido)
                                <span class="badge badge-warning">
                                    {{ $diasRestantes }} día{{ $diasRestantes != 1 ? 's' : '' }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="bi bi-calendar-check"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Fecha Apartado</span>
                                    <span class="info-value">{{ $fechaApartadoFormateada }}</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="bi bi-calendar-x"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Vencimiento</span>
                                    <span class="info-value {{ $estaVencido ? 'text-danger' : 'text-success' }}">
                                        {{ $fechaVencimientoFormateada }}
                                    </span>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="bi bi-person-badge"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Asesor</span>
                                    <span class="info-value">{{ $apartado->usuario->nombre ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="info-item full-width">
                                <div class="info-icon">
                                    <i class="bi bi-map"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Lotes Apartados</span>
                                    <div class="lotes-container">
                                        @if($apartado->lotesApartados && $apartado->lotesApartados->count() > 0)
                                            @foreach($apartado->lotesApartados as $lote)
                                                <div class="lote-info">
                                                    <span class="lote-number">Lote {{ $lote->lote->numeroLote ?? 'N/A' }}</span>
                                                    <span class="fraccionamiento-name">{{ $lote->lote->fraccionamiento->nombre ?? 'N/A' }}</span>
                                                </div>
                                            @endforeach
                                        @else
                                            <span class="no-lotes">Sin lotes asignados</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <a href="{{ route('asesor.apartados.show', $apartado->id_apartado) }}" class="btn-details">
                            <i class="bi bi-eye"></i> Ver detalles
                        </a>
                    </div>
                </div>
            @endforeach
        @else
            <div class="no-results">
                <i class="bi bi-inbox"></i>
                <h3>Sin apartados</h3>
                <p>No hay apartados registrados en este momento.</p>
            </div>
        @endif
    </div>

    <div id="no-results-message" class="no-results" style="display: none;">
        <i class="bi bi-search"></i>
        <h3>No se encontraron resultados</h3>
        <p>Intenta ajustar los filtros para ver más apartados.</p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const apartadoCards = document.querySelectorAll('.apartado-card');
    
    if (apartadoCards.length > 0) {
        const filterButtons = document.querySelectorAll('.btn-filter[data-filter]');
        const typeButtons = document.querySelectorAll('.btn-filter[data-type]');
        const noResultsMessage = document.getElementById('no-results-message');

        function aplicarFiltros() {
            const estadoFiltro = document.querySelector('.btn-filter[data-filter].active')?.dataset.filter || 'todos';
            const tipoFiltro = document.querySelector('.btn-filter[data-type].active')?.dataset.type || 'todos';

            let cardsVisibles = 0;

            apartadoCards.forEach(card => {
                const estadoCard = card.dataset.estado;
                const tipoCard = card.dataset.tipo;

                const coincideEstado = estadoFiltro === 'todos' || estadoFiltro === estadoCard;
                const coincideTipo = tipoFiltro === 'todos' || tipoFiltro === tipoCard;

                if (coincideEstado && coincideTipo) {
                    card.style.display = 'flex';
                    cardsVisibles++;
                } else {
                    card.style.display = 'none';
                }
            });

            noResultsMessage.style.display = cardsVisibles === 0 ? 'block' : 'none';
        }

        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                aplicarFiltros();
            });
        });

        typeButtons.forEach(button => {
            button.addEventListener('click', function() {
                typeButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                aplicarFiltros();
            });
        });

        aplicarFiltros();
    }
});
</script>
@endsection