@extends('admin.navbar')
@section('title', 'Gestión de Ventas')
@push('styles')
    <link href="{{ asset('css/ventas-style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<div class="ventas-content">
    <div class="ventas-container">
        <!-- Header -->
        <div class="ventas-header">
            <h1 class="ventas-title">Gestión de Ventas</h1>
            <a href="{{ route('admin.ventas.index', ['ticket_estatus' => 'solicitud']) }}" 
               class="btn btn-warning">
                <i class="fas fa-receipt"></i> Validar Tickets Pendientes
            </a>
        </div>

        <!-- Estadísticas -->
        <div class="ventas-stats">
            <div class="stat-card">
                <span class="stat-number">{{ $totalVentas }}</span>
                <span class="stat-label">Total Ventas</span>
                <i class="fas fa-shopping-cart stat-icon"></i>
            </div>
            
            <div class="stat-card warning">
                <span class="stat-number">{{ $porEstatus['solicitud'] ?? 0 }}</span>
                <span class="stat-label">Tickets Pendientes</span>
                <i class="fas fa-clock stat-icon"></i>
            </div>
            
            <div class="stat-card success">
                <span class="stat-number">{{ $porEstatus['pagos'] ?? 0 }}</span>
                <span class="stat-label">En Pagos</span>
                <i class="fas fa-money-bill-wave stat-icon"></i>
            </div>
            
            <div class="stat-card info">
                <span class="stat-number">{{ $porEstatus['liquidado'] ?? 0 }}</span>
                <span class="stat-label">Liquidados</span>
                <i class="fas fa-flag-checkered stat-icon"></i>
            </div>
        </div>

        <!-- Filtros -->
        <div class="ventas-filters">
            <form method="GET" action="{{ route('admin.ventas.index') }}" id="filterForm">
                <div class="filter-grid">
                    <div class="form-group">
                        <label class="form-label">Estatus Venta</label>
                        <select name="estatus" class="form-select" id="estatusFilter">
                            <option value="">Todos los estatus</option>
                            <option value="solicitud" {{ $request->estatus == 'solicitud' ? 'selected' : '' }}>Solicitud</option>
                            <option value="pagos" {{ $request->estatus == 'pagos' ? 'selected' : '' }}>Pagos</option>
                            <option value="retraso" {{ $request->estatus == 'retraso' ? 'selected' : '' }}>Retraso</option>
                            <option value="liquidado" {{ $request->estatus == 'liquidado' ? 'selected' : '' }}>Liquidado</option>
                            <option value="cancelado" {{ $request->estatus == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Estatus Ticket</label>
                        <select name="ticket_estatus" class="form-select" id="ticketFilter">
                            <option value="">Todos los tickets</option>
                            <option value="solicitud" {{ $request->ticket_estatus == 'solicitud' ? 'selected' : '' }}>Solicitud</option>
                            <option value="rechazado" {{ $request->ticket_estatus == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                            <option value="aceptado" {{ $request->ticket_estatus == 'aceptado' ? 'selected' : '' }}>Aceptado</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Buscar Cliente</label>
                        <input type="text" 
                               id="searchInput" 
                               class="form-input" 
                               placeholder="Buscar en la tabla por nombre del cliente..."
                               title="Buscar en los nombres de clientes mostrados">
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Aplicar Filtros
                        </button>
                        @if($request->hasAny(['estatus', 'ticket_estatus']))
                            <a href="{{ route('admin.ventas.index') }}" class="btn btn-outline">
                                <i class="fas fa-times"></i> Limpiar
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Lista de Ventas -->
        <div class="ventas-table-container">
            <div id="searchResultsInfo" class="search-results-info hidden">
                <p id="searchResultsText"></p>
                <button id="clearSearch" class="btn btn-sm btn-outline">
                    <i class="fas fa-times"></i> Limpiar búsqueda
                </button>
            </div>

            <table class="ventas-table" id="ventasTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Vendedor</th>
                        <th>Fecha Solicitud</th>
                        <th>Estatus Venta</th>
                        <th>Estatus Ticket</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($ventas as $venta)
                        <tr data-cliente="{{ $venta->clienteVenta->nombres }} {{ $venta->clienteVenta->apellidos }}">
                            <td>#{{ $venta->id_venta }}</td>
                            <td>
                                <strong class="cliente-nombre">{{ $venta->clienteVenta->nombres }} {{ $venta->clienteVenta->apellidos }}</strong>
                            </td>
                            <td>
                                {{ $venta->apartado->usuario->nombre }} {{ $venta->apartado->usuario->apellidos }}
                            </td>
                            <td>{{ $venta->fechaSolicitud->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge badge-{{ $venta->estatus === 'liquidado' ? 'success' : ($venta->estatus === 'pagos' ? 'warning' : ($venta->estatus === 'retraso' ? 'danger' : ($venta->estatus === 'cancelado' ? 'secondary' : 'info'))) }}">
                                    {{ ucfirst($venta->estatus) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $venta->ticket_estatus === 'aceptado' ? 'success' : ($venta->ticket_estatus === 'rechazado' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($venta->ticket_estatus) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.ventas.show', $venta->id_venta) }}" 
                                       class="btn btn-sm btn-info" 
                                       title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($venta->ticket_estatus === 'solicitud')
                                        <a href="{{ route('admin.ventas.ticket', $venta->id_venta) }}" 
                                           class="btn btn-sm btn-warning" 
                                           title="Validar ticket">
                                            <i class="fas fa-receipt"></i>
                                        </a>
                                    @endif
                                    
                                    <button class="btn btn-sm btn-primary change-status-btn" 
                                            data-venta-id="{{ $venta->id_venta }}"
                                            data-current-status="{{ $venta->estatus }}"
                                            title="Cambiar estatus">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <p>No se encontraron ventas</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Paginación -->
            @if($ventas->hasPages())
                <div class="pagination">
                    @if($ventas->onFirstPage())
                        <span class="pagination-link disabled">« Anterior</span>
                    @else
                        <a href="{{ $ventas->previousPageUrl() }}{{ $request->has('estatus') ? '&estatus=' . $request->estatus : '' }}{{ $request->has('ticket_estatus') ? '&ticket_estatus=' . $request->ticket_estatus : '' }}" 
                           class="pagination-link">« Anterior</a>
                    @endif

                    @foreach(range(1, $ventas->lastPage()) as $page)
                        @if($page == $ventas->currentPage())
                            <span class="pagination-link active">{{ $page }}</span>
                        @else
                            <a href="{{ $ventas->url($page) }}{{ $request->has('estatus') ? '&estatus=' . $request->estatus : '' }}{{ $request->has('ticket_estatus') ? '&ticket_estatus=' . $request->ticket_estatus : '' }}" 
                               class="pagination-link">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($ventas->hasMorePages())
                        <a href="{{ $ventas->nextPageUrl() }}{{ $request->has('estatus') ? '&estatus=' . $request->estatus : '' }}{{ $request->has('ticket_estatus') ? '&ticket_estatus=' . $request->ticket_estatus : '' }}" 
                           class="pagination-link">Siguiente »</a>
                    @else
                        <span class="pagination-link disabled">Siguiente »</span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para Cambiar Estatus de Venta -->
<div class="ventas-modal-overlay" id="statusModal">
    <div class="ventas-modal-content">
        <div class="ventas-modal-header">
            <h5 class="ventas-modal-title">Cambiar Estatus de Venta</h5>
            <button type="button" class="ventas-modal-close">&times;</button>
        </div>
        <div class="ventas-modal-body">
            <form id="statusForm">
                @csrf
                <input type="hidden" id="ventaId" name="venta_id">
                
                <div class="form-group">
                    <label class="form-label">Estatus Actual:</label>
                    <p id="currentStatus" class="current-status-text"></p>
                </div>
                
                <div class="form-group">
                    <label for="newStatus" class="form-label">Nuevo Estatus:</label>
                    <select class="form-select" id="newStatus" name="estatus" required>
                        <option value="">Seleccionar estatus...</option>
                        <option value="pagos">Pagos</option>
                        <option value="retraso">Retraso</option>
                        <option value="liquidado">Liquidado</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>

                <div id="cancelWarning" class="alert alert-warning cancel-warning-alert hidden">
                    <i class="fas fa-exclamation-triangle"></i> 
                    Al cancelar la venta, los lotes asociados serán liberados y marcados como disponibles.
                </div>
            </form>
        </div>
        <div class="ventas-modal-footer">
            <button type="button" class="btn btn-secondary modal-cancel">Cancelar</button>
            <button type="button" class="btn btn-primary" id="saveStatus">Guardar Cambios</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.getElementById('tableBody');
    const searchResultsInfo = document.getElementById('searchResultsInfo');
    const searchResultsText = document.getElementById('searchResultsText');
    const clearSearch = document.getElementById('clearSearch');
    const rows = tableBody.querySelectorAll('tr[data-cliente]');
    const totalRows = rows.length;

    // Función para filtrar la tabla
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;

        rows.forEach(row => {
            const clienteNombre = row.getAttribute('data-cliente').toLowerCase();
            const clienteText = row.querySelector('.cliente-nombre').textContent.toLowerCase();
            
            if (clienteNombre.includes(searchTerm) || clienteText.includes(searchTerm)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Mostrar información de resultados
        if (searchTerm) {
            searchResultsText.textContent = `Mostrando ${visibleCount} de ${totalRows} ventas para: "${searchTerm}"`;
            searchResultsInfo.classList.remove('hidden');
        } else {
            searchResultsInfo.classList.add('hidden');
        }

        // Mostrar mensaje si no hay resultados
        const emptyRow = tableBody.querySelector('.empty-state');
        if (emptyRow) {
            if (searchTerm && visibleCount === 0) {
                emptyRow.style.display = '';
                emptyRow.innerHTML = `
                    <td colspan="7" class="empty-state">
                        <i class="fas fa-search"></i>
                        <p>No se encontraron ventas para "${searchTerm}"</p>
                        <button onclick="clearSearchHandler()" class="btn btn-primary mt-2">
                            Mostrar todas las ventas
                        </button>
                    </td>
                `;
            } else if (!searchTerm) {
                emptyRow.style.display = 'none';
            }
        }
    }

    // Event listener para la búsqueda
    searchInput.addEventListener('input', function() {
        clearTimeout(this.searchTimeout);
        this.searchTimeout = setTimeout(filterTable, 300);
    });

    // Event listener para Enter (evitar submit del form)
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            filterTable();
        }
    });

    // Limpiar búsqueda
    function clearSearchHandler() {
        searchInput.value = '';
        filterTable();
        searchInput.focus();
    }

    clearSearch.addEventListener('click', clearSearchHandler);

    // Manejar cambio de estatus (código existente)
    document.querySelectorAll('.change-status-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const ventaId = this.dataset.ventaId;
            const currentStatus = this.dataset.currentStatus;
            
            document.getElementById('ventaId').value = ventaId;
            document.getElementById('currentStatus').textContent = 
                currentStatus.charAt(0).toUpperCase() + currentStatus.slice(1);
            document.getElementById('newStatus').value = '';
            document.getElementById('cancelWarning').classList.add('hidden');
            
            document.getElementById('statusModal').classList.add('active');
        });
    });

    // Mostrar advertencia para cancelación
    document.getElementById('newStatus').addEventListener('change', function() {
        const warning = document.getElementById('cancelWarning');
        if (this.value === 'cancelado') {
            warning.classList.remove('hidden');
        } else {
            warning.classList.add('hidden');
        }
    });

    // Guardar nuevo estatus
    document.getElementById('saveStatus').addEventListener('click', function() {
        const ventaId = document.getElementById('ventaId').value;
        const newStatus = document.getElementById('newStatus').value;
        
        if (!newStatus) {
            showNotification('error', 'Por favor selecciona un estatus');
            return;
        }

        fetch(`/admin/ventas/${ventaId}/venta-estatus`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ estatus: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('statusModal').classList.remove('active');
                showNotification('success', data.message);
                
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showNotification('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Error al actualizar el estatus');
        });
    });

    // Cerrar modal
    document.querySelector('.ventas-modal-close').addEventListener('click', function() {
        document.getElementById('statusModal').classList.remove('active');
    });
    
    document.querySelector('.modal-cancel').addEventListener('click', function() {
        document.getElementById('statusModal').classList.remove('active');
    });

    // Cerrar modal al hacer click fuera
    document.getElementById('statusModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
        }
    });
});

// Sistema de notificaciones
function showNotification(type, message) {
    const notification = document.createElement('div');
    notification.className = `ventas-notification ventas-notification-${type}`;
    notification.innerHTML = `
        <div class="ventas-notification-content">
            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Hacer la función global para el botón de limpiar
function clearSearchHandler() {
    const searchInput = document.getElementById('searchInput');
    const searchResultsInfo = document.getElementById('searchResultsInfo');
    
    searchInput.value = '';
    searchResultsInfo.classList.add('hidden');
    
    // Mostrar todas las filas
    document.querySelectorAll('#tableBody tr[data-cliente]').forEach(row => {
        row.style.display = '';
    });
    
    // Ocultar mensaje de vacío si existe
    const emptyRow = document.querySelector('.empty-state');
    if (emptyRow && emptyRow.closest('tr')) {
        emptyRow.closest('tr').style.display = 'none';
    }
}
</script>
@endpush