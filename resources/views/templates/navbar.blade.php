<!DOCTYPE html>
<html>
<head>
    <!-- Incluir Font Awesome para los iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="icon" href="{{ asset('/images/favicon.ico') }}" type="image/x-icon">
    <title>{{ $title }}</title>
    <style>
        /* Estilos generales */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            overflow-x: hidden;
            color: #022F4A;
            background-color: #f9f9f9;
            padding-top: 80px; /* Espacio para el navbar fijo */
        }

        /* Estilos del navbar */
        /* Barra superior negra - Ocultar en móviles */
        .top-bar {
            background-color: #022F4A;
            color: white;
            padding: 12px 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 30px;
        }
        
        .top-bar-content {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 30px;
            max-width: 1200px;
            width: 100%;
        }
        
        .contact-info {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .contact-info a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }
        
        .contact-info a:hover {
            text-decoration: underline;
        }
        
        .address {
            display: flex;
            align-items: center;
            gap: 8px;
            text-align: center;
        }
        
        /* Barra de navegación principal */
        .navbar {
            display: flex;
            justify-content: center;
            padding: 15px 30px;
            background-color: #f8f8f8;
            border-bottom: 1px solid #e7e7e7;
            position: fixed; /* Cambiado a fixed */
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000; /* Asegura que esté por encima */
            width: 100%;
        }
        
        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            max-width: 1200px;
        }
        
        .nav-logo {
            display: flex;
            justify-content: center;
        }
        
        .nav-logo img {
            height: 50px;
            width: auto;
        }
        
        .nav-main-content {
            display: flex;
            align-items: center;
            gap: 30px;
        }
        
        .nav-links {
            display: flex;
            gap: 25px;
        }
        
        .social-linksNavbar{
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .social-linksNavbar a {
            color: #022F4A;
            font-size: 18px;
            transition: all 0.3s;
        }
        
        .social-linksNavbar a:hover {
            transform: translateY(-2px);
        }
        
        .social-linksNavbar .fa-whatsapp:hover {
            color: #25D366;
        }
        
        .social-linksNavbar .fa-facebook-f:hover {
            color: #1877F2;
        }
        
        .social-linksNavbar .fa-instagram:hover {
            color: #E4405F;
        }
        
        .social-linksNavbars .fa-tiktok:hover {
            color: #000000;
        }
        
        .social-linksNavbar .fa-youtube:hover {
            color: #FF0000;
        }
        
        .nav-links a {
            text-decoration: none;
            color: #022F4A;
            font-size: 15px;
            font-weight: 500;
            text-transform: uppercase;
            transition: all 0.3s;
            padding: 5px 0;
            position: relative;
        }
        
        .nav-links a:hover {
            color: #022F4A;
        }
        
        .nav-links a:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background: #000;
            bottom: 0;
            left: 0;
            transition: width 0.3s;
        }
        
        .nav-links a:hover:after {
            width: 100%;
        }
        
        /* Menú hamburguesa para móviles */
        .hamburger {
            display: none;
            cursor: pointer;
            font-size: 24px;
            padding: 5px;
            z-index: 1001;
        }
        
        /* Estilos responsivos */
        @media (max-width: 1100px) {
            .top-bar {
                gap: 20px;
                padding: 12px 20px;
            }
            
            .top-bar-content {
                gap: 20px;
            }
            
            .contact-info {
                gap: 15px;
            }
            
            .nav-links {
                gap: 15px;
            }
            
            .nav-main-content {
                gap: 20px;
            }
        }
        
        @media (max-width: 850px) {
            .top-bar {
                gap: 15px;
                padding: 10px 15px;
            }
            
            .top-bar-content {
                flex-direction: column;
                gap: 10px;
            }
            
            .contact-info {
                gap: 10px;
            }
            
            .address {
                text-align: center;
            }
            
            .social-links {
                display: none;
            }
            
            .mobile-social-links {
                display: flex;
                justify-content: center;
                gap: 20px;
                padding: 15px 0;
                background-color: #f0f0f0;
                width: 100%;
            }
        }
        
        @media (max-width: 768px) {
            /* Ocultar barra superior en móviles */
            .top-bar {
                display: none;
            }
            
            .hamburger {
                display: block;
                position: relative;
                right: 0;
                top: 0;
                transform: none;
            }
            
            .nav-container {
                justify-content: space-between;
            }
            
            .nav-main-content {
                flex-direction: column;
                position: fixed;
                top: 80px;
                left: -100%;
                width: 100%;
                background-color: #f8f8f8;
                gap: 0;
                padding: 0;
                border-bottom: 1px solid #e7e7e7;
                transition: left 0.3s ease;
                box-shadow: 0 5px 10px rgba(0,0,0,0.1);
                max-height: calc(100vh - 80px);
                overflow-y: auto;
            }
            
            .nav-main-content.active {
                left: 0;
                display: flex;
            }
            
            .nav-links {
                flex-direction: column;
                width: 100%;
                gap: 0;
            }
            
            .nav-links a {
                padding: 15px 0;
                width: 100%;
                text-align: center;
                border-bottom: 1px solid #eee;
            }
            
            .nav-links a:last-child {
                border-bottom: none;
            }
            
            .nav-links a:hover {
                background-color: rgba(0,0,0,0.05);
                color: #000;
            }
            
            .nav-links a:after {
                display: none;
            }
            
            .mobile-social-links {
                display: flex;
            }
        }
        
        @media (max-width: 480px) {
            .navbar {
                padding: 10px 15px;
            }
            
            .nav-logo img {
                height: 40px;
            }
            
            .hamburger {
                font-size: 20px;
            }
            
            .nav-main-content {
                top: 70px;
            }
            
            .mobile-social-links a {
                font-size: 20px;
            }
        }

        /* Resto de tus estilos... */
        section {
            padding: 80px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .mobile-social-links {
            display: none;
        }
    </style>
</head>
<body>
    <!-- Barra superior con información de contacto (oculta en móviles) -->
    <div class="top-bar">
        <div class="top-bar-content">
            <div class="contact-info">
                <a href="tel:29581199171">
                    <i class="fas fa-phone-alt"></i>
                    <span>958-119-9171</span>
                </a>
                <a href="mailto:marketing@nelvabienesraices.com">
                    <i class="fas fa-envelope"></i>
                    <span>marketing@nelvabienesraices.com</span>
                </a>
            </div>
            <div class="address">
                <i class="fas fa-map-marker-alt"></i>
                <span>Calle Matamoros, Esquina Abasolo, Frente a Cfe, 70900 San Pedro Pochutla, Oax.</span>
            </div>
        </div>
    </div>
    
    <!-- Barra de navegación principal -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <img src="/images/logo.png" alt="NELVA Logo">
            </div>
            
            <div class="hamburger" id="hamburger">
                <i class="fas fa-bars"></i>
            </div>
            
            <div class="nav-main-content" id="navMainContent">
                <div class="nav-links" id="navLinks">
                    <a href="/">Inicio</a>
                    <a href="/nosotros">Nosotros</a>
                    <a href="/servicios">Servicios</a>
                    <a href="/contacto">Contacto</a>
                    <a href="/mas">Más</a>
                    <a href="/atractivos">Atractivos</a>
                    <a href="/mapaInteractivo">Mapa</a>
                </div>
                
                <!-- Redes sociales para desktop -->
                <div class="social-linksNavbar">
                    <a href="https://api.whatsapp.com/send/?phone=9581199171&text&type=phone_number&app_absent=0" target="_blank" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    <a href="https://www.facebook.com/profile.php?id=100063637222584" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/nelvabienesraices/l" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="https://tiktok.com/@tuperfil" target="_blank" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                    <a href="https://www.youtube.com/@NELVABIENESRAICES" target="_blank" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Resto de tu contenido HTML... -->
    
    <script>
        // JavaScript mejorado para el menú hamburguesa
        document.addEventListener('DOMContentLoaded', function() {
            const hamburger = document.getElementById('hamburger');
            const navMainContent = document.getElementById('navMainContent');
            
            // Toggle del menú al hacer clic en el icono hamburguesa
            hamburger.addEventListener('click', function(e) {
                e.stopPropagation(); // Evita que el evento se propague
                navMainContent.classList.toggle('active');
                
                // Cambiar el icono entre hamburguesa y X
                const icon = hamburger.querySelector('i');
                if (navMainContent.classList.contains('active')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                } else {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            });
            
            // Cerrar el menú al hacer clic en un enlace
            document.querySelectorAll('.nav-links a').forEach(link => {
                link.addEventListener('click', function() {
                    navMainContent.classList.remove('active');
                    const icon = hamburger.querySelector('i');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                });
            });
            
            // Cerrar el menú al hacer clic fuera de él
            document.addEventListener('click', function(event) {
                if (!navMainContent.contains(event.target)) {
                    navMainContent.classList.remove('active');
                    const icon = hamburger.querySelector('i');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            });
        });
    </script>
</body>
</html>