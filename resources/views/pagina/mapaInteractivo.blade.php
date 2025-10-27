<?= view('templates/navbar', ['title' => 'Nelva Bienes Raíces']) ?>
<link rel="stylesheet" href="{{ asset('css/pagina/mapaInteractivo.css') }}">

<div class="nelva-container">
    <!-- Parallax Hero Section -->
    <section class="nelva-parallax-hero">
        <div class="nelva-parallax-content">
            <h1 class="nelva-main-title">Explora Nuestras Propiedades</h1>
            <p class="nelva-hero-subtitle">Ubicaciones estratégicas en toda la región</p>
        </div>
    </section>

    <!-- Map Section -->
    <section class="nelva-map-display">
        <div class="nelva-map-wrapper">
            <iframe src="https://www.google.com/maps/d/u/2/embed?mid=1XIZPxdnOg8TX-6V4844hF261e85CgyY&ehbc=2E312F" 
                    width="100%" 
                    height="650" 
                    class="nelva-iframe-map" 
                    allowfullscreen="" 
                    loading="lazy">
            </iframe>
        </div>
        
        <div class="nelva-map-actions">
            <h2 class="nelva-action-title">¿Interesado en alguna propiedad?</h2>
            <p class="nelva-action-text">Nuestros asesores están disponibles para mostrarte cualquiera de nuestras propiedades en el mapa.</p>
            <a href="/asesores" class="nelva-primary-button">Contactar un asesor</a>
        </div>
    </section>
</div>

<?= view('templates/footer') ?>