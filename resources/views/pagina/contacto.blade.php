<?= view('templates/navbar', ['title' => 'Contacto - Nelva Bienes Raíces']) ?>
<link href="{{ asset('css/pagina/contacto.css') }}" rel="stylesheet">
<!-- Agregar AOS CSS -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<section class="contact-info-section" style="padding: 60px 5%; background-color: #f9f9f9;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 30px;">
            <!-- Bloque 1: Ubicación -->
            <div style="flex: 1; min-width: 250px; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); text-align: center;" 
                 data-aos="fade-up" data-aos-delay="100">
                <div style="color: #333edaff; font-size: 30px; margin-bottom: 15px;">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3 style="font-size: 20px; color: #2c3e50; margin-bottom: 15px; text-transform: uppercase;">ENCUÉNTRANOS AQUÍ</h3>
                <p style="color: #555; line-height: 1.6;">
                    Calle Matamoros, Esquina Abasolo<br>
                    Frente a CFE, Segunda Planta<br>
                    San Pedro Pochutla, Mexico
                </p>
            </div>
            
            <!-- Bloque 2: Contacto -->
            <div style="flex: 1; min-width: 250px; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); text-align: center;" 
                 data-aos="fade-up" data-aos-delay="200">
                <div style="color: #333edaff; font-size: 30px; margin-bottom: 15px;">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <h3 style="font-size: 20px; color: #2c3e50; margin-bottom: 15px; text-transform: uppercase;">CONTÁCTANOS</h3>
                <p style="color: #555; line-height: 1.6;">
                    <strong>Teléfono:</strong> 958-119-9171<br>
                    <strong>Email:</strong> marketingnelvabr@gmail.com
                </p>
            </div>
            
            <!-- Bloque 3: Horarios -->
            <div style="flex: 1; min-width: 250px; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); text-align: center;" 
                 data-aos="fade-up" data-aos-delay="300">
                <div style="color: #333edaff; font-size: 30px; margin-bottom: 15px;">
                    <i class="fas fa-clock"></i>
                </div>
                <h3 style="font-size: 20px; color: #2c3e50; margin-bottom: 15px; text-transform: uppercase;">NUESTROS HORARIOS</h3>
                <p style="color: #555; line-height: 1.6;">
                    <strong>Lunes a viernes:</strong><br>
                    9:30 am - 5:30 pm<br><br>
                    <strong>Sábados:</strong><br>
                    9:30 am - 1:30 pm
                </p>
            </div>
        </div>
    </div>
</section>

<section class="contact-section" style="padding: 20px 5%; background-color: #ffffff;">
    <div style="max-width: 1200px; margin: 0 auto; text-align: center;">
        <h1 style="font-size: 42px; color: #2c3e50; margin-bottom: 20px; text-transform: uppercase;" 
            data-aos="fade-up">CONTÁCTA CON NOSOTROS</h1>
        <p style="font-size: 20px; color: #7f8c8d; margin-bottom: 50px;" 
           data-aos="fade-up" data-aos-delay="100">Nos encantaría saber de usted.</p>
        <div style="height: 2px; width: 100px; background-color: #e74c3c; margin: 0 auto 50px;" 
             data-aos="fade-up" data-aos-delay="200"></div>
        
        <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 40px; text-align: left;">
            <!-- Información de Contacto -->
            <div style="flex: 1; min-width: 300px;">
                <div style="margin-bottom: 30px;" data-aos="fade-right" data-aos-delay="300">
                    <h2 style="font-size: 24px; color: #2c3e50; margin-bottom: 15px; text-transform: uppercase;">Contacta con nuestros asesores</h2>
                    <p style="color: #555; line-height: 1.6;">
                        Si deseas una atención más personalizada, no dudes en ponerte en contacto con cualquiera de nuestros asesores, ya sea del área de Costa o del Istmo. Están disponibles para brindarte toda la información que necesites, resolver tus dudas y acompañarte en cada paso del proceso. Tu satisfacción es nuestra prioridad.
                    </p>
                </div>

                <!-- Sección del botón de contacto -->
                <div style="text-align: center; padding: 40px 0; background-color: #f9f9f9; margin-bottom: 40px;" 
                     data-aos="zoom-in" data-aos-delay="400">
                    <a href="/asesores" style="
                        display: inline-block;
                        padding: 15px 40px;
                        background-color: #333edaff;
                        color: white;
                        text-decoration: none;
                        border-radius: 30px;
                        font-size: 18px;
                        font-weight: 600;
                        transition: all 0.3s ease;
                        box-shadow: 0 4px 15px rgba(51, 62, 218, 0.2);">
                        Contáctanos
                    </a>
                </div>
            </div>
            
            <!-- Mapa -->
            <div style="flex: 1; min-width: 300px; height: 400px; background-color: #f5f5f5;" 
                 data-aos="fade-left" data-aos-delay="500">
                <!-- Aquí se inserta el Google Maps -->
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3840.0962516054083!2d-96.47265642221858!3d15.746046645921181!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85b8d524d1c0c813%3A0x28e1b7915d888ef8!2sNELVA%20Bienes%20Ra%C3%ADces!5e0!3m2!1ses!2smx!4v1754065107116!5m2!1ses!2smx" 
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy">
                </iframe>
            </div>
        </div>
    </div>
</section>

<?= view('templates/footer') ?>

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
    });
</script>