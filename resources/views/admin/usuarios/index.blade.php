<!-- resources/views/admin/usuarios/index.blade.php -->
@extends('admin.navbar')

@section('title', 'Nelva Bienes Raíces - Lista de Usuarios')

@push('styles')
<link href="{{ asset('css/indexUsuarios.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

@endpush

@section('content')
<div class="container usuarios-container">
    <div class="page-header">
        <div class="header-content">
            <div class="header-title">
                <i class="fas fa-users-cog"></i>
                <h1>Gestión de Usuarios</h1>
            </div>
            <p class="header-subtitle">Administre y supervise las cuentas de usuario del sistema</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.usuarios.create') }}" class="btn">
                <i class="fas fa-user-plus"></i> Nuevo Usuario
            </a>
        </div>
    </div>
    
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeIn" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <!-- Filtros globales -->
    <div class="card filter-card animate__animated animate__fadeInUp">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3 search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="form-control filter-input search-input" 
                           placeholder="Buscar por nombre, email..." 
                           id="live-search">
                </div>
                <div class="col-md-2">
                    <select class="form-select filter-input" id="tipo-filter">
                        <option value="">Todos los tipos</option>
                        @foreach (\App\Models\TipoUsuario::all() as $tipo)
                            <option value="{{ $tipo->id_tipo }}">{{ $tipo->tipo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select filter-input" id="estatus-filter">
                        <option value="">Todos los estatus</option>
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select filter-input" id="zona-filter">
                        <option value="">Todas las zonas</option>
                        <option value="costa">Costa</option>
                        <option value="istmo">Istmo</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="button" id="reset-filters" class="btn btn-light flex-fill">
                        <i class="fas fa-eraser me-1"></i> Limpiar
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card animate__animated animate__fadeInUp">
        <div class="card-header">
            <h5><i class="fas fa-list me-2"></i>Usuarios Registrados</h5>
            <span class="badge bg-primary" id="user-count">{{ $usuarios->total() }} usuarios</span>
        </div>
        <div class="card-body p-0">
            <div class="loading-spinner" id="loading-spinner">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2 text-muted">Buscando usuarios...</p>
            </div>
            <div class="table-responsive" id="table-container">
                <table class="table table-hover mb-0" id="users-table">
                    <thead>
                        <tr>
                            <th class="sortable" data-sort="nombre">
                                Usuario
                                <span class="sort-icon fas fa-sort"></span>
                            </th>
                            <th class="sortable" data-sort="email">
                                Email
                                <span class="sort-icon fas fa-sort"></span>
                            </th>
                            <th class="sortable" data-sort="tipo">
                                Tipo de Usuario
                                <span class="sort-icon fas fa-sort"></span>
                            </th>
                            <th class="sortable" data-sort="estatus">
                                Estado
                                <span class="sort-icon fas fa-sort"></span>
                            </th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="users-tbody">
                        @forelse ($usuarios as $usuario)
                            <tr>
                                <td data-label="Usuario">
                                    <div class="user-avatar">
                                        <div class="avatar-container">
                                            @if($usuario->asesorInfo && $usuario->asesorInfo->path_fotografia)
                                                <img src="{{ asset('storage/' . $usuario->asesorInfo->path_fotografia) }}" 
                                                     alt="{{ $usuario->nombre }}" 
                                                     class="avatar-img">
                                            @else
                                                @php
                                                    $nombres = explode(' ', $usuario->nombre);
                                                    $iniciales = '';
                                                    foreach($nombres as $nombre) {
                                                        $iniciales .= strtoupper(substr($nombre, 0, 1));
                                                        if(strlen($iniciales) >= 2) break;
                                                    }
                                                @endphp
                                                <div class="avatar-initials">
                                                    {{ $iniciales }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="user-info">
                                            <div class="user-name">{{ $usuario->nombre }}</div>
                                            <div class="user-details">
                                                <span class="user-email">{{ $usuario->usuario_nombre }}</span>
                                                @if($usuario->asesorInfo && $usuario->asesorInfo->zona)
                                                    <span class="user-zona">{{ $usuario->asesorInfo->zona }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Email">{{ $usuario->email }}</td>
                                <td data-label="Tipo">
                                    <span class="badge bg-secondary">{{ $usuario->tipo->tipo ?? 'N/A' }}</span>
                                </td>
                                <td data-label="Estado">
                                    @if ($usuario->estatus)
                                        <span class="badge bg-success">
                                            <span class="status-indicator status-active"></span>
                                            Activo
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <span class="status-indicator status-inactive"></span>
                                            Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td data-label="Acciones">
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.usuarios.edit', $usuario->id_usuario) }}" 
                                           class="btn btn-action btn-edit" 
                                           data-bs-toggle="tooltip" title="Editar usuario">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        @if ($usuario->estatus)
                                            <button type="button" class="btn btn-action btn-deactivate"
                                                    data-bs-toggle="tooltip" title="Inactivar usuario">
                                                <i class="fas fa-user-slash"></i>
                                            </button>
                                            <form action="{{ route('admin.usuarios.inactivate', $usuario->id_usuario) }}" method="POST" class="d-none">
                                                @csrf
                                                @method('PATCH')
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-action btn-activate"
                                                    data-bs-toggle="tooltip" title="Activar usuario">
                                                <i class="fas fa-user-check"></i>
                                            </button>
                                            <form action="{{ route('admin.usuarios.activate', $usuario->id_usuario) }}" method="POST" class="d-none">
                                                @csrf
                                                @method('PATCH')
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="table-empty-state">
                                        <i class="fas fa-users"></i>
                                        <h5>No hay usuarios registrados</h5>
                                        <p>Comience agregando el primer usuario al sistema.</p>
                                        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary mt-2">
                                            <i class="fas fa-user-plus me-1"></i> Crear Usuario
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($usuarios->hasPages())
        <div class="card-footer bg-light" id="pagination-container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Mostrando {{ $usuarios->firstItem() }} - {{ $usuarios->lastItem() }} de {{ $usuarios->total() }} registros
                </div>
                {{ $usuarios->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="confirmation-icon">
                    <i class="fas" id="modalIcon"></i>
                </div>
                <h4 id="modalMessage"></h4>
                <p class="text-muted" id="modalDetails"></p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <form id="confirmationForm" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn" id="confirmButton">
                        <i class="fas fa-check me-2"></i>Confirmar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// JavaScript para funcionalidades dinámicas
document.addEventListener('DOMContentLoaded', function() {
    let currentSort = 'nombre';
    let currentDirection = 'asc';
    let searchTimeout;

    // Initialize tooltips
    var tooltipList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')).map(function (el) {
        return new bootstrap.Tooltip(el);
    });

    // Live Search Functionality
    document.getElementById('live-search').addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            updateUsers();
        }, 500);
    });

    // Filter change handlers
    document.getElementById('tipo-filter').addEventListener('change', updateUsers);
    document.getElementById('estatus-filter').addEventListener('change', updateUsers);
    document.getElementById('zona-filter').addEventListener('change', updateUsers);

    // Reset filters
    document.getElementById('reset-filters').addEventListener('click', function() {
        document.getElementById('live-search').value = '';
        document.getElementById('tipo-filter').value = '';
        document.getElementById('estatus-filter').value = '';
        document.getElementById('zona-filter').value = '';
        updateUsers();
    });

    // Sorting functionality
    document.querySelectorAll('.sortable').forEach(header => {
        header.addEventListener('click', function() {
            const sortField = this.dataset.sort;
            
            if (currentSort === sortField) {
                currentDirection = currentDirection === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort = sortField;
                currentDirection = 'asc';
            }
            
            updateUsers();
        });
    });

    function updateUsers() {
        const search = document.getElementById('live-search').value;
        const tipo = document.getElementById('tipo-filter').value;
        const estatus = document.getElementById('estatus-filter').value;
        const zona = document.getElementById('zona-filter').value;

        showLoading();

        const params = new URLSearchParams({
            search: search,
            tipo_usuario: tipo,
            estatus: estatus,
            zona: zona,
            sort: currentSort,
            direction: currentDirection,
            ajax: true
        });

        fetch(`{{ route('admin.usuarios.index') }}?${params}`)
            .then(response => response.text())
            .then(html => {
                // Simple page reload for this implementation
                window.location.href = `{{ route('admin.usuarios.index') }}?${params}`;
            })
            .catch(error => {
                console.error('Error:', error);
                hideLoading();
            });
    }

    function showLoading() {
        document.getElementById('loading-spinner').style.display = 'block';
        document.getElementById('table-container').style.opacity = '0.5';
    }

    function hideLoading() {
        document.getElementById('loading-spinner').style.display = 'none';
        document.getElementById('table-container').style.opacity = '1';
    }

    function initializeActionButtons() {
        // Add click handlers for action buttons
        document.querySelectorAll('.btn-activate, .btn-deactivate').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.nextElementSibling;
                const userName = this.closest('tr').querySelector('.user-name').textContent;
                const isActivate = this.classList.contains('btn-activate');
                
                showConfirmationModal(userName, isActivate, form.action);
            });
        });
    }

    function showConfirmationModal(userName, isActivate, formAction) {
        const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        const modalTitle = document.getElementById('modalTitle');
        const modalIcon = document.getElementById('modalIcon');
        const modalMessage = document.getElementById('modalMessage');
        const modalDetails = document.getElementById('modalDetails');
        const confirmButton = document.getElementById('confirmButton');
        const confirmationForm = document.getElementById('confirmationForm');
        
        if (isActivate) {
            modalTitle.textContent = 'Activar Usuario';
            modalIcon.className = 'fas fa-user-check activate-icon';
            modalMessage.textContent = `¿Activar a ${userName}?`;
            modalDetails.textContent = 'El usuario podrá acceder al sistema nuevamente.';
            confirmButton.className = 'btn btn-success';
        } else {
            modalTitle.textContent = 'Inactivar Usuario';
            modalIcon.className = 'fas fa-user-slash deactivate-icon';
            modalMessage.textContent = `¿Inactivar a ${userName}?`;
            modalDetails.textContent = 'El usuario no podrá acceder al sistema temporalmente.';
            confirmButton.className = 'btn btn-warning';
        }
        
        confirmationForm.action = formAction;
        modal.show();
    }

    // Initialize action buttons on page load
    initializeActionButtons();
});
</script>
@endpush

@endsection