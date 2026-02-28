@extends('admin.navbar')
<style>
    /* ===== VARIABLES CSS - ESQUEMA CORPORATIVO ===== */
:root {
    /* Colores corporativos - Azul profundo y tonos neutros */
    --primary-color: #1a365d;
    --primary-dark: #0f1a2e;
    --primary-light: #2d4a8a;
    --secondary-color: #2d3748;
    --accent-color: #4299e1;
    --success-color: #48bb78;
    --danger-color: #f56565;
    --warning-color: #ed8936;
    
    /* Tonos neutros */
    --gray-50: #f7fafc;
    --gray-100: #edf2f7;
    --gray-200: #e2e8f0;
    --gray-300: #cbd5e0;
    --gray-400: #a0aec0;
    --gray-500: #718096;
    --gray-600: #4a5568;
    --gray-700: #2d3748;
    --gray-800: #1a202c;
    --gray-900: #171923;
    
    /* Espaciado */
    --spacing-xs: 0.25rem;
    --spacing-sm: 0.5rem;
    --spacing-md: 1rem;
    --spacing-lg: 1.5rem;
    --spacing-xl: 2rem;
    --spacing-2xl: 3rem;
    
    /* Bordes */
    --border-radius-sm: 4px;
    --border-radius-md: 8px;
    --border-radius-lg: 12px;
    
    /* Sombras */
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    
    /* Tipografía */
    --font-primary: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    --font-mono: 'SF Mono', Monaco, 'Cascadia Code', monospace;
}

/* ===== RESET Y ESTILOS BASE ===== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: var(--font-primary);
    background-color: var(--gray-50);
    color: var(--gray-800);
    line-height: 1.6;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* ===== CONTENEDOR PRINCIPAL ===== */
.container.mt-5 {
    max-width: 1400px;
    margin: 0 auto;
    padding: var(--spacing-xl);
    animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ===== ENCABEZADO ===== */
h2.mb-4 {
    color: var(--primary-dark);
    font-weight: 600;
    font-size: 2rem;
    letter-spacing: -0.5px;
    margin-bottom: var(--spacing-xl);
    position: relative;
    padding-bottom: var(--spacing-sm);
}

h2.mb-4::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, var(--accent-color), var(--primary-light));
    border-radius: 2px;
}

/* ===== TABLA CORPORATIVA ===== */
.table-responsive {
    overflow: hidden;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-lg);
    background: white;
    border: 1px solid var(--gray-200);
    transition: box-shadow 0.3s ease;
}

.table-responsive:hover {
    box-shadow: var(--shadow-xl);
}

.table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 0.95rem;
}

/* Encabezado de tabla */
.table thead.thead-dark {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
}

.table thead.thead-dark th {
    padding: var(--spacing-lg);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.85rem;
    border: none;
    position: relative;
    transition: all 0.3s ease;
}

.table thead.thead-dark th:hover {
    background: rgba(255, 255, 255, 0.05);
}

.table thead.thead-dark th:not(:last-child)::after {
    content: '';
    position: absolute;
    right: 0;
    top: 20%;
    height: 60%;
    width: 1px;
    background: rgba(255, 255, 255, 0.2);
}

/* Celdas de tabla */
.table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid var(--gray-100);
}

.table tbody tr:hover {
    background-color: var(--gray-50);
    transform: translateX(2px);
    box-shadow: var(--shadow-sm);
}

.table tbody tr:last-child {
    border-bottom: none;
}

.table tbody td {
    padding: var(--spacing-lg);
    vertical-align: middle;
    color: var(--gray-700);
    font-weight: 400;
}

.table tbody tr:nth-child(even) {
    background-color: var(--gray-50);
}

/* IDs y números */
.table tbody td:first-child {
    font-family: var(--font-mono);
    font-weight: 600;
    color: var(--primary-color);
    font-size: 0.9rem;
}

/* Fechas */
.table tbody td:nth-child(3) {
    color: var(--gray-600);
    font-size: 0.9rem;
}

/* Montos monetarios */
.table tbody td:nth-child(4) {
    font-family: var(--font-mono);
    font-weight: 600;
    color: var(--success-color);
    font-size: 1.05rem;
}

/* ===== BADGES DE ESTADO ===== */
.badge {
    display: inline-block;
    padding: var(--spacing-xs) var(--spacing-sm);
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-radius: var(--border-radius-sm);
    transition: all 0.3s ease;
}

.badge-success {
    background: linear-gradient(135deg, var(--success-color), #38a169);
    color: white;
    box-shadow: 0 2px 4px rgba(72, 187, 120, 0.2);
}

.badge-danger {
    background: linear-gradient(135deg, var(--danger-color), #e53e3e);
    color: white;
    box-shadow: 0 2px 4px rgba(245, 101, 101, 0.2);
}

.badge:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* ===== ACCIONES (SI AGREGAS BOTONES EN EL FUTURO) ===== */
.table tbody td:last-child {
    text-align: center;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-xs);
    padding: var(--spacing-sm) var(--spacing-md);
    font-size: 0.85rem;
    font-weight: 500;
    border-radius: var(--border-radius-md);
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-info {
    background: linear-gradient(135deg, var(--accent-color), #3182ce);
    color: white;
}

.btn-danger {
    background: linear-gradient(135deg, var(--danger-color), #e53e3e);
    color: white;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.btn:active {
    transform: translateY(0);
}

/* ===== ESTADOS VACÍOS ===== */
.table tbody tr td[colspan] {
    text-align: center;
    padding: var(--spacing-2xl);
    color: var(--gray-500);
    font-style: italic;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1200px) {
    .container.mt-5 {
        padding: var(--spacing-lg);
    }
    
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .table {
        min-width: 1000px;
    }
}

@media (max-width: 768px) {
    .container.mt-5 {
        padding: var(--spacing-md);
    }
    
    h2.mb-4 {
        font-size: 1.5rem;
    }
    
    .table thead.thead-dark th,
    .table tbody td {
        padding: var(--spacing-md);
    }
}

@media (max-width: 480px) {
    h2.mb-4 {
        font-size: 1.25rem;
    }
    
    .table thead.thead-dark th,
    .table tbody td {
        padding: var(--spacing-sm);
        font-size: 0.85rem;
    }
    
    .badge {
        font-size: 0.7rem;
    }
}

/* ===== ANIMACIONES ADICIONALES ===== */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.8;
    }
}

.table tbody tr {
    animation: fadeInRow 0.5s ease-out forwards;
    animation-delay: calc(var(--row-index, 0) * 0.05s);
    opacity: 0;
}

@keyframes fadeInRow {
    to {
        opacity: 1;
    }
}

/* ===== ESTILOS PARA SCROLLBAR ===== */
.table-responsive::-webkit-scrollbar {
    height: 8px;
    width: 8px;
}

.table-responsive::-webkit-scrollbar-track {
    background: var(--gray-100);
    border-radius: var(--border-radius-sm);
}

.table-responsive::-webkit-scrollbar-thumb {
    background: var(--gray-400);
    border-radius: var(--border-radius-sm);
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: var(--gray-500);
}

/* ===== EFECTOS DE FOCUS ===== */
.table tbody tr:focus-within {
    outline: 2px solid var(--accent-color);
    outline-offset: 2px;
}

/* ===== LOADING STATE (OPCIONAL) ===== */
.loading .table tbody tr {
    animation: pulse 1.5s ease-in-out infinite;
}

.loading .table tbody td {
    color: transparent;
    position: relative;
    overflow: hidden;
}

.loading .table tbody td::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
    animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
    0% {
        transform: translateX(-100%);
    }
    100% {
        transform: translateX(100%);
    }
}
</style>
@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Todos los Apartados</h2>

    <p class="mb-4">En esta sección se muestran todos los apartados registrados en el sistema, incluyendo detalles como el tipo de apartado, el asesor responsable, las fechas relevantes, la cantidad apartada, el estado actual y los lotes asociados. Utiliza esta vista para gestionar y revisar los apartados de manera eficiente.</p>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Asesor</th>
                    <th>Fecha del Apartado</th>
                    <th>Fecha de Vencimiento</th>
                    <th>Cantidad</th>
                    <th>Estado</th>
                    <th>Lotes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($apartados as $apartado)
                <tr>
                    <td>{{ $apartado->id }}</td>
                    <td>{{ $apartado->tipo_apartado }}</td>
                    <td>{{ $apartado->asesor }}</td>
                    <td>{{ $apartado->fecha_apartado}}</td>
                    <td>{{ $apartado->fecha_vencimiento }}</td>
                    <td>${{ number_format($apartado->total_apartado, 2) }}</td>
                    <td>
                        @if($apartado->estado == 'activo')
                            <span class="badge badge-success">Activo</span>
                        @else
                            <span class="badge badge-danger">Cancelado</span>
                        @endif
                    </td>
                     <td>
                        <div class="lotes-container">
                            @if($apartado->lotes != 'Sin lotes')
                                @php
                                    $lotesArray = explode(', ', $apartado->lotes);
                                @endphp
                                @foreach($lotesArray as $lote)
                                    <span class="badge badge-light border">{{ $lote }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">{{ $apartado->lotes }}</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection