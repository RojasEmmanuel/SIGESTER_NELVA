
@extends('ingeniero.navbar')

@section('title', 'Nelva Bienes Raíces - Inicio')

@push('styles')
<link href="{{ asset('css/IngenieroInicio.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container"> 
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-drafting-compass"></i>
            <span>Bienvenido, {{ Auth::user()->nombre }}</span>
        </h1>
        
    </div>
    
    <!-- Stats Overview -->
    <div class="stats-container">
        <div class="stat-card total">
            <h3 class="stat-card-title">
                <i class="fas fa-city"></i>
                <span>Total Fraccionamientos</span>
            </h3>
            <p class="stat-card-value">{{ $totalFraccionamientos }}</p>
            <p class="stat-card-description">Activos</p>
        </div>
        <div class="stat-card lotes">
            <h3 class="stat-card-title">
                <i class="fas fa-map-marked-alt"></i>
                <span>Total Lotes</span>
            </h3>
            <p class="stat-card-value">{{ $totalLotes }}</p>
            <p class="stat-card-description">Registrados</p>
        </div>
        <div class="stat-card manzanas">
            <h3 class="stat-card-title">
                <i class="fas fa-th-large"></i>
                <span>Total Manzanas</span>
            </h3>
            <p class="stat-card-value">{{ $totalManzanas }}</p>
            <p class="stat-card-description">Diseñadas</p>
        </div>
        <div class="stat-card disponibles">
            <h3 class="stat-card-title">
                <i class="fas fa-check-circle"></i>
                <span>Lotes Disponibles</span>
            </h3>
            <p class="stat-card-value">{{ $lotesDisponibles }}</p>
            <p class="stat-card-description">Para venta</p>
        </div>
    </div>

    <!-- Fraccionamientos List -->
    <div class="section-header">
        <div class="section-title-container">
            <h2 class="section-title">
                <i class="fas fa-list-ul"></i>
                <span>Lista de Fraccionamientos</span>
            </h2>
        </div>
        
        <!-- Filtros por Zona -->
        <div class="filtros-zona">
            <button class="btn-filtro active" data-zona="todos">
                <i class="fas fa-globe"></i>
                <span class="filtro-text">Todos</span>
            </button>
            <button class="btn-filtro" data-zona="costa">
                <i class="fas fa-umbrella-beach"></i>
                <span class="filtro-text">Costa</span>
            </button>
            <button class="btn-filtro" data-zona="istmo">
                <i class="fas fa-mountain"></i>
                <span class="filtro-text">Istmo</span>
            </button>
        </div>
    </div>

    <!-- LISTA DE FRACCIONAMIENTOS -->
    <div class="fraccionamientos-list" id="fraccionamientos-container">
        @forelse($fraccionamientos as $fraccionamiento)
        <div class="fraccionamiento-card" data-zona="{{ strtolower($fraccionamiento->zona) }}">
            <div class="fraccionamiento-image-container">
                <img src="{{ $fraccionamiento->path_imagen ? asset('storage/' . $fraccionamiento->path_imagen) : asset('images/placeholder.jpg') }}" 
                        alt="{{ $fraccionamiento->nombre }}" 
                        class="fraccionamiento-image">
                <span class="zona-badge {{ strtolower($fraccionamiento->zona) }}-badge">
                    {{ ucfirst($fraccionamiento->zona) }}
                </span>
            </div>
            <div class="fraccionamiento-content">
                <div class="fraccionamiento-header"> 
                    <div class="fraccionamiento-info">
                        <h3 class="fraccionamiento-name">{{ $fraccionamiento->nombre }}</h3>
                        <p class="fraccionamiento-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $fraccionamiento->ubicacion }}</span>
                        </p>
                    </div>
                </div>
                <div class="fraccionamiento-stats">
                    <div class="stat-item lotes-stat">
                        <span class="stat-number">{{ $fraccionamiento->total_lotes }}</span>
                        <span class="stat-label">Total Lotes</span>
                    </div>
                    <div class="stat-item manzanas-stat">
                        <span class="stat-number">{{ $fraccionamiento->total_manzanas }}</span>
                        <span class="stat-label">Manzanas</span>
                    </div>  
                    <div class="stat-item disponibles-stat">
                        <span class="stat-number">{{ $fraccionamiento->lotes_disponibles }}</span>
                        <span class="stat-label">Disponibles</span>
                    </div>
                </div>
                <div class="fraccionamiento-actions">
                    <a href="#" 
                        class="btn btn-primary ver-detalles">
                        <i class="fas fa-eye"></i>
                        <span>Ver Detalles</span>
                    </a>
                </div>
            </div>
        </div>
        @empty
            <p class="no-data">No hay fraccionamientos disponibles.</p>
        @endforelse
    </div>
</div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filtros = document.querySelectorAll('.btn-filtro');
        const fraccionamientos = document.querySelectorAll('.fraccionamiento-card');
        
        filtros.forEach(filtro => {
            filtro.addEventListener('click', function() {
                // Remover clase active de todos los botones
                filtros.forEach(btn => btn.classList.remove('active'));
                // Agregar clase active al botón clickeado
                this.classList.add('active');
                
                const zona = this.getAttribute('data-zona');
                
                // Filtrar fraccionamientos
                fraccionamientos.forEach(fracc => {
                    if (zona === 'todos') {
                        fracc.style.display = 'block';
                    } else {
                        if (fracc.getAttribute('data-zona') === zona) {
                            fracc.style.display = 'block';
                        } else {
                            fracc.style.display = 'none';
                        }
                    }
                });
            });
        });
    });
</script>
@endpush