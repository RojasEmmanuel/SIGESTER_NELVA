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

@section('title', 'Nelva Bienes Raíces - Inicio')

@push('styles')
<link href="{{ asset('css/inicioAsesor.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container"> 
        <div class="page-header">
            <h1 class="page-title">
                <span>Hola, {{ $usuario->nombre }}</span>
            </h1>
            <a href="{{ route('ventas.directa.crear') }}" class="btn-vender">
                <i class="fas fa-handshake"></i>
                <span>Vender</span>
            </a>
        </div>
        
        <!-- Stats Overview - Compactas con gráfico circular (FUNCIONANDO) -->
        <div class="stats-container-compact">
            <!-- Total Fraccionamientos -->
            <div class="stat-card-compact total">
                
                <div class="stat-content">
                    <div class="stat-value">{{ $totalFraccionamientos }}</div>
                    <div class="stat-label">Fraccionamientos</div>
                </div>
                <div class="circular-progress" data-percent="100">
                    <span class="progress-value">100%</span>
                </div>
            </div>

            <!-- Lotes Vendidos -->
            <div class="stat-card-compact sold">
                
                <div class="stat-content">
                    <div class="stat-value">{{ $lotesVendidos }}</div>
                    <div class="stat-label">Vendidos</div>
                </div>
                <div class="circular-progress" 
                    data-percent="{{ $totalLotes > 0 ? round(($lotesVendidos / $totalLotes) * 100, 1) : 0 }}">
                    <span class="progress-value">
                        {{ $totalLotes > 0 ? round(($lotesVendidos / $totalLotes) * 100, 1) : 0 }}%
                    </span>
                </div>
            </div>

            <!-- Lotes Apartados -->
            <div class="stat-card-compact reserved">
                
                <div class="stat-content">
                    <div class="stat-value">{{ $lotesApartados }}</div>
                    <div class="stat-label">Apartados</div>
                </div>
                <div class="circular-progress" 
                    data-percent="{{ $totalLotes > 0 ? round(($lotesApartados / $totalLotes) * 100, 1) : 0 }}">
                    <span class="progress-value">
                        {{ $totalLotes > 0 ? round(($lotesApartados / $totalLotes) * 100, 1) : 0 }}%
                    </span>
                </div>
            </div>

            <!-- Lotes Disponibles -->
            <div class="stat-card-compact available">
                
                <div class="stat-content">
                    <div class="stat-value">{{ $lotesDisponibles }}</div>
                    <div class="stat-label">Disponibles</div>
                </div>
                <div class="circular-progress" 
                    data-percent="{{ $totalLotes > 0 ? round(($lotesDisponibles / $totalLotes) * 100, 1) : 0 }}">
                    <span class="progress-value">
                        {{ $totalLotes > 0 ? round(($lotesDisponibles / $totalLotes) * 100, 1) : 0 }}%
                    </span>
                </div>
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
            @empty
                <p class="no-data">No hay fraccionamientos disponibles.</p>
            @endforelse
        </div>
    </div>

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
                
                // Filtrar fraccionamientos - usar display block que funciona en ambos casos
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.circular-progress').forEach(el => {
        const percent = el.getAttribute('data-percent') || 0;
        el.style.setProperty('--percent', 0);
        setTimeout(() => el.style.setProperty('--percent', percent), 100);
    });
});
</script>
@endpush
@endsection