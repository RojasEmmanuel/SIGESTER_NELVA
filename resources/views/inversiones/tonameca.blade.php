<?= view('templates/navbar', ['title' => 'Tonameca - Nelva Bienes Raíces']) ?>
<link href="{{ asset('css/inversiones/tonameca.css') }}" rel="stylesheet">

  <!-- Sección Hero con Parallax -->
<section class="hero">
    <div class="hero-content">
        <h1>Inversión en Tonameca</h1>
        <p>Descubre terrenos exclusivos en Santa María Tonameca, un lugar privilegiado por su belleza natural y gran potencial de desarrollo. Invierte en un destino que combina tranquilidad, cercanía a playas icónicas y un futuro prometedor. Este es el momento perfecto para asegurar tu espacio en un entorno que promete alto crecimiento y conexión con la naturaleza.</p>
        <a href="/asesores?zona=costa" class="btn-hero">Contactar un asesor</a>
    </div>
</section>

<!-- Sección Banner -->
<section class="banner-section">
    <div class="banner-container">
        <div class="banner-image"></div>
        <div class="banner-content">
            <h2>¡Descubre oportunidades inmobiliarias de primer nivel en Santa María Tonameca¡</h2>
            <p>Experimente la impresionante belleza y el potencial de crecimiento de las principales subdivisiones.</p>
            <a href="/asesores?zona=costa" class="btn-banner">CONTACTA A UN ASESOR</a>
        </div>
    </div>
</section>


<!-- Sección Fraccionamientos -->
<section class="developments-section">
    <div class="section-header">
        <h2>Invierta en el Paraíso: Tonameca le Espera</h2>
        <p>Descubre las mejores opciones de inversión en los fraccionamientos más exclusivos de la región</p>
    </div>
    
    <div class="developments-container">
        <!-- Fraccionamiento 1 -->
        <div class="development-card">
            <img src="/images/inversiones/jicaro.webp" alt="Vista del fraccionamiento" class="card-image">
            <div class="card-content">
                <h3>Fraccionamiento El Jicaro</h3>
                <p>Ubicado en una zona de alto potencial, el Fraccionamiento el Jicaro ofrece lotes únicos rodeados de un entorno natural y sereno. Con un diseño pensado para brindar comodidad y vistas espectaculares, este desarrollo de Nelva Bienes Raíces es la inversión ideal para quienes buscan un futuro próspero en una de las áreas con mayor crecimiento y expansión de la región.</p>
                <a href="/el-jicaro" class="btn-info">Más información</a>
            </div>
        </div>
        
        <!-- Fraccionamiento 2 -->
        <div class="development-card">
            <img src="/images/inversiones/nyssa.webp" alt="Vista del fraccionamiento" class="card-image">
            <div class="card-content">
                <h3>Fraccionamiento Nyssa</h3>
                <p>Disfruta de la oportunidad de invertir en terrenos únicos en una ubicación estratégica, rodeados de naturaleza y con un alto potencial de valorización. En el Fraccionamiento Real Campestre, cada lote ofrece la combinación perfecta entre un ambiente tranquilo y un futuro lleno de posibilidades. Este desarrollo de Nelva Bienes Raíces es ideal para quienes buscan un espacio para vivir o invertir en una zona de crecimiento continuo.</p>
                <a href="/nyssa" class="btn-info">Más información</a>
            </div>
        </div>
        
        <!-- Fraccionamiento 3 -->
        <div class="development-card">
            <img src="/images/inversiones/realCampestre.webp" alt="Vista del fraccionamiento" class="card-image">
            <div class="card-content">
                <h3>Fraccionamiento Real Campestre</h3>
                <p>Disfruta de la oportunidad de invertir en terrenos únicos en una ubicación estratégica, rodeados de naturaleza y con un alto potencial de valorización. En el Fraccionamiento Real Campestre, cada lote ofrece la combinación perfecta entre un ambiente tranquilo y un futuro lleno de posibilidades. Este desarrollo de Nelva Bienes Raíces es ideal para quienes buscan un espacio para vivir o invertir en una zona de crecimiento continuo.</p>
                <a href="/real-campestre" class="btn-info">Más información</a>
            </div>
        </div>
    </div>
</section>
<?= view('templates/footer') ?>