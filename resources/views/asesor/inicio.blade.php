@extends('asesor.navbar')

@section('title', 'Página de Inicio')

@push('styles')
<link href="{{ asset('css/inicioAsesor.css') }}" rel="stylesheet">
@endpush

@section('content')
    
    <div class="container"> 
        <h1 class="page-title">
            <i class="fas fa-map-marked-alt"></i>
            <span>Bienvenido de nuevo</span>
        </h1>

        <!-- Stats Overview -->
        <div class="stats-container">
            <div class="stat-card total">
                <h3 class="stat-card-title">
                    <i class="fas fa-city"></i>
                    <span>Total Fraccionamientos</span>
                </h3>
                <p class="stat-card-value">12</p>
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
                <p class="stat-card-value">245</p>
                <p class="stat-card-description">Finalizados</p>
                <div class="progress-container">
                    <div class="progress-bar progress-available" style="width: 38%"></div>
                </div>
            </div>
            <div class="stat-card reserved">
                <h3 class="stat-card-title">
                    <i class="fas fa-clock"></i>
                    <span>Lotes Apartados</span>
                </h3>
                <p class="stat-card-value">78</p>
                <p class="stat-card-description">En proceso</p>
                <div class="progress-container">
                    <div class="progress-bar progress-reserved" style="width: 12%"></div>
                </div>
            </div>
            <div class="stat-card sold">
                <h3 class="stat-card-title">
                    <i class="fas fa-check-circle"></i>
                    <span>Lotes disponibles</span>
                </h3>
                <p class="stat-card-value">312</p>
                <p class="stat-card-description">para venta</p>
                <div class="progress-container">
                    <div class="progress-bar progress-sold" style="width: 50%"></div>
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
            

            <!-- Fraccionamiento 2 -->
            <div class="fraccionamiento-card">
                <div class="fraccionamiento-image-container">
                    <img src="https://nelvabienesraices.com/images/inicio/OCEANICA.png" alt="Villas del Mar" class="fraccionamiento-image">
                </div>
                <div class="fraccionamiento-content">
                    <div class="fraccionamiento-header"> 
                    
                        <div class="fraccionamiento-info">
                            <h3 class="fraccionamiento-name">OCEÁNICA</h3>
                            <p class="fraccionamiento-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>PLAYA GUAPINOLE, STA. MARÍA TONAMECA, OAX. NELVA</span>
                            </p>
                            
                        </div>
                    </div>
                    <div class="fraccionamiento-stats">
                        
                        <div class="stat-item sold-stat">
                            <span class="stat-number">50</span>
                            <span class="stat-label">Disponibles</span>
                        </div>

                        <div class="stat-item reserved-stat">
                            <span class="stat-number">18</span>
                            <span class="stat-label">Apartados</span>
                        </div>  
                       
                        <div class="stat-item available-stat">
                            <span class="stat-number">32</span>
                            <span class="stat-label">Vendidos</span>
                        </div>
                    </div>
                    <div class="fraccionamiento-actions">
                        <button class="btn btn-primary ver-detalles">
                            <i class="fas fa-eye"></i>
                            <span>Ver Detalles</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        
    </div>

    <!-- El resto de tu contenido específico de la página de inicio -->
@endsection