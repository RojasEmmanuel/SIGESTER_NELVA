<!-- resources/views/admin/usuarios/create.blade.php -->
@extends('admin.navbar')

@section('title', 'Nelva Bienes Raíces - Registrar Usuario')

@push('styles')
<link href="{{ asset('css/createUsuarios.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --primary-color: #1e478a;
        --primary-light: #6366f1;
        --secondary-color: #3d86df;
        --accent-color: #e1f3fd;
        --text-color: #334155;
        --text-light: #64748b;
        --light-gray: #f8fafc;
        --medium-gray: #e2e8f0;
        --dark-gray: #94a3b8;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --white: #ffffff;
        --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
        --rounded: 12px;
        --rounded-sm: 8px;
        --transition: all 0.2s ease;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    body {
        color: var(--text-color);
        background: linear-gradient(135deg, var(--light-gray) 0%, #f0f4f8 100%);
        min-height: 100vh;
        line-height: 1.5;
    }

    /* Main Content */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1.25rem 1rem;
        padding-bottom: 100px;
    }

    /* Header Section */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .header-content {
        flex: 1;
    }

    .header-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.5rem;
    }

    .header-title h1 {
        font-size: 1.75rem;
        color: var(--text-color);
        font-weight: 700;
        margin: 0;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .header-title i {
        color: var(--primary-color);
        font-size: 1.5rem;
    }

    .header-subtitle {
        color: var(--text-light);
        font-size: 1rem;
        margin: 0;
    }

    .page-actions {
        display: flex;
        gap: 0.8rem;
    }

    /* Card Styles */
    .card {
        background: var(--white);
        border-radius: var(--rounded);
        box-shadow: var(--shadow);
        overflow: hidden;
        transition: var(--transition);
        border: 1px solid var(--medium-gray);
    }

    .card:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-2px);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 1px solid var(--medium-gray);
        background: var(--light-gray);
        position: relative;
    }

    .card-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    }

    .card-header h5 {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.1rem;
        color: var(--text-color);
        font-weight: 600;
        margin: 0;
    }

    .card-header i {
        color: var(--primary-color);
    }

    .card-body {
        padding: 2rem;
    }

    /* Form Grid */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    @media (min-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr 1fr;
        }
        
        .form-grid .full-width {
            grid-column: 1 / -1;
        }
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--text-color);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-control {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 1.5px solid var(--medium-gray);
        border-radius: var(--rounded-sm);
        font-size: 0.9rem;
        transition: var(--transition);
        background: var(--white);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(30, 71, 138, 0.1);
        transform: translateY(-1px);
    }

    /* Input with Icon */
    .input-group {
        position: relative;
        display: flex;
        align-items: stretch;
        width: 100%;
    }

    .input-group-text {
        display: flex;
        align-items: center;
        padding: 0.875rem 1rem;
        background: var(--light-gray);
        border: 1.5px solid var(--medium-gray);
        border-right: none;
        border-radius: var(--rounded-sm) 0 0 var(--rounded-sm);
        color: var(--text-light);
        transition: var(--transition);
    }

    .input-group .form-control {
        border-left: none;
        border-radius: 0 var(--rounded-sm) var(--rounded-sm) 0;
    }

    .input-group:focus-within .input-group-text {
        border-color: var(--primary-color);
        background: rgba(30, 71, 138, 0.05);
        color: var(--primary-color);
    }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 1.5rem;
        border: none;
        border-radius: var(--rounded-sm);
        font-weight: 500;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        font-size: 0.9rem;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: var(--white);
        box-shadow: 0 2px 4px rgba(30, 71, 138, 0.2);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-outline-secondary {
        background: transparent;
        border: 1.5px solid var(--medium-gray);
        color: var(--text-color);
    }

    .btn-outline-secondary:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
        background: var(--accent-color);
        transform: translateY(-2px);
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--medium-gray);
    }

    /* Alerts */
    .alert {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 1.25rem 1.5rem;
        border-radius: var(--rounded);
        margin-bottom: 1.5rem;
        position: relative;
        backdrop-filter: blur(10px);
    }

    .alert-danger {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        color: #7f1d1d;
        border-left: 4px solid var(--danger-color);
    }

    .alert-heading {
        color: inherit;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .alert-dismissible {
        padding-right: 3rem;
    }

    .btn-close {
        position: absolute;
        top: 1rem;
        right: 1.5rem;
        background: none;
        border: none;
        font-size: 1.25rem;
        cursor: pointer;
        color: inherit;
        opacity: 0.7;
        transition: var(--transition);
    }

    .btn-close:hover {
        opacity: 1;
        transform: rotate(90deg);
    }

    /* Toggle Password */
    .toggle-password {
        border-left: 1px solid var(--medium-gray);
        border-radius: 0 var(--rounded-sm) var(--rounded-sm) 0;
    }

    /* Select Styles */
    .form-select {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 1.5px solid var(--medium-gray);
        border-radius: var(--rounded-sm);
        font-size: 0.9rem;
        background: var(--white) url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") no-repeat right 1rem center/16px 12px;
        appearance: none;
        transition: var(--transition);
    }

    .form-select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(30, 71, 138, 0.1);
        transform: translateY(-1px);
    }

    /* Asesor Fields Card */
    #asesor-fields {
        margin-top: 1.5rem;
        border: 2px dashed var(--medium-gray);
        background: var(--accent-color);
    }

    #asesor-fields .card-header {
        background: linear-gradient(135deg, var(--accent-color) 0%, #bae6fd 100%);
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in {
        animation: fadeInUp 0.6s ease-out;
    }

    /* Icon Animations */
    .fas, .fab {
        transition: var(--transition);
    }

    .btn:hover .fas,
    .btn:hover .fab {
        transform: scale(1.1);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .header-title {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .page-actions {
            width: 100%;
            justify-content: flex-start;
        }
        
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .card-body {
            padding: 1.5rem;
        }
    }

    @media (max-width: 480px) {
        .container {
            padding: 1rem 0.5rem;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        .btn {
            width: 100%;
        }
    }

    /* Form Text */
    .form-text {
        display: block;
        margin-top: 0.375rem;
        font-size: 0.75rem;
        color: var(--text-light);
    }

    .text-danger {
        color: var(--danger-color) !important;
    }

    /* Glass Effect */
    .glass {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
</style>
@endpush

@section('content')
<div class="container fade-in">
    <div class="page-header">
        <div class="header-content">
            <div class="header-title">
                <i class="fas fa-user-plus"></i>
                <h1>Registrar Nuevo Usuario</h1>
            </div>
            <p class="header-subtitle">Complete la información para crear una nueva cuenta de usuario en el sistema</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver a la lista
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
            <h5><i class="fas fa-user-circle me-2"></i>Información del Usuario</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.usuarios.store') }}" method="POST" id="userForm">
                @csrf
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nombre" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" name="nombre" id="nombre" class="form-control" 
                                   value="{{ old('nombre') }}" placeholder="Ingrese el nombre completo" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="telefono" class="form-label">Teléfono <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input type="text" name="telefono" id="telefono" class="form-control" 
                                   value="{{ old('telefono') }}" placeholder="Ingrese el número telefónico" required>
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
                        <label for="usuario_nombre" class="form-label">Nombre de Usuario <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-at"></i></span>
                            <input type="text" name="usuario_nombre" id="usuario_nombre" class="form-control" 
                                   value="{{ old('usuario_nombre') }}" placeholder="Ingrese nombre de usuario" required>
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
                        <label for="tipo_usuario" class="form-label">Tipo de Usuario <span class="text-danger">*</span></label>
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
                        <i class="fas fa-save me-1"></i> Registrar Usuario
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