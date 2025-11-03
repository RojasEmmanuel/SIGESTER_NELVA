<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | Nelva Bienes Raíces</title>
    <link rel="icon" type="image/png" href="https://nelvabienesraices.com/wp-content/uploads/2025/01/cropped-MARCA_OFICIAL-1_Mesa-de-trabajo-1-1-1.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href="{{ asset('/images/favicon.ico') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('/css/login.css') }}">
    
</head>
<body>
    <div class="login-container">
        <div class="login-card" id="loginCard">
            <div class="card-header">
                <div class="logo-container">
                    <img src="/images/logo.png" alt="Logo Nelva Bienes Raíces" class="company-logo">
                </div>
                <h1 class="login-title">Iniciar Sesión</h1>
                <p class="login-subtitle">Accede a tu cuenta de Gestor de Terrenos</p>
            </div>
            
            <!-- Mostrar mensajes de éxito -->
            @if(session('success'))
                <div class="alert-container">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif
            
            <form id="loginForm" class="login-form" method="POST" action="{{ url('/login') }}">
                @csrf
                <div class="input-group">
                    <div class="input-container">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="usuario_nombre" placeholder=" " value="{{ old('usuario_nombre') }}" required>
                        <label for="username">Usuario</label>
                    </div>
                </div>
                
                <div class="input-group">
                    <div class="input-container">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder=" " required>
                        <label for="password">Contraseña</label>
                        <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                    </div>
                </div>
                
                <div class="options-row">
                    <label class="checkbox-container">
                        <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                        <span class="checkbox-label">Recuérdame</span>
                    </label>
                    <a href="#" class="forgot-password">¿Olvidaste tu contraseña?</a>
                </div>
                
                <button type="submit" class="login-button">
                    <span class="button-text">Acceder</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <!-- Contenedor de error tradicional (oculto) -->
            @if ($errors->any())
                <div class="error-container">
                    <i class="fas fa-exclamation-circle"></i>
                    <div class="error-content">
                        <h3>Error de acceso</h3>
                        <ul class="error-list">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            
            <div class="footer">
                <p>© 2025 Nelva Bienes Raíces. Todos los derechos reservados.</p>
            </div>
        </div>
    </div>

    <!-- Notificación de error flotante -->
    @if ($errors->any())
    <div class="error-notification" id="errorNotification">
        <i class="fas fa-exclamation-triangle"></i>
        <div class="error-notification-content">
            <h4>Error de acceso</h4>
            <p>{{ $errors->first() }}</p>
        </div>
        <button class="error-notification-close" onclick="closeErrorNotification()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            
            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        togglePassword.classList.remove('fa-eye');
                        togglePassword.classList.add('fa-eye-slash');
                    } else {
                        passwordInput.type = 'password';
                        togglePassword.classList.remove('fa-eye-slash');
                        togglePassword.classList.add('fa-eye');
                    }
                });
            }
            
            // Add floating label functionality
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                // Check if input has value on page load
                if (input.value) {
                    input.parentElement.classList.add('filled');
                }
                
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                    if (this.value) {
                        this.parentElement.classList.add('filled');
                    } else {
                        this.parentElement.classList.remove('filled');
                    }
                });
            });

            // Mostrar notificación de error si existe
            const errorNotification = document.getElementById('errorNotification');
            if (errorNotification) {
                // Agregar efecto de vibración al formulario
                const loginCard = document.getElementById('loginCard');
                if (loginCard) {
                    loginCard.classList.add('shake');
                    setTimeout(() => {
                        loginCard.classList.remove('shake');
                    }, 600);
                }

                // Mostrar notificación después de un breve delay
                setTimeout(() => {
                    errorNotification.classList.add('show');
                    
                    // Auto-ocultar después de 5 segundos
                    setTimeout(() => {
                        closeErrorNotification();
                    }, 5000);
                }, 300);
            }
        });

        function closeErrorNotification() {
            const errorNotification = document.getElementById('errorNotification');
            if (errorNotification) {
                errorNotification.classList.remove('show');
                errorNotification.classList.add('hide');
                
                // Remover del DOM después de la animación
                setTimeout(() => {
                    if (errorNotification.parentNode) {
                        errorNotification.parentNode.removeChild(errorNotification);
                    }
                }, 500);
            }
        }

        // Cerrar notificación al hacer clic fuera de ella
        document.addEventListener('click', function(event) {
            const errorNotification = document.getElementById('errorNotification');
            if (errorNotification && !errorNotification.contains(event.target)) {
                closeErrorNotification();
            }
        });

        // También cerrar con la tecla Escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeErrorNotification();
            }
        });
    </script>
</body>
</html>