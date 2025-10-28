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
<link href="{{ asset('css/perfilAsesor.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="profile-page-body">
    <div class="profile-page-container">
        <div class="profile-page-card">
            <div class="profile-page-header">
                <h2 class="profile-page-title">Mi Perfil</h2>
                <p class="profile-page-lead">Actualiza tu información personal</p>
            </div>
            
            <div class="profile-page-body-content">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="profilePageForm" action="{{ route('asesor.perfil.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="profile-vertical-layout">
                        <!-- Sección Superior: Foto + Información Básica -->
                        <div class="profile-top-section">
                            <div class="profile-photo-compact">
                                <div class="profile-photo-wrapper">
                                    <img 
                                        src="{{ $asesorInfo && $asesorInfo->path_fotografia ? asset('storage/' . $asesorInfo->path_fotografia) : 'https://ui-avatars.com/api/?name=' . urlencode($usuario->nombre) . '&background=1e478a&color=fff&size=100' }}" 
                                        alt="Foto de perfil" 
                                        class="profile-photo-img" 
                                        id="profilePageImage">
                                    <div class="profile-photo-overlay">
                                        <button type="button" class="profile-photo-change-btn">
                                            <i class="fas fa-camera"></i>
                                        </button>
                                    </div>
                                </div>
                                <input type="file" id="profilePageImageUpload" name="foto" accept="image/*" class="d-none">
                                <div class="profile-photo-meta">
                                    <button type="button" class="profile-upload-btn" onclick="document.getElementById('profilePageImageUpload').click()">
                                        <i class="fas fa-camera me-1"></i>Cambiar
                                    </button>
                                    <small class="profile-text-muted">JPG, PNG, GIF • 2MB max</small>
                                </div>
                            </div>

                            <div class="profile-basic-info">
                                <div class="profile-field-row">
                                    <div class="profile-field-compact">
                                        <label for="profilePageNombre" class="profile-field-label">Nombre completo *</label>
                                        <input type="text" class="profile-field-input" id="profilePageNombre" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" required>
                                    </div>
                                    
                                    <div class="profile-field-compact">
                                        <label for="profilePageUsuarioNombre" class="profile-field-label">Usuario *</label>
                                        <input type="text" class="profile-field-input" id="profilePageUsuarioNombre" name="usuario_nombre" value="{{ old('usuario_nombre', $usuario->usuario_nombre) }}" required>
                                    </div>
                                </div>

                                <div class="profile-field-row">
                                    <div class="profile-field-compact">
                                        <label for="profilePageEmail" class="profile-field-label">Email *</label>
                                        <input type="email" class="profile-field-input" id="profilePageEmail" name="email" value="{{ old('email', $usuario->email) }}" required>
                                    </div>

                                    <div class="profile-field-compact">
                                        <label for="profilePageTelefono" class="profile-field-label">Teléfono</label>
                                        <input type="tel" class="profile-field-input" id="profilePageTelefono" name="telefono" value="{{ old('telefono', $usuario->telefono) }}">
                                    </div>
                                    
                                </div>
                            </div>
                        </div>

                        <!-- Sección Media: Información Profesional -->
                        <div class="profile-middle-section">
                            <div class="profile-professional-grid">
                                <div class="profile-field-group">
                                    <label for="profilePageZona" class="profile-field-label">Zona asignada</label>
                                    <div class="profile-field-with-help">
                                        <input type="text" class="profile-field-input" id="profilePageZona" value="{{ $asesorInfo->zona ?? 'No asignada' }}" disabled>
                                        <small class="profile-field-help">Contacta al administrador para cambios</small>
                                    </div>
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

                        <!-- Sección Inferior: Seguridad y Acciones -->
                        <div class="profile-bottom-section">
                            <div class="profile-security-compact">
                                <div class="profile-security-content">
                                    <i class="fas fa-shield-alt profile-security-icon"></i>
                                    <div>
                                        <p class="profile-security-title">Información de Seguridad</p>
                                        <p class="profile-security-text">Para cambiar tu contraseña, contacta al administrador del sistema.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="profile-actions-compact">
                                <a href="{{ route('asesor.perfil.index') }}" class="profile-btn profile-btn-outline">
                                    <i class="fas fa-times me-2"></i>Cancelar
                                </a>
                                <button type="submit" class="profile-btn profile-btn-primary">
                                    <i class="fas fa-save me-2"></i>Guardar cambios
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
        const uploadButton = document.querySelector('.profile-photo-change-btn');
        
        uploadButton.addEventListener('click', function() {
            imageUpload.click();
        });
        
        imageUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    alert('El archivo es demasiado grande. El tamaño máximo permitido es 2MB.');
                    this.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(event) {
                    profileImage.src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endsection