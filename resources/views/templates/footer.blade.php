<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nelva Bienes Raíces - Footer Moderno</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #008fd9;
            --primary-dark: #1f6b52;
            --dark: #022F4A;
            --dark-gray: #022F4A;
            --medium-gray: #022F4A;
            --light-gray: #f5f5f5;
            --text: #e0e0e0;
            --text-light: #d3cfcf;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            line-height: 1.6;
        }
        
        .modern-footer {
            background-color: var(--dark-gray);
            padding: 60px 0 0;
            color: var(--text);
        }
        
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
        }
        
        .footer-brand {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .footer-logo {
            width: 180px;
            height: auto;
            filter: brightness(0) invert(1);
        }
        
        .footer-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text);
            line-height: 1.3;
            position: relative;
            padding-bottom: 15px;
        }
        
        .footer-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 60px;
            height: 3px;
            background: var(--primary);
        }
        
        .footer-description {
            font-size: 16px;
            color: var(--text-light);
            opacity: 0.9;
        }
        
        .newsletter-social {
            display: flex;
            flex-direction: column;
            gap: 25px;
            margin-top: 20px;
        }
        
        .social-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text);
        }
        
        .social-links {
            display: flex;
            gap: 15px;
        }
        
        .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: var(--medium-gray);
            color: var(--text);
            border-radius: 50%;
            transition: all 0.3s ease;
            font-size: 18px;
        }
        
        .social-link:hover {
            background-color: var(--primary);
            color: var(--text);
            transform: translateY(-3px);
        }
        
        .footer-section h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .contact-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        
        .contact-icon {
            color: var(--primary);
            font-size: 18px;
            margin-top: 3px;
            flex-shrink: 0;
        }
        
        .contact-text {
            font-size: 15px;
            line-height: 1.5;
            color: var(--text-light);
        }
        
        .contact-text a {
            color: var(--text-light);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .contact-text a:hover {
            color: var(--primary);
        }
        
        .map-container {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            height: 280px;
            border: 1px solid var(--medium-gray);
        }
        
        .map-frame {
            width: 100%;
            height: 100%;
            border: 0;
        }
        
        .footer-bottom {
            margin-top: 60px;
            padding: 25px 0;
            background-color: var(--dark);
            color: var(--text-light);
            text-align: center;
            font-size: 14px;
        }
        
        .copyright {
            opacity: 0.8;
        }
        
        @media (max-width: 768px) {
            .footer-container {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .footer-title {
                font-size: 24px;
            }
            
            .map-container {
                height: 250px;
            }
        }
    </style>
</head>
<body>
    <footer class="modern-footer">
        <div class="footer-container">
            <div class="footer-brand">
                <img src="/images/logo.png" alt="Nelva Bienes Raíces" class="footer-logo">
                <h2 class="footer-title">¡Mantente al día con las últimas novedades!</h2>
                <p class="footer-description">En Nelva Bienes Raíces, ofrecemos soluciones personalizadas para encontrar la propiedad de tus sueños. Desde urbanización hasta la renta de equipos especializados, estamos aquí para ayudarte.</p>
                
                <div class="newsletter-social">
                    <p class="social-title">Síguenos en redes</p>
                    <div class="social-links">
                        <a href="https://api.whatsapp.com/send/?phone=9581199171&text&type=phone_number&app_absent=0" class="social-link" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                        <a href="https://www.facebook.com/profile.php?id=100063637222584" class="social-link" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.instagram.com/nelvabienesraices/" class="social-link" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.youtube.com/@NELVABIENESRAICES?themeRefresh=1" class="social-link" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                        <a href="https://www.tiktok.com/@nelvabienesraices.mx?is_from_webapp=1&sender_device=pc" class="social-link" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="footer-section">
                <h3>Información de contacto</h3>
                <div class="contact-list">
                    <div class="contact-item">
                        <i class="fas fa-phone-alt contact-icon"></i>
                        <div class="contact-text">(+52) 958-119-9171</div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope contact-icon"></i>
                        <div class="contact-text">
                            <a href="mailto:marketing@nelvabienesraices.com">marketing@nelvabienesraices.com</a>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt contact-icon"></i>
                        <div class="contact-text">Calle Matamoros, Esquina Abasolo, Frente a CFE, 70900 San Pedro Pochutla, Oax.</div>
                    </div>
                </div>
            </div>
            
            <div class="footer-section">
                <div class="map-container">

                    <iframe class="map-frame"  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d417.46676100758384!2d-96.46799853583771!3d15.745939307495133!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85b8d524d1c0c813%3A0x28e1b7915d888ef8!2sNELVA%20Bienes%20Ra%C3%ADces!5e0!3m2!1ses-419!2smx!4v1765300521200!5m2!1ses-419!2smx" allowfullscreen="" loading="lazy"></iframe>
                   
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p class="copyright">© 2023 Nelva Bienes Raíces. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>