<!DOCTYPE html>
<html lang="es">
<head>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña | Nelva Bienes Raíces</title>
    <link rel="icon" type="image/png" href="{{ asset('/images/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('/css/login.css') }}">
</head>
<body>
    <div class="login-container">
        <div class="login-card" id="resetCard">
            <div class="card-header">
                <div class="logo-container">
                    <img src="/images/logo.png" alt="Logo" class="company-logo">
                </div>
                <h1 class="login-title">Recuperar Contraseña</h1>
                <p class="login-subtitle">Te enviaremos un código de 6 dígitos a tu correo</p>
            </div>

            <!-- Mensaje de éxito (si los datos son correctos) -->
            @if(session('status'))
                <div class="alert-container">
                    <div class="alert alert-success">
                        <i class="fas fa-envelope"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                </div>
            @endif

            <!-- Errores -->
            @if($errors->any())
                <div class="error-container">
                    <i class="fas fa-exclamation-circle"></i>
                    <div class="error-content">
                        <ul class="error-list">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="login-form">
                @csrf

                <div class="input-group">
                    <div class="input-container">
                        <i class="fas fa-user"></i>
                        <input type="text" name="usuario_nombre" value="{{ old('usuario_nombre') }}" required autofocus placeholder=" ">
                        <label>Nombre de usuario</label>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-container">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder=" ">
                        <label>Correo electrónico</label>
                    </div>
                </div>

                <button type="submit" class="login-button">
                    <span class="button-text">Enviar código</span>
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>

            <div class="footer">
                <a href="{{ route('login') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i> Volver al inicio de sesión
                </a>
                <p>© 2025 Nelva Bienes Raíces. Todos los derechos reservados.</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                if (input.value) input.parentElement.classList.add('filled');
                input.addEventListener('focus', () => input.parentElement.classList.add('focused'));
                input.addEventListener('blur', () => {
                    input.parentElement.classList.remove('focused');
                    if (input.value) input.parentElement.classList.add('filled');
                    else input.parentElement.classList.remove('filled');
                });
            });
        });
    </script>
</body>
</html>