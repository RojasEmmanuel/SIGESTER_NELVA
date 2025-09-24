<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Apartados | Inmobiliaria</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/apartados.css') }}" rel="stylesheet">

</head>
<body>

    <!-- Main Content -->
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-calendar-check"></i>
                <span>Control de Apartados</span>
            </h1>
            <div class="page-actions">
                <button class="btn btn-primary">
                    <i class="fas fa-filter"></i> Filtros
                </button>
                
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="stats-container">
            <div class="stat-card total">
                <h3 class="stat-card-title">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Total Apartados</span>
                </h3>
                <p class="stat-card-value">48</p>
                <p class="stat-card-description">Este mes</p>
                <div class="progress-container">
                    <div class="progress-bar" style="width: 100%"></div>
                </div>
            </div>
            <div class="stat-card active">
                <h3 class="stat-card-title">
                    <i class="fas fa-check-circle"></i>
                    <span>Activos</span>
                </h3>
                <p class="stat-card-value">32</p>
                <p class="stat-card-description">Vigentes</p>
                <div class="progress-container">
                    <div class="progress-bar" style="width: 67%"></div>
                </div>
            </div>
            <div class="stat-card expired">
                <h3 class="stat-card-title">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>Vencidos</span>
                </h3>
                <p class="stat-card-value">9</p>
                <p class="stat-card-description">Por atender</p>
                <div class="progress-container">
                    <div class="progress-bar" style="width: 19%"></div>
                </div>
            </div>
            <div class="stat-card word">
                <h3 class="stat-card-title">
                    <i class="fas fa-handshake"></i>
                    <span>De palabra</span>
                </h3>
                <p class="stat-card-value">7</p>
                <p class="stat-card-description">Sin depósito</p>
                <div class="progress-container">
                    <div class="progress-bar" style="width: 14%"></div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="filters">
            <div class="filter-group">
                <label class="filter-label">Tipo de apartado</label>
                <select class="filter-select">
                    <option>Todos</option>
                    <option>Con depósito</option>
                    <option>De palabra</option>
                </select>
                <i class="fas fa-chevron-down filter-icon"></i>
            </div>
            <div class="filter-group">
                <label class="filter-label">Fraccionamiento</label>
                <select class="filter-select">
                    <option>Todos</option>
                    <option>OCEÁNICA</option>
                    <option>REAL CAMPESTRE</option>
                    <option>SICARÚ</option>
                </select>
                <i class="fas fa-chevron-down filter-icon"></i>
            </div>
            <div class="filter-group">
                <label class="filter-label">Estado</label>
                <select class="filter-select">
                    <option>Activos</option>
                    <option>Vencidos</option>
                    <option>Todos</option>
                </select>
                <i class="fas fa-chevron-down filter-icon"></i>
            </div>
            <div class="filter-group">
                <label class="filter-label">Vendedor</label>
                <select class="filter-select">
                    <option>Todos</option>
                    <option>Juan Pérez</option>
                    <option>Ana López</option>
                    <option>Luisa Martínez</option>
                </select>
                <i class="fas fa-chevron-down filter-icon"></i>
            </div>
        </div>

        <!-- Cards Grid -->
        <div class="cards-grid">
            <!-- Card 1 - Con depósito -->
            <div class="apartado-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tag"></i>
                        <span>L-25</span>
                    </h3>
                    <span class="badge badge-deposito">Con depósito</span>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Cliente</span>
                            <span class="info-value highlight">María González</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Fraccionamiento</span>
                            <span class="info-value">OCEÁNICA</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Vendedor</span>
                            <span class="info-value">Juan Pérez</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Fecha</span>
                            <span class="info-value">25/07/2023</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Vence</span>
                        <span class="info-value highlight">27/07 - 18:00</span>
                    </div>
                    
                    <div class="timer-container">
                        <div class="timer timer-verde" id="timer1">
                            <i class="fas fa-clock"></i> 20h restantes
                        </div>
                    </div>
                    
                    <a href="detalles_apartados" class="details-link">
                        Ver detalles <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
            
            <!-- Card 2 - De palabra -->
            <div class="apartado-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tag"></i>
                        <span>L-42</span>
                    </h3>
                    <span class="badge badge-palabra">De palabra</span>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Cliente</span>
                            <span class="info-value highlight">Carlos Ruiz</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Fraccionamiento</span>
                            <span class="info-value">REAL CAMPESTRE</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Vendedor</span>
                            <span class="info-value">Ana López</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Fecha</span>
                            <span class="info-value">24/07/2023</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Vence</span>
                        <span class="info-value highlight">26/07 - 12:00</span>
                    </div>
                    
                    <div class="timer-container">
                        <div class="timer timer-amarillo" id="timer2">
                            <i class="fas fa-clock"></i> 10h restantes
                        </div>
                    </div>
                    
                    <a href="detalles_apartados" class="details-link">
                        Ver detalles <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
            
            <!-- Card 3 - Con depósito (urgente) -->
            <div class="apartado-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tag"></i>
                        <span>L-18</span>
                    </h3>
                    <span class="badge badge-deposito">Con depósito</span>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Cliente</span>
                            <span class="info-value highlight">Roberto Sánchez</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Fraccionamiento</span>
                            <span class="info-value">SICARÚ</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Vendedor</span>
                            <span class="info-value">Luisa Martínez</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Fecha</span>
                            <span class="info-value">23/07/2023</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Vence</span>
                        <span class="info-value highlight">25/07 - 14:00</span>
                    </div>
                    
                    <div class="timer-container">
                        <div class="timer timer-rojo" id="timer3">
                            <i class="fas fa-clock"></i> 2h restantes
                        </div>
                    </div>
                    
                    <a href="detalles_apartados" class="details-link">
                        Ver detalles <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
            
            <!-- Card 4 - Ejemplo adicional -->
            <div class="apartado-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tag"></i>
                        <span>L-07</span>
                    </h3>
                    <span class="badge badge-palabra">De palabra</span>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Cliente</span>
                            <span class="info-value highlight">Laura Fernández</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Fraccionamiento</span>
                            <span class="info-value">OCEÁNICA</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Vendedor</span>
                            <span class="info-value">Miguel Ángel</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Fecha</span>
                            <span class="info-value">26/07/2023</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Vence</span>
                        <span class="info-value highlight">28/07 - 16:00</span>
                    </div>
                    
                    <div class="timer-container">
                        <div class="timer timer-verde" id="timer4">
                            <i class="fas fa-clock"></i> 45h restantes
                        </div>
                    </div>
                    
                    <a href="detalles_apartados" class="details-link">
                        Ver detalles <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>


    <script>
        // Aquí iría el código JavaScript para:
        // 1. Calcular las horas restantes para cada apartado
        // 2. Actualizar los temporizadores en tiempo real
        // 3. Cambiar los colores según el tiempo restante
        // 4. Filtrar las cards según los selectores
        
        function actualizarTemporizadores() {
            // Ejemplo de implementación:
            // 1. Obtener fecha de vencimiento de cada apartado
            // 2. Calcular diferencia con fecha actual
            // 3. Actualizar el texto y color según las horas restantes
            // 4. Repetir cada minuto
            
            // Por ahora solo mostramos los valores de ejemplo
        }
        
        document.addEventListener('DOMContentLoaded', actualizarTemporizadores);
    </script>


    <div id="logout-modal" style="display:none;">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <h2>¿Cerrar sesión?</h2>
        <p>¿Estás seguro que deseas salir de tu cuenta?</p>
        <div class="modal-actions">
        <button id="confirm-logout" class="btn btn-primary">Sí, salir</button>
        <button id="cancel-logout" class="btn btn-outline">Cancelar</button>
        </div>
    </div>
    </div>


    <script>
        // Selección de botones
        const logoutBtns = [document.getElementById('logout-btn-desktop'), document.getElementById('logout-btn-mobile')];
        const modal = document.getElementById('logout-modal');
        const confirmBtn = document.getElementById('confirm-logout');
        const cancelBtn = document.getElementById('cancel-logout');

        logoutBtns.forEach(btn => {
            if (btn) btn.addEventListener('click', function(e) {
            e.preventDefault();
            modal.style.display = 'block';
            });
        });

        cancelBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        confirmBtn.addEventListener('click', function() {
            // Redirige o realiza el cierre de sesión
            window.location.href = 'index'; // Cambia la URL según tu flujo
        });

        // Cierra el modal si se hace click fuera del contenido
        modal.querySelector('.modal-overlay').addEventListener('click', function() {
            modal.style.display = 'none';
        });
    </script>
</body>
</html>