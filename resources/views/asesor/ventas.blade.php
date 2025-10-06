@extends('asesor.navbar')

@section('title', 'Nelva Bienes Raíces - Ventas')

@push('styles')
<link href="{{ asset('css/ventasAsesor.css') }}" rel="stylesheet">
@endpush

@section('content')

     <!-- Main Content -->
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-history"></i>
                <span>Historial de Ventas</span>
            </h1>
            <div class="page-actions">
                <button class="btn btn-outline">
                    <i class="fas fa-download"></i> Exportar
                </button>
                <button class="btn btn-primary">
                    <i class="fas fa-filter"></i> Filtros
                </button>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="stats-container">
            <div class="stat-card total">
                <h3 class="stat-card-title">
                    <i class="fas fa-chart-bar"></i>
                    <span>Total Ventas</span>
                </h3>
                <p class="stat-card-value">142</p>
                <p class="stat-card-description">Transacciones</p>
            </div>
            <div class="stat-card completed">
                <h3 class="stat-card-title">
                    <i class="fas fa-check-circle"></i>
                    <span>Liquidados</span>
                </h3>
                <p class="stat-card-value">98</p>
                <p class="stat-card-description">69% del total</p>
            </div>
            <div class="stat-card pending">
                <h3 class="stat-card-title">
                    <i class="fas fa-clock"></i>
                    <span>En Pagos</span>
                </h3>
                <p class="stat-card-value">32</p>
                <p class="stat-card-description">23% del total</p>
            </div>
            <div class="stat-card cancelled">
                <h3 class="stat-card-title">
                    <i class="fas fa-times-circle"></i>
                    <span>Cancelados</span>
                </h3>
                <p class="stat-card-value">12</p>
                <p class="stat-card-description">8% del total</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters">
            <div class="filter-group">
                <label class="filter-label">Fraccionamiento</label>
                <select class="filter-select">
                    <option>Todos</option>
                    <option>OCEÁNICA</option>
                    <option>REAL CAMPESTRE</option>
                    <option>SICARÚ</option>
                </select>
                <i class="fas fa-chevron-down filter-icon"></i>
            </div>
            <div class="filter-group">
                <label class="filter-label">Estatus</label>
                <select class="filter-select">
                    <option>Todos</option>
                    <option>Liquidado</option>
                    <option>En Pagos</option>
                    <option>Retraso de Pagos</option>
                    <option>Cancelado</option>
                </select>
                <i class="fas fa-chevron-down filter-icon"></i>
            </div>
            <div class="filter-group">
                <label class="filter-label">Asesor</label>
                <select class="filter-select">
                    <option>Todos</option>
                    <option>Juan Pérez</option>
                    <option>María González</option>
                    <option>Carlos Ruiz</option>
                </select>
                <i class="fas fa-chevron-down filter-icon"></i>
            </div>
            <div class="filter-group">
                <label class="filter-label">Fecha</label>
                <select class="filter-select">
                    <option>Últimos 3 meses</option>
                    <option>Este año</option>
                    <option>2023</option>
                    <option>Todas</option>
                </select>
                <i class="fas fa-chevron-down filter-icon"></i>
            </div>
        </div>

        <!-- Sales Grid -->
        <div class="sales-grid">
            <!-- Sale 1 - Completed -->
            <div class="sale-card">
                <div class="sale-header">
                    <h3 class="sale-title">
                        <i class="fas fa-home"></i>
                        <span>OCEÁNICA - Lote 25</span>
                    </h3>
                    <span class="sale-status status-completed">Liquidado</span>
                </div>
                <div class="sale-body">
                    <div class="sale-details">
                        <div class="detail-group">
                            <span class="detail-label">Asesor</span>
                            <span class="detail-value highlight">Juan Pérez</span>
                        </div>
                        <div class="detail-group">
                            <span class="detail-label">Cliente</span>
                            <span class="detail-value">María González</span>
                        </div>
                        <div class="detail-group">
                            <span class="detail-label">Precio</span>
                            <span class="detail-value">$450,000 MXN</span>
                        </div>
                        <div class="detail-group">
                            <span class="detail-label">Método de pago</span>
                            <span class="detail-value">Contado</span>
                        </div>
                    </div>
                </div>
                <div class="sale-footer">
                    <span class="sale-date">
                        <i class="far fa-calendar-alt"></i>
                        <span>15/03/2023</span>
                    </span>
                    <button class="btn btn-primary btn-sm">
                        <i class="fas fa-eye"></i> Detalles
                    </button>
                </div>
            </div>
            
            <!-- Sale 2 - Pending -->
            <div class="sale-card">
                <div class="sale-header">
                    <h3 class="sale-title">
                        <i class="fas fa-home"></i>
                        <span>REAL CAMPESTRE - Lote 42</span>
                    </h3>
                    <span class="sale-status status-pending">En Pagos</span>
                </div>
                <div class="sale-body">
                    <div class="sale-details">
                        <div class="detail-group">
                            <span class="detail-label">Asesor</span>
                            <span class="detail-value highlight">María González</span>
                        </div>
                        <div class="detail-group">
                            <span class="detail-label">Cliente</span>
                            <span class="detail-value">Carlos Ruiz</span>
                        </div>
                        <div class="detail-group">
                            <span class="detail-label">Precio</span>
                            <span class="detail-value">$380,000 MXN</span>
                        </div>
                        <div class="detail-group">
                            <span class="detail-label">Método de pago</span>
                            <span class="detail-value">Mensualidades (24)</span>
                        </div>
                    </div>
                </div>
                <div class="sale-footer">
                    <span class="sale-date">
                        <i class="far fa-calendar-alt"></i>
                        <span>22/05/2023</span>
                    </span>
                    <button class="btn btn-primary btn-sm">
                        <i class="fas fa-eye"></i> Detalles
                    </button>
                </div>
            </div>
            
            <!-- Sale 3 - Delayed -->
            <div class="sale-card">
                <div class="sale-header">
                    <h3 class="sale-title">
                        <i class="fas fa-home"></i>
                        <span>SICARÚ - Lote 18</span>
                    </h3>
                    <span class="sale-status status-delayed">Retraso</span>
                </div>
                <div class="sale-body">
                    <div class="sale-details">
                        <div class="detail-group">
                            <span class="detail-label">Asesor</span>
                            <span class="detail-value highlight">Carlos Ruiz</span>
                        </div>
                        <div class="detail-group">
                            <span class="detail-label">Cliente</span>
                            <span class="detail-value">Roberto Sánchez</span>
                        </div>
                        <div class="detail-group">
                            <span class="detail-label">Precio</span>
                            <span class="detail-value">$520,000 MXN</span>
                        </div>
                        <div class="detail-group">
                            <span class="detail-label">Método de pago</span>
                            <span class="detail-value">Mensualidades (36)</span>
                        </div>
                    </div>
                </div>
                <div class="sale-footer">
                    <span class="sale-date">
                        <i class="far fa-calendar-alt"></i>
                        <span>10/01/2023</span>
                    </span>
                    <button class="btn btn-primary btn-sm">
                        <i class="fas fa-eye"></i> Detalles
                    </button>
                </div>
            </div>
            
            <!-- Sale 4 - Cancelled -->
            <div class="sale-card">
                <div class="sale-header">
                    <h3 class="sale-title">
                        <i class="fas fa-home"></i>
                        <span>OCEÁNICA - Lote 07</span>
                    </h3>
                    <span class="sale-status status-cancelled">Cancelado</span>
                </div>
                <div class="sale-body">
                    <div class="sale-details">
                        <div class="detail-group">
                            <span class="detail-label">Asesor</span>
                            <span class="detail-value highlight">María González</span>
                        </div>
                        <div class="detail-group">
                            <span class="detail-label">Cliente</span>
                            <span class="detail-value">Laura Fernández</span>
                        </div>
                        <div class="detail-group">
                            <span class="detail-label">Precio</span>
                            <span class="detail-value">$490,000 MXN</span>
                        </div>
                        <div class="detail-group">
                            <span class="detail-label">Método de pago</span>
                            <span class="detail-value">Mensualidades (12)</span>
                        </div>
                    </div>
                </div>
                <div class="sale-footer">
                    <span class="sale-date">
                        <i class="far fa-calendar-alt"></i>
                        <span>28/02/2023</span>
                    </span>
                    <button class="btn btn-primary btn-sm">
                        <i class="fas fa-eye"></i> Detalles
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Aquí iría el código JavaScript para:
        // 1. Filtrar las ventas según los selectores
        // 2. Cargar datos dinámicamente
        // 3. Manejar la exportación de datos
        // 4. Mostrar detalles de cada venta
        
        document.addEventListener('DOMContentLoaded', function() {
            // Ejemplo de implementación de filtros
            const filterSelects = document.querySelectorAll('.filter-select');
            
            filterSelects.forEach(select => {
                select.addEventListener('change', function() {
                    // Aquí iría la lógica para filtrar las ventas
                    console.log(`Filtrar por: ${this.value}`);
                });
            });
            
            
        });



        
    </script>

@endsection