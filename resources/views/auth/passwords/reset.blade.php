<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña | Nelva Bienes Raíces</title>
    <link rel="icon" type="image/png" href="{{ asset('/images/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('/css/login.css') }}">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="card-header">
                <div class="logo-container">
                    <img src="/images/logo.png" alt="Logo" class="company-logo">
                </div>
                <h1 class="login-title">Establecer Nueva Contraseña</h1>
                <p class="login-subtitle">Ingresa el código que enviamos a tu correo</p>
            </div>

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

                <form method="POST" action="{{ route('password.update', $token) }}" class="login-form">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="input-group">
                    <div class="input-container">
                        <i class="fas fa-key"></i>
                        <input 
                            type="text" 
                            name="code" 
                            inputmode="numeric"
                            pattern="[0-9\s\-]*"
                            maxlength="12"
                            required 
                            autofocus 
                            placeholder=" "
                            value="{{ old('code') }}"
                            class="text-center"
                            style="letter-spacing: 8px; font-size: 1.4rem;">
                        <label>Código de 6 dígitos</label>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-container">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" required placeholder=" ">
                        <label>Nueva contraseña (mínimo 8 caracteres)</label>
                        <i class="fas fa-eye toggle-password"></i>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-container">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password_confirmation" required placeholder=" ">
                        <label>Confirmar nueva contraseña</label>
                        <i class="fas fa-eye toggle-password"></i>
                    </div>
                </div>
                <button type="submit" class="login-button">
                    <span class="button-text">Cambiar contraseña</span>
                    <i class="fas fa-check"></i>
                </button>
            </form>

            <div class="footer">
                <a href="{{ route('password.request') }}" class="back-link">
                    <i class="fas fa-redo"></i> Enviar código otra vez
                </a>
                <p>© 2025 Nelva Bienes Raíces. Todos los derechos reservados.</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.toggle-password').forEach(icon => {
                icon.addEventListener('click', function () {
                    const input = this.parentElement.querySelector('input');
                    const type = input.type === 'password' ? 'text' : 'password';
                    input.type = type;
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                });
            });

            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                if (input.value) input.parentElement.classList.add('filled');
                input.addEventListener('focus', () => input.parentElement.classList.add('focused'));
                input.addEventListener('blur', () => {
                    input.parentElement.classList.remove('focused');
                    input.value ? input.parentElement.classList.add('filled') : input.parentElement.classList.remove('filled');
                });
            });
        });
    </script>
</body>
</html>