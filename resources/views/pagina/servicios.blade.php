<?= view('templates/navbar', ['title' => 'Servicios - Nelva Bienes Raíces']) ?>
<link rel="stylesheet" href="{{ asset('css/pagina/servicios.css') }}">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">

<!-- Hero Section -->
<section class="nbr-hero">
    <div class="nbr-hero-content">
        <h1>Soluciones inmobiliarias integrales en Oaxaca</h1>
        <p>Nos especializamos en la venta de lotes con alta plusvalía, servicios de topografía, renta de maquinaria y desarrollo de construcción, con enfoque de calidad y cuidado del medio ambiente.</p>
        <a href="#nbr-servicios" class="nbr-btn">Nuestros Servicios</a>
    </div>
</section>

<!-- Services Section -->
<section class="nbr-section" id="nbr-servicios">
    <div class="nbr-section-title">
        <h2>Nuestros Servicios</h2>
        <p>Ofrecemos soluciones integrales para tus proyectos inmobiliarios y de construcción en los principales destinos turísticos de Oaxaca.</p>
    </div>
    
    <div class="nbr-services-container">
        <!-- Service 1 - Venta de Terrenos -->
        <div class="nbr-service-card">
            <div class="nbr-service-img">
                <img src="{{ asset('images/servicios/ventaTerrenos.png') }}" alt="Venta de Terrenos">
                <span class="nbr-service-badge">Más popular</span>
            </div>
            <div class="nbr-service-content">
                <h3 class="nbr-service-title">Venta de Terrenos en Zonas Privilegiadas</h3>
                
                <div class="nbr-service-features">
                    <div class="nbr-feature-item">
                        <div class="nbr-feature-icon">
                            <i class="fas fa-umbrella-beach"></i>
                        </div>
                        <span class="nbr-feature-text">Acceso directo a playas vírgenes</span>
                    </div>
                    <div class="nbr-feature-item">
                        <div class="nbr-feature-icon">
                            <i class="fas fa-binoculars"></i>
                        </div>
                        <span class="nbr-feature-text">Vistas panorámicas al océano</span>
                    </div>
                    <div class="nbr-feature-item">
                        <div class="nbr-feature-icon">
                            <i class="fas fa-swimming-pool"></i>
                        </div>
                        <span class="nbr-feature-text">Zonas para deportes acuáticos</span>
                    </div>
                    <div class="nbr-feature-item">
                        <div class="nbr-feature-icon">
                            <i class="fas fa-fish"></i>
                        </div>
                        <span class="nbr-feature-text">Excelentes áreas de pesca</span>
                    </div>
                    <div class="nbr-feature-item">
                        <div class="nbr-feature-icon">
                            <i class="fas fa-archway"></i>
                        </div>
                        <span class="nbr-feature-text">Cercanía a sitios arqueológicos</span>
                    </div>
                    <div class="nbr-feature-item">
                        <div class="nbr-feature-icon">
                            <i class="fas fa-ruler-combined"></i>
                        </div>
                        <span class="nbr-feature-text">Terrenos desde 200m² hasta 5,000m²</span>
                    </div>
                </div>
                
                <div class="nbr-service-contact">
                    <h4 class="nbr-contact-title">Contacto directo:</h4>
                    <div class="nbr-contact-item">
                        <i class="fas fa-phone nbr-contact-icon"></i>
                        <span>+52 (958) 119 9171</span>
                    </div>
                    <div class="nbr-contact-item">
                        <i class="fas fa-envelope nbr-contact-icon"></i>
                        <span>ventas@nelvabienesraices.com</span>
                    </div>
                    <div class="nbr-contact-item">
                        <i class="fas fa-map-marker-alt nbr-contact-icon"></i>
                        <span>Oficinas en Puerto Escondido y Huatulco</span>
                    </div>
                </div>

                <a href="{{ url('/asesores') }}" class="nbr-service-btn">Contactar a un asesor</a>
            </div>
        </div>
        
        <!-- Service 2 - Topografía -->
        <div class="nbr-service-card">
            <div class="nbr-service-img">
                <img src="{{ asset('images/servicios/topografia.png') }}" alt="Levantamiento Topográfico">
                <span class="nbr-service-badge">Servicio profesional</span>
            </div>
            <div class="nbr-service-content">
                <h3 class="nbr-service-title">Levantamientos Topográficos de Precisión</h3>
                
                <div class="nbr-service-features">
                    <div class="nbr-feature-item">
                        <div class="nbr-feature-icon">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <span class="nbr-feature-text">Estudio detallado del terreno</span>
                    </div>
                    <div class="nbr-feature-item">
                        <div class="nbr-feature-icon">
                            <i class="fas fa-mountain"></i>
                        </div>
                        <span class="nbr-feature-text">Medición precisa de desniveles y altimetría</span>
                    </div>
                    <div class="nbr-feature-item">
                        <div class="nbr-feature-icon">
                            <i class="fas fa-globe-americas"></i>
                        </div>
                        <span class="nbr-feature-text">Análisis de las características geográficas</span>
                    </div>
                    <div class="nbr-feature-item">
                        <div class="nbr-feature-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <span class="nbr-feature-text">Informe técnico sobre el perfil del terreno</span>
                    </div>
                    <div class="nbr-feature-item">
                        <div class="nbr-feature-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <span class="nbr-feature-text">Detección de áreas verdes y vegetación relevante</span>
                    </div>
                    <div class="nbr-feature-item">
                        <div class="nbr-feature-icon">
                            <i class="fas fa-water"></i>
                        </div>
                        <span class="nbr-feature-text">Estudio de drenaje y cuerpos de agua cercanos</span>
                    </div>
                    <div class="nbr-feature-item">
                        <div class="nbr-feature-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <span class="nbr-feature-text">Evaluación de la proximidad a zonas naturales y reservas</span>
                    </div>
                    <div class="nbr-feature-item">
                        <div class="nbr-feature-icon">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <span class="nbr-feature-text">Recomendaciones para proyectos de construcción</span>
                    </div>
                </div>
                
                <div class="nbr-service-contact">
                    <h4 class="nbr-contact-title">Contacto para topografía:</h4>
                    <div class="nbr-contact-item">
                        <i class="fas fa-phone nbr-contact-icon"></i>
                        <span>+52 (958) 119 9172</span>
                    </div>
                    <div class="nbr-contact-item">
                        <i class="fas fa-envelope nbr-contact-icon"></i>
                        <span>topografia@nelvabienesraices.com</span>
                    </div>
                    <div class="nbr-contact-item">
                        <i class="fas fa-clock nbr-contact-icon"></i>
                        <span>Lunes a Viernes: 8:00 - 18:00 hrs</span>
                    </div>
                </div>
                
                <button type="button" class="nbr-service-btn nbr-modal-btn" data-modal-target="nbr-modal-topografia">Cotizar servicio</button>
            </div>
        </div>
        
        <!-- Service 3 - Maquinaria -->
        <div class="nbr-service-card">
            <div class="nbr-service-img">
                <img src="{{ asset('images/servicios/renta.png') }}" alt="Renta de Maquinaria">
                <span class="nbr-service-badge">Equipo disponible</span>
            </div>
            <div class="nbr-service-content">
                <h3 class="nbr-service-title">Renta de Maquinaria para Construcción</h3>
                <p style="margin-bottom: 20px; color: var(--nbr-light-text);">Flota moderna de maquinaria pesada con operadores certificados y mantenimiento preventivo incluido.</p>
                
                <div class="nbr-service-features">
                    <div class="nbr-feature-item">
                        <div class="nbr-feature-icon">
                            <i class="fas fa-truck-monster"></i>
                        </div>
                        <span class="nbr-feature-text">Maquinaria pesada</span>
                    </div>
                    <div class="nbr-feature-item">
                        <div class="nbr-feature-icon">
                            <i class="fas fa-truck-monster"></i>
                        </div>
                        <span class="nbr-feature-text">Equipos de construcción</span>
                    </div>
                    <div class="nbr-feature-item">
                        <div class="nbr-feature-icon">
                            <i class="fas fa-truck-monster"></i>
                        </div>
                        <span class="nbr-feature-text">Tecnología avanzada para medición</span>
                    </div>
                    <div class="nbr-feature-item">
                        <div class="nbr-feature-icon">
                            <i class="fas fa-truck-monster"></i>
                        </div>
                        <span class="nbr-feature-text">Soporte integral</span>
                    </div>
                    
                </div>
                
                <div class="nbr-service-contact">
                    <h4 class="nbr-contact-title">Disponibilidad:</h4>
                    <div class="nbr-contact-item">
                        <i class="fas fa-phone nbr-contact-icon"></i>
                        <span>+52 (958) 119 9173</span>
                    </div>
                    <div class="nbr-contact-item">
                        <i class="fas fa-envelope nbr-contact-icon"></i>
                        <span>maquinaria@nelvabienesraices.com</span>
                    </div>
                    <div class="nbr-contact-item">
                        <i class="fas fa-calendar-check nbr-contact-icon"></i>
                        <span>Reservas con 48 hrs de anticipación</span>
                    </div>
                </div>
                
                <button type="button" class="nbr-service-btn nbr-modal-btn" data-modal-target="nbr-modal-maquinaria">Consultar disponibilidad</button>
            </div>
        </div>
    </div>
</section>

<!-- Modal Topografía -->
<div id="nbr-modal-topografia" class="nbr-modal">
    <div class="nbr-modal-content">
        <span class="nbr-close-modal">&times;</span>
        <div class="nbr-modal-header">
            <h2>Servicio Profesional de Topografía</h2>
       </div>
        <div class="nbr-modal-body">
            <div class="nbr-modal-text">
                <p>El servicio de levantamiento topográfico para proporcionar mediciones precisas y detalladas de terrenos. Este proceso es clave para identificar los límites, las características físicas y las dimensiones exactas de una propiedad, siendo una herramienta fundamental para proyectos de construcción, desarrollo inmobiliario, trámites legales y más.</p>
               
                <p><strong>Tipos de levantamientos:</strong></p>
                <ul>
                    <li>Topografía de lote</li>
                    <li>Nivelación de terreno</li>
                    <li>Plano de construcción</li>
                    <li>Estudios de pendiente</li>
                    <li>Delimitación de predios</li>
                </ul>
            </div>
            <div class="nbr-modal-gallery">
                <div class="nbr-carousel" id="nbr-topografia-carousel">
                    <div class="nbr-carousel-inner">
                        <div class="nbr-carousel-item active">
                            <a href="{{ asset('images/servicios/topografia.png') }}" data-lightbox="nbr-topografia" data-title="Equipo de topografía en acción">
                                <img src="{{ asset('images/servicios/topografia.png') }}" alt="Equipo de topografía">
                            </a>
                            <div class="nbr-carousel-caption">Equipo profesional trabajando</div>
                        </div>
                        <div class="nbr-carousel-item">
                            <a href="{{ asset('images/servicios/topografia1.png') }}" data-lightbox="nbr-topografia" data-title="Trabajo de campo preciso">
                                <img src="{{ asset('images/servicios/topografia1.png') }}" alt="Trabajo de campo">
                            </a>
                            <div class="nbr-carousel-caption">Trabajo de campo preciso</div>
                        </div>
                       
                    </div>
                    <div class="nbr-carousel-controls">
                        <button class="nbr-carousel-control prev" onclick="nbrMoveSlide('nbr-topografia-carousel', -1)">&#10094;</button>
                        <button class="nbr-carousel-control next" onclick="nbrMoveSlide('nbr-topografia-carousel', 1)">&#10095;</button>
                    </div>
                    <div class="nbr-carousel-indicators">
                        <span class="nbr-carousel-indicator active" onclick="nbrGoToSlide('nbr-topografia-carousel', 0)"></span>
                        <span class="nbr-carousel-indicator" onclick="nbrGoToSlide('nbr-topografia-carousel', 1)"></span>
                        <span class="nbr-carousel-indicator" onclick="nbrGoToSlide('nbr-topografia-carousel', 2)"></span>
                    </div>
                </div>
            </div>
            
        </div>
        
    </div>
   
</div>

<!-- Modal Maquinaria -->
<div id="nbr-modal-maquinaria" class="nbr-modal">
    <div class="nbr-modal-content">
        <span class="nbr-close-modal">&times;</span>
        <div class="nbr-modal-header">
            <h2>Renta de Maquinaria para Construcción</h2>
        </div>
        <div class="nbr-modal-body">
            <div class="nbr-modal-text">
                <p>Ofrecemos renta de equipo de construcción de alta calidad, como excavadoras, compactadoras y herramientas especializadas, ideales para proyectos de cualquier escala. Todos nuestros equipos están en excelentes condiciones, garantizando eficiencia y seguridad. Al alquilar con nosotros, obtienes maquinaria de última tecnología sin los costos de mantenimiento y almacenamiento.</p>
                
                <p><strong>Nuestro inventario incluye:</strong></p>
                <ul>
                    <li>Retroexcavadoras CAT 320</li>
                    <li>Excavadoras hidráulicas</li>
                    <li>Vibrocompactadores</li>
                    <li>Camiones de volteo 14m³</li>
                </ul>
                
            </div>
            <div class="nbr-modal-gallery">
                <div class="nbr-carousel" id="nbr-maquinaria-carousel">
                    <div class="nbr-carousel-inner">
                        <div class="nbr-carousel-item active">
                            <a href="{{ asset('images/servicios/maquinaria1.png') }}" data-lightbox="nbr-maquinaria" data-title="Retroexcavadora CAT 320">
                                <img src="{{ asset('images/servicios/maquinaria1.png') }}" alt="Retroexcavadora">
                            </a>
                            <div class="nbr-carousel-caption">Retroexcavadora CAT 320</div>
                        </div>
                        <div class="nbr-carousel-item">
                            <a href="{{ asset('images/servicios/maquinaria2.png') }}" data-lightbox="nbr-maquinaria" data-title="Bulldozer D6T">
                                <img src="{{ asset('images/servicios/maquinaria2.png') }}" alt="Bulldozer">
                            </a>
                            <div class="nbr-carousel-caption">Bulldozer D6T</div>
                        </div>
                        <div class="nbr-carousel-item">
                            <a href="{{ asset('images/servicios/maquinaria3.png') }}" data-lightbox="nbr-maquinaria" data-title="Camiones de volteo">
                                <img src="{{ asset('images/servicios/maquinaria3.png') }}" alt="Camiones de volteo">
                            </a>
                            <div class="nbr-carousel-caption">Volteo de 14m³</div>
                        </div>
                        <div class="nbr-carousel-item">
                            <a href="{{ asset('images/servicios/maquinaria4.png') }}" data-lightbox="nbr-maquinaria" data-title="Excavadora hidráulica">
                                <img src="{{ asset('images/servicios/maquinaria4.png') }}" alt="Excavadora">
                            </a>
                            <div class="nbr-carousel-caption">Excavadora hidráulica</div>
                        </div>
                    </div>
                    <div class="nbr-carousel-controls">
                        <button class="nbr-carousel-control prev" onclick="nbrMoveSlide('nbr-maquinaria-carousel', -1)">&#10094;</button>
                        <button class="nbr-carousel-control next" onclick="nbrMoveSlide('nbr-maquinaria-carousel', 1)">&#10095;</button>
                    </div>
                    <div class="nbr-carousel-indicators">
                        <span class="nbr-carousel-indicator active" onclick="nbrGoToSlide('nbr-maquinaria-carousel', 0)"></span>
                        <span class="nbr-carousel-indicator" onclick="nbrGoToSlide('nbr-maquinaria-carousel', 1)"></span>
                        <span class="nbr-carousel-indicator" onclick="nbrGoToSlide('nbr-maquinaria-carousel', 2)"></span>
                        <span class="nbr-carousel-indicator" onclick="nbrGoToSlide('nbr-maquinaria-carousel', 3)"></span>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script>
    // Cerrar todos los modales al cargar la página
    document.querySelectorAll('.nbr-modal').forEach(modal => {
        modal.style.display = 'none';
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetElement = document.querySelector(this.getAttribute('href'));
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Modal functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Configurar lightbox si está disponible - versión más robusta
        if (window.lightbox) {
            try {
                window.lightbox.option({
                    'resizeDuration': 200,
                    'wrapAround': true,
                    'disableScrolling': true,
                    'alwaysShowNavOnTouchDevices': true
                });
            } catch (e) {
                console.error('Error configuring lightbox:', e);
            }
        }

        // Manejo de modales
        document.addEventListener('click', function(e) {
            // Abrir modal
            if (e.target.closest('[data-modal-target]')) {
                e.preventDefault();
                const modalSelector = e.target.closest('[data-modal-target]').getAttribute('data-modal-target');
                const modal = document.getElementById(modalSelector);
                if (modal && modal.nodeType === Node.ELEMENT_NODE) {
                    modal.style.display = 'block';
                    document.body.style.overflow = 'hidden';
                    // Reset carousel si existe
                    const carouselId = modal.id + '-carousel';
                    const carousel = document.getElementById(carouselId);
                    if (carousel) {
                        nbrGoToSlide(carouselId, 0);
                    }
                }
            }
            
            // Cerrar modal
            if (e.target.closest('.nbr-close-modal') || e.target.classList.contains('nbr-modal')) {
                e.preventDefault();
                const modal = e.target.closest('.nbr-modal');
                if (modal && modal.nodeType === Node.ELEMENT_NODE) {
                    modal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            }
        });

        // Gestos táctiles para carruseles
        document.querySelectorAll('.nbr-carousel').forEach(carousel => {
            if (!carousel || carousel.nodeType !== Node.ELEMENT_NODE) return;
            
            let startX = null;
            
            carousel.addEventListener('touchstart', e => {
                startX = e.touches[0].clientX;
            }, { passive: true });
            
            carousel.addEventListener('touchend', e => {
                if (!startX) return;
                const diffX = startX - e.changedTouches[0].clientX;
                if (Math.abs(diffX) > 50) {
                    nbrMoveSlide(carousel.id, diffX > 0 ? 1 : -1);
                }
                startX = null;
            }, { passive: true });
        });
    });

    // Funciones del carrusel
    function nbrMoveSlide(carouselId, direction) {
        const carousel = document.getElementById(carouselId);
        if (!carousel || carousel.nodeType !== Node.ELEMENT_NODE) return;
        
        const inner = carousel.querySelector('.nbr-carousel-inner');
        const items = carousel.querySelectorAll('.nbr-carousel-item');
        const indicators = carousel.querySelectorAll('.nbr-carousel-indicator');
        
        if (!inner || !items.length) return;
        
        const currentIndex = Array.from(items).findIndex(item => item.classList.contains('active'));
        const newIndex = (currentIndex + direction + items.length) % items.length;
        
        items[currentIndex]?.classList.remove('active');
        indicators[currentIndex]?.classList.remove('active');
        items[newIndex]?.classList.add('active');
        indicators[newIndex]?.classList.add('active');
        
        inner.style.transform = `translateX(-${newIndex * 100}%)`;
    }

    function nbrGoToSlide(carouselId, index) {
        const carousel = document.getElementById(carouselId);
        if (!carousel || carousel.nodeType !== Node.ELEMENT_NODE) return;
        
        const inner = carousel.querySelector('.nbr-carousel-inner');
        const items = carousel.querySelectorAll('.nbr-carousel-item');
        const indicators = carousel.querySelectorAll('.nbr-carousel-indicator');
        
        if (!inner || !items.length) return;
        
        items.forEach((item, i) => {
            item.classList.toggle('active', i === index);
            indicators[i]?.classList.toggle('active', i === index);
        });
        
        inner.style.transform = `translateX(-${index * 100}%)`;
    }
</script>
<?= view('templates/footer') ?>