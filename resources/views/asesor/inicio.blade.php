@extends('asesor.navbar')

@section('title', 'Página de Inicio')

@push('styles')
<link href="{{ asset('css/inicioAsesor.css') }}" rel="stylesheet">
@endpush

@section('content')
    
    <div class="container"> 
        <h1 class="page-title">
            <i class="fas fa-map-marked-alt"></i>
            <span>Bienvenido de nuevo, {{ $usuario->nombre }}</span>
        </h1>
        <!-- Stats Overview -->
        <div class="stats-container">
            <div class="stat-card total">
                <h3 class="stat-card-title">
                    <i class="fas fa-city"></i>
                    <span>Total Fraccionamientos</span>
                </h3>
                <p class="stat-card-value">{{ $totalFraccionamientos }}</p>
                <p class="stat-card-description">Activos</p>
                <div class="progress-container">
                    <div class="progress-bar" style="width: 100%"></div>
                </div>
            </div>
            <div class="stat-card available">
                <h3 class="stat-card-title">
                    <i class="fas fa-check-double"></i>
                    <span>Lotes Vendidos</span>
                </h3>
                <p class="stat-card-value">{{ $lotesVendidos }}</p>
                <p class="stat-card-description">Finalizados</p>
                <div class="progress-container">
                    <div class="progress-bar progress-available" 
                         style="width: {{ $totalLotes > 0 ? ($lotesVendidos / $totalLotes * 100) : 0 }}%"></div>
                </div>
            </div>
            <div class="stat-card reserved">
                <h3 class="stat-card-title">
                    <i class="fas fa-clock"></i>
                    <span>Lotes Apartados</span>
                </h3>
                <p class="stat-card-value">{{ $lotesApartados }}</p>
                <p class="stat-card-description">En proceso</p>
                <div class="progress-container">
                    <div class="progress-bar progress-reserved" 
                         style="width: {{ $totalLotes > 0 ? ($lotesApartados / $totalLotes * 100) : 0 }}%"></div>
                </div>
            </div>
            <div class="stat-card sold">
                <h3 class="stat-card-title">
                    <i class="fas fa-check-circle"></i>
                    <span>Lotes disponibles</span>
                </h3>
                <p class="stat-card-value">{{ $lotesDisponibles }}</p>
                <p class="stat-card-description">para venta</p>
                <div class="progress-container">
                    <div class="progress-bar progress-sold" 
                         style="width: {{ $totalLotes > 0 ? ($lotesDisponibles / $totalLotes * 100) : 0 }}%"></div>
                </div>
            </div>
        </div>

        <!-- Fraccionamientos List -->
        <h2 class="section-title">
            <i class="fas fa-list-ul"></i>
            <span>Lista de Fraccionamientos</span>
        </h2>

        <!-- LISTA DE FRACCIONAMIENTOS-->
        <div class="fraccionamientos-list">
            @foreach($fraccionamientos as $fraccionamiento)
            <div class="fraccionamiento-card">
                <div class="fraccionamiento-image-container">
                    <img src="{{ $fraccionamiento->path_imagen}}" 
                        alt="{{ $fraccionamiento->nombre }}" class="fraccionamiento-image">
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
                        <div class="stat-item sold-stat">
                            <span class="stat-number">{{ $fraccionamiento->lotes_disponibles }}</span>
                            <span class="stat-label">Disponibles</span>
                        </div>
                        <div class="stat-item reserved-stat">
                            <span class="stat-number">{{ $fraccionamiento->lotes_apartados }}</span>
                            <span class="stat-label">Apartados</span>
                        </div>  
                        <div class="stat-item available-stat">
                            <span class="stat-number">{{ $fraccionamiento->lotes_vendidos }}</span>
                            <span class="stat-label">Vendidos</span>
                        </div>
                    </div>
                    <div class="fraccionamiento-actions">
                        <a href="{{ route('asesor.fraccionamiento.show', $fraccionamiento->id_fraccionamiento) }}" 
                        class="btn btn-primary ver-detalles">
                            <i class="fas fa-eye"></i>
                            <span>Ver Detalles</span>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endsection