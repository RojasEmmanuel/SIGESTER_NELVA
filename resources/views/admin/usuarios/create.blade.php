<!-- resources/views/admin/usuarios/create.blade.php -->
@extends('admin.navbar')

@section('title', 'Nelva Bienes Raíces - Registrar Usuario')

@push('styles')
<link href="{{ asset('css/createUsuarios.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

@endpush

@section('content')
<div class="container fade-in">
    <div class="page-header">
        <div class="header-content">
            <div class="header-title">
                <h1>Nuevo Usuario</h1>
            </div>
            <p class="header-subtitle">Complete la información para crear una nueva cuenta de usuario en el sistema</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>
    
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible glass" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <h5 class="alert-heading">Error en el formulario</h5>
                <ul style="margin: 0; padding-left: 1rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn-close" onclick="this.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif
    
    <div class="card glass">
        <div class="card-header">
            <h5><i class="fas fa-user-circle me-2"></i>Información general</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.usuarios.store') }}" method="POST" id="userForm">
                @csrf
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nombre" class="form-label">Nombre<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" name="nombre" id="nombre" class="form-control" 
                                   value="{{ old('nombre') }}" placeholder="Nombre completo" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="telefono" class="form-label">Teléfono <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input type="text" name="telefono" id="telefono" class="form-control" 
                                   value="{{ old('telefono') }}" placeholder="Número telefónico" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" id="email" class="form-control" 
                                   value="{{ old('email') }}" placeholder="correo@ejemplo.com" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="usuario_nombre" class="form-label">Usuario <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-at"></i></span>
                            <input type="text" name="usuario_nombre" id="usuario_nombre" class="form-control" 
                                   value="{{ old('usuario_nombre') }}" placeholder="Nombre de usuario" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control" 
                                   placeholder="Mínimo 8 caracteres" required>
                            <button type="button" class="btn btn-outline-secondary toggle-password btn-sm" data-target="password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="form-text">La contraseña debe tener al menos 8 caracteres.</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="form-control" placeholder="Repita la contraseña" required>
                            <button type="button" class="btn btn-outline-secondary toggle-password btn-sm" data-target="password_confirmation">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="estatus" class="form-label">Estatus <span class="text-danger">*</span></label>
                        <select name="estatus" id="estatus" class="form-select" required>
                            <option value="1" {{ old('estatus') == 1 ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ old('estatus') == 0 ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="tipo_usuario" class="form-label">Tipo<span class="text-danger">*</span></label>
                        <select name="tipo_usuario" id="tipo_usuario" class="form-select" required onchange="toggleAsesorFields()">
                            @foreach ($tipos as $tipo)
                                <option value="{{ $tipo->id_tipo }}" {{ old('tipo_usuario') == $tipo->id_tipo ? 'selected' : '' }}>
                                    {{ $tipo->tipo }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div id="asesor-fields" class="card mt-4" style="display: none;">
                    <div class="card-header">
                        <h5><i class="fas fa-user-tie me-2"></i>Información de Asesor</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="zona" class="form-label">Zona</label>
                                <select name="zona" id="zona" class="form-select">
                                    <option value="costa" {{ old('zona') == 'costa' ? 'selected' : '' }}>Costa</option>
                                    <option value="istmo" {{ old('zona') == 'istmo' ? 'selected' : '' }}>Istmo</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="path_facebook" class="form-label">URL Facebook</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fab fa-facebook"></i></span>
                                    <input type="url" name="path_facebook" id="path_facebook" class="form-control" 
                                           value="{{ old('path_facebook') }}" placeholder="https://facebook.com/usuario">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="path_fotografia" class="form-label">Ruta Fotografía</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-image"></i></span>
                                    <input type="text" name="path_fotografia" id="path_fotografia" class="form-control" 
                                           value="{{ old('path_fotografia') }}" placeholder="/images/asesores/foto.jpg">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="reset" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-1"></i> Limpiar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleAsesorFields() {
    const tipo = document.getElementById('tipo_usuario').value;
    const asesorFields = document.getElementById('asesor-fields');
    if (tipo != 4) {
        asesorFields.style.display = 'block';
    } else {
        asesorFields.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    toggleAsesorFields();
    
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const passwordInput = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
    
    // Form validation
    document.getElementById('userForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        
        if (password.length < 8) {
            e.preventDefault();
            alert('La contraseña debe tener al menos 8 caracteres.');
            return;
        }
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Las contraseñas no coinciden.');
            return;
        }
    });
});
</script>
@endpush
@endsection