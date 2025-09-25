@extends('asesor.navbar')

@section('title', 'Mis Apartados')

@push('styles')
<link href="{{ asset('css/ApartadoAsesor.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container mt-4">
    <div class="page-header">
        <div class="header-content">
            <h2 class="page-title"><i class="bi bi-journal-text"></i> Mis Apartados</h2>
            <p class="page-subtitle">Consulta tus apartados activos y vencidos</p>
        </div>
    </div>

    <!-- Filtros (solo se muestran si hay apartados) -->
    @if($apartados->count() > 0)
    <div class="filters-section mb-4">
        <div class="row">
            <div class="col-md-6">
                
            </div>
            <div class="col-md-6">
                <div class="filter-group">
                    <label class="filter-label">Tipo:</label>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-filter active" data-type="todos">
                            Todos
                        </button>
                        <button type="button" class="btn btn-filter" data-type="palabra">
                            Palabra
                        </button>
                        <button type="button" class="btn btn-filter" data-type="deposito">
                            Depósito
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="cards-grid">
       @if(isset($apartados) && $apartados->count() > 0)
            @foreach($apartados as $apartado)
                @php
                    $hoy = \Carbon\Carbon::now();
                    $vencimiento = \Carbon\Carbon::parse($apartado->fechaVencimiento);
                    $estaVencido = $vencimiento->lt($hoy);
                    $estadoClass = $estaVencido ? 'vencido' : 'activo';
                @endphp
                
                <div class="apartado-card {{ $estadoClass }}" data-estado="{{ $estadoClass }}" data-tipo="{{ $apartado->tipoApartado }}">
                    <div class="card-header">
                        <span class="card-title">
                            <i class="bi bi-person-vcard"></i> 
                            {{ $apartado->cliente_nombre }} {{ $apartado->cliente_apellidos }}
                        </span>
                        <div class="badges-container">
                            <span class="badge {{ $apartado->tipoApartado == 'palabra' ? 'badge-palabra' : 'badge-deposito' }}">
                                {{ ucfirst($apartado->tipoApartado) }}
                            </span>
                            
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Fecha Apartado</span>
                                <span class="info-value">{{ \Carbon\Carbon::parse($apartado->fechaApartado)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Vencimiento</span>
                                <span class="info-value {{ $estaVencido ? 'text-danger' : 'text-success' }}">
                                    {{ \Carbon\Carbon::parse($apartado->fechaVencimiento)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}
                                </span>
                            </div>
                            <div class="info-item full-width">
                                <span class="info-label">Asesor</span>
                                <span class="info-value">{{ $apartado->usuario->nombre ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item full-width">
                                <span class="info-label">Lotes Apartados</span>
                                <span class="info-value">
                                    @if($apartado->lotesApartados && $apartado->lotesApartados->count() > 0)
                                        <ul class="list-unstyled mb-0">
                                            @foreach($apartado->lotesApartados as $lote)
                                                <li>
                                                    <strong>Lote:</strong> {{ $lote->lote->numeroLote ?? 'N/A' }} <br>
                                                    <strong>Fraccionamiento:</strong> {{ $lote->lote->fraccionamiento->nombre ?? 'N/A' }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span>No hay lotes apartados.</span>
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="card-actions">
                            <a href="{{ route('asesor.apartados.show', $apartado->id_apartado) }}" class="btn-details">
                                <i class="bi bi-eye"></i> Ver detalles
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            
        @endif
    </div>

    <!-- Mensaje para cuando no hay resultados después de filtrar -->
    <div id="no-results-message" class="empty-state no-results" style="display: none;">
        <i class="bi bi-search"></i>
        <h3>No se encontraron resultados</h3>
        <p>Intenta ajustar los filtros para ver más apartados.</p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Solo inicializar filtros si hay apartados
    const apartadoCards = document.querySelectorAll('.apartado-card');
    
    if (apartadoCards.length > 0) {
        // Filtros por estado
        const filterButtons = document.querySelectorAll('.btn-filter[data-filter]');
        const typeButtons = document.querySelectorAll('.btn-filter[data-type]');
        const noResultsMessage = document.getElementById('no-results-message');

        // Función para aplicar filtros
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
                    card.style.display = 'block';
                    cardsVisibles++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Mostrar u ocultar mensaje de no resultados
            if (cardsVisibles === 0) {
                noResultsMessage.style.display = 'block';
            } else {
                noResultsMessage.style.display = 'none';
            }
        }

        // Event listeners para filtros de estado
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                aplicarFiltros();
            });
        });

        // Event listeners para filtros de tipo
        typeButtons.forEach(button => {
            button.addEventListener('click', function() {
                typeButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                aplicarFiltros();
            });
        });

        // Aplicar filtros inicialmente (por si hay necesidad de reset)
        aplicarFiltros();
    }

    // Función original para ver detalles (mantenida por si acaso)
    window.verDetallesApartado = function(id) {
        fetch(`/asesor/apartados/${id}`)
        .then(response => response.json())
        .then(apartado => {
            document.getElementById("detalleCliente").textContent = `${apartado.cliente_nombre} ${apartado.cliente_apellidos}`;
            document.getElementById("detalleTipo").textContent = apartado.tipoApartado;
            
            const fechaApartado = new Date(apartado.fechaApartado);
            const fechaVencimiento = new Date(apartado.fechaVencimiento);
            
            const opciones = { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                timeZone: 'UTC'
            };
            
            document.getElementById("detalleFecha").textContent = 
                fechaApartado.toLocaleDateString('es-ES', opciones);
            document.getElementById("detalleVencimiento").textContent = 
                fechaVencimiento.toLocaleDateString('es-ES', opciones);

            const ul = document.getElementById("detalleLotes");
            ul.innerHTML = "";

            apartado.lotesApartados.forEach(l => {
                const li = document.createElement("li");
                li.classList.add("list-group-item");
                li.innerHTML = `
                    <span><strong>Lote:</strong> ${l.lote.numeroLote}</span> - 
                    <span><strong>Fraccionamiento:</strong> ${l.lote.fraccionamiento.nombre}</span>
                `;
                ul.appendChild(li);
            });
            document.getElementById("detalleAsesor").textContent = apartado.usuario?.nombre || 'N/A';
            document.getElementById("detalleTelefono").textContent = apartado.usuario?.telefono || 'N/A';
            document.getElementById("detalleEmail").textContent = apartado.usuario?.email || 'N/A';

            let modal = new bootstrap.Modal(document.getElementById('modalDetallesApartado'));
            modal.show();
        })
        .catch(error => {
            console.error('Error cargando detalles:', error);
            alert('Ocurrió un error al cargar los detalles del apartado.');
        });
    }
});
</script>

<style>
.filters-section {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    border: 1px solid #e9ecef;
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.filter-label {
    font-weight: 600;
    color: #495057;
    min-width: 60px;
}

.btn-group {
    display: flex;
    gap: 0.5rem;
}

.btn-filter {
    padding: 0.5rem 1rem;
    border: 2px solid #dee2e6;
    background: white;
    color: #6c757d;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.btn-filter:hover {
    border-color: #007bff;
    color: #007bff;
}

.btn-filter.active {
    background: #007bff;
    border-color: #007bff;
    color: white;
}

.badges-container {
    display: flex;
    gap: 0.5rem;
}

.badge-activo {
    background: #28a745;
    color: white;
}

.badge-vencido {
    background: #dc3545;
    color: white;
}

.apartado-card.vencido {
    opacity: 0.8;
    border-left: 4px solid #dc3545;
}

.apartado-card.activo {
    border-left: 4px solid #28a745;
}

.motivational-message {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 15px;
    margin-top: 2rem;
    text-align: center;
}

.motivational-content h4 {
    margin-bottom: 1rem;
}

.motivational-list {
    text-align: left;
    display: inline-block;
    margin: 1rem 0;
}

.motivational-list li {
    margin-bottom: 0.5rem;
}

.motivational-list .bi-check-circle {
    color: #28a745;
    margin-right: 0.5rem;
}

.no-results {
    text-align: center;
    padding: 3rem;
    color: #6c757d;
}

@media (max-width: 768px) {
    .filter-group {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .btn-group {
        width: 100%;
    }
    
    .btn-filter {
        flex: 1;
        text-align: center;
    }
}
</style>
@endsection