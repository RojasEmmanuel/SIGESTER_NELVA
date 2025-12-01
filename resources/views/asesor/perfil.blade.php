@php
    $navbarMap = [
        'Administrador' => 'admin.navbar',
        'Asesor' => 'asesor.navbar',
        'Cobranza' => 'cobranza.navbar',
        'Ingeniero' => 'ingeniero.navbar',
    ];

    $usuario = Auth::user();
    if (! $usuario->relationLoaded('tipo')) {
        $usuario->load('tipo');
    }
    $tipoNombre = $usuario->tipo->tipo ?? 'Asesor';
    $navbar = $navbarMap[$tipoNombre] ?? 'asesor.navbar';
@endphp

@extends($navbar)

@section('title', 'Nelva Bienes Raíces - Perfil')

@push('styles')
<style>
    :root {
        --profile-primary: #1e478a;
        --profile-primary-dark: #15325e;
        --profile-secondary: #3d86df;
        --profile-accent: #e1f3fd;
        --profile-light-bg: #f8fafc;
        --profile-card-bg: #ffffff;
        --profile-text: #334155;
        --profile-text-light: #64748b;
        --profile-border: #e2e8f0;
        --profile-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
        --profile-radius: 16px;
        --profile-transition: all 0.25s ease;
    }

    .profile-page-body {
        background-color: var(--profile-light-bg);
        color: var(--profile-text);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        line-height: 1.6;
        min-height: 100vh;
        padding: 2rem 0;
    }

    .profile-page-container {
        max-width: 1300px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    .profile-page-card {
        background-color: var(--profile-card-bg);
        border-radius: var(--profile-radius);
        box-shadow: var(--profile-shadow);
        overflow: hidden;
        border: 1px solid var(--profile-border);
    }

    /* Header */
    .profile-page-header {
        padding: 2.5rem 2rem 1.5rem;
        text-align: center;
        border-bottom: 1px solid var(--profile-border);
        background: white;
    }

    .profile-page-title {
        font-weight: 700;
        font-size: 1.9rem;
        margin: 0 0 0.5rem;
    }

    .profile-page-lead {
        font-size: 1rem;
        opacity: 0.9;
        margin: 0;
    }

    .profile-page-body-content {
        padding: 2.5rem;
    }

    /* Alertas */
    .alert {
        padding: 1rem 1.25rem;
        border-radius: var(--profile-radius);
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
        border: 1px solid transparent;
    }

    .alert-success { 
        background-color: #ecfdf5; 
        color: #065f46; 
        border-color: #6ee7b7; 
    }
    
    .alert-danger { 
        background-color: #fef2f2; 
        color: #991b1b; 
        border-color: #fca5a5; 
    }

    /* Layout mejorado */
    .profile-layout {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 2rem;
    }

    /* Panel lateral */
    .profile-sidebar {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .profile-photo-section {
        background: var(--profile-card-bg);
        border-radius: var(--profile-radius);
        padding: 2rem;
        text-align: center;
        border: 1px solid var(--profile-border);
        box-shadow: var(--profile-shadow);
    }

    .profile-photo-wrapper {
        position: relative;
        width: 160px;
        height: 160px;
        border-radius: 50%;
        overflow: hidden;
        border: 4px solid var(--profile-accent);
        background: var(--profile-light-bg);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: var(--profile-transition);
        cursor: pointer;
        margin: 0 auto 1rem;
    }

    .profile-photo-wrapper:hover {
        transform: scale(1.03);
        box-shadow: 0 8px 20px rgba(30, 71, 138, 0.15);
    }

    .profile-photo-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-photo-overlay {
        position: absolute;
        inset: 0;
        background: rgba(30, 71, 138, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: var(--profile-transition);
    }

    .profile-photo-wrapper:hover .profile-photo-overlay {
        opacity: 1;
    }

    .profile-photo-change-btn {
        background: white;
        color: var(--profile-primary);
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        cursor: pointer;
        transition: var(--profile-transition);
    }

    .profile-photo-change-btn:hover {
        transform: scale(1.15);
    }

    .profile-photo-hint {
        font-size: 0.75rem;
        color: var(--profile-text-light);
        text-align: center;
        margin-bottom: 0.5rem;
    }

    /* Botón de selección de archivo personalizado */
    .profile-file-btn {
        display: inline-block;
        padding: 0.6rem 1.2rem;
        background-color: var(--profile-primary);
        color: white;
        border: none;
        border-radius: var(--profile-radius);
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--profile-transition);
        text-align: center;
        margin-top: 0.5rem;
    }

    .profile-file-btn:hover {
        background-color: var(--profile-primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(30, 71, 138, 0.3);
    }

    .profile-file-btn i {
        margin-right: 0.5rem;
    }

    .profile-file-input {
        display: none;
    }

    .profile-file-name {
        font-size: 0.8rem;
        color: var(--profile-text-light);
        margin-top: 0.5rem;
        word-break: break-word;
        max-width: 100%;
        display: none;
    }

    .profile-file-name.show {
        display: block;
    }

    /* Información de seguridad */
    .profile-security-card {
        background: var(--profile-card-bg);
        border-radius: var(--profile-radius);
        padding: 1.5rem;
        border: 1px solid var(--profile-border);
        box-shadow: var(--profile-shadow);
    }

    .profile-security-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .profile-security-icon {
        color: var(--profile-primary);
        font-size: 1.25rem;
    }

    .profile-security-title {
        font-weight: 600;
        color: var(--profile-primary);
        font-size: 1rem;
        margin: 0;
    }

    .profile-security-text {
        color: var(--profile-text);
        font-size: 0.85rem;
        line-height: 1.5;
        margin: 0;
    }

    /* Contenido principal */
    .profile-main-content {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .profile-section {
        background: var(--profile-card-bg);
        border-radius: var(--profile-radius);
        padding: 2rem;
        border: 1px solid var(--profile-border);
        box-shadow: var(--profile-shadow);
    }

    .profile-section-title {
        font-weight: 600;
        color: var(--profile-primary);
        font-size: 1.2rem;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--profile-accent);
    }

    /* Formulario */
    .profile-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }

    .profile-field-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .profile-field-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--profile-text);
    }

    .profile-field-input {
        padding: 0.85rem;
        border: 1px solid var(--profile-border);
        border-radius: var(--profile-radius);
        font-size: 0.9rem;
        background-color: var(--profile-light-bg);
        transition: var(--profile-transition);
        width: 100%;
    }

    .profile-field-input:focus {
        outline: none;
        border-color: var(--profile-primary);
        box-shadow: 0 0 0 4px rgba(30, 71, 138, 0.12);
    }

    .profile-field-input:disabled {
        background-color: #f8fafc;
        color: var(--profile-text-light);
        cursor: not-allowed;
    }

    .profile-field-help {
        font-size: 0.78rem;
        color: var(--profile-text-light);
        font-style: italic;
    }

    /* Campos sociales */
    .profile-social-input {
        position: relative;
    }

    .profile-social-icon {
        position: absolute;
        left: 0.9rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--profile-primary);
        font-size: 1.1rem;
        z-index: 2;
    }

    .profile-field-input.with-icon {
        padding-left: 2.8rem;
    }

    /* Acciones */
    .profile-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--profile-border);
    }

    .profile-btn {
        padding: 0.8rem 1.75rem;
        border-radius: var(--profile-radius);
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: var(--profile-transition);
        border: 1px solid transparent;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 130px;
    }

    .profile-btn-primary {
        background-color: var(--profile-primary);
        color: white;
    }

    .profile-btn-primary:hover {
        background-color: var(--profile-primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 71, 138, 0.3);
    }

    .profile-btn-outline {
        background-color: transparent;
        color: var(--profile-primary);
        border-color: var(--profile-primary);
    }

    .profile-btn-outline:hover {
        background-color: var(--profile-accent);
        transform: translateY(-2px);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .profile-layout {
            grid-template-columns: 1fr;
        }
        
        .profile-sidebar {
            order: 2;
        }
        
        .profile-main-content {
            order: 1;
        }
        
        .profile-photo-section {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            text-align: left;
        }
        
        .profile-photo-wrapper {
            margin: 0;
            width: 120px;
            height: 120px;
        }
    }

    @media (max-width: 768px) {
        .profile-form-grid {
            grid-template-columns: 1fr;
        }
        
        .profile-photo-section {
            flex-direction: column;
            text-align: center;
        }
        
        .profile-actions {
            flex-direction: column;
        }
        
        .profile-btn {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .profile-page-body-content {
            padding: 1.5rem;
        }
        
        .profile-section {
            padding: 1.5rem;
        }
        
        .profile-photo-wrapper {
            width: 100px;
            height: 100px;
        }
    }

    .profile-page-title i {
        margin-right: 4px;
        font-size: 40px;
        color: #4A90E2; /* color opcional */
    }

</style>
@endpush

@section('content')
<div class="profile-page-body">
    <div class="profile-page-container">
        <div class="profile-page-card">
            <div class="profile-page-header">
                <h1 class="profile-page-title">
                    <i class="fas fa-user-circle"></i> Mi Perfil
                </h1>
                <p class="profile-page-lead">Gestiona y actualiza tu información personal</p>
            </div>


            <div class="profile-page-body-content">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="profilePageForm" action="@if($usuario->tipo_usuario == 4){{ route('ing.perfil.update') }}@else{{ route('asesor.perfil.update') }}@endif" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="profile-layout">
                        <!-- Panel lateral -->
                        <div class="profile-sidebar">
                            <!-- Foto de perfil -->
                            <div class="profile-photo-section">
                                <div class="profile-photo-wrapper">
                                    <img 
                                        src="{{ $asesorInfo && $asesorInfo->path_fotografia ? asset('storage/' . $asesorInfo->path_fotografia) : 'https://ui-avatars.com/api/?name=' . urlencode($usuario->nombre) . '&background=1e478a&color=fff&size=160' }}" 
                                        alt="Foto de perfil" 
                                        class="profile-photo-img" 
                                        id="profilePageImage">
                                    <div class="profile-photo-overlay">
                                        <button type="button" class="profile-photo-change-btn" title="Cambiar foto">
                                            <i class="fas fa-camera"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="profile-photo-hint">Haz clic en la foto o usa el botón</div>
                                <div class="profile-photo-hint">JPG, PNG, GIF</div>
                                
                                <!-- Botón personalizado para seleccionar archivo -->
                                <button type="button" class="profile-file-btn" id="profileFileButton">
                                    <i class="fas fa-folder-open"></i>Seleccionar Archivo
                                </button>
                                <input type="file" id="profilePageImageUpload" name="foto" accept="image/*" class="profile-file-input">
                                <div class="profile-file-name" id="profileFileName"></div>
                            </div>

                            <!-- Información de seguridad -->
                            <div class="profile-security-card">
                                <div class="profile-security-header">
                                    <i class="fas fa-shield-alt profile-security-icon"></i>
                                    <h3 class="profile-security-title">Seguridad</h3>
                                </div>
                                <p class="profile-security-text">
                                    Para cambiar tu contraseña, contacta al administrador del sistema.
                                </p>
                            </div>
                        </div>

                        <!-- Contenido principal -->
                        <div class="profile-main-content">
                            <!-- Información personal -->
                            <div class="profile-section">
                                <h3 class="profile-section-title">Información Personal</h3>
                                <div class="profile-form-grid">
                                    <div class="profile-field-group">
                                        <label for="profilePageNombre" class="profile-field-label">Nombre completo *</label>
                                        <input type="text" class="profile-field-input" id="profilePageNombre" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" required>
                                    </div>
                                    <div class="profile-field-group">
                                        <label for="profilePageUsuarioNombre" class="profile-field-label">Usuario *</label>
                                        <input type="text" class="profile-field-input" id="profilePageUsuarioNombre" name="usuario_nombre" value="{{ old('usuario_nombre', $usuario->usuario_nombre) }}" required>
                                    </div>
                                    <div class="profile-field-group">
                                        <label for="profilePageEmail" class="profile-field-label">Email *</label>
                                        <input type="email" class="profile-field-input" id="profilePageEmail" name="email" value="{{ old('email', $usuario->email) }}" required>
                                    </div>
                                    <div class="profile-field-group">
                                        <label for="profilePageTelefono" class="profile-field-label">Teléfono</label>
                                        <input type="tel" class="profile-field-input" id="profilePageTelefono" name="telefono" value="{{ old('telefono', $usuario->telefono) }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Información profesional -->
                            @if($usuario->tipo_usuario != 4)
                            <div class="profile-section">
                                <h3 class="profile-section-title">Información Profesional</h3>
                                <div class="profile-form-grid">
                                    <div class="profile-field-group">
                                        <label for="profilePageZona" class="profile-field-label">Zona asignada</label>
                                        <input type="text" class="profile-field-input" id="profilePageZona" value="{{ $asesorInfo->zona ?? 'No asignada' }}" disabled>
                                        <small class="profile-field-help">Contacta al administrador para cambios</small>
                                        <input type="hidden" name="zona" value="{{ $asesorInfo->zona ?? '' }}">
                                    </div>
                                    <div class="profile-field-group">
                                        <label for="profilePageFacebook" class="profile-field-label">Facebook</label>
                                        <div class="profile-social-input">
                                            <i class="fab fa-facebook profile-social-icon"></i>
                                            <input type="url" class="profile-field-input with-icon" id="profilePageFacebook" name="path_facebook" value="{{ old('path_facebook', $asesorInfo->path_facebook ?? '') }}" placeholder="https://facebook.com/tu-usuario">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <!-- Para Ingeniero: Solo mostrar mensaje de seguridad -->
                           
                            @endif

                            <!-- Acciones -->
                            <div class="profile-actions">
                                <a href="@if($usuario->tipo_usuario == 4){{ route('ing.perfil.index') }}@else{{ route('asesor.perfil.index') }}@endif" class="profile-btn profile-btn-outline">
                                   Cancelar
                                </a>
                                <button type="submit" class="profile-btn profile-btn-primary">
                                    Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageUpload = document.getElementById('profilePageImageUpload');
        const profileImage = document.getElementById('profilePageImage');
        const photoWrapper = document.querySelector('.profile-photo-wrapper');
        const fileButton = document.getElementById('profileFileButton');
        const fileName = document.getElementById('profileFileName');

        // Click en la foto
        photoWrapper.addEventListener('click', () => imageUpload.click());
        
        // Click en el botón personalizado
        fileButton.addEventListener('click', () => imageUpload.click());

        imageUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            if (file.size > 2 * 1024 * 1024) {
                alert('El archivo es demasiado grande. Máximo 2MB.');
                this.value = '';
                fileName.classList.remove('show');
                return;
            }

            // Mostrar nombre del archivo
            fileName.textContent = `Archivo seleccionado: ${file.name}`;
            fileName.classList.add('show');

            // Previsualizar imagen
            const reader = new FileReader();
            reader.onload = (e) => profileImage.src = e.target.result;
            reader.readAsDataURL(file);
        });
    });
</script>
@endsection