@extends('asesor.navbar')

@section('title', 'Mi Perfil - Asesor')

@push('styles')
<link href="{{ asset('css/perfilAsesor.css') }}" rel="stylesheet">
@endpush

@section('content')
<body class="profile-page-body">
    <div class="profile-page-container">
        <div class="profile-page-card">
            <div class="profile-page-header">
                <h2 class="profile-page-title">Mi Perfil</h2>
                <p class="profile-page-lead">Actualiza tu información personal</p>
            </div>
            
            <div class="profile-page-body-content">
                <!-- Mensaje de éxito -->
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Errores de validación -->
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
                    <div class="profile-page-row">
                        <div class="profile-page-col profile-page-col-sidebar">
                            <div class="profile-page-img-container">
                                <img 
                                    src="{{ $asesorInfo && $asesorInfo->path_fotografia ? asset('storage/' . $asesorInfo->path_fotografia) : 'https://ui-avatars.com/api/?name=' . urlencode($usuario->nombre) . '&background=1e478a&color=fff&size=120' }}" 
                                    alt="Foto de perfil" 
                                    class="profile-page-img" 
                                    id="profilePageImage">
                            </div>
                            <div class="profile-page-file-upload">
                                <button type="button" class="profile-page-file-upload-btn">
                                    <i class="fas fa-camera me-2"></i>Cambiar foto
                                </button>
                                <input type="file" id="profilePageImageUpload" name="foto" accept="image/*">
                            </div>
                            <small class="profile-page-text-muted">Formatos: JPG, PNG, GIF. Máx. 2MB</small>
                        </div>
                        
                        <div class="profile-page-col">
                            <div class="profile-page-row">
                                <div class="profile-page-col">
                                    <label for="profilePageNombre" class="profile-page-form-label">Nombre completo</label>
                                    <input type="text" class="profile-page-form-control" id="profilePageNombre" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" required>
                                </div>
                                <div class="profile-page-col">
                                    <label for="profilePageUsuarioNombre" class="profile-page-form-label">Nombre de usuario</label>
                                    <input type="text" class="profile-page-form-control" id="profilePageUsuarioNombre" name="usuario_nombre" value="{{ old('usuario_nombre', $usuario->usuario_nombre) }}" required>
                                </div>
                            </div>
                            
                            <div class="profile-page-row">
                                <div class="profile-page-col">
                                    <label for="profilePageTelefono" class="profile-page-form-label">Teléfono</label>
                                    <input type="tel" class="profile-page-form-control" id="profilePageTelefono" name="telefono" value="{{ old('telefono', $usuario->telefono) }}">
                                </div>
                                <div class="profile-page-col">
                                    <label for="profilePageEmail" class="profile-page-form-label">Correo electrónico</label>
                                    <input type="email" class="profile-page-form-control" id="profilePageEmail" name="email" value="{{ old('email', $usuario->email) }}" required>
                                </div>
                            </div>
                            
                            <div class="profile-page-row">
                                <div class="profile-page-col">
                                    <label for="profilePageZona" class="profile-page-form-label">Zona (solo lectura)</label>
                                    <input type="text" class="profile-page-form-control" id="profilePageZona" name="zona" value="{{ $asesorInfo->zona ?? 'No asignada' }}" disabled>
                                    <small class="profile-page-text-muted">Contacta al administrador para cambiar tu zona</small>
                                </div>
                            </div>
                            
                            <div class="profile-page-row">
                                <div class="profile-page-col">
                                    <label for="profilePageFacebook" class="profile-page-form-label">Perfil de Facebook</label>
                                    <div class="profile-page-social-input">
                                        <span class="input-group-text"><i class="fab fa-facebook"></i></span>
                                        <input type="url" class="profile-page-form-control" id="profilePageFacebook" name="path_facebook" value="{{ old('path_facebook', $asesorInfo->path_facebook ?? '') }}" placeholder="https://facebook.com/tu-usuario">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="profile-page-divider"></div>
                            
                            <!-- Información adicional sin campo de contraseña -->
                            <div class="profile-page-row">
                                <div class="profile-page-col">
                                    <p class="profile-page-text-muted" style="font-style: italic;">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Para cambiar tu contraseña, contacta al administrador del sistema.
                                    </p>
                                </div>
                            </div>
                            
                            <div class="profile-page-actions">
                                <a href="{{ route('asesor.perfil.index') }}" class="profile-page-btn profile-page-btn-outline">Cancelar</a>
                                <button type="submit" class="profile-page-btn profile-page-btn-primary">Guardar cambios</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Previsualización de imagen
            const imageUpload = document.getElementById('profilePageImageUpload');
            const profileImage = document.getElementById('profilePageImage');
            const uploadButton = document.querySelector('.profile-page-file-upload-btn');
            
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
</body>
@endsection
