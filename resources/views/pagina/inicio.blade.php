<!-- Incrustar el navbar -->
<?= view('templates/navbar', ['title' => 'Nelva Bienes Raíces']) ?>
<link href="{{ asset('css/pagina/inicio.css') }}" rel="stylesheet">

<section class="parallax-section">
    <div class="parallax-bg">
        <iframe width="100%" height="100%" 
                src="https://www.youtube.com/embed/JyQgWZVuCAw?autoplay=1&mute=1&loop=1&controls=0&playlist=JyQgWZVuCAw" 
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen></iframe>
    </div>
    <div class="parallax-overlay"></div>
    <div class="parallax-content">
        <h1>TODO LO QUE NECESITAS PARA <span class="highlight">TUS PROYECTOS</span> EN UN SOLO LUGAR</h1>
        <p>Ya sea que busques invertir en terrenos cerca de los destinos turísticos más hermosos de Oaxaca, servicios profesionales de topografía o renta de equipo de maquinaria especializada, estamos aquí para ayudarte.</p>
        <a href="/asesores" class="btn">Contactar con un asesor</a>
    </div>
</section>

<section class="stats-section">
    <div class="container">
        <h2 class="section-title">Nuestros Logros</h2>
        
        <div class="stats-grid">
            <!-- Tarjeta 1 -->
            <div class="stat-card">
                <div class="stat-front">
                    <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
                    <div class="stat-number">6+</div>
                    <h3 class="stat-title">Años de experiencia</h3>
                </div>
                <div class="stat-back">
                    <p class="stat-description">
                        En Nelva Bienes Raíces, ofrecemos confianza y calidad en la venta de terrenos, topografía, renta de equipos y construcción cerca de los mejores destinos turísticos de Oaxaca.
                    </p>
                </div>
            </div>

            <!-- Tarjeta 2 -->
            <div class="stat-card">
                <div class="stat-front">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-number">1000+</div>
                    <h3 class="stat-title">Clientes satisfechos</h3>
                </div>
                <div class="stat-back">
                    <p class="stat-description">
                        Más de 1000 personas han confiado en nosotros para invertir en terrenos y convertir sus sueños en proyectos reales con nuestra asesoría especializada.
                    </p>
                </div>
            </div>

            <!-- Tarjeta 3 -->
            <div class="stat-card">
                <div class="stat-front">
                    <div class="stat-icon"><i class="fas fa-map-marked-alt"></i></div>
                    <div class="stat-number">20+</div>
                    <h3 class="stat-title">Desarrollos</h3>
                </div>
                <div class="stat-back">
                    <p class="stat-description">
                        Desarrollos estratégicos en la Costa e Istmo de Tehuantepec, ideales para invertir y construir cerca de los destinos más exclusivos de Oaxaca.
                    </p>
                </div>
            </div>

            <!-- Tarjeta 4 -->
            <div class="stat-card">
                <div class="stat-front">
                    <div class="stat-icon"><i class="fas fa-star"></i></div>
                    <div class="stat-number">100+</div>
                    <h3 class="stat-title">5 estrellas</h3>
                </div>
                <div class="stat-back">
                    <p class="stat-description">
                        Reconocidos por más de 100 clientes satisfechos que avalan nuestra calidad y compromiso en cada proyecto realizado.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección de Proyectos - Versión Dinámica -->
<section class="projects-section">
    <div class="projects-container">
        <div class="section-header">
            <h2>Nuestros Fraccionamientos</h2>
            <p>Descubre nuestros desarrollos estratégicamente ubicados en las zonas más atractivas de Oaxaca</p>
        </div>
        
        <!-- Pestañas de zonas -->
        <div class="zone-tabs">
            <div class="zone-tab active" data-zone="costa">Costa</div>
            <div class="zone-tab" data-zone="istmo">Istmo</div>
        </div>
        
        
        <!-- Contenido de la Costa -->
        <div class="zone-content active" id="costa-zone">
            <div class="projects-grid">
                @foreach($fraccionamientos['costa'] ?? [] as $fraccionamiento)
                    <div class="project-card">
                        <div class="project-logo-container">
                            <img src="{{ asset('storage/' . $fraccionamiento->path_imagen) }}" alt="{{ $fraccionamiento->nombre }}" class="project-logo">
                        </div>
                        <div class="project-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $fraccionamiento->ubicacion }}</span>
                        </div>
                        <div class="project-overlay">
                            <a href="{{ route('pagina.fraccionamiento.show', $fraccionamiento->id_fraccionamiento) }}" class="project-btn">Ver Proyecto</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Contenido del Istmo -->
        <div class="zone-content" id="istmo-zone">
            <div class="projects-grid">
                @foreach($fraccionamientos['istmo'] ?? [] as $fraccionamiento)
                    <div class="project-card">
                        <div class="project-logo-container">
                            <img src="{{ asset('storage/' . $fraccionamiento->path_imagen) }}" alt="{{ $fraccionamiento->nombre }}" class="project-logo">
                        </div>
                        <div class="project-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $fraccionamiento->ubicacion }}</span>
                        </div>
                        <div class="project-overlay">
                            <a href="{{ route('pagina.fraccionamiento.show', $fraccionamiento->id_fraccionamiento) }}" class="project-btn">Ver Proyecto</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


    </div>
</section>

<!-- Sección de Destinos Turísticos Compacta -->
<section class="destinations-section">
    <div class="destinations-container">
        <div class="section-header">
            <h2>Destinos Turísticos Exclusivos</h2>
            <p>Descubre los lugares más emblemáticos de Oaxaca donde tenemos desarrollos estratégicos para tu inversión</p>
        </div>
        
        <div class="destinations-grid">
            <!-- Mazunte -->
            <div class="destination-card">
                <div class="destination-image">
                    <img src="{{ asset('/images/inicio/mazunte.png') }}" alt="Mazunte">
                </div>
                <div class="destination-content">
                    <h3>Mazunte</h3>
                    <p>Pueblo Mágico conocido por sus playas vírgenes, ambiente bohemio y espectaculares atardeceres frente al Pacífico.</p>
                    
                    <div class="destination-highlights">
                        <div class="highlight-item">
                            <div class="highlight-icon">
                                <i class="fas fa-umbrella-beach"></i>
                            </div>
                            <span>Playas vírgenes</span>
                        </div>
                        <div class="highlight-item">
                            <div class="highlight-icon">
                                <i class="fas fa-sun"></i>
                            </div>
                            <span>Atardeceres únicos</span>
                        </div>
                        <div class="highlight-item">
                            <div class="highlight-icon">
                                <i class="fas fa-tree"></i>
                            </div>
                            <span>Reserva natural</span>
                        </div>
                    </div>
                    
                    <a href="/inversiones-mazunte" class="destination-btn">Conoce más</a>
                </div>
            </div>
            
            <!-- Santa María Tonameca -->
            <div class="destination-card">
                <div class="destination-image">
                    <img src="{{ asset('/images/inicio/tonameca.png') }}" alt="Santa María Tonameca">
                </div>
                <div class="destination-content">
                    <h3>Santa María Tonameca</h3>
                    <p>Combinación perfecta entre la tranquilidad de un pueblo auténtico y la cercanía a playas paradisíacas.</p>
                    
                    <div class="destination-highlights">
                        <div class="highlight-item">
                            <div class="highlight-icon">
                                <i class="fas fa-mountain"></i>
                            </div>
                            <span>Entorno natural</span>
                        </div>
                        <div class="highlight-item">
                            <div class="highlight-icon">
                                <i class="fas fa-water"></i>
                            </div>
                            <span>Ríos y manglares</span>
                        </div>
                        <div class="highlight-item">
                            <div class="highlight-icon">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                            <span>Ubicación estratégica</span>
                        </div>
                    </div>
                    
                    <a href="/inversiones/tonameca" class="destination-btn">Conoce más</a>
                </div>
            </div>
            
            <!-- Salina Cruz -->
            <div class="destination-card">
                <div class="destination-image">
                    <img src="{{ asset('/images/inicio/salinas.png') }}" alt="Salina Cruz">
                </div>
                <div class="destination-content">
                    <h3>Salina Cruz</h3>
                    <p>Zona clave del Corredor Interoceánico con gran potencial de desarrollo económico y crecimiento urbano.</p>
                    
                    <div class="destination-highlights">
                        <div class="highlight-item">
                            <div class="highlight-icon">
                                <i class="fas fa-anchor"></i>
                            </div>
                            <span>Puerto estratégico</span>
                        </div>
                        <div class="highlight-item">
                            <div class="highlight-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <span>Zona en desarrollo</span>
                        </div>
                        <div class="highlight-item">
                            <div class="highlight-icon">
                                <i class="fas fa-leaf"></i>
                            </div>
                            <span>Equilibrio ecológico</span>
                        </div>
                    </div>
                    
                    <a href="/inversiones/salina-cruz" class="destination-btn">Conoce más</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección de Pasos Compacta -->
<section class="compact-steps">
    <div class="container">
        <div class="section-header">
            <h2>Tu camino hacia el lote perfecto</h2>
            <p>Un proceso simple y transparente en solo 5 pasos</p>
        </div>
        
        <div class="steps-container">
            <!-- Paso 1 -->
            <div class="step-card">
                <div class="step-number">1</div>
                <div class="step-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h3>Haz una cita</h3>
                <p>Agenda una cita para conocer tus necesidades y preferencias sobre el tipo de propiedad que deseas.</p>
                <div class="step-connector"></div>
            </div>
            
            <!-- Paso 2 -->
            <div class="step-card">
                <div class="step-number">2</div>
                <div class="step-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3>Evalúa propiedades</h3>
                <p>Te ayudamos a evaluar las propiedades disponibles según precio, ubicación y potencial.</p>
                <div class="step-connector"></div>
            </div>
            
            <!-- Paso 3 -->
            <div class="step-card">
                <div class="step-number">3</div>
                <div class="step-icon">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                <h3>Crédito sin buro</h3>
                <p>Gestionamos tu crédito sin revisión de buró de manera ágil y transparente.</p>
                <div class="step-connector"></div>
            </div>
            
            <!-- Paso 4 -->
            <div class="step-card">
                <div class="step-number">4</div>
                <div class="step-icon">
                    <i class="fas fa-key"></i>
                </div>
                <h3>Posesión inmediata</h3>
                <p>Posibilidad de tomar posesión inmediata del lote seleccionado.</p>
                <div class="step-connector"></div>
            </div>
            
            <!-- Paso 5 -->
            <div class="step-card">
                <div class="step-number">5</div>
                <div class="step-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3>Cierra y disfruta</h3>
                <p>Cerramos el trato eficientemente para que disfrutes tu nueva propiedad con tranquilidad.</p>
            </div>
        </div>
    </div>
</section>

<!-- Sección de Promociones Dinámicas -->
<section class="nelva-promos-section">
    <div class="container">
        <div class="nelva-promos-header">
            <h2>Promociones Especiales</h2>
            <p>Aprovecha nuestras ofertas exclusivas en los mejores fraccionamientos</p>
        </div>
        
        @if($promocionesActivas->count() > 0)
        <div class="nelva-promos-grid">
            @foreach($promocionesActivas as $fraccionamiento)
                @foreach($fraccionamiento->promociones as $promocion)
                @php
                    // Detectar orientación de imagen
                    $esVertical = false;
                    if ($promocion->imagen_path) {
                        try {
                            $rutaImagen = storage_path('app/public/' . $promocion->imagen_path);
                            if (file_exists($rutaImagen)) {
                                list($ancho, $alto) = getimagesize($rutaImagen);
                                $esVertical = $alto > $ancho;
                            }
                        } catch (Exception $e) {
                            $esVertical = false;
                        }
                    }
                    
                    // Calcular días restantes
                    $diasRestantes = null;
                    if ($promocion->fecha_fin) {
                        $fechaFin = \Carbon\Carbon::parse($promocion->fecha_fin);
                        $diasRestantes = now()->diffInDays($fechaFin, false);
                    }
                @endphp
                
                <div class="nelva-promo-card">
                    <div class="nelva-promo-image-wrapper {{ $esVertical ? 'vertical' : 'horizontal' }}">
                        <img src="{{ asset('storage/' . $promocion->imagen_path) }}" 
                             alt="{{ $promocion->titulo }}"
                             class="nelva-promo-img"
                             loading="lazy">
                        
                        <div class="nelva-promo-overlay">
                            <div class="nelva-promo-actions">
                                <a href="{{ route('pagina.fraccionamiento.show', $fraccionamiento->id_fraccionamiento) }}" 
                                   class="nelva-action-btn">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ asset('storage/' . $promocion->imagen_path) }}" 
                                   class="nelva-action-btn"
                                   download="promocion-{{ Str::slug($promocion->titulo) }}.jpg">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>
                        
                        @if($diasRestantes !== null && $diasRestantes <= 15)
                        <div class="nelva-time-badge">
                            <i class="fas fa-clock"></i>
                            {{ $diasRestantes > 0 ? $diasRestantes . ' días' : 'Último día' }}
                        </div>
                        @endif
                    </div>
                    
                    <div class="nelva-promo-info">
                        <div class="nelva-promo-badge">Promoción Activa</div>
                        
                        <h3 class="nelva-promo-title">{{ $promocion->titulo }}</h3>
                        
                        <div class="nelva-fracc-info">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $fraccionamiento->nombre }}</span>
                        </div>
                        
                        <div class="nelva-promo-dates">
                            <div class="nelva-date-item">
                                <span class="nelva-date-label">Válido hasta:</span>
                                <span class="nelva-date-value">
                                    @if($promocion->fecha_fin)
                                        {{ \Carbon\Carbon::parse($promocion->fecha_fin)->format('d/m/Y') }}
                                    @else
                                        <span class="nelva-indefinite">Indefinido</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        
                        <a href="{{ route('pagina.fraccionamiento.show', $fraccionamiento->id_fraccionamiento) }}" 
                           class="nelva-cta-btn">
                            <i class="fas fa-arrow-right"></i>
                            Ver fraccionamiento
                        </a>
                    </div>
                </div>
                @endforeach
            @endforeach
        </div>
        @else
        <div class="nelva-no-promos">
            <i class="fas fa-gift"></i>
            <h3>Próximamente nuevas promociones</h3>
            <p>Estamos preparando ofertas especiales para ti</p>
        </div>
        @endif
    </div>
</section>


<!-- Incrustar el footer -->
<?= view('templates/footer') ?>

<script>
    // Script para manejar las pestañas de zonas
    document.addEventListener('DOMContentLoaded', function() {
        const zoneTabs = document.querySelectorAll('.zone-tab');
        
        zoneTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remover clase active de todas las pestañas
                zoneTabs.forEach(t => t.classList.remove('active'));
                
                // Agregar clase active a la pestaña clickeada
                this.classList.add('active');
                
                // Ocultar todos los contenidos de zona
                document.querySelectorAll('.zone-content').forEach(content => {
                    content.classList.remove('active');
                });
                
                // Mostrar el contenido correspondiente
                const zoneId = this.getAttribute('data-zone');
                document.getElementById(`${zoneId}-zone`).classList.add('active');
            });
        });
    });
</script>