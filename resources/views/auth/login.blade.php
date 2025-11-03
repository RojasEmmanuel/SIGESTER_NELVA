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
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #3b82f6;
            --secondary: #64748b;
            --light-bg: #f8fafc;
            --card-bg: #ffffff;
            --text: #334155;
            --text-light: #64748b;
            --error: #ef4444;
            --success: #10b981;
            --border: #e2e8f0;
            --border-focus: #93c5fd;
            --shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-hover: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --shadow-card: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --gradient: linear-gradient(135deg, #185cdd 0%, #4facfe 50%, #90ceff 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: var(--gradient);
            color: var(--text);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            line-height: 1.5;
            position: relative;
            overflow-x: hidden;
        }

        /* Efectos de fondo decorativos */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 10% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 20%);
            z-index: -1;
        }

        .login-container {
            width: 100%;
            max-width: 440px;
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-card {
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: var(--shadow);
            padding: 40px 32px;
            width: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--primary-light));
        }

        .login-card:hover {
            box-shadow: var(--shadow-hover);
            transform: translateY(-5px);
        }

        .card-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-container {
            margin-bottom: 24px;
            padding: 10px;
            border-radius: 12px;
            background: rgba(37, 99, 235, 0.05);
            display: inline-block;
        }

        .company-logo {
            max-width: 250px;
            height: auto;
            transition: transform 0.3s ease;
        }

        .logo-container:hover .company-logo {
            transform: scale(1.05);
        }

        .login-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 8px;
            background: linear-gradient(90deg, var(--primary), var(--primary-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .login-subtitle {
            font-size: 15px;
            color: var(--text-light);
            max-width: 300px;
            margin: 0 auto;
        }

        .login-form {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .input-group {
            display: flex;
            flex-direction: column;
        }

        .input-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-container i:first-child {
            position: absolute;
            left: 16px;
            color: var(--text-light);
            font-size: 18px;
            z-index: 1;
            transition: color 0.2s ease;
        }

        .input-container input {
            width: 100%;
            padding: 16px 16px 16px 48px;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-size: 16px;
            background-color: var(--card-bg);
            transition: all 0.2s ease;
            box-shadow: var(--shadow-card);
        }

        .input-container label {
            position: absolute;
            left: 48px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            pointer-events: none;
            transition: all 0.2s ease;
            font-size: 16px;
            background: transparent;
        }

        .input-container input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .input-container input:focus + label,
        .input-container input:not(:placeholder-shown) + label,
        .input-container.filled label {
            top: 0;
            left: 48px;
            font-size: 12px;
            background: var(--card-bg);
            padding: 0 8px;
            color: var(--primary);
            font-weight: 500;
        }

        .input-container input:focus ~ i:first-child {
            color: var(--primary);
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            color: var(--text-light);
            cursor: pointer;
            font-size: 18px;
            transition: color 0.2s ease;
            z-index: 2;
        }

        .toggle-password:hover {
            color: var(--primary);
        }

        .options-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: transform 0.1s ease;
        }

        .checkbox-container:hover {
            transform: translateY(-1px);
        }

        .checkbox-container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .checkmark {
            height: 20px;
            width: 20px;
            background-color: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 5px;
            margin-right: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            box-shadow: var(--shadow-card);
        }

        .checkbox-container input:checked ~ .checkmark {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .checkmark:after {
            content: "";
            display: none;
            width: 4px;
            height: 8px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
            position: relative;
            top: -1px;
        }

        .checkbox-container input:checked ~ .checkmark:after {
            display: block;
        }

        .checkbox-label {
            color: var(--text);
            font-weight: 500;
        }

        .forgot-password {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
            position: relative;
        }

        .forgot-password::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background: var(--primary);
            transition: width 0.3s ease;
        }

        .forgot-password:hover {
            color: var(--primary-dark);
        }

        .forgot-password:hover::after {
            width: 100%;
        }

        .login-button {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            border: none;
            padding: 16px 24px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 8px;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.3);
            position: relative;
            overflow: hidden;
        }

        .login-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .login-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.4);
        }

        .login-button:hover::before {
            left: 100%;
        }

        .login-button:active {
            transform: translateY(0);
        }

        .alert-container {
            margin-top: 20px;
            animation: slideIn 0.4s ease-out;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-10px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .alert {
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideIn 0.4s ease-out;
            box-shadow: var(--shadow-card);
        }

        .alert-success {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .alert-error {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .alert i {
            font-size: 20px;
        }

        .error-container {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 16px;
            margin-top: 24px;
            display: flex;
            gap: 12px;
            animation: slideIn 0.4s ease-out;
            box-shadow: var(--shadow-card);
        }

        .error-container i {
            color: var(--error);
            font-size: 20px;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .error-content h3 {
            font-size: 16px;
            font-weight: 600;
            color: var(--error);
            margin-bottom: 4px;
        }

        .error-list {
            list-style: none;
            font-size: 14px;
            color: var(--text);
        }

        .error-list li {
            margin-bottom: 4px;
            position: relative;
            padding-left: 12px;
        }

        .error-list li::before {
            content: '•';
            position: absolute;
            left: 0;
            color: var(--error);
        }

        .footer {
            text-align: center;
            margin-top: 32px;
            font-size: 12px;
            color: var(--text-light);
            padding-top: 16px;
            border-top: 1px solid var(--border);
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .login-card {
                padding: 24px 20px;
            }
            
            .options-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }
            
            .forgot-password {
                margin-left: 28px;
            }
            
            .login-title {
                font-size: 24px;
            }
        }

        @media (max-width: 400px) {
            .login-title {
                font-size: 22px;
            }
            
            .input-container input {
                padding: 14px 14px 14px 44px;
            }
            
            .input-container i:first-child {
                left: 14px;
            }
            
            .input-container input:focus + label,
            .input-container input:not(:placeholder-shown) + label,
            .input-container.filled label {
                left: 44px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
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
        });
    </script>
</body>
</html>