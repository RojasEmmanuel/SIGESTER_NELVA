<?= view('templates/navbar', ['title' => 'Más - Nelva Bienes Raíces']) ?>
<link href="{{ asset('css/pagina/mas.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Agregar AOS CSS -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<div class="news-section">
    <div class="news-container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title">Noticias y Eventos</h2>
            <p class="section-subtitle">Descubre nuestras últimas actividades, proyectos y compromisos con la comunidad</p>
        </div>
        
        <div class="news-grid">
            <!-- Noticia 1 - Expansión Agua Marina -->
            <div class="news-card" data-aos="fade-up" data-aos-delay="100">
                <div class="news-carousel">
                    <div class="carousel-slides">
                        <div class="carousel-slide">
                            <img src="/images/facebook/noticiaAguamarina1.jpg" alt="Nueva oficina">
                        </div>
                        <div class="carousel-slide">
                            <img src="/images/facebook/noticiaAguamarina2.jpg" alt="Equipo de trabajo">
                        </div>
                        <div class="carousel-slide">
                            <img src="/images/facebook/noticiaAguamarina3.jpg" alt="Equipo de trabajo">
                        </div>
                    </div>
                    <button class="carousel-btn carousel-prev">❮</button>
                    <button class="carousel-btn carousel-next">❯</button>
                    <div class="carousel-dots">
                        <div class="carousel-dot active"></div>
                        <div class="carousel-dot"></div>
                        <div class="carousel-dot"></div>
                    </div>
                </div>
                
                <div class="news-content">
                    <span class="news-date">03 Junio 2025</span>
                    <h3 class="news-title">Expansión Agua Marina</h3>
                    <p class="news-text">
                        De parte de todo el equipo de Nelva Bienes Raíces, agradecemos a todo nuestro equipo de ventas, clientes y amigos que estuvieron presentes en esta gran inauguración de Expansión Agua Marina.
                    </p>
                    
                    <div class="news-footer">
                        <span class="news-tag">Expansión</span>
                        <div class="news-social">
                            <a href="https://www.facebook.com/share/p/19nVgKHRyN/" target="_blank" title="Compartir en Facebook"><i class="fab fa-facebook-f"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Noticia 2 - Torneo de Basketball -->
            <div class="news-card" data-aos="fade-up" data-aos-delay="200">
                <div class="news-carousel">
                    <div class="carousel-slides">
                        <div class="carousel-slide">
                            <img src="/images/facebook/noticiaTorneo1.jpg" alt="Partido de basketball">
                        </div>
                        <div class="carousel-slide">
                            <img src="/images/facebook/noticiaTorneo2.jpg" alt="Equipo local">
                        </div>
                        <div class="carousel-slide">
                            <img src="/images/facebook/noticiaTorneo3.jpg" alt="Trofeo">
                        </div>
                    </div>
                    <button class="carousel-btn carousel-prev">❮</button>
                    <button class="carousel-btn carousel-next">❯</button>
                    <div class="carousel-dots">
                        <div class="carousel-dot active"></div>
                        <div class="carousel-dot"></div>
                        <div class="carousel-dot"></div>
                    </div>
                </div>
                
                <div class="news-content">
                    <span class="news-date">15 Julio 2023</span>
                    <h3 class="news-title">En NELVA BIENES RAÍCES apoyamos el deporte</h3>
                    <p class="news-text">
                        Nos enorgullece ser patrocinadores de la Liga de Basketball Pochutla, apoyando el talento local y fomentando un estilo de vida saludable.
                        El deporte no solo fortalece el cuerpo, sino que también promueve valores como la disciplina, el trabajo en equipo y la perseverancia.🤠✨
                    </p>
                    
                    <div class="news-footer">
                        <span class="news-tag">Comunidad</span>
                        <div class="news-social">
                            <a href="#" title="Compartir en Facebook"><i class="fab fa-facebook-f"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Noticia 3 - Día del Niño -->
            <div class="news-card" data-aos="fade-up" data-aos-delay="300">
                <div class="news-carousel">
                    <div class="carousel-slides">
                        <div class="carousel-slide">
                            <img src="/images/facebook/noticiaDiaNiño1.jpg" alt="Niños">
                        </div>
                        <div class="carousel-slide">
                            <img src="/images/facebook/noticiaDiaNiño2.jpg" alt="Niños">
                        </div>
                        <div class="carousel-slide">
                            <img src="/images/facebook/noticiaDiaNiño3.jpg" alt="Niños">
                        </div>
                    </div>
                    <button class="carousel-btn carousel-prev">❮</button>
                    <button class="carousel-btn carousel-next">❯</button>
                    <div class="carousel-dots">
                        <div class="carousel-dot active"></div>
                        <div class="carousel-dot"></div>
                        <div class="carousel-dot"></div>
                    </div>
                </div>
                    
                <div class="news-content">
                    <span class="news-date">30 Abril 2025</span>
                    <h3 class="news-title">🎉 ¡Un Día del Niño lleno de alegría en Guelaguechi! 🧸🌟</h3>
                    <p class="news-text">
                        Este 30 de abril fuimos parte de una hermosa celebración organizada por el Comité Ejidal de Guelaguechi, donde se regalaron momentos de felicidad a las niñas y niños de la comunidad.
                        Gracias al comité y a todos los que hicieron posible esta jornada tan significativa. 💛
                    </p>
                    
                    <div class="news-footer">
                        <span class="news-tag">Comunidad</span>
                        <div class="news-social">
                            <a href="https://www.facebook.com/share/p/1B1H7GrRYc/" target="_blank" title="Compartir en Facebook"><i class="fab fa-facebook-f"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Noticias ocultas (si las tienes) -->
            <!--
            <div class="news-card hidden-news" data-aos="fade-up" data-aos-delay="400">
                ...
            </div>
            -->
        </div>
        
        <div class="load-more-container" data-aos="fade-up" data-aos-delay="400">
            <button class="load-more-btn" id="loadMoreBtn">Ver más noticias</button>
        </div>
    </div>
</div>

<div class="social-container">
    <section class="social-section">
        <div class="social-header" data-aos="fade-up">
            <h1 class="social-title2">Conéctate con Nelva Bienes Raíces</h1>
            <p class="social-subtitle">Síguenos en nuestras redes sociales para conocer las mejores propiedades y promociones exclusivas</p>
        </div>
        
        <div class="social-grid">
            <div class="social-card" 
                 onclick="window.open('https://www.facebook.com/profile.php?id=100063637222584', '_blank')"
                 data-aos="zoom-in" data-aos-delay="100">
                <i class="fab fa-facebook-f social-icon facebook"></i>
                <h3 class="social-name">Facebook</h3>
                <p class="social-username">@NelvaBienesRaices</p>
                <a class="social-link2">Seguir</a>
            </div>
            
            <div class="social-card" 
                 onclick="window.open('https://www.instagram.com/nelvabienesraices/', '_blank')"
                 data-aos="zoom-in" data-aos-delay="200">
                <i class="fab fa-instagram social-icon instagram"></i>
                <h3 class="social-name">Instagram</h3>
                <p class="social-username">@NelvaBienesRaices</p>
                <a class="social-link2">Seguir</a>
            </div>
            
            <div class="social-card" 
                 onclick="window.open('https://www.tiktok.com/@nelvabienesraices.mx?is_from_webapp=1&sender_device=pc', '_blank')"
                 data-aos="zoom-in" data-aos-delay="300">
                <i class="fab fa-tiktok social-icon tiktok"></i>
                <h3 class="social-name">TikTok</h3>
                <p class="social-username">@nelvabienesraices.mx</p>
                <a class="social-link2">Seguir</a>
            </div>
            
            <div class="social-card" 
                 onclick="window.open('https://www.youtube.com/@NELVABIENESRAICES', '_blank')"
                 data-aos="zoom-in" data-aos-delay="400">
                <i class="fab fa-youtube social-icon youtube"></i>
                <h3 class="social-name">YouTube</h3>
                <p class="social-username">Nelva Bienes Raíces</p>
                <a class="social-link2">Suscribirse</a>
            </div>
            
            <div class="social-card" 
                 onclick="window.open('https://wa.me/9581199171', '_blank')"
                 data-aos="zoom-in" data-aos-delay="500">
                <i class="fab fa-whatsapp social-icon whatsapp"></i>
                <h3 class="social-name">WhatsApp</h3>
                <p class="social-username">+52 123 456 7890</p>
                <a class="social-link2">Contactar</a>
            </div>
        </div>
    </section>
</div>

<!-- Script de AOS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // Inicializar AOS
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 50,
            delay: 0
        });
        
        // Refresh después de que todo cargue
        window.addEventListener('load', function() {
            AOS.refresh();
        });

        // Inicializar todos los carruseles
        document.querySelectorAll('.news-carousel').forEach(carousel => {
            const slides = carousel.querySelector('.carousel-slides');
            const slideItems = carousel.querySelectorAll('.carousel-slide');
            const prevBtn = carousel.querySelector('.carousel-prev');
            const nextBtn = carousel.querySelector('.carousel-next');
            const dots = carousel.querySelectorAll('.carousel-dot');
            
            let currentIndex = 0;
            const totalSlides = slideItems.length;
            
            // Actualizar posición del carrusel
            function updateCarousel() {
                slides.style.transform = `translateX(-${currentIndex * 100}%)`;
                
                // Actualizar dots
                dots.forEach((dot, index) => {
                    dot.classList.toggle('active', index === currentIndex);
                });
            }
            
            // Evento para botón anterior
            prevBtn.addEventListener('click', () => {
                currentIndex = (currentIndex > 0) ? currentIndex - 1 : totalSlides - 1;
                updateCarousel();
            });
            
            // Evento para botón siguiente
            nextBtn.addEventListener('click', () => {
                currentIndex = (currentIndex < totalSlides - 1) ? currentIndex + 1 : 0;
                updateCarousel();
            });
            
            // Eventos para dots
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    currentIndex = index;
                    updateCarousel();
                });
            });
            
            // Auto-avance cada 5 segundos
            let interval = setInterval(() => {
                currentIndex = (currentIndex < totalSlides - 1) ? currentIndex + 1 : 0;
                updateCarousel();
            }, 5000);
            
            // Pausar auto-avance al interactuar
            carousel.addEventListener('mouseenter', () => clearInterval(interval));
            carousel.addEventListener('mouseleave', () => {
                interval = setInterval(() => {
                    currentIndex = (currentIndex < totalSlides - 1) ? currentIndex + 1 : 0;
                    updateCarousel();
                }, 5000);
            });
        });
        
        // Función para mostrar más noticias
        document.getElementById('loadMoreBtn').addEventListener('click', function() {
            // Mostrar todas las noticias ocultas
            document.querySelectorAll('.hidden-news').forEach(news => {
                news.classList.remove('hidden-news');
                // Refresh AOS para las nuevas noticias
                AOS.refresh();
            });
            
            // Ocultar el botón después de hacer clic
            this.style.display = 'none';
        });
    });
</script>

<?= view('templates/footer') ?>