<!-- resources/views/admin/usuarios/index.blade.php -->
@extends('admin.navbar')

@section('title', 'Nelva Bienes Raíces - Lista de Usuarios')

@push('styles')
<link href="{{ asset('css/indexUsuarios.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- Alternativa: Usar una versión más reciente de Font Awesome -->
@endpush

@section('content')
<div class="usuarios-container">
    <!-- Header Corporativo Mejorado -->
    <div class="usu-corporate-header">
        <div class="usu-header-content">
            <div class="usu-header-text">
                <div class="usu-header-title">
                    <i class="fas fa-users-cog"></i>
                    <h1>Gestión de Usuarios</h1>
                </div>
                <p class="usu-header-subtitle">Administre y supervise las cuentas de usuario del sistema</p>
            </div>
            <div class="usu-header-actions">
                <a href="{{ route('admin.usuarios.create') }}" class="usu-btn-corporate">
                    <i class="fas fa-user-plus"></i> Nuevo Usuario
                </a>
            </div>
        </div>
    </div>
    
    @if (session('success'))
        <div class="usu-alert-corporate">
            <i class="fas fa-check-circle"></i>
            <div class="usu-alert-content">{{ session('success') }}</div>
            <button type="button" class="usu-btn-close-corporate" onclick="this.parentElement.style.display='none'">&times;</button>
        </div>
    @endif
    
    <!-- Panel de Filtros Simplificado -->
    <div class="usu-filters-panel">
        <div class="usu-filters-grid">
            <div class="usu-filter-group">
                <label class="usu-filter-label">Buscar Usuario</label>
                <div class="usu-search-container">
                    <i class="fas fa-search usu-search-icon"></i>
                    <input type="text" class="usu-filter-input usu-search-input" 
                           placeholder="Nombre, email, usuario..." 
                           id="live-search">
                </div>
            </div>
            
            <div class="usu-filter-group">
                <label class="usu-filter-label">Tipo de Usuario</label>
                <select class="usu-filter-input" id="tipo-filter">
                    <option value="">Todos los tipos</option>
                    @foreach (\App\Models\TipoUsuario::all() as $tipo)
                        <option value="{{ $tipo->id_tipo }}">{{ $tipo->tipo }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="usu-filter-group">
                <label class="usu-filter-label">Estado</label>
                <select class="usu-filter-input" id="estatus-filter">
                    <option value="">Todos los estados</option>
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>
            
            <div class="usu-filter-group">
                <label class="usu-filter-label">Zona</label>
                <select class="usu-filter-input" id="zona-filter">
                    <option value="">Todas las zonas</option>
                    <option value="costa">Costa</option>
                    <option value="istmo">Istmo</option>
                </select>
            </div>
        </div>
        
        <div class="usu-filter-actions">
            <button type="button" id="reset-filters" class="usu-btn-corporate usu-btn-outline">
                <i class="fas fa-eraser"></i> Limpiar
            </button>
            <button type="button" id="apply-filters" class="usu-btn-corporate">
                <i class="fas fa-filter"></i> Aplicar Filtros
            </button>
        </div>
    </div>
    
    <!-- Panel de Datos (TABLA CORPORATIVA MANTENIDA) -->
    <div class="usu-data-panel">
        <div class="usu-panel-header">
            <div class="usu-panel-stats">
                <div class="usu-panel-title">
                    <i class="fas fa-list"></i>
                    <span>Usuarios Registrados</span>
                </div>
                <span class="usu-stats-badge" id="user-count">{{ $usuarios->total() }} usuarios</span>
            </div>
        </div>
        
        <div class="usu-table-container">
            <table class="usu-corporate-table" id="users-table">
                <thead>
                    <tr>
                        <th class="sortable" data-sort="nombre">
                            Usuario <span class="sort-icon fas fa-sort"></span>
                        </th>
                        <th class="sortable" data-sort="email">
                            Email <span class="sort-icon fas fa-sort"></span>
                        </th>
                        <th class="sortable" data-sort="tipo">
                            Tipo de Usuario <span class="sort-icon fas fa-sort"></span>
                        </th>
                        <th class="sortable" data-sort="estatus">
                            Estado <span class="sort-icon fas fa-sort"></span>
                        </th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="users-tbody">
                    @forelse ($usuarios as $usuario)
                        <tr>
                            <td data-label="Usuario">
                                <div class="usu-user-profile">
                                    @if($usuario->asesorInfo && $usuario->asesorInfo->path_fotografia)
                                        <img src="{{ asset('storage/' . $usuario->asesorInfo->path_fotografia) }}" 
                                             alt="{{ $usuario->nombre }}" 
                                             class="usu-avatar-corporate">
                                    @else
                                        @php
                                            $nombres = explode(' ', $usuario->nombre);
                                            $iniciales = '';
                                            foreach($nombres as $nombre) {
                                                $iniciales .= strtoupper(substr($nombre, 0, 1));
                                                if(strlen($iniciales) >= 2) break;
                                            }
                                        @endphp
                                        <div class="usu-avatar-initials-corporate">
                                            {{ $iniciales }}
                                        </div>
                                    @endif
                                    <div class="usu-user-details-corporate">
                                        <div class="usu-user-name-corporate">{{ $usuario->nombre }}</div>
                                        <div class="usu-user-meta">
                                            <span class="usu-user-email-corporate">{{ $usuario->usuario_nombre }}</span>
                                            @if($usuario->asesorInfo && $usuario->asesorInfo->zona)
                                                <span class="usu-user-zona-corporate">{{ $usuario->asesorInfo->zona }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Email">{{ $usuario->email }}</td>
                            <td data-label="Tipo">
                                <span class="usu-badge-corporate usu-badge-primary">{{ $usuario->tipo->tipo ?? 'N/A' }}</span>
                            </td>
                            <td data-label="Estado">
                                @if ($usuario->estatus)
                                    <span class="usu-badge-corporate usu-badge-success">
                                        <span class="usu-status-indicator-corporate usu-status-active"></span>
                                        Activo
                                    </span>
                                @else
                                    <span class="usu-badge-corporate usu-badge-danger">
                                        <span class="usu-status-indicator-corporate usu-status-inactive"></span>
                                        Inactivo
                                    </span>
                                @endif
                            </td>
                            <td data-label="Acciones">
                                <div class="usu-actions-corporate">
                                    <a href="{{ route('admin.usuarios.edit', $usuario->id_usuario) }}" 
                                       class="usu-btn-action-corporate usu-btn-edit-corporate" 
                                       title="Editar usuario">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    @if ($usuario->estatus)
                                        <button type="button" class="usu-btn-action-corporate usu-btn-deactivate-corporate"
                                                title="Inactivar usuario"
                                                data-user-id="{{ $usuario->id_usuario }}"
                                                data-user-name="{{ $usuario->nombre }}">
                                            <i class="fas fa-user-slash"></i>
                                        </button>
                                    @else
                                        <button type="button" class="usu-btn-action-corporate usu-btn-activate-corporate"
                                                title="Activar usuario"
                                                data-user-id="{{ $usuario->id_usuario }}"
                                                data-user-name="{{ $usuario->nombre }}">
                                            <i class="fas fa-user-check"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="usu-empty-state-corporate">
                                    <i class="fas fa-users"></i>
                                    <h4>No hay usuarios registrados</h4>
                                    <p>Comience agregando el primer usuario al sistema.</p>
                                    <a href="{{ route('admin.usuarios.create') }}" class="usu-btn-corporate">
                                        <i class="fas fa-user-plus"></i> Crear Primer Usuario
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($usuarios->hasPages())
        <div class="usu-pagination-corporate">
            <div class="usu-pagination-info">
                Mostrando {{ $usuarios->firstItem() }} - {{ $usuarios->lastItem() }} de {{ $usuarios->total() }} registros
            </div>
            <ul class="usu-pagination-nav">
                @if ($usuarios->onFirstPage())
                    <li class="page-item disabled"><span class="usu-page-link-corporate">Anterior</span></li>
                @else
                    <li class="page-item"><a class="usu-page-link-corporate" href="{{ $usuarios->previousPageUrl() }}">Anterior</a></li>
                @endif

                @foreach ($usuarios->getUrlRange(1, $usuarios->lastPage()) as $page => $url)
                    @if ($page == $usuarios->currentPage())
                        <li class="page-item usu-page-active"><span class="usu-page-link-corporate">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="usu-page-link-corporate" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach

                @if ($usuarios->hasMorePages())
                    <li class="page-item"><a class="usu-page-link-corporate" href="{{ $usuarios->nextPageUrl() }}">Siguiente</a></li>
                @else
                    <li class="page-item disabled"><span class="usu-page-link-corporate">Siguiente</span></li>
                @endif
            </ul>
        </div>
        @endif
    </div>
</div>

<!-- Modal de Confirmación Corporativo MEJORADO -->
<div class="usu-modal-corporate" id="confirmationModal">
    <div class="usu-modal-content-corporate">
        <div class="usu-modal-header-corporate">
            <h5 id="modalTitle">Confirmar Acción</h5>
            <button type="button" class="usu-modal-close" id="modalCloseButton">&times;</button>
        </div>
        <div class="usu-modal-body-corporate">
            <div class="usu-confirmation-icon-corporate">
                <i class="fas" id="modalIcon"></i>
            </div>
            <h4 class="usu-modal-message-corporate" id="modalMessage"></h4>
            <p class="usu-modal-details-corporate" id="modalDetails"></p>
        </div>
        <div class="usu-modal-footer-corporate">
            <button type="button" class="usu-modal-btn-corporate usu-btn-cancel-corporate" id="modalCancelButton">
                <i class="fas fa-times"></i> Cancelar
            </button>
            <button type="button" class="usu-modal-btn-corporate" id="confirmActionButton">
                <i class="fas fa-check"></i> Confirmar
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentSort = 'nombre';
    let currentDirection = 'asc';
    let searchTimeout;
    let currentActionUrl = '';

    // Búsqueda en tiempo real
    document.getElementById('live-search').addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            updateUsers();
        }, 500);
    });

    // Manejo de cambios en filtros
    document.getElementById('tipo-filter').addEventListener('change', updateUsers);
    document.getElementById('estatus-filter').addEventListener('change', updateUsers);
    document.getElementById('zona-filter').addEventListener('change', updateUsers);

    // Aplicar filtros
    document.getElementById('apply-filters').addEventListener('click', updateUsers);

    // Reiniciar filtros
    document.getElementById('reset-filters').addEventListener('click', function() {
        document.getElementById('live-search').value = '';
        document.getElementById('tipo-filter').value = '';
        document.getElementById('estatus-filter').value = '';
        document.getElementById('zona-filter').value = '';
        updateUsers();
    });

    // Ordenamiento
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

        const params = new URLSearchParams({
            search: search,
            tipo_usuario: tipo,
            estatus: estatus,
            zona: zona,
            sort: currentSort,
            direction: currentDirection
        });

        // Recargar la página con los nuevos parámetros
        window.location.href = `{{ route('admin.usuarios.index') }}?${params}`;
    }

    // Inicializar botones de activación/desactivación
    function initializeActionButtons() {
        document.querySelectorAll('.usu-btn-activate-corporate, .usu-btn-deactivate-corporate').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const userId = this.getAttribute('data-user-id');
                const userName = this.getAttribute('data-user-name');
                const isActivate = this.classList.contains('usu-btn-activate-corporate');
                
                // Determinar la URL de la acción
                const actionUrl = isActivate 
                    ? `{{ url('admin/usuarios') }}/${userId}/activate`
                    : `{{ url('admin/usuarios') }}/${userId}/inactivate`;
                
                showConfirmationModal(userName, isActivate, actionUrl);
            });
        });
    }

    function showConfirmationModal(userName, isActivate, actionUrl) {
        const modal = document.getElementById('confirmationModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalIcon = document.getElementById('modalIcon');
        const modalMessage = document.getElementById('modalMessage');
        const modalDetails = document.getElementById('modalDetails');
        const confirmButton = document.getElementById('confirmActionButton');
        
        if (isActivate) {
            modalTitle.textContent = 'Activar Usuario';
            modalIcon.className = 'fas fa-user-check usu-activate-icon';
            modalMessage.textContent = `¿Activar a ${userName}?`;
            modalDetails.textContent = 'El usuario podrá acceder al sistema nuevamente.';
            confirmButton.className = 'usu-modal-btn-corporate usu-btn-confirm-corporate';
            confirmButton.innerHTML = '<i class="fas fa-check"></i> Activar';
        } else {
            modalTitle.textContent = 'Inactivar Usuario';
            modalIcon.className = 'fas fa-user-slash usu-deactivate-icon';
            modalMessage.textContent = `¿Inactivar a ${userName}?`;
            modalDetails.textContent = 'El usuario no podrá acceder al sistema temporalmente.';
            confirmButton.className = 'usu-modal-btn-corporate usu-btn-warning-corporate';
            confirmButton.innerHTML = '<i class="fas fa-check"></i> Inactivar';
        }
        
        // Guardar la URL de acción actual
        currentActionUrl = actionUrl;
        
        // Mostrar el modal
        modal.classList.add('usu-show');
        
        // Prevenir scroll del body
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        const modal = document.getElementById('confirmationModal');
        modal.classList.remove('usu-show');
        document.body.style.overflow = 'auto';
        currentActionUrl = '';
    }

    // Configurar eventos de cierre del modal
    document.getElementById('modalCloseButton').addEventListener('click', closeModal);
    document.getElementById('modalCancelButton').addEventListener('click', closeModal);

    // Confirmar acción
    document.getElementById('confirmActionButton').addEventListener('click', function() {
        if (currentActionUrl) {
            // Crear un formulario dinámico para enviar la petición
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = currentActionUrl;
            
            // Agregar token CSRF
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Agregar método PATCH
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PATCH';
            form.appendChild(methodInput);
            
            // Agregar formulario al body y enviar
            document.body.appendChild(form);
            form.submit();
        }
    });

    // Cerrar modal al hacer clic fuera del contenido
    document.getElementById('confirmationModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Cerrar modal con tecla Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });

    // Inicializar botones al cargar la página
    initializeActionButtons();


    
});






</script>




@endpush
@endsection