<?= view('templates/navbar', ['title' => 'Asesores - Nelva Bienes Raíces']) ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/pagina/asesores.css') }}">

<div class="container">
    <div class="header">
        <h1>Nuestros Asesores</h1>
        <p>Conecta con nuestros expertos en bienes raíces disponibles para brindarte atención personalizada</p>
    </div>
    
    <div class="filters-container">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Buscar asesor..." aria-label="Buscar asesor">
        </div>
        
        <div class="filter-buttons">
            <button class="filter-btn active" data-filter="all">
                <i class="fas fa-users"></i> Todos
            </button>
            <button class="filter-btn" data-filter="istmo">
                <i class="fas fa-mountain"></i> Istmo
            </button>
            <button class="filter-btn" data-filter="costa">
                <i class="fas fa-umbrella-beach"></i> Costa
            </button>
        </div>
    </div>
    
    <div class="asesores-grid" id="asesoresContainer">
        <!-- Los asesores se renderizan dinámicamente aquí -->
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const searchInput = document.getElementById('searchInput');
    const asesoresContainer = document.getElementById('asesoresContainer');
    
    // Datos de asesores inyectados desde el controlador
    const asesores = @json($asesores);

    // Función para obtener parámetros de la URL
    function getUrlParameter(name) {
        name = name.replace(/[\[\]]/g, '\\$&');
        const regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)');
        const results = regex.exec(window.location.href);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }

    // Función para mezclar array (Fisher-Yates shuffle)
    function shuffleArray(array) {
        const newArray = [...array];
        for (let i = newArray.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [newArray[i], newArray[j]] = [newArray[j], newArray[i]];
        }
        return newArray;
    }

    // Función para formatear número de teléfono
    function formatPhoneNumber(phone) {
        if (phone.startsWith('52')) {
            phone = phone.substring(2);
        }
        return phone.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
    }

    // Función para renderizar los asesores
    function renderAsesores(asesores, filter = null) {
        if (asesores.length === 0) {
            asesoresContainer.innerHTML = `
                <div class="no-results">
                    <i class="fas fa-user-slash"></i>
                    <h3>No hay asesores disponibles</h3>
                    <p>Próximamente tendremos más asesores para atenderte</p>
                </div>
            `;
            return;
        }
        
        // Filtrar según parámetro
        let asesoresFiltrados = [...asesores];
        if (filter === 'costa' || filter === 'istmo') {
            asesoresFiltrados = asesores.filter(a => a.zona.toLowerCase() === filter);
        }
        
        // Mezclar aleatoriamente
        asesoresFiltrados = shuffleArray(asesoresFiltrados);
        
        let html = '';
        
        asesoresFiltrados.forEach(asesor => {
            const nombreCompleto = `${asesor.nombre} ${asesor.apellido_paterno} ${asesor.apellido_materno}`.trim();
            const zona = asesor.zona.toLowerCase();
            const iniciales = `${asesor.nombre.charAt(0)}${asesor.apellido_paterno.charAt(0)}`;
            
            const telefonoFormateado = asesor.telefono.startsWith('52') ? 
                asesor.telefono : `52${asesor.telefono}`;
            
            html += `
                <div class="asesor-card" data-ubicacion="${zona}" data-nombre="${nombreCompleto.toLowerCase()}">
                    <div class="asesor-img-container">
                        ${asesor.foto_url ? 
                            `<img src="${asesor.foto_url}" alt="${nombreCompleto}" class="asesor-img">` : 
                            `<div class="avatar-fallback">${iniciales}</div>`
                        }
                        <span class="asesor-badge ${zona}">${asesor.zona}</span>
                    </div>
                    <div class="asesor-content">
                        <h2 class="asesor-name">${nombreCompleto}</h2>
                        
                        <div class="asesor-contact">
                            <div class="contact-item">
                                <i class="fas fa-phone-alt"></i>
                                <span>${formatPhoneNumber(asesor.telefono)}</span>
                            </div>
                        </div>
                        
                        <div class="contact-buttons">
                            ${asesor.facebook_url ? 
                                `<a href="${asesor.facebook_url}" class="facebook-btn" target="_blank">
                                    <i class="fab fa-facebook-f"></i> Facebook
                                </a>` : ''
                            }
                            <a href="https://wa.me/${telefonoFormateado}" class="whatsapp-btn" target="_blank">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            `;
        });
        
        asesoresContainer.innerHTML = html;
    }

    // Función para configurar el filtrado
    function setupFiltering() {
        const asesorCards = () => document.querySelectorAll('.asesor-card');
        
        function filterAsesores() {
            const searchTerm = searchInput.value.toLowerCase();
            const activeFilter = document.querySelector('.filter-btn.active').dataset.filter;
            
            let hasResults = false;
            
            asesorCards().forEach(card => {
                const nombre = card.dataset.nombre;
                const ubicacion = card.dataset.ubicacion;
                
                const matchesSearch = nombre.includes(searchTerm);
                const matchesFilter = activeFilter === 'all' || ubicacion === activeFilter;
                
                if (matchesSearch && matchesFilter) {
                    card.style.display = 'flex';
                    hasResults = true;
                } else {
                    card.style.display = 'none';
                }
            });
            
            const noResultsElement = document.querySelector('.no-results');
            if (!hasResults) {
                if (!noResultsElement) {
                    const noResults = document.createElement('div');
                    noResults.className = 'no-results';
                    noResults.innerHTML = `
                        <i class="fas fa-search"></i>
                        <h3>No se encontraron asesores</h3>
                        <p>Intenta con otros términos de búsqueda</p>
                    `;
                    asesoresContainer.appendChild(noResults);
                }
            } else if (noResultsElement) {
                noResultsElement.remove();
            }
        }
        
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.dataset.filter;
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Re-renderizar con el nuevo filtro y orden aleatorio
                renderAsesores(asesores, filter === 'all' ? null : filter);
                
                // Actualizar URL sin recargar
                const url = new URL(window.location);
                if (filter === 'all') {
                    url.searchParams.delete('zona');
                } else {
                    url.searchParams.set('zona', filter);
                }
                window.history.pushState({}, '', url);
            });
        });
        
        searchInput.addEventListener('input', filterAsesores);
    }

    // Renderizar asesores al cargar la página
    const initialFilter = getUrlParameter('zona');
    renderAsesores(asesores, initialFilter);
    
    // Configurar filtros y búsqueda
    setupFiltering();
    
    // Activar el botón de filtro correspondiente
    if (initialFilter) {
        const filterBtn = document.querySelector(`.filter-btn[data-filter="${initialFilter}"]`);
        if (filterBtn) {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            filterBtn.classList.add('active');
        }
    }
});
</script>

<?= view('templates/footer') ?>