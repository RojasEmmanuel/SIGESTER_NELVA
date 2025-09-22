@extends('asesor.navbar')

@section('title', 'Página de Inicio')

@push('styles')
<link href="{{ asset('css/perfilAsesor.css') }}" rel="stylesheet">
@endpush

@section('content')
    
<body class="profile-page-body">
    <div class="container">
        <div class="profile-page-card">
            <div class="profile-page-header">
                <h2 class="profile-page-title">Mi Perfil</h2>
                <p class="profile-page-lead">Actualiza tu información personal</p>
            </div>
            
            <div class="profile-page-body-content">
                <form id="profilePageForm">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <div class="profile-page-img-container">
                                <img src="https://via.placeholder.com/150" alt="Foto de perfil" class="profile-page-img" id="profilePageImage">
                            </div>
                            <div class="profile-page-file-upload mb-3">
                                <button type="button" class="profile-page-file-upload-btn btn">
                                    <i class="fas fa-camera me-2"></i>Cambiar foto
                                </button>
                                <input type="file" id="profilePageImageUpload" accept="image/*">
                            </div>
                            <small class="profile-page-text-muted">Formatos: JPG, PNG, GIF. Máx. 2MB</small>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="profilePageNombre" class="profile-page-form-label">Nombre completo</label>
                                    <input type="text" class="form-control profile-page-form-control" id="profilePageNombre" name="nombre" value="Juan Pérez" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="profilePageUsuarioNombre" class="profile-page-form-label">Nombre de usuario</label>
                                    <input type="text" class="form-control profile-page-form-control" id="profilePageUsuarioNombre" name="usuario_nombre" value="juanperez" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="profilePageTelefono" class="profile-page-form-label">Teléfono</label>
                                    <input type="tel" class="form-control profile-page-form-control" id="profilePageTelefono" name="telefono" value="+1 234 567 8900">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="profilePageEmail" class="profile-page-form-label">Correo electrónico</label>
                                    <input type="email" class="form-control profile-page-form-control" id="profilePageEmail" name="email" value="juan@example.com" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="profilePageZona" class="profile-page-form-label">Zona (solo lectura)</label>
                                <input type="text" class="form-control profile-page-form-control" id="profilePageZona" name="zona" value="Zona Norte" disabled>
                                <small class="profile-page-text-muted">Contacta al administrador para cambiar tu zona</small>
                            </div>
                            
                            <div class="mb-3 profile-page-social-input">
                                <label for="profilePageFacebook" class="profile-page-form-label">Perfil de Facebook</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-end-0"><i class="fab fa-facebook"></i></span>
                                    <input type="url" class="form-control profile-page-form-control border-start-0" id="profilePageFacebook" name="path_facebook" value="https://facebook.com/juanperez" placeholder="https://facebook.com/tu-usuario">
                                </div>
                            </div>
                            
                            <div class="profile-page-divider"></div>
                            
                            <div class="mb-4">
                                <label for="profilePagePassword" class="profile-page-form-label">Cambiar contraseña</label>
                                <div class="input-group">
                                    <input type="password" class="form-control profile-page-form-control" id="profilePagePassword" name="password" placeholder="Dejar en blanco para no cambiar">
                                    <span class="input-group-text profile-page-password-toggle" id="profilePageTogglePassword">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                                <small class="profile-page-text-muted">Mínimo 8 caracteres, incluyendo letras y números</small>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <button type="button" class="btn profile-page-btn-outline-primary me-md-2">Cancelar</button>
                                <button type="submit" class="btn profile-page-btn-primary">Guardar cambios</button>
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
            // Toggle para mostrar/ocultar contraseña
            const togglePassword = document.getElementById('profilePageTogglePassword');
            const passwordInput = document.getElementById('profilePagePassword');
            
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Cambiar icono
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
            
            // Previsualización de imagen al seleccionar un archivo
            const imageUpload = document.getElementById('profilePageImageUpload');
            const profileImage = document.getElementById('profilePageImage');
            
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
            
            // Validación del formulario
            const form = document.getElementById('profilePageForm');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validación básica
                const email = document.getElementById('profilePageEmail').value;
                const password = document.getElementById('profilePagePassword').value;
                
                if (!isValidEmail(email)) {
                    alert('Por favor, introduce un correo electrónico válido.');
                    return;
                }
                
                if (password && password.length < 8) {
                    alert('La contraseña debe tener al menos 8 caracteres.');
                    return;
                }
                
                // Aquí iría el código para enviar los datos al servidor
                alert('Cambios guardados correctamente');
                // form.submit();
            });
            
            function isValidEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }
        });
    </script>
</body>
@endsection