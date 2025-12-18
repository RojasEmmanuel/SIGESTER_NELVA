@extends('cobranza.navbar')

@section('title', 'Nelva Bienes Raíces - Contratos Pendientes')
@push('styles')
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/ventasindex-cobranza.css') }}">
@endpush

@section('content')
<body>
    <div class="container">
        <div class="page-header">
            <div class="page-title-section">
                <h1 class="page-title">Consultar ventas</h1>
                <p class="page-description">
                    Aquí encontrarás la información general de cada una de las ventas en seguimiento. 
                    En las ventas con estatus <strong>"En Pagos"</strong> y con fecha reciente 
                    es posible <strong>generar un contrato</strong> para formalizar el proceso.
                </p>
            </div>
            
            <div class="search-sort-container">
                <div class="search-wrapper">
                    <input type="text" 
                        class="search-input" 
                        placeholder="Buscar cliente..." 
                        title="Buscar por nombre, ID o estatus">
                    <span class="material-icons search-icon">search</span>
                </div>
                
                <div class="sort-wrapper">
                    <select class="sort-select">
                        <!-- Las opciones se llenarán con JavaScript -->
                    </select>
                </div>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-error">
                <span class="material-icons alert-icon">error</span>
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            
            <div class="card-content">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID Venta</th>
                                <th>Cliente</th>
                                <th>Fecha Solicitud</th>
                                <th>Enganche</th>
                                <th>Total</th>
                                <th>Estatus</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ventas as $venta)
                                <tr>
                                    <td>{{ $venta->id_venta }}</td>
                                    <td>{{ $venta->clienteVenta->nombres }} {{ $venta->clienteVenta->apellidos }}</td>
                                    <td>{{ $venta->fechaSolicitud->format('d/m/Y') }}</td>
                                    <td>$ {{ number_format($venta->enganche, 2) }}</td>
                                    <td>$ {{ number_format($venta->total, 2) }}</td>
                                    <td>
                                        <span class="status-badge status-{{ strtolower($venta->estatus) }}">
                                            {{ $venta->estatus }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('cobranza.ventas.show', $venta->id_venta) }}" class="btn btn-primary">
                                            Consultar
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <span class="material-icons empty-state-icon">inventory_2</span>
                                            <p class="empty-state-text">No hay ventas disponibles con estatus 'pagos' y ticket 'aceptado'.</p>
                                            <button class="btn btn-primary">
                                                <span class="material-icons">refresh</span>
                                                Actualizar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($ventas->count() > 0)
                <div class="pagination-container">
                    {{ $ventas->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</body>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Obtener elementos
    const sortSelect = document.querySelector('.sort-select');
    const searchInput = document.querySelector('.search-input');
    const table = document.querySelector('.data-table');
    const tbody = table.querySelector('tbody');
    const emptyState = document.querySelector('.empty-state');
    
    // Variables para manejar datos
    let originalData = null;
    let filteredData = null;
    let currentSort = 'fecha-desc';
    let currentSearch = '';
    
    // Función para extraer datos de la tabla
    function extractTableData() {
        if (originalData) return originalData; // Ya tenemos los datos
        
        const rows = Array.from(tbody.querySelectorAll('tr'));
        return rows.map(row => {
            const cells = Array.from(row.querySelectorAll('td'));
            return {
                element: row.cloneNode(true), // Clonar el nodo para preservarlo
                originalRow: row,
                id: parseInt(cells[0].textContent),
                cliente: cells[1].textContent.trim().toLowerCase(),
                clienteDisplay: cells[1].textContent.trim(),
                fecha: parseDate(cells[2].textContent),
                fechaDisplay: cells[2].textContent.trim(),
                enganche: parseCurrency(cells[3].textContent),
                engancheDisplay: cells[3].textContent.trim(),
                total: parseCurrency(cells[4].textContent),
                totalDisplay: cells[4].textContent.trim(),
                estatus: cells[5].querySelector('.status-badge').textContent.trim(),
                estatusClass: cells[5].querySelector('.status-badge').className
            };
        });
    }
    
    // Función para parsear fechas
    function parseDate(dateStr) {
        const parts = dateStr.split('/');
        if (parts.length === 3) {
            return new Date(parts[2], parts[1] - 1, parts[0]);
        }
        return new Date();
    }
    
    // Función para parsear montos de dinero
    function parseCurrency(currencyStr) {
        const cleaned = currencyStr.replace(/[^0-9.-]+/g, '');
        return parseFloat(cleaned) || 0;
    }
    
    // Función para buscar en los datos
    function searchData(data, searchTerm) {
        if (!searchTerm.trim()) return data;
        
        const term = searchTerm.toLowerCase().trim();
        return data.filter(item => {
            return item.cliente.includes(term) || 
                   item.id.toString().includes(term) ||
                   item.estatus.toLowerCase().includes(term);
        });
    }
    
    // Función para ordenar los datos
    function sortData(data, sortBy, order = 'asc') {
        return [...data].sort((a, b) => {
            let valA = a[sortBy];
            let valB = b[sortBy];
            
            // Ordenar por tipo de dato
            if (sortBy === 'fecha') {
                return order === 'asc' ? valA - valB : valB - valA;
            } else if (sortBy === 'cliente' || sortBy === 'estatus') {
                return order === 'asc' 
                    ? valA.localeCompare(valB)
                    : valB.localeCompare(valA);
            } else if (sortBy === 'id') {
                return order === 'asc' ? valA - valB : valB - valA;
            } else {
                // Para montos de dinero
                return order === 'asc' ? valA - valB : valB - valA;
            }
        });
    }
    
    // Función para actualizar la tabla
    function updateTable(data) {
        // Limpiar el tbody
        tbody.innerHTML = '';
        
        if (data.length === 0) {
            // Mostrar mensaje de no resultados
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td colspan="7" class="no-results">
                    <span class="material-icons" style="font-size: 48px; opacity: 0.5; margin-bottom: 16px;">search_off</span>
                    <p>No se encontraron resultados para "${currentSearch}"</p>
                    <button class="btn btn-outline" onclick="clearSearch()" style="margin-top: 16px;">
                        <span class="material-icons">clear</span>
                        Limpiar búsqueda
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
            return;
        }
        
        // Agregar las filas ordenadas
        data.forEach(item => {
            // Crear una nueva fila con los datos
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${item.id}</td>
                <td>${item.clienteDisplay}</td>
                <td>${item.fechaDisplay}</td>
                <td>${item.engancheDisplay}</td>
                <td>${item.totalDisplay}</td>
                <td><span class="${item.estatusClass}">${item.estatus}</span></td>
                <td>
                    <a href="${item.element.querySelector('a').href}" class="btn btn-primary">
                        Consultar
                    </a>
                </td>
            `;
            tbody.appendChild(tr);
        });
        
        // Actualizar contador de resultados
        updateResultsCounter(data.length);
    }
    
    // Función para actualizar el contador de resultados
    function updateResultsCounter(count) {
        // Eliminar contador anterior si existe
        const existingCounter = document.querySelector('.results-counter');
        if (existingCounter) {
            existingCounter.remove();
        }
        
        // Crear nuevo contador
        const counter = document.createElement('div');
        counter.className = 'results-counter';
        counter.textContent = `Mostrando ${count} de ${originalData.length} resultados`;
        
        // Insertar después de la tabla
        table.parentNode.insertBefore(counter, table.nextSibling);
    }
    
    // Función para aplicar filtros y ordenamiento
    function applyFiltersAndSort() {
        // Aplicar búsqueda
        filteredData = searchData(originalData, currentSearch);
        
        // Aplicar ordenamiento
        const [sortBy, order] = currentSort.split('-');
        const sortedData = sortData(filteredData, sortBy, order);
        
        // Actualizar tabla
        updateTable(sortedData);
    }
    
    // Configurar evento del selector de ordenamiento
    sortSelect.addEventListener('change', function() {
        currentSort = this.value;
        applyFiltersAndSort();
        
        // Agregar clase para mostrar orden actual
        this.classList.add('sort-' + currentSort.split('-')[1]);
    });
    
    // Configurar evento del buscador
    searchInput.addEventListener('input', function() {
        currentSearch = this.value;
        
        // Debounce para evitar demasiadas actualizaciones
        clearTimeout(searchInput.timeout);
        searchInput.timeout = setTimeout(() => {
            applyFiltersAndSort();
        }, 300); // 300ms de delay
    });
    
    // Configurar evento de tecla Enter en el buscador
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyFiltersAndSort();
        }
    });
    
    // Función global para limpiar búsqueda
    window.clearSearch = function() {
        searchInput.value = '';
        currentSearch = '';
        applyFiltersAndSort();
        searchInput.focus();
    };
    
    // Inicializar datos
    originalData = extractTableData();
    
    // Configurar opciones del select
    const selectOptions = `
        <option value="fecha-desc">Fecha: Más reciente</option>
        <option value="fecha-asc">Fecha: Más antigua</option>
        <option value="cliente-asc">Cliente: A-Z</option>
        <option value="cliente-desc">Cliente: Z-A</option>
        <option value="total-desc">Total: Mayor a menor</option>
        <option value="total-asc">Total: Menor a mayor</option>
        <option value="enganche-desc">Enganche: Mayor a menor</option>
        <option value="enganche-asc">Enganche: Menor a mayor</option>
    `;
    
    sortSelect.innerHTML = selectOptions;
    sortSelect.value = 'fecha-desc';
    
    // Aplicar filtros iniciales
    applyFiltersAndSort();
});
</script>

@endsection