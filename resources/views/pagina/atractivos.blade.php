<?= view('templates/navbar', ['title' => 'Atractivos - Nelva Bienes Raíces']) ?>
<link rel="stylesheet" href="{{ asset('css/pagina/atractivos.css') }}">
<!-- Agregar AOS CSS -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<section class="parallax-hero">
    <div class="parallax-bg" id="parallaxBg"></div>
    <div class="parallax-overlay"></div>
    <div class="parallax-content" data-aos="fade-up" data-aos-duration="1000">
        <h1>Descubre la Costa Mágica de Oaxaca</h1>
        <p data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">Descubre la riqueza de Oaxaca a través de sus increíbles destinos. Desde playas paradisíacas y mágicos manglares hasta imponentes montañas y sitios arqueológicos llenos de historia.</p>
    </div>
    <div class="scroll-indicator" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="400">
        <i class="fas fa-chevron-down"></i>
    </div>
</section>

<!-- Sección de Destinos -->
<div class="destinations-container">
    <h2 class="section-title2" data-aos="fade-up">Paraísos por Descubrir</h2>
    <p class="section-subtitle2" data-aos="fade-up" data-aos-delay="100">Explora estos destinos únicos en la costa de Oaxaca, donde la naturaleza y la cultura se unen para crear experiencias inolvidables.</p>
    
    <div class="destination-grid">
        <!-- Destino 1 - La Ventanilla -->
        <div class="destination-card-alt" data-aos="fade-up" data-aos-delay="200">
            <div class="card-header-alt">
                <img src="{{ asset('images/atractivos/ventanilla3.png') }}" alt="La Ventanilla" class="card-image-alt">
            </div>
            
            <div class="card-body-alt">
                <h3 class="card-title-alt">Ventanilla Oax.</h3>
                
                <div class="card-features-alt">
                    <span class="feature-tag" data-aos="zoom-in" data-aos-delay="300">Manglares</span>
                    <span class="feature-tag" data-aos="zoom-in" data-aos-delay="400">Fauna</span>
                    <span class="feature-tag" data-aos="zoom-in" data-aos-delay="500">Tranquilidad</span>
                </div>
                
                <p class="card-description-alt" data-aos="fade-up" data-aos-delay="300">
                    Ventanilla es un rincón paradisíaco en Oaxaca, famoso por su tranquilidad, paisajes naturales y su rica biodiversidad. Ideal para quienes buscan conexión con la naturaleza.
                </p>
                
                <div class="location-info-alt" data-aos="fade-up" data-aos-delay="400">
                    <i class="fas fa-map-marker-alt location-icon"></i>
                    <div>
                        <p class="location-text"><strong>Ubicación:</strong> La Ventanilla se encuentra a 12 km al oeste de Mazunte, en el municipio de Santa María Tonameca. Accesible por carretera desde Puerto Escondido (1.5 horas) o desde Pochutla (30 minutos).</p>
                    </div>
                </div>
                
                <div class="gallery-alt">
                    <img src="{{ asset('images/atractivos/ventanilla1.png') }}" alt="Ventanilla 1" onclick="openModal(this)" data-aos="zoom-in" data-aos-delay="500">
                    <img src="{{ asset('images/atractivos/ventanilla2.png') }}" alt="Ventanilla 2" onclick="openModal(this)" data-aos="zoom-in" data-aos-delay="600">
                    <img src="{{ asset('images/atractivos/ventanilla4.png') }}" alt="Ventanilla 3" onclick="openModal(this)" data-aos="zoom-in" data-aos-delay="700">
                    <img src="{{ asset('images/atractivos/ventanilla5.png') }}" alt="Ventanilla 3" onclick="openModal(this)" data-aos="zoom-in" data-aos-delay="800">
                </div>
            </div>
            
            <div class="card-footer-alt" data-aos="fade-up" data-aos-delay="600">
                <iframe class="card-map-alt" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d245780.23953209125!2d-96.94686960404245!3d15.734401019421178!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85b92808fca9472d%3A0xc65df1b8df4192a6!2sPlaya%20La%20Ventanilla!5e1!3m2!1ses!2smx!4v1754415567220!5m2!1ses!2smx" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
        
        <!-- Destino 2 - Punta Cometa -->
        <div class="destination-card-alt" data-aos="fade-up" data-aos-delay="300">
            <div class="card-header-alt">
                <img src="{{ asset('images/atractivos/puntacometa1.png') }}" alt="Punta Cometa" class="card-image-alt">
            </div>
            
            <div class="card-body-alt">
                <h3 class="card-title-alt">Punta Cometa</h3>
                
                <div class="card-features-alt">
                    <span class="feature-tag" data-aos="zoom-in" data-aos-delay="400">Atardeceres</span>
                    <span class="feature-tag" data-aos="zoom-in" data-aos-delay="500">Mirador</span>
                    <span class="feature-tag" data-aos="zoom-in" data-aos-delay="600">Senderismo</span>
                </div>
                
                <p class="card-description-alt" data-aos="fade-up" data-aos-delay="400">
                    Punta Cometa, en Mazunte, es un rincón mágico donde el océano Pacífico se encuentra con la tierra en un espectáculo visual. Este mirador ofrece vistas panorámicas impresionantes.
                </p>
                
                <div class="location-info-alt" data-aos="fade-up" data-aos-delay="500">
                    <i class="fas fa-map-marker-alt location-icon"></i>
                    <div>
                        <p class="location-text"><strong>Ubicación:</strong> Punta Cometa se ubica en el extremo sur de Mazunte, a 250 km al sureste de la ciudad de Oaxaca. Se puede llegar caminando desde el centro de Mazunte (20 minutos).</p>
                    </div>
                </div>
                
                <div class="gallery-alt">
                    <img src="{{ asset('images/atractivos/puntacometa2.png') }}" alt="Punta Cometa 1" onclick="openModal(this)" data-aos="zoom-in" data-aos-delay="600">
                    <img src="{{ asset('images/atractivos/puntacometa3.png') }}" alt="Punta Cometa 2" onclick="openModal(this)" data-aos="zoom-in" data-aos-delay="700">
                    <img src="{{ asset('images/atractivos/puntacometa4.png') }}" alt="Punta Cometa 3" onclick="openModal(this)" data-aos="zoom-in" data-aos-delay="800">
                    <img src="{{ asset('images/atractivos/puntacometa5.png') }}" alt="Punta Cometa 3" onclick="openModal(this)" data-aos="zoom-in" data-aos-delay="900">
                </div>
            </div>
            
            <div class="card-footer-alt" data-aos="fade-up" data-aos-delay="700">
                <iframe class="card-map-alt" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3841.753280071125!2d-96.55874722543669!3d15.658121150313528!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85b928402c82221d%3A0x7678ce7c27654759!2sPunta%20Cometa!5e0!3m2!1ses!2smx!4v1754415821086!5m2!1ses!2smx" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
        
        <!-- Destino 3 - Mazunte -->
        <div class="destination-card-alt" data-aos="fade-up" data-aos-delay="400">
            <div class="card-header-alt">
                <img src="{{ asset('images/atractivos/mazunte1.png') }}" alt="Mazunte" class="card-image-alt">
                <div class="card-badge-alt" data-aos="zoom-in" data-aos-delay="500">Cultura</div>
            </div>
            
            <div class="card-body-alt">
                <h3 class="card-title-alt">Mazunte</h3>
                
                <div class="card-features-alt">
                    <span class="feature-tag" data-aos="zoom-in" data-aos-delay="500">Playas</span>
                    <span class="feature-tag" data-aos="zoom-in" data-aos-delay="600">Ecología</span>
                    <span class="feature-tag" data-aos="zoom-in" data-aos-delay="700">Relax</span>
                </div>
                
                <p class="card-description-alt" data-aos="fade-up" data-aos-delay="500">
                    Mazunte es un paraíso costero en Oaxaca, conocido por sus playas vírgenes, aguas cristalinas y su ambiente tranquilo. Ideal para quienes buscan desconectar y disfrutar de la naturaleza.
                </p>
                
                <div class="location-info-alt" data-aos="fade-up" data-aos-delay="600">
                    <i class="fas fa-map-marker-alt location-icon"></i>
                    <div>
                        <p class="location-text"><strong>Ubicación:</strong> Mazunte se localiza en la costa del Pacífico, a 22 km al suroeste de Pochutla. Desde Oaxaca capital son aproximadamente 6 horas en auto.</p>
                    </div>
                </div>
                
                <div class="gallery-alt">
                    <img src="{{ asset('images/atractivos/mazunte2.png') }}" alt="Mazunte 1" onclick="openModal(this)" data-aos="zoom-in" data-aos-delay="700">
                    <img src="{{ asset('images/atractivos/mazunte3.png') }}" alt="Mazunte 2" onclick="openModal(this)" data-aos="zoom-in" data-aos-delay="800">
                    <img src="{{ asset('images/atractivos/mazunte4.png') }}" alt="Mazunte 3" onclick="openModal(this)" data-aos="zoom-in" data-aos-delay="900">
                    <img src="{{ asset('images/atractivos/mazunte5.png') }}" alt="Mazunte 3" onclick="openModal(this)" data-aos="zoom-in" data-aos-delay="1000">
                </div>
            </div>
            
            <div class="card-footer-alt" data-aos="fade-up" data-aos-delay="800">
                <iframe class="card-map-alt" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3823.064229064683!2d-96.3846826856187!3d15.66994398873393!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85b8d9a9a3d4e5a5%3A0x9e8a6b1b1b1b1b1b!2sMazunte%2C%20Oaxaca!5e1!3m2!1ses!2smx!4v1620000000000!5m2!1ses!2smx" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
        
        <!-- Destino 4 - San Agustinillo -->
        <div class="destination-card-alt" data-aos="fade-up" data-aos-delay="500">
            <div class="card-header-alt">
                <img src="{{ asset('images/atractivos/agustinillo3.png') }}" alt="San Agustinillo" class="card-image-alt">
                <div class="card-badge-alt" data-aos="zoom-in" data-aos-delay="600">Paraíso</div>
            </div>
            
            <div class="card-body-alt">
                <h3 class="card-title-alt">San Agustinillo</h3>
                
                <div class="card-features-alt">
                    <span class="feature-tag" data-aos="zoom-in" data-aos-delay="600">Tranquilidad</span>
                    <span class="feature-tag" data-aos="zoom-in" data-aos-delay="700">Gastronomía</span>
                    <span class="feature-tag" data-aos="zoom-in" data-aos-delay="800">Surf</span>
                </div>
                
                <p class="card-description-alt" data-aos="fade-up" data-aos-delay="600">
                    Descubre un rincón mágico en la costa del Pacífico mexicano, donde el ritmo pausado de las olas y la calidez de su gente crean un refugio perfecto para desconectarte del mundo.
                </p>
                
                <div class="location-info-alt" data-aos="fade-up" data-aos-delay="700">
                    <i class="fas fa-map-marker-alt location-icon"></i>
                    <div>
                        <p class="location-text"><strong>Ubicación:</strong> San Agustinillo se encuentra a solo 3 km al este de Mazunte. Se accede por la misma carretera costera que lleva a Mazunte y Zipolite.</p>
                    </div>
                </div>
                
                <div class="gallery-alt">
                    <img src="{{ asset('images/atractivos/agustinillo1.png') }}" alt="San Agustinillo 1" onclick="openModal(this)" data-aos="zoom-in" data-aos-delay="800">
                    <img src="{{ asset('images/atractivos/agustinillo2.png') }}" alt="San Agustinillo 2" onclick="openModal(this)" data-aos="zoom-in" data-aos-delay="900">
                    <img src="{{ asset('images/atractivos/agustinillo4.png') }}" alt="San Agustinillo 3" onclick="openModal(this)" data-aos="zoom-in" data-aos-delay="1000">
                </div>
            </div>
            
            <div class="card-footer-alt" data-aos="fade-up" data-aos-delay="900">
                <iframe class="card-map-alt" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3823.064229064683!2d-96.3846826856187!3d15.66994398873393!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85b8d9a9a3d4e5a5%3A0x9e8a6b1b1b1b1b1b!2sSan%20Agustinillo%2C%20Oaxaca!5e1!3m2!1ses!2smx!4v1620000000000!5m2!1ses!2smx" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Modal para la galería -->
<div id="imageModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="modalImage">
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

        // Efecto Parallax optimizado para móviles
        window.addEventListener('scroll', function() {
            const parallaxBg = document.getElementById('parallaxBg');
            // Reducir efecto en móviles para mejor rendimiento
            const intensity = window.innerWidth > 768 ? 0.5 : 0.3;
            let scrollPosition = window.pageYOffset;
            parallaxBg.style.transform = 'translateY(' + scrollPosition * intensity + 'px)';
        });
        
        // Animación suave al hacer scroll
        document.querySelector('.scroll-indicator').addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector('.destinations-container').scrollIntoView({ 
                behavior: 'smooth' 
            });
        });
    });

    // Funciones para la galería modal
    function openModal(img) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        modal.style.display = "block";
        modalImg.src = img.src;
        
        // Bloquear scroll del body cuando el modal está abierto
        document.body.style.overflow = 'hidden';
    }
    
    function closeModal() {
        document.getElementById('imageModal').style.display = "none";
        // Restaurar scroll del body
        document.body.style.overflow = 'auto';
    }
    
    // Cerrar modal al hacer clic fuera de la imagen
    window.onclick = function(event) {
        const modal = document.getElementById('imageModal');
        if (event.target == modal) {
            closeModal();
        }
    }
    
    // Cerrar modal con tecla ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            closeModal();
        }
    });
    
    // Optimización para móviles: evitar hover en dispositivos táctiles
    function hasTouch() {
        return 'ontouchstart' in document.documentElement
            || navigator.maxTouchPoints > 0
            || navigator.msMaxTouchPoints > 0;
    }
    
    if (hasTouch()) {
        document.body.classList.add('touch-device');
    }
</script>

<?= view('templates/footer') ?>