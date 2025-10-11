<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nelva Bienes Raíces')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/navbar.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('/images/favicon.ico') }}" type="image/x-icon">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
   @stack('styles')
</head>
<body>
    <!-- Navbar Desktop -->
    <nav class="navbar-desktop">
        <div class="navbar-desktop-container">
            <div class="logo">
                <img src="/images/Logo.png"  width="200px">
            </div>
            <div class="nav-links">
                <a href="{{ url('asesor/inicio') }}" class="{{ Request::is('inicio') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Inicio
                </a>
                <a href="{{ url('asesor/apartados') }}" class="{{ Request::is('apartados') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i> Apartados
                </a>
                <a href="{{ url('asesor/ventas') }}" class="{{ Request::is('ventas') ? 'active' : '' }}">
                    <i class="fas fa-history"></i> Ventas
                </a>
               <a href="{{ route('asesor.perfil.index') }}" class="{{ Request::routeIs('asesor.perfil') ? 'active' : '' }}">
                    <i class="fas fa-user"></i> Perfil
                </a>
                <a href="#" id="logout-btn-desktop">
                    <i class="fas fa-sign-out-alt"></i> Salir
                </a>
            </div>
        </div>
    </nav>

    <!-- Mobile Header -->
    <div class="mobile-header">
        <div class="mobile-header-container">
            <img src="/images/Logo.png" width="150px">
            <button class="hamburger-btn" id="hamburger-btn">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

    <!-- Navbar Mobile (Overlay) -->
    <nav class="navbar-mobile" id="navbar-mobile">
        <div class="navbar-mobile-container">
            <button class="close-btn" id="close-btn">
                <i class="fas fa-times"></i>
            </button>
            <div class="nav-items">
                <a href="{{ url('asesor/inicio') }}" class="nav-item {{ Request::is('inicio') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span>
                </a>
                <a href="{{ url('asesor/apartados') }}" class="nav-item {{ Request::is('apartados') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    <span>Apartados</span>
                </a>
                <a href="{{ url('asesor/ventas') }}" class="nav-item {{ Request::is('ventas') ? 'active' : '' }}">
                    <i class="fas fa-history"></i>
                    <span>Ventas</span>
                </a>
                <a href="{{ route('asesor.perfil.index') }}" class="nav-item {{ Request::routeIs('asesor.perfil') ? 'active' : '' }}">
                    <i class="fas fa-user"></i>
                    <span>Perfil</span>
                </a>
                <a href="#" class="nav-item" id="logout-btn-mobile">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Salir</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        @yield('content')
        
        @yield('scripts')
    </div>

    <!-- Modal de cierre de sesión -->
    <!-- Modal de cierre de sesión -->
    <div id="logout-modal" style="display: none;">
        <div class="modal-overlay"></div>
        <div class="modal-content">
            <h2>¿Cerrar sesión?</h2>
            <p>¿Estás seguro que deseas salir de tu cuenta?</p>
            <div class="modal-actions">
                <button id="confirm-logout" class="btn btn-primary">Sí, salir</button>
                <button id="cancel-logout" class="btn btn-outline">Cancelar</button>
            </div>
        </div>
    </div>

    <script>
        // Funcionalidad del menú hamburguesa
        const hamburgerBtn = document.getElementById('hamburger-btn');
        const closeBtn = document.getElementById('close-btn');
        const navbarMobile = document.getElementById('navbar-mobile');
        
        if (hamburgerBtn && closeBtn && navbarMobile) {
            hamburgerBtn.addEventListener('click', function() {
                navbarMobile.classList.add('active');
            });
            
            closeBtn.addEventListener('click', function() {
                navbarMobile.classList.remove('active');
            });

            // Cerrar menú al hacer clic en un enlace
            document.querySelectorAll('.nav-item').forEach(item => {
                item.addEventListener('click', function() {
                    navbarMobile.classList.remove('active');
                });
            });
        }

        // Modal de cierre de sesión - CÓDIGO MEJORADO
        const logoutBtns = [
            document.getElementById('logout-btn-desktop'), 
            document.getElementById('logout-btn-mobile')
        ];
        const modal = document.getElementById('logout-modal');
        const confirmBtn = document.getElementById('confirm-logout');
        const cancelBtn = document.getElementById('cancel-logout');

        if (modal && confirmBtn && cancelBtn) {
            // Función para mostrar el modal
            function showModal() {
                modal.style.display = 'block';
                // Prevenir scroll del body cuando el modal está abierto
                document.body.style.overflow = 'hidden';
            }

            // Función para ocultar el modal
            function hideModal() {
                modal.style.display = 'none';
                // Restaurar scroll del body
                document.body.style.overflow = 'auto';
            }

            // Agregar event listeners a los botones de logout
            logoutBtns.forEach(btn => {
                if (btn) {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        showModal();
                    });
                }
            });

            // Botón cancelar
            cancelBtn.addEventListener('click', hideModal);

            // Botón confirmar
            confirmBtn.addEventListener('click', function() {
                // Redirige o realiza el cierre de sesión
                window.location.href = '{{ url("/") }}';
            });

            // Cerrar modal al hacer clic fuera del contenido
            if (modal.querySelector('.modal-overlay')) {
                modal.querySelector('.modal-overlay').addEventListener('click', hideModal);
            }

            // Cerrar modal con la tecla Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal.style.display === 'block') {
                    hideModal();
                }
            });
        }
    </script>
    
    @stack('scripts')
</body>

</html>