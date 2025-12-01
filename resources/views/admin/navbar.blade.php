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

    @php
        $pendientesCount = \Cache::remember('admin_pendientes_count', now()->addMinutes(3), fn() =>
            \App\Models\Apartado::where('tipoApartado', 'deposito')->where('estatus', 'en curso')
                ->whereHas('deposito', fn($q) => $q->where('ticket_estatus', 'solicitud'))
                ->count()
        );

        $historialCount = \Cache::remember('admin_historial_ticket_count', now()->addMinutes(3), fn() =>
            \App\Models\Venta::where('ticket_estatus', 'solicitud')->count()
        );
    @endphp
        <!-- Navbar Desktop -->
    <nav class="navbar-desktop">
        <div class="navbar-desktop-container">
            <div class="logo">
                <img src="/images/Logo.png" width="200px" alt="Nelva Bienes Raíces">
            </div>
            <div class="nav-links-desktop">

                <!-- Principal -->
                <a href="{{ route('admin.index') }}" class="{{ Request::is('admin/inicio*', 'inicio*') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Inicio
                </a>
                <a href="{{ route('asesor.perfil.index') }}" class="{{ Request::routeIs('asesor.perfil*') ? 'active' : '' }}">
                    <i class="fas fa-user-circle"></i> Perfil
                </a>

                <!-- Operaciones -->
                <a href="{{ url('asesor/apartados') }}" class="{{ Request::is('asesor/apartados*') ? 'active' : '' }}">
                    <i class="fas fa-bookmark"></i> Apartados
                </a>
                <a href="{{ url('asesor/ventas') }}" class="{{ Request::is('asesor/ventas*') ? 'active' : '' }}">
                    <i class="fas fa-dollar-sign"></i> Ventas
                </a>

                <!-- Administración -->
                <a href="{{ url('admin/usuarios') }}" class="{{ Request::is('admin/usuarios*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Usuarios
                </a>
                <!-- Pendientes -->
                <a href="{{ url('admin/apartados-pendientes') }}" class="{{ Request::is('admin/apartados-pendientes*') ? 'active' : '' }}">
                    <i class="fas fa-clock"></i> Pendientes
                    @if($pendientesCount ?? 0 > 0)
                        <span class="badge-counter">{{ $pendientesCount }}</span>
                    @endif
                </a>

                <!-- Historial -->
                <a href="{{ url('admin/ventas') }}" class="{{ Request::is('admin/ventas*') ? 'active' : '' }}">
                    <i class="fas fa-history"></i> Historial
                    @if($historialCount ?? 0 > 0)
                        <span class="badge-counter">{{ $historialCount }}</span>
                    @endif
                </a>

                <!-- Salir -->
                <a href="#" id="logout-btn-desktop" class="logout-link">
                    <i class="fas fa-sign-out-alt"></i> Salir
                </a>
            </div>
        </div>
    </nav>

    <!-- Mobile Header -->
    <div class="mobile-header">
        <div class="mobile-header-container">
            <img src="/images/Logo.png" width="150px" alt="Nelva Bienes Raíces">
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

                <!-- Principal -->
                <div class="nav-group-mobile">
                    <span class="group-title">Principal</span>
                    <a href="{{ route('admin.index') }}" class="nav-item {{ Request::is('admin/inicio*', 'inicio*') ? 'active' : '' }}">
                        <i class="fas fa-home"></i> <span>Inicio</span>
                    </a>
                    <a href="{{ route('asesor.perfil.index') }}" class="nav-item {{ Request::routeIs('asesor.perfil*') ? 'active' : '' }}">
                        <i class="fas fa-user-circle"></i> <span>Perfil</span>
                    </a>
                </div>

                <!-- Operaciones -->
                <div class="nav-group-mobile">
                    <span class="group-title">Operaciones</span>
                    <a href="{{ url('asesor/apartados') }}" class="nav-item {{ Request::is('asesor/apartados*') ? 'active' : '' }}">
                        <i class="fas fa-bookmark"></i> <span>Apartados</span>
                    </a>
                    <a href="{{ url('asesor/ventas') }}" class="nav-item {{ Request::is('asesor/ventas*') ? 'active' : '' }}">
                        <i class="fas fa-dollar-sign"></i> <span>Ventas</span>
                    </a>
                </div>

                <!-- Administración -->
                <div class="nav-group-mobile">
                    <span class="group-title">Administración</span>
                    <a href="{{ url('admin/usuarios') }}" class="nav-item {{ Request::is('admin/usuarios*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i> <span>Usuarios</span>
                    </a>
                    <!-- Pendientes - Móvil -->
                    <a href="{{ url('admin/apartados-pendientes') }}" 
                    class="nav-item {{ Request::is('admin/apartados-pendientes*') ? 'active' : '' }}">
                        <i class="fas fa-clock"></i> 
                        <span>Pendientes</span>
                        @if(($pendientesCount ?? 0) > 0)
                            <span class="badge-counter">
                                {{ $pendientesCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Historial - Móvil -->
                    <a href="{{ url('admin/ventas') }}" 
                    class="nav-item {{ Request::is('admin/ventas*') ? 'active' : '' }}">
                        <i class="fas fa-history"></i> 
                        <span>Historial</span>
                        @if(($historialCount ?? 0) > 0)
                            <span class="badge-counter" >
                                {{ $historialCount }}
                            </span>
                        @endif
                    </a>
                </div>

                <!-- Salir -->
                <div class="nav-group-mobile logout-group-mobile">
                    <a href="#" class="nav-item" id="logout-btn-mobile">
                        <i class="fas fa-sign-out-alt"></i> <span>Salir</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        @yield('content')
        @yield('scripts')
    </div>

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
        // Menú hamburguesa
        const hamburgerBtn = document.getElementById('hamburger-btn');
        const closeBtn = document.getElementById('close-btn');
        const navbarMobile = document.getElementById('navbar-mobile');

        if (hamburgerBtn && closeBtn && navbarMobile) {
            hamburgerBtn.addEventListener('click', () => navbarMobile.classList.add('active'));
            closeBtn.addEventListener('click', () => navbarMobile.classList.remove('active'));

            document.querySelectorAll('.nav-item').forEach(item => {
                item.addEventListener('click', () => navbarMobile.classList.remove('active'));
            });
        }

        // Modal de logout
        const logoutBtns = [document.getElementById('logout-btn-desktop'), document.getElementById('logout-btn-mobile')];
        const modal = document.getElementById('logout-modal');
        const confirmBtn = document.getElementById('confirm-logout');
        const cancelBtn = document.getElementById('cancel-logout');

        if (modal && confirmBtn && cancelBtn) {
            const showModal = () => {
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            };
            const hideModal = () => {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            };

            logoutBtns.forEach(btn => btn?.addEventListener('click', e => { e.preventDefault(); showModal(); }));
            cancelBtn.addEventListener('click', hideModal);

            confirmBtn.addEventListener('click', () => {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("logout") }}';
                form.style.display = 'none';

                const token = document.createElement('input');
                token.type = 'hidden';
                token.name = '_token';
                token.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                form.appendChild(token);
                document.body.appendChild(form);
                form.submit();
            });

            modal.querySelector('.modal-overlay')?.addEventListener('click', hideModal);

            document.addEventListener('keydown', e => {
                if (e.key === 'Escape' && modal.style.display === 'block') hideModal();
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>