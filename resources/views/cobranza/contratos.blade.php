<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nelva Bienes Raíces</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1e478a;
            --primary-light: #6366f1;
            --secondary-color: #3d86df;
            --accent-color: #e1f3fd;
            --text-color: #334155;
            --text-light: #64748b;
            --light-gray: #f8fafc;
            --medium-gray: #e2e8f0;
            --dark-gray: #94a3b8;
            --success-color: #10b981;
            --success-light: #a7f3d0;
            --warning-color: #f59e0b;
            --warning-light: #fde68a;
            --white: #ffffff;
            --dark-bg: #1e293b;
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --rounded: 12px;
            --rounded-sm: 8px;
            --blue-accent: #3b82f6;
            --transition: all 0.2s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            color: var(--text-color);
            background-color: var(--light-gray);
            min-height: 100vh;
            padding-bottom: 80px;
            line-height: 1.5;
        }

        /* Navbar Desktop */
        .navbar-desktop {
            display: none;
            background-color: var(--white);
            box-shadow: var(--shadow);
            padding: 0.75rem 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-desktop-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .logo {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo i {
            font-size: 1.5rem;
        }

        .nav-links {
            display: flex;
            gap: 1.25rem;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-light);
            font-weight: 500;
            font-size: 0.9rem;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0;
            position: relative;
        }

        .nav-links a:hover {
            color: var(--primary-color);
        }

        .nav-links a.active {
            color: var(--primary-color);
            font-weight: 600;
        }

        .nav-links a.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: var(--primary-color);
            border-radius: 3px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-left: 1rem;
            padding-left: 1rem;
            border-left: 1px solid var(--medium-gray);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* Navbar Mobile */
        .navbar-mobile {
            display: flex;
            justify-content: space-around;
            align-items: center;
            background-color: var(--white);
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 0.5rem 0;
            z-index: 100;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: var(--dark-gray);
            font-size: 0.7rem;
            transition: var(--transition);
            padding: 0.4rem;
            border-radius: var(--rounded-sm);
        }

        .nav-item i {
            font-size: 1.1rem;
            margin-bottom: 0.2rem;
        }

        .nav-item.active {
            color: var(--primary-color);
            background-color: var(--light-gray);
        }

        /* Modal para logout */
        .modal-overlay {
            position: fixed; 
            left: 0; 
            top: 0; 
            width: 100vw; 
            height: 100vh;
            background: rgba(30,71,138,0.18); 
            z-index: 999;
            display: none;
        }
        
        .modal-content {
            position: fixed; 
            left: 50%; 
            top: 50%; 
            transform: translate(-50%,-50%);
            background: var(--white); 
            border-radius: var(--rounded); 
            box-shadow: var(--shadow-md);
            padding: 2rem 1.5rem; 
            z-index: 1000; 
            min-width: 300px; 
            text-align: center;
            display: none;
        }
        
        .modal-actions { 
            margin-top: 1.5rem; 
            display: flex; 
            gap: 1rem; 
            justify-content: center; 
        }

        .btn {
            padding: 0.6rem 1.2rem;
            border-radius: var(--rounded-sm);
            border: none;
            font-weight: 500;
            font-size: 0.85rem;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: var(--white);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
        }

        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
        }
        
        .btn-outline:hover {
            background-color: var(--accent-color);
        }

        /* Desktop Styles */
        @media (min-width: 768px) {
            .navbar-desktop {
                display: block;
            }

            .navbar-mobile {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar Desktop -->
    <nav class="navbar-desktop">
        <div class="navbar-desktop-container">
            <div class="logo">
                <i class="fas fa-home"></i>
                <span>Nelva Bienes Raíces</span>
            </div>
            <div style="display: flex; align-items: center;">
                <div class="nav-links" id="nav-links">
                    <!-- Los enlaces se cargarán dinámicamente según el tipo de usuario -->
                </div>
                <div class="user-info">
                    <div class="user-avatar" id="user-avatar">U</div>
                    <div>
                        <div id="user-name">Usuario</div>
                        <div id="user-role" style="font-size: 0.7rem; color: var(--text-light);">Rol</div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Navbar Mobile -->
    <nav class="navbar-mobile" id="navbar-mobile">
        <!-- Los elementos se cargarán dinámicamente según el tipo de usuario -->
    </nav>

    <!-- Modal para logout -->
    <div class="modal-overlay" id="logout-modal-overlay"></div>
    <div class="modal-content" id="logout-modal">
        <h2>¿Cerrar sesión?</h2>
        <p>¿Estás seguro que deseas salir de tu cuenta?</p>
        <div class="modal-actions">
            <button id="confirm-logout" class="btn btn-primary">Sí, salir</button>
            <button id="cancel-logout" class="btn btn-outline">Cancelar</button>
        </div>
    </div>

    <script>
        // Datos del usuario (en una aplicación real estos vendrían del backend)
        // Simulamos que obtenemos el tipo de usuario desde el backend
        const userTypes = {
            ADMIN: 1,
            ASESOR: 2,
            COBRANZA: 3,
            INGENIERO: 4
        };

        // Obtener el tipo de usuario (en una app real esto vendría de la sesión)
        // Por ahora lo simulamos con un parámetro en la URL o valor por defecto
        const urlParams = new URLSearchParams(window.location.search);
        const userType = parseInt(urlParams.get('userType')) || userTypes.ADMIN;
        
        // Datos de ejemplo para cada tipo de usuario
        const users = {
            [userTypes.ADMIN]: {
                name: "Administrador",
                avatar: "A",
                role: "Administrador"
            },
            [userTypes.ASESOR]: {
                name: "Juan Pérez",
                avatar: "J",
                role: "Asesor de Ventas"
            },
            [userTypes.COBRANZA]: {
                name: "María García",
                avatar: "M",
                role: "Departamento de Cobranza"
            },
            [userTypes.INGENIERO]: {
                name: "Carlos López",
                avatar: "C",
                role: "Ingeniero"
            }
        };

        // Definir las opciones de navegación para cada tipo de usuario
        const navOptions = {
            [userTypes.ADMIN]: [
                { href: "admin-dashboard", icon: "fas fa-home", text: "Dashboard", mobile: true },
                { href: "fraccionamientos", icon: "fas fa-city", text: "Fraccionamientos", mobile: true },
                { href: "usuarios", icon: "fas fa-users", text: "Usuarios", mobile: true },
                { href: "reportes", icon: "fas fa-chart-bar", text: "Reportes", mobile: true },
                { href: "configuracion", icon: "fas fa-cog", text: "Configuración", mobile: false }
            ],
            [userTypes.ASESOR]: [
                { href: "asesor-dashboard", icon: "fas fa-home", text: "Inicio", mobile: true },
                { href: "clientes", icon: "fas fa-users", text: "Clientes", mobile: true },
                { href: "apartados", icon: "fas fa-calendar-check", text: "Apartados", mobile: true },
                { href: "ventas", icon: "fas fa-history", text: "Ventas", mobile: true },
                { href: "prospectos", icon: "fas fa-address-book", text: "Prospectos", mobile: false }
            ],
            [userTypes.COBRANZA]: [
                { href: "cobranza-dashboard", icon: "fas fa-home", text: "Inicio", mobile: true },
                { href: "pagos", icon: "fas fa-money-bill-wave", text: "Pagos", mobile: true },
                { href: "adeudos", icon: "fas fa-exclamation-triangle", text: "Adeudos", mobile: true },
                { href: "reportes-cobranza", icon: "fas fa-chart-pie", text: "Reportes", mobile: true },
                { href: "clientes-morosos", icon: "fas fa-user-times", text: "Clientes Morosos", mobile: false }
            ],
            [userTypes.INGENIERO]: [
                { href: "ingeniero-dashboard", icon: "fas fa-home", text: "Inicio", mobile: true },
                { href: "planos", icon: "fas fa-drafting-compass", text: "Planos", mobile: true },
                { href: "proyectos", icon: "fas fa-hard-hat", text: "Proyectos", mobile: true },
                { href: "avances", icon: "fas fa-tasks", text: "Avances", mobile: true },
                { href: "materiales", icon: "fas fa-tools", text: "Materiales", mobile: false }
            ]
        };

        // Función para cargar la navegación según el tipo de usuario
        function loadNavigation() {
            const user = users[userType];
            const options = navOptions[userType];
            
            // Actualizar información del usuario
            document.getElementById('user-name').textContent = user.name;
            document.getElementById('user-role').textContent = user.role;
            document.getElementById('user-avatar').textContent = user.avatar;
            
            // Cargar navegación desktop
            const navLinksDesktop = document.getElementById('nav-links');
            navLinksDesktop.innerHTML = ''; // Limpiar existentes
            
            options.forEach(option => {
                const link = document.createElement('a');
                link.href = option.href;
                link.innerHTML = `<i class="${option.icon}"></i> ${option.text}`;
                
                // Marcar como activo si coincide con la página actual (simplificado)
                if (window.location.href.includes(option.href)) {
                    link.classList.add('active');
                }
                
                navLinksDesktop.appendChild(link);
            });
            
            // Añadir enlace de logout en desktop
            const logoutLinkDesktop = document.createElement('a');
            logoutLinkDesktop.href = '#';
            logoutLinkDesktop.id = 'logout-btn-desktop';
            logoutLinkDesktop.innerHTML = '<i class="fas fa-sign-out-alt"></i> Salir';
            navLinksDesktop.appendChild(logoutLinkDesktop);
            
            // Cargar navegación mobile
            const navMobile = document.getElementById('navbar-mobile');
            navMobile.innerHTML = ''; // Limpiar existentes
            
            // Solo incluir opciones marcadas como mobile: true
            options.filter(option => option.mobile).forEach(option => {
                const link = document.createElement('a');
                link.href = option.href;
                link.classList.add('nav-item');
                link.innerHTML = `<i class="${option.icon}"></i><span>${option.text}</span>`;
                
                // Marcar como activo si coincide con la página actual (simplificado)
                if (window.location.href.includes(option.href)) {
                    link.classList.add('active');
                }
                
                navMobile.appendChild(link);
            });
            
            // Añadir logout en mobile
            const logoutLinkMobile = document.createElement('a');
            logoutLinkMobile.href = '#';
            logoutLinkMobile.classList.add('nav-item');
            logoutLinkMobile.id = 'logout-btn-mobile';
            logoutLinkMobile.innerHTML = '<i class="fas fa-sign-out-alt"></i><span>Salir</span>';
            navMobile.appendChild(logoutLinkMobile);
            
            // Añadir event listeners para los botones de logout
            document.getElementById('logout-btn-desktop').addEventListener('click', function(e) {
                e.preventDefault();
                showLogoutModal();
            });
            
            document.getElementById('logout-btn-mobile').addEventListener('click', function(e) {
                e.preventDefault();
                showLogoutModal();
            });
        }
        
        // Función para mostrar el modal de logout
        function showLogoutModal() {
            document.getElementById('logout-modal-overlay').style.display = 'block';
            document.getElementById('logout-modal').style.display = 'block';
        }
        
        // Función para ocultar el modal de logout
        function hideLogoutModal() {
            document.getElementById('logout-modal-overlay').style.display = 'none';
            document.getElementById('logout-modal').style.display = 'none';
        }
        
        // Configurar event listeners para el modal
        document.getElementById('confirm-logout').addEventListener('click', function() {
            // Redirigir al logout (en una app real sería una petición al servidor)
            window.location.href = '/logout';
        });
        
        document.getElementById('cancel-logout').addEventListener('click', hideLogoutModal);
        document.getElementById('logout-modal-overlay').addEventListener('click', hideLogoutModal);
        
        // Cargar la navegación cuando el documento esté listo
        document.addEventListener('DOMContentLoaded', loadNavigation);
    </script>
</body>
</html>