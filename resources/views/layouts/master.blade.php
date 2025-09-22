<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --success-color: #27ae60;
            --success-dark: #219653;
            --error-color: #e74c3c;
            --error-dark: #c0392b;
            --warning-color: #f39c12;
            --warning-dark: #d35400;
            --info-color: #3498db;
            --info-dark: #2980b9;
            --text-light: #ffffff;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            font-weight: 400;
            line-height: 1.6;
            color: #2d3748;
            background-color: #f8fafc;
        }
        
        .alert-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            max-width: 400px;
        }
        
        .alert {
            padding: 15px 20px;
            margin-bottom: 15px;
            border-radius: 12px;
            color: var(--text-light);
            font-weight: 500;
            box-shadow: var(--shadow-md);
            animation: fadeIn 0.5s cubic-bezier(0.18, 1.25, 0.4, 1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
            transition: var(--transition);
            transform-origin: top right;
        }
        
        .alert:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }
        
        .alert-success {
            background: linear-gradient(135deg, var(--success-color), var(--success-dark));
            border-left: 4px solid var(--success-dark);
        }
        
        .alert-error {
            background: linear-gradient(135deg, var(--error-color), var(--error-dark));
            border-left: 4px solid var(--error-dark);
        }
        
        .alert-warning {
            background: linear-gradient(135deg, var(--warning-color), var(--warning-dark));
            border-left: 4px solid var(--warning-dark);
        }
        
        .alert-info {
            background: linear-gradient(135deg, var(--info-color), var(--info-dark));
            border-left: 4px solid var(--info-dark);
        }
        
        .alert-content {
            flex: 1;
            font-size: 14px;
            letter-spacing: 0.01em;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        
        .alert-close {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: var(--text-light);
            font-size: 18px;
            cursor: pointer;
            margin-left: 15px;
            opacity: 0.8;
            transition: var(--transition);
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .alert-close:hover {
            opacity: 1;
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }
        
        .progress-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: linear-gradient(to right, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.4));
            width: 100%;
            animation: progressBar 5s linear forwards;
            border-radius: 0 0 12px 12px;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
            to {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
        }
        
        @keyframes progressBar {
            from {
                width: 100%;
            }
            to {
                width: 0%;
            }
        }
        
        .alert.fade-out {
            animation: fadeOut 0.5s cubic-bezier(0.4, 0, 1, 1) forwards;
        }
        
        /* Mejoras generales de estilo */
        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
            color: #1a202c;
            letter-spacing: -0.025em;
        }
        
        h1 {
            font-size: 2.5rem;
            background: linear-gradient(135deg, #4a5568, #1a202c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1.5rem;
        }
        
        p {
            font-size: 1.125rem;
            color: #4a5568;
        }
        
        .btn {
            display: inline-block;
            font-weight: 600;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            background-image: linear-gradient(135deg, #4a5568, #1a202c);
            color: white;
            border: none;
            border-radius: 8px;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 0.875rem;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            overflow: hidden;
        }
        
        .card:hover {
            box-shadow: var(--shadow-md);
        }
        
        .card-header {
            font-weight: 600;
            color: #1a202c;
            border-bottom: 1px solid #e2e8f0;
            font-size: 1.25rem;
        }
        
        .form-control {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            transition: var(--transition);
            font-family: 'Inter', sans-serif;
        }
        
        .form-control:focus {
            border-color: #4299e1;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
        }
        
        /* Efectos de transición para elementos de la página */
        * {
            transition: color 0.2s ease, background-color 0.3s ease, border-color 0.2s ease;
        }
    </style>
</head>
<body>
    <div class="alert-container">
        @if(session('success'))
            <div class="alert alert-success" id="alert-success">
                <div class="alert-content">
                    {{ session('success') }}
                </div>
                <button class="alert-close" onclick="closeAlert('alert-success')">×</button>
                <div class="progress-bar"></div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error" id="alert-error">
                <div class="alert-content">
                    {{ session('error') }}
                </div>
                <button class="alert-close" onclick="closeAlert('alert-error')">×</button>
                <div class="progress-bar"></div>
            </div>
        @endif
        
        @if(session('warning'))
            <div class="alert alert-warning" id="alert-warning">
                <div class="alert-content">
                    {{ session('warning') }}
                </div>
                <button class="alert-close" onclick="closeAlert('alert-warning')">×</button>
                <div class="progress-bar"></div>
            </div>
        @endif
        
        @if(session('info'))
            <div class="alert alert-info" id="alert-info">
                <div class="alert-content">
                    {{ session('info') }}
                </div>
                <button class="alert-close" onclick="closeAlert('alert-info')">×</button>
                <div class="progress-bar"></div>
            </div>
        @endif
    </div>

    @yield('content')

    <script>
        // Cerrar alertas automáticamente después de 5 segundos
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            
            alerts.forEach(alert => {
                setTimeout(() => {
                    closeAlert(alert.id);
                }, 5000);
            });
        });
        
        // Función para cerrar alertas manualmente
        function closeAlert(alertId) {
            const alert = document.getElementById(alertId);
            if (alert) {
                alert.classList.add('fade-out');
                setTimeout(() => {
                    alert.remove();
                }, 500);
            }
        }
        
        // Cerrar alerta al hacer clic fuera de ella
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.alert') && !event.target.classList.contains('alert-close')) {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    alert.classList.add('fade-out');
                    setTimeout(() => {
                        alert.remove();
                    }, 500);
                });
            }
        });
    </script>
</body>
</html>