@extends('asesor.navbar')

@section('title', 'Nelva Bienes Raíces - Apartado Detalles')

@push('styles')
<link href="{{ asset('css/ventaForm.css') }}" rel="stylesheet">
@endpush


@section('content')
    
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-plus-circle"></i>
                <span>Crear Nueva Venta</span>
            </h1>
            <a href="#" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al listado
            </a>
        </div>

        <!-- Progress Bar -->
        <div class="progress-container">
            <div class="progress-header">
                <h3>Progreso del Formulario</h3>
                <span id="progress-percentage">0%</span>
            </div>
            <div class="progress-steps">
                <div class="progress-bar" id="progress-bar" style="width: 0%"></div>
                <div class="step active" data-step="1">
                    <div class="step-circle">1</div>
                    <div class="step-label">Venta</div>
                </div>
                <div class="step" data-step="2">
                    <div class="step-circle">2</div>
                    <div class="step-label">Apartado</div>
                </div>
                <div class="step" data-step="3">
                    <div class="step-circle">3</div>
                    <div class="step-label">Cliente</div>
                </div>
                <div class="step" data-step="4">
                    <div class="step-circle">4</div>
                    <div class="step-label">Contacto</div>
                </div>
                <div class="step" data-step="5">
                    <div class="step-circle">5</div>
                    <div class="step-label">Dirección</div>
                </div>
                <div class="step" data-step="6">
                    <div class="step-circle">6</div>
                    <div class="step-label">Opcional</div>
                </div>
            </div>
        </div>

        <form action="#" method="POST" enctype="multipart/form-data" id="venta-form">
            <!-- Información de la Venta -->
            <div class="card" id="card-venta">
                <div class="card-header" data-target="card-venta-body">
                    <h3><i class="fas fa-shopping-cart"></i> Información de la Venta</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="card-body" id="card-venta-body">
                    <div class="form-group">
                        <label for="fechaSolicitud">Fecha de Solicitud <span class="required">*</span></label>
                        <input type="date" name="fechaSolicitud" id="fechaSolicitud" class="form-control" value="" required>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i> Este campo es obligatorio
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="estatus">Estatus <span class="required">*</span></label>
                        <select name="estatus" id="estatus" class="form-control" required>
                            <option value="">Seleccione un estatus</option>
                            <option value="solicitud">Solicitud</option>
                            <option value="pagos">Pagos</option>
                            <option value="retraso">Retraso</option>
                            <option value="liquidado">Liquidado</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i> Este campo es obligatorio
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ticket_path">Ticket de Pago (PDF, JPG, PNG)</label>
                        <input type="file" name="ticket_path" id="ticket_path" class="form-control" accept=".pdf,.jpg,.png">
                        <div class="helper-text">Formatos aceptados: PDF, JPG, PNG. Tamaño máximo: 5MB</div>
                    </div>
                    <div class="form-group">
                        <label for="enganche">Enganche <span class="required">*</span></label>
                        <input type="number" name="enganche" id="enganche" class="form-control" value="" step="0.01" min="0" required>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i> Este campo es obligatorio
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="total">Precio Total <span class="required">*</span></label>
                        <input type="number" name="total" id="total" class="form-control" value="" step="0.01" min="0" required>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i> Este campo es obligatorio
                        </div>
                    </div>
                    
                    <!-- Financial Summary -->
                    <div class="financial-summary" id="financial-summary" style="display: none;">
                        <div class="financial-item">
                            <div class="financial-label">Enganche</div>
                            <div class="financial-value" id="enganche-display">$0.00</div>
                        </div>
                        <div class="financial-item">
                            <div class="financial-label">Precio Total</div>
                            <div class="financial-value" id="total-display">$0.00</div>
                        </div>
                        <div class="financial-item">
                            <div class="financial-label">Saldo Pendiente</div>
                            <div class="financial-value" id="saldo-display">$0.00</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del Apartado -->
            <div class="card" id="card-apartado">
                <div class="card-header" data-target="card-apartado-body">
                    <h3><i class="fas fa-calendar-alt"></i> Información del Apartado</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="card-body" id="card-apartado-body">
                    <div class="form-group">
                        <label for="tipoApartado">Tipo de Apartado <span class="required">*</span></label>
                        <input type="text" name="tipoApartado" id="tipoApartado" class="form-control" value="" required>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i> Este campo es obligatorio
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cliente_nombre">Nombre del Cliente (Apartado) <span class="required">*</span></label>
                        <input type="text" name="cliente_nombre" id="cliente_nombre" class="form-control" value="" required>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i> Este campo es obligatorio
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cliente_apellidos">Apellidos del Cliente (Apartado) <span class="required">*</span></label>
                        <input type="text" name="cliente_apellidos" id="cliente_apellidos" class="form-control" value="" required>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i> Este campo es obligatorio
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fechaApartado">Fecha de Apartado <span class="required">*</span></label>
                        <input type="date" name="fechaApartado" id="fechaApartado" class="form-control" value="" required>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i> Este campo es obligatorio
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fechaVencimiento">Fecha de Vencimiento <span class="required">*</span></label>
                        <input type="date" name="fechaVencimiento" id="fechaVencimiento" class="form-control" value="" required>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i> Este campo es obligatorio
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="id_usuario">Asesor <span class="required">*</span></label>
                        <select name="id_usuario" id="id_usuario" class="form-control" required>
                            <option value="">Seleccione un asesor</option>
                            <option value="1">Juan Pérez</option>
                            <option value="2">María García</option>
                            <option value="3">Carlos López</option>
                        </select>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i> Este campo es obligatorio
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="lotes">Lotes <span class="required">*</span></label>
                        <select name="lotes[]" id="lotes" class="form-control" multiple required>
                            <option value="1">Lote A-001</option>
                            <option value="2">Lote A-002</option>
                            <option value="3">Lote A-003</option>
                            <option value="4">Lote B-001</option>
                            <option value="5">Lote B-002</option>
                        </select>
                        <div class="helper-text">Mantén presionada la tecla Ctrl (Cmd en Mac) para seleccionar múltiples lotes</div>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i> Este campo es obligatorio
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del Cliente -->
            <div class="card" id="card-cliente">
                <div class="card-header" data-target="card-cliente-body">
                    <h3><i class="fas fa-user"></i> Información del Cliente</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="card-body" id="card-cliente-body">
                    <div class="form-group">
                        <label for="cliente_nombres">Nombres <span class="required">*</span></label>
                        <input type="text" name="cliente[nombres]" id="cliente_nombres" class="form-control" value="" required>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i> Este campo es obligatorio
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cliente_apellidos">Apellidos <span class="required">*</span></label>
                        <input type="text" name="cliente[apellidos]" id="cliente_apellidos" class="form-control" value="" required>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i> Este campo es obligatorio
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cliente_edad">Edad <span class="required">*</span></label>
                        <input type="number" name="cliente[edad]" id="cliente_edad" class="form-control" value="" min="18" max="100" required>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i> La edad debe estar entre 18 y 100 años
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cliente_estado_civil">Estado Civil <span class="required">*</span></label>
                        <select name="cliente[estado_civil]" id="cliente_estado_civil" class="form-control" required>
                            <option value="">Seleccione un estado civil</option>
                            <option value="soltero">Soltero(a)</option>
                            <option value="casado">Casado(a)</option>
                            <option value="divorciado">Divorciado(a)</option>
                            <option value="viudo">Viudo(a)</option>
                            <option value="union_libre">Unión Libre</option>
                        </select>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i> Este campo es obligatorio
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cliente_lugar_origen">Lugar de Origen <span class="required">*</span></label>
                        <input type="text" name="cliente[lugar_origen]" id="cliente_lugar_origen" class="form-control" value="" required>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i> Este campo es obligatorio
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cliente_ocupacion">Ocupación <span class="required">*</span></label>
                        <input type="text" name="cliente[ocupacion]" id="cliente_ocupacion" class="form-control" value="" required>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i> Este campo es obligatorio
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cliente_clave_elector">Clave de Elector</label>
                        <input type="text" name="cliente[clave_elector]" id="cliente_clave_elector" class="form-control" value="" pattern="[A-Z0-9]{18}">
                        <div class="helper-text">Formato: 18 caracteres alfanuméricos</div>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i> La clave de elector debe tener 18 caracteres alfanuméricos
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contacto del Cliente -->
            <div class="card" id="card-contacto">
                <div class="card-header" data-target="card-contacto-body">
                    <h3><i class="fas fa-address-book"></i> Contacto del Cliente</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="card-body" id="card-contacto-body">
                    <div class="form-group">
                        <label for="contacto_telefono">Teléfono <span class="required">*</span></label>
                        <input type="tel" name="contacto[telefono]" id="contacto_telefono" class="form-control" value="" pattern="[0-9]{10}" required>
                        <div class="helper-text">Formato: 10 dígitos sin espacios ni guiones</div>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i> El teléfono debe tener 10 dígitos
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="contacto_email">Email <span class="required">*</span></label>
                        <input type="email" name="contacto[email]" id="contacto_email" class="form-control" value="" required>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i> Por favor ingresa un email válido
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dirección del Cliente -->
            <div class="card" id="card-direccion">
                <div class="card-header" data-target="card-direccion-body">
                    <h3><i class="fas fa-map-marker-alt"></i> Dirección del Cliente</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="card-body" id="card-direccion-body">
                    <div class="form-group">
                        <label for="direccion_nacionalidad">Nacionalidad <span class="required">*</span></label>
                        <input type="text" name="direccion[nacionalidad]" id="direccion_nacionalidad" class="form-control" value="Mexicana" required>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i> Este campo es obligatorio
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="direccion_estado">Estado <span class="required">*</span></label>
                        <input type="text" name="direccion[estado]" id="direccion_estado" class="form-control" value="" required>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i> Este campo es obligatorio
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="direccion_municipio">Municipio <span class="required">*</span></label>
                        <input type="text" name="direccion[municipio]" id="direccion_municipio" class="form-control" value="" required>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i> Este campo es obligatorio
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="direccion_localidad">Localidad <span class="required">*</span></label>
                        <input type="text" name="direccion[localidad]" id="direccion_localidad" class="form-control" value="" required>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i> Este campo es obligatorio
                        </div>
                    </div>
                </div>
            </div>

            <!-- Beneficiario (Opcional) -->
            <div class="card optional" id="card-beneficiario">
                <div class="card-header" data-target="card-beneficiario-body">
                    <h3><i class="fas fa-user-friends"></i> Beneficiario (Opcional)</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="card-body" id="card-beneficiario-body">
                    <div class="form-group">
                        <label for="beneficiario_nombres">Nombres</label>
                        <input type="text" name="beneficiario[nombres]" id="beneficiario_nombres" class="form-control" value="">
                    </div>
                    <div class="form-group">
                        <label for="beneficiario_apellidos">Apellidos</label>
                        <input type="text" name="beneficiario[apellidos]" id="beneficiario_apellidos" class="form-control" value="">
                    </div>
                    <div class="form-group">
                        <label for="beneficiario_telefono">Teléfono</label>
                        <input type="tel" name="beneficiario[telefono]" id="beneficiario_telefono" class="form-control" value="" pattern="[0-9]{10}">
                        <div class="helper-text">Formato: 10 dígitos sin espacios ni guiones</div>
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i> El teléfono debe tener 10 dígitos
                        </div>
                    </div>
                </div>
            </div>

            <!-- Crédito (Opcional) -->
            <div class="card optional" id="card-credito">
                <div class="card-header" data-target="card-credito-body">
                    <h3><i class="fas fa-credit-card"></i> Crédito (Opcional)</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="card-body" id="card-credito-body">
                    <div class="form-group">
                        <label for="credito_fecha_inicio">Fecha de Inicio</label>
                        <input type="date" name="credito[fecha_inicio]" id="credito_fecha_inicio" class="form-control" value="">
                    </div>
                    <div class="form-group">
                        <label for="credito_plazo_financiamiento">Plazo de Financiamiento (meses)</label>
                        <input type="number" name="credito[plazo_financiamiento]" id="credito_plazo_financiamiento" class="form-control" value="" min="1" max="360">
                    </div>
                    <div class="form-group">
                        <label for="credito_modalidad_pago">Modalidad de Pago</label>
                        <select name="credito[modalidad_pago]" id="credito_modalidad_pago" class="form-control">
                            <option value="">Seleccione una modalidad</option>
                            <option value="mensual">Mensual</option>
                            <option value="quincenal">Quincenal</option>
                            <option value="semanal">Semanal</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="credito_formas_pago">Formas de Pago</label>
                        <select name="credito[formas_pago]" id="credito_formas_pago" class="form-control">
                            <option value="">Seleccione una forma de pago</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="transferencia">Transferencia</option>
                            <option value="tarjeta">Tarjeta</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="credito_dia_pago">Día de Pago</label>
                        <input type="number" name="credito[dia_pago]" id="credito_dia_pago" class="form-control" value="" min="1" max="31">
                    </div>
                    <div class="form-group">
                        <label for="credito_observaciones">Observaciones</label>
                        <textarea name="credito[observaciones]" id="credito_observaciones" class="form-control"></textarea>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Crear Venta
                </button>
                <a href="#" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle card sections
            const cardHeaders = document.querySelectorAll('.card-header');
            cardHeaders.forEach(header => {
                header.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const targetBody = document.getElementById(targetId);
                    const icon = this.querySelector('i.fa-chevron-down');
                    
                    if (targetBody.classList.contains('collapsed')) {
                        targetBody.classList.remove('collapsed');
                        this.classList.remove('collapsed');
                        icon.style.transform = 'rotate(0deg)';
                    } else {
                        targetBody.classList.add('collapsed');
                        this.classList.add('collapsed');
                        icon.style.transform = 'rotate(-90deg)';
                    }
                });
            });

            // Financial calculations
            const engancheInput = document.getElementById('enganche');
            const totalInput = document.getElementById('total');
            const financialSummary = document.getElementById('financial-summary');
            
            function updateFinancialSummary() {
                const enganche = parseFloat(engancheInput.value) || 0;
                const total = parseFloat(totalInput.value) || 0;
                const saldo = total - enganche;
                
                if (enganche > 0 || total > 0) {
                    financialSummary.style.display = 'grid';
                    document.getElementById('enganche-display').textContent = formatCurrency(enganche);
                    document.getElementById('total-display').textContent = formatCurrency(total);
                    document.getElementById('saldo-display').textContent = formatCurrency(saldo);
                } else {
                    financialSummary.style.display = 'none';
                }
                
                updateProgress();
            }
            
            function formatCurrency(amount) {
                return new Intl.NumberFormat('es-MX', {
                    style: 'currency',
                    currency: 'MXN'
                }).format(amount);
            }
            
            engancheInput.addEventListener('input', updateFinancialSummary);
            totalInput.addEventListener('input', updateFinancialSummary);

            // Form validation
            const form = document.getElementById('venta-form');
            const inputs = form.querySelectorAll('input, select, textarea');
            
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    validateField(this);
                });
                
                input.addEventListener('input', function() {
                    if (this.classList.contains('is-invalid')) {
                        validateField(this);
                    }
                });
            });
            
            function validateField(field) {
                const value = field.value.trim();
                const isRequired = field.hasAttribute('required');
                const pattern = field.getAttribute('pattern');
                const type = field.getAttribute('type');
                
                // Remove previous validation classes
                field.classList.remove('is-invalid', 'is-valid');
                
                // Check if field is required and empty
                if (isRequired && value === '') {
                    field.classList.add('is-invalid');
                    return false;
                }
                
                // Check pattern if exists
                if (pattern && value !== '') {
                    const regex = new RegExp(pattern);
                    if (!regex.test(value)) {
                        field.classList.add('is-invalid');
                        return false;
                    }
                }
                
                // Special validation for email
                if (type === 'email' && value !== '') {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(value)) {
                        field.classList.add('is-invalid');
                        return false;
                    }
                }
                
                // Special validation for number fields with min/max
                if ((type === 'number' || field.tagName === 'SELECT') && value !== '') {
                    const min = field.getAttribute('min');
                    const max = field.getAttribute('max');
                    
                    if (min && parseFloat(value) < parseFloat(min)) {
                        field.classList.add('is-invalid');
                        return false;
                    }
                    
                    if (max && parseFloat(value) > parseFloat(max)) {
                        field.classList.add('is-invalid');
                        return false;
                    }
                }
                
                // If we passed all validations and field has value, mark as valid
                if (value !== '') {
                    field.classList.add('is-valid');
                }
                
                return true;
            }
            
            // Progress tracking
            function updateProgress() {
                const requiredFields = form.querySelectorAll('[required]');
                let completedFields = 0;
                
                requiredFields.forEach(field => {
                    if (field.value.trim() !== '') {
                        completedFields++;
                    }
                });
                
                const progressPercentage = Math.round((completedFields / requiredFields.length) * 100);
                const progressBar = document.getElementById('progress-bar');
                const progressPercentageDisplay = document.getElementById('progress-percentage');
                
                progressBar.style.width = `${progressPercentage}%`;
                progressPercentageDisplay.textContent = `${progressPercentage}%`;
                
                // Update step indicators
                const steps = document.querySelectorAll('.step');
                steps.forEach(step => {
                    const stepNumber = parseInt(step.getAttribute('data-step'));
                    step.classList.remove('active', 'completed');
                    
                    if (stepNumber * 16 <= progressPercentage) {
                        step.classList.add('completed');
                    } else if ((stepNumber - 1) * 16 <= progressPercentage) {
                        step.classList.add('active');
                    }
                });
            }
            
            // Update progress on any input
            inputs.forEach(input => {
                input.addEventListener('input', updateProgress);
            });
            
            // Form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                let isValid = true;
                inputs.forEach(input => {
                    if (!validateField(input)) {
                        isValid = false;
                    }
                });
                
                if (isValid) {
                    // Show success message
                    alert('¡Formulario enviado correctamente!');
                    // In a real application, you would submit the form here
                } else {
                    // Scroll to first error
                    const firstError = form.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstError.focus();
                    }
                }
            });
            
            // Initialize progress
            updateProgress();
        });
    </script>
</body>
@endsection