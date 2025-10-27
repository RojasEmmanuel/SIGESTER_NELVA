<?= view('templates/navbar', ['title' => 'Mazunte - Nelva Bienes Raíces']) ?>
<link href="{{ asset('css/inversiones/mazunte.css') }}" rel="stylesheet">

  <!-- Sección Hero con Parallax -->
<section class="hero">
    <div class="hero-content">
        <h1>Inversión en Mazunte</h1>
        <p>Descubre las mejores oportunidades de inversión en Mazunte. Terrenos exclusivos en una ubicación inmejorable, rodeado de naturaleza y con alto potencial de crecimiento.</p>
        <a href="/asesores?zona=costa" class="btn-hero">Contactar un asesor</a>
    </div>
</section>

<!-- Sección Banner -->
<section class="banner-section">
    <div class="banner-container">
        <div class="banner-image"></div>
        <div class="banner-content">
            <h2>¡Descubre oportunidades inmobiliarias de primer nivel en Mazunte!</h2>
            <p>Experimente la impresionante belleza y el potencial de crecimiento de las principales subdivisiones de Mazunte.</p>
            <a href="/asesores?zona=costa" class="btn-banner">CONTACTA A UN ASESOR</a>
        </div>
    </div>
</section>


<!-- Sección Fraccionamientos -->
<section class="developments-section">
    <div class="section-header">
        <h2>Nuestros Fraccionamientos en Mazunte</h2>
        <p>Descubre las mejores opciones de inversión en los fraccionamientos más exclusivos de la región</p>
    </div>
    
    <div class="developments-container">
        <!-- Fraccionamiento 1 -->
        <div class="development-card">
            <img src="/images/inversiones/andromeda.webp" alt="Vista del fraccionamiento" class="card-image">
            <div class="card-content">
                <h3>Fraccionamiento Andromeda</h3>
                <p>Explore nuestros exclusivos lotes en el Fraccionamiento Andrómeda, donde la naturaleza se encuentra con la comodidad. Con vistas inigualables y un entorno tranquilo, este desarrollo de Nelva Bienes Raíces ofrece un futuro próspero en una de las zonas de mayor crecimiento de Mazunte.</p>
                <a href="/andromeda" class="btn-info">Más información</a>
            </div>
        </div>
        
        <!-- Fraccionamiento 2 -->
        <div class="development-card">
            <img src="/images/inversiones/nura.webp" alt="Vista del fraccionamiento" class="card-image">
            <div class="card-content">
                <h3>Fraccionamiento Nura Campestre</h3>
                <p>Descubra Nura Campestre, un desarrollo único que combina la belleza de la naturaleza con la comodidad de vivir en un entorno tranquilo y seguro. Ubicado en el corazón de Mazunte, este es el lugar perfecto para invertir en un estilo de vida campestre con grandes perspectivas de crecimiento.</p>
                <a href="/nura" class="btn-info">Más información</a>
            </div>
        </div>
        
        <!-- Fraccionamiento 3 -->
        
    </div>
</section>
<?= view('templates/footer') ?>