<?= view('templates/navbar', ['title' => 'Salina Cruz - Nelva Bienes Raíces']) ?>
<link href="{{ asset('css/inversiones/salinaCruz.css') }}" rel="stylesheet">

  <!-- Sección Hero con Parallax -->
<section class="hero">
    <div class="hero-content">
        <h1>Inversión en Salina Cruz</h1>
        <p>Explora terrenos únicos en Salina Cruz, el puerto estratégico de Oaxaca con un enorme potencial de desarrollo. Ubicado en una región vibrante y en crecimiento, ofrece cercanía al mar, infraestructura clave y oportunidades inigualables para invertir. Asegura tu lugar en este destino que combina tradición, modernidad y proyección hacia el futuro.</p>
        <a href="/asesores?zona=istmo" class="btn-hero">Contactar un asesor</a>
    </div>
</section>

<!-- Sección Banner -->
<section class="banner-section">
    <div class="banner-container">
        <div class="banner-image"></div>
        <div class="banner-content">
            <h2>¡Descubre oportunidades inmobiliarias de primer nivel en Salina Cruz¡</h2>
            <p>Experimente la impresionante belleza y el potencial de crecimiento de las principales subdivisiones.</p>
            <a href="/asesores?zona=istmo" class="btn-banner">CONTACTA A UN ASESOR</a>
        </div>
    </div>
</section>


<!-- Sección Fraccionamientos -->
<section class="developments-section">
    <div class="section-header">
        <h2>Invierta en el Paraíso: Salina Cruz le Espera</h2>
        <p>Descubre las mejores opciones de inversión en los fraccionamientos más exclusivos de la región</p>
    </div>
    
    <div class="developments-container">
        <!-- Fraccionamiento 1 -->
        <div class="development-card">
            <img src="/images/inversiones/zull.webp" alt="Vista del fraccionamiento" class="card-image">
            <div class="card-content">
                <h3>Fraccionamiento Zull</h3>
                <p>Ubicado en una zona de alto potencial, el Fraccionamiento zull ofrece lotes únicos rodeados de un entorno natural y sereno. Con un diseño pensado para brindar comodidad y vistas espectaculares, este desarrollo de Nelva Bienes Raíces es la inversión ideal para quienes buscan un futuro próspero en una de las áreas con mayor crecimiento y expansión de la región.</p>
                <a href="/zull" class="btn-info">Más información</a>
            </div>
        </div>
        
        <!-- Fraccionamiento 2 -->
        <div class="development-card">
            <img src="/images/inversiones/nyssa.webp" alt="Vista del fraccionamiento" class="card-image">
            <div class="card-content">
                <h3>Fraccionamiento Rompe Olas</h3>
                <p>Descubre la oportunidad de invertir en terrenos exclusivos en el Fraccionamiento Rompe Olas, ubicado en una zona estratégica y rodeado por la belleza de la costa. Cada lote ofrece un entorno tranquilo y un alto potencial de valorización, perfecto para quienes buscan un espacio para vivir, vacacionar o invertir. Este desarrollo combina la cercanía al mar con un futuro prometedor en una región en constante crecimiento.</p>
                <a href="/#" class="btn-info">Más información</a>
            </div>
        </div>
        
        <!-- Fraccionamiento 3 -->
        <div class="development-card">
            <img src="/images/inversiones/realCampestre.webp" alt="Vista del fraccionamiento" class="card-image">
            <div class="card-content">
                <h3>Fraccionamiento Sicarú</h3>
                <p>En el Fraccionamiento Sicarú encontrarás una oportunidad única para invertir en un entorno que combina belleza natural y proyección de desarrollo. Este exclusivo espacio ofrece terrenos ideales para construir tu hogar o proyecto, en una zona con excelente ubicación y alto crecimiento. Vive la tranquilidad de estar rodeado de naturaleza, mientras aseguras una inversión con gran potencial de valorización.</p>
                <a href="/sicaru" class="btn-info">Más información</a>
            </div>
        </div>
    </div>
</section>
<?= view('templates/footer') ?>