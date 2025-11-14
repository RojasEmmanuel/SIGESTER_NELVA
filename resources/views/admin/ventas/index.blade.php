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
            
            <div class="stat-card success">
                <span class="stat-number">{{ $porEstatus['pagos'] ?? 0 }}</span>
                <span class="stat-label">En Pagos</span>
                <i class="fas fa-money-bill-wave stat-icon"></i>
            </div>

            <div class="stat-card warning">
                <span class="stat-number">{{ $ventasEnRetraso }}</span>
                <span class="stat-label">En Retraso</span>
                <i class="fas fa-exclamation-triangle stat-icon"></i>
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
    <div class="ventas-modal-content modern-modal">
        <div class="modern-modal-header">
            <div class="modern-modal-icon">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <div class="modern-modal-title">
                <h5>Cambiar Estatus de Venta</h5>
                <p>Actualiza el estado de la venta seleccionada</p>
            </div>
            <button type="button" class="modern-modal-close">&times;</button>
        </div>
        <div class="modern-modal-body">
            <form id="statusForm">
                @csrf
                <input type="hidden" id="ventaId" name="venta_id">
                
                <div class="form-group">
                    <label class="form-label">Estatus Actual:</label>
                    <div class="current-status-badge">
                        <span id="currentStatus" class="status-badge"></span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="newStatus" class="form-label">Nuevo Estatus:</label>
                    <div class="modern-select-wrapper">
                        <select class="modern-form-select" id="newStatus" name="estatus" required>
                            <option value="">Seleccionar estatus...</option>
                            <option value="pagos">Pagos</option>
                            <option value="retraso">Retraso</option>
                            <option value="liquidado">Liquidado</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                        <i class="fas fa-chevron-down modern-select-arrow"></i>
                    </div>
                </div>

                <div id="cancelWarning" class="modern-alert modern-alert-warning hidden">
                    <i class="fas fa-exclamation-triangle"></i> 
                    <div>
                        <strong>Advertencia:</strong> Al cancelar la venta, los lotes asociados serán liberados y marcados como disponibles.
                    </div>
                </div>
            </form>
        </div>
        <div class="modern-modal-footer">
            <button type="button" class="btn btn-outline modal-cancel">Cancelar</button>
            <button type="button" class="btn btn-primary" id="saveStatus">
                <span class="btn-text">Guardar Cambios</span>
                <div class="btn-loading hidden">
                    <div class="loading-spinner"></div>
                    <span>Procesando...</span>
                </div>
            </button>
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
    
    // Filtros automáticos
    const estatusFilter = document.getElementById('estatusFilter');
    const ticketFilter = document.getElementById('ticketFilter');
    const filterForm = document.getElementById('filterForm');
    let filterTimeout;

    // Función para aplicar filtros automáticamente
    function applyFilters() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(() => {
            filterForm.submit();
        }, 800);
    }

    // Event listeners para filtros automáticos
    estatusFilter.addEventListener('change', applyFilters);
    ticketFilter.addEventListener('change', applyFilters);

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

    // Manejar cambio de estatus
    document.querySelectorAll('.change-status-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const ventaId = this.dataset.ventaId;
            const currentStatus = this.dataset.currentStatus;
            
            document.getElementById('ventaId').value = ventaId;
            
            // Actualizar badge de estatus actual
            const currentStatusElement = document.getElementById('currentStatus');
            currentStatusElement.textContent = currentStatus.charAt(0).toUpperCase() + currentStatus.slice(1);
            currentStatusElement.className = 'status-badge status-' + currentStatus;
            
            // Resetear el formulario
            document.getElementById('newStatus').value = '';
            document.getElementById('cancelWarning').classList.add('hidden');
            
            // Resetear estado del botón
            const saveBtn = document.getElementById('saveStatus');
            const btnText = saveBtn.querySelector('.btn-text');
            const btnLoading = saveBtn.querySelector('.btn-loading');
            
            btnText.classList.remove('hidden');
            btnLoading.classList.add('hidden');
            saveBtn.disabled = false;
            
            // Mostrar modal
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
        const saveBtn = this;
        const btnText = saveBtn.querySelector('.btn-text');
        const btnLoading = saveBtn.querySelector('.btn-loading');
        
        if (!newStatus) {
            showNotification('error', 'Por favor selecciona un estatus');
            return;
        }

        // Mostrar estado de carga
        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');
        saveBtn.disabled = true;

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
                showNotification('success', data.message);
                
                // Cerrar modal después de un breve delay
                setTimeout(() => {
                    document.getElementById('statusModal').classList.remove('active');
                    // Recargar la página después de cerrar el modal
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                }, 1500);
            } else {
                showNotification('error', data.message);
                // Restaurar botón
                resetSaveButton();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Error al actualizar el estatus');
            // Restaurar botón
            resetSaveButton();
        });
        
        function resetSaveButton() {
            btnText.classList.remove('hidden');
            btnLoading.classList.add('hidden');
            saveBtn.disabled = false;
        }
    });

    // Cerrar modal
    document.querySelector('.modern-modal-close').addEventListener('click', function() {
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

<style>
/* Estilos adicionales para mejoras visuales */

/* Filtros automáticos con indicador de carga */
.filter-grid {
    position: relative;
}

.filter-loading {
    position: absolute;
    top: 0;
    right: 0;
    width: 20px;
    height: 20px;
    opacity: 0;
    transition: opacity 0.3s;
}

.filter-loading.active {
    opacity: 1;
}

/* Modal moderno sin marco blanco */
.modern-modal {
    max-width: 480px;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.25);
    background: linear-gradient(135deg, #fff 0%, #f8fafc 100%);
    border: none;
}

.modern-modal-header {
    display: flex;
    align-items: flex-start;
    padding: 1.5rem 1.5rem 1rem;
    background: linear-gradient(135deg, #1e478a 0%, #3d86df 100%);
    color: white;
    position: relative;
}

.modern-modal-icon {
    width: 48px;
    height: 48px;
    background: rgba(255,255,255,0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    flex-shrink: 0;
}

.modern-modal-icon i {
    font-size: 1.25rem;
}

.modern-modal-title h5 {
    margin: 0 0 0.25rem 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.modern-modal-title p {
    margin: 0;
    opacity: 0.8;
    font-size: 0.875rem;
}

.modern-modal-close {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: rgba(255,255,255,0.2);
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 1.25rem;
}

.modern-modal-close:hover {
    background: rgba(255,255,255,0.3);
    transform: rotate(90deg);
}

.modern-modal-body {
    padding: 1.5rem;
}

.modern-modal-footer {
    padding: 1rem 1.5rem 1.5rem;
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
    border-top: 1px solid #e2e8f0;
}

/* Badges de estatus mejorados */
.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: capitalize;
    display: inline-block;
}

.status-solicitud {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
    border: 1px solid rgba(59, 130, 246, 0.2);
}

.status-pagos {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.status-retraso {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.status-liquidado {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.status-cancelado {
    background: rgba(100, 116, 139, 0.1);
    color: #64748b;
    border: 1px solid rgba(100, 116, 139, 0.2);
}

/* Select moderno */
.modern-select-wrapper {
    position: relative;
}

.modern-form-select {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    background: white;
    color: #374151;
    font-size: 0.875rem;
    appearance: none;
    transition: all 0.3s ease;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}

.modern-form-select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.modern-select-arrow {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6b7280;
    pointer-events: none;
}

/* Alertas modernas */
.modern-alert {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 1rem;
    border-radius: 8px;
    margin-top: 1rem;
    font-size: 0.875rem;
}

.modern-alert-warning {
    background: rgba(245, 158, 11, 0.05);
    border: 1px solid rgba(245, 158, 11, 0.2);
    color: #92400e;
}

.modern-alert-warning i {
    color: #f59e0b;
    margin-top: 0.125rem;
}

.modern-alert strong {
    display: block;
    margin-bottom: 0.25rem;
}

/* Botón con estado de carga - SOLO se muestra cuando es necesario */
.btn-loading {
    display: none;
    align-items: center;
    gap: 0.5rem;
}

.btn-loading.hidden {
    display: none;
}

.btn-loading:not(.hidden) {
    display: flex;
}

.loading-spinner {
    width: 16px;
    height: 16px;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Transiciones suaves para filtros */
.form-select, .form-input {
    transition: all 0.3s ease;
}

.form-select:focus, .form-input:focus {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}

/* Mejoras responsive */
@media (max-width: 640px) {
    .modern-modal {
        margin: 1rem;
        max-width: calc(100% - 2rem);
    }
    
    .modern-modal-header {
        flex-direction: column;
        text-align: center;
    }
    
    .modern-modal-icon {
        margin: 0 auto 1rem;
    }
    
    .modern-modal-footer {
        flex-direction: column-reverse;
    }
    
    .modern-modal-footer .btn {
        width: 100%;
    }
}

/* Mejora visual para el overlay del modal */
.ventas-modal-overlay {
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
}

/* Estado del botón cuando está deshabilitado */
.btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none !important;
}
</style>