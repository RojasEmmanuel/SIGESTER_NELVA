@php
    $navbarMap = [
        'Administrador' => 'admin.navbar',
        'Asesor' => 'asesor.navbar',
        'Cobranza' => 'cobranza.navbar',
        'Ingeniero' => 'ingeniero.navbar',
    ];
    $usuario = Auth::user();
    if (! $usuario->relationLoaded('tipo')) {
        $usuario->load('tipo');
    }
    $tipoNombre = $usuario->tipo->tipo ?? 'Asesor';
    $navbar = $navbarMap[$tipoNombre] ?? 'asesor.navbar';
@endphp

@extends($navbar)

@section('title', 'Nelva Bienes Raíces - Venta')

@push('styles')
<link href="{{ asset('css/ventaForm.css') }}" rel="stylesheet">
<style>
    /* ========================================
   1. SELECTOR DE LOTE (fraccionamiento + lotes)
   ======================================== */

/* Select de fraccionamiento */
#fraccionamiento_id {
    border: 1.5px solid #d1d3e2;
    border-radius: 8px;
    padding: 10px 12px;
    font-size: 0.95rem;
    background-color: #fdfdff;
    transition: all 0.2s ease;
}

#fraccionamiento_id:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.2);
    outline: none;
}

/* Select múltiple de lotes */
#lotes-select {
    height: 160px;
    font-size: 0.9rem;
    background-color: #f8f9fc;
    border: 1.5px solid #d1d3e2;
    border-radius: 8px;
    padding: 8px;
}

#lotes-select option {
    padding: 6px 8px;
}

#lotes-select option:checked {
    background: #4e73df;
    color: white;
}

/* Botón "Agregar seleccionados" */
#add-lotes-btn {
    background: #1cc88a;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
}

#add-lotes-btn:hover {
    background: #17a673;
    transform: translateY(-1px);
}

#add-lotes-btn i {
    font-size: 1rem;
}

/* Área de lotes seleccionados (badges) */
#lotes-selected {
    min-height: 60px;
    padding: 12px;
    background-color: #f8f9fc;
    border: 1.5px dashed #d1d3e2;
    border-radius: 8px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
    margin-top: 12px;
    transition: border 0.2s ease;
}

#lotes-selected.is-invalid {
    border-color: #e74c3c;
    background-color: #fdf2f2;
}

#lotes-selected .badge {
    background: #4e73df;
    color: white;
    padding: 6px 10px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
}

/* Texto pequeño (ayuda) */
#llotes-selector small {
    color: #858796;
    font-size: 0.8rem;
}

/* ========================================
   2. RADIOS: TIPO DE VENTA (Contado / Crédito)
   ======================================== */

.form-check {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
    padding-left: 0;
}

.form-check-input {
    width: 1.2em;
    height: 1.2em;
    margin-top: 0;
    margin-right: 10px;
    border: 2px solid #d1d3e2;
    border-radius: 50%;
    cursor: pointer;
    appearance: none;
    position: relative;
    transition: all 0.2s ease;
}

.form-check-input:checked {
    background-color: #4e73df;
    border-color: #4e73df;
}

.form-check-input:checked::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 6px;
    height: 6px;
    background: white;
    border-radius: 50%;
    transform: translate(-50%, -50%);
}

.form-check-label {
    font-weight: 500;
    color: #2c3e50;
    cursor: pointer;
    user-select: none;
    font-size: 0.95rem;
}

/* Etiqueta del grupo */
label[for="pago-contado"],
label[for="pago-credito"] {
    margin-bottom: 8px;
    font-weight: 600;
    color: #2c3e50;
}
</style>
@endpush

@section('content')
<div class="container">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-home"></i>
            <span>Venta</span>
        </h1>
        <a href="{{ route('ventas.index') }}" class="btn btn-secondary">
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
            <div class="step active" data-step="1"><div class="step-circle">1</div><div class="step-label">Lotes</div></div>
            <div class="step" data-step="2"><div class="step-circle">2</div><div class="step-label">Venta</div></div>
            <div class="step" data-step="3"><div class="step-circle">3</div><div class="step-label">Cliente</div></div>
            <div class="step" data-step="4"><div class="step-circle">4</div><div class="step-label">Contacto</div></div>
            <div class="step" data-step="5"><div class="step-circle">5</div><div class="step-label">Dirección</div></div>
            <div class="step" data-step="6"><div class="step-circle">6</div><div class="step-label">Opcional</div></div>
        </div>
    </div>

    <form action="{{ route('ventas.direct.store') }}" method="POST" enctype="multipart/form-data" id="venta-form">
        @csrf

        <!-- Selección de Lotes -->
        <div class="card" id="card-lotes">
            <div class="card-header" data-target="card-lotes-body">
                <h3><i class="fas fa-map-marked-alt"></i> Seleccionar Lotes</h3>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="card-body" id="card-lotes-body">
                <div class="form-group">
                    <label for="fraccionamiento_id">Fraccionamiento <span class="required">*</span></label>
                    <select id="fraccionamiento_id" class="form-control">
                        <option value="">Seleccione un fraccionamiento</option>
                        @foreach ($fraccionamientos as $frac)
                            <option value="{{ $frac->id_fraccionamiento }}">
                                {{ $frac->nombre }} ({{ $frac->lotes->count() }} disponibles)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" id="lotes-selector" style="display: none;">
                    <label>Lotes Disponibles</label>
                    <select id="lotes-select" class="form-control" multiple>
                        <!-- Solo ID del lote -->
                    </select>
                    <small class="text-muted">Mantén presionado Ctrl (o Cmd) para seleccionar varios</small>
                    <button type="button" id="add-lotes-btn" class="btn btn-sm btn-outline-primary mt-2">
                        <i class="fas fa-plus"></i> Agregar seleccionados
                    </button>
                </div>

                <div class="form-group mt-3">
                    <label>Lotes Seleccionados <span class="required">*</span></label>
                    <div id="lotes-selected" class="border p-2 rounded bg-light" style="min-height: 50px;">
                        <em class="text-muted">Ningún lote seleccionado</em>
                    </div>
                    @error('lotes')
                        <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Tipo de Venta -->
        <div class="card" id="card-tipo-venta">
            <div class="card-header" data-target="card-tipo-venta-body">
                <h3><i class="fas fa-money-check-alt"></i> Tipo de Venta</h3>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="card-body" id="card-tipo-venta-body">
                <div class="form-group">
                    <label>Tipo de Pago <span class="required">*</span></label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_pago" id="pago-contado" value="contado" checked>
                        <label class="form-check-label" for="pago-contado">Contado</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_pago" id="pago-credito" value="credito">
                        <label class="form-check-label" for="pago-credito">Con Crédito</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información de la Venta -->
        <div class="card" id="card-venta">
            <div class="card-header" data-target="card-venta-body">
                <h3><i class="fas fa-shopping-cart"></i> Información de la Venta</h3>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="card-body" id="card-venta-body">
                <div class="form-group">
                    <label for="ticket_path">Ticket de Pago <span class="required">*</span></label>
                    <input type="file" name="ticket_path" id="ticket_path" class="form-control" accept=".pdf,.jpg,.png" required>
                    <div class="helper-text">PDF, JPG, PNG. Máx: 5MB</div>
                    @error('ticket_path') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <!-- Enganche: solo visible si es crédito -->
                <div class="form-group" id="enganche-group" style="display: none;">
                    <label for="enganche">Enganche <span class="required">*</span></label>
                    <input type="number" name="enganche" id="enganche" class="form-control" step="0.01" min="0">
                    @error('enganche') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="total">Precio Total <span class="required">*</span></label>
                    <input type="number" name="total" id="total" class="form-control" step="0.01" min="0" required>
                    @error('total') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="financial-summary" id="financial-summary" style="display: none;">
                    <div class="financial-item">
                        <div class="financial-label">Enganche</div>
                        <div class="financial-value" id="enganche-display">$0.00</div>
                    </div>
                    <div class="financial-item">
                        <div class="financial-label">Total</div>
                        <div class="financial-value" id="total-display">$0.00</div>
                    </div>
                    <div class="financial-item">
                        <div class="financial-label">Saldo</div>
                        <div class="financial-value" id="saldo-display">$0.00</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Crédito (solo si es crédito) -->
        <div class="card optional" id="card-credito" style="display: none;">
            <div class="card-header" data-target="card-credito-body">
                <h3><i class="fas fa-credit-card"></i> Detalles del Crédito</h3>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="card-body" id="card-credito-body">
                <div class="form-group">
                    <label for="credito_fecha_inicio">Fecha de Inicio</label>
                    <input type="date" name="credito[fecha_inicio]" id="credito_fecha_inicio" class="form-control">
                </div>
                <div class="form-group">
                    <label for="credito_plazo_financiamiento">Plazo de Financiamiento</label>
                    <select name="credito[plazo_financiamiento]" id="credito_plazo_financiamiento" class="form-control">
                        <option value="">Seleccione un plazo</option>
                        <option value="12 meses">12 meses</option>
                        <option value="24 meses">24 meses</option>
                        <option value="36 meses">36 meses</option>
                        <option value="48 meses">48 meses</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>
                <div class="form-group" id="custom_plazo_group" style="display: none;">
                    <label for="credito_otro_plazo">Especificar Plazo (meses) <span class="required">*</span></label>
                    <input type="number" name="credito[otro_plazo]" id="credito_otro_plazo" class="form-control" min="1" max="360">
                </div>
                <div class="form-group">
                    <label for="credito_modalidad_pago">Modalidad de Pago</label>
                    <select name="credito[modalidad_pago]" id="credito_modalidad_pago" class="form-control">
                        <option value="">Seleccione</option>
                        <option value="mensual">Mensual</option>
                        <option value="bimestral">Bimestral</option>
                        <option value="trimestral">Trimestral</option>
                        <option value="semestral">Semestral</option>
                        <option value="anual">Anual</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="credito_formas_pago">Formas de Pago</label>
                    <select name="credito[formas_pago]" id="credito_formas_pago" class="form-control">
                        <option value="">Seleccione</option>
                        <option value="efectivo">Efectivo</option>
                        <option value="transferencia">Transferencia</option>
                        <option value="cheque">Cheque</option>
                        <option value="tarjeta credito/debito">Tarjeta</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="credito_dia_pago">Día de Pago</label>
                    <input type="number" name="credito[dia_pago]" id="credito_dia_pago" class="form-control" min="1" max="31">
                </div>
                <div class="form-group">
                    <label for="credito_observaciones">Observaciones</label>
                    <textarea name="credito[observaciones]" id="credito_observaciones" class="form-control"></textarea>
                </div>
            </div>
        </div>

        <!-- Cliente, Contacto, Dirección, Beneficiario (igual que antes) -->
        <!-- [INCLUIR LOS MISMOS BLOQUES QUE EN VERSIONES ANTERIORES] -->
        <!-- Cliente -->
        <div class="card" id="card-cliente">
            <div class="card-header" data-target="card-cliente-body">
                <h3><i class="fas fa-user"></i> Información del Cliente</h3>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="card-body" id="card-cliente-body">
                <div class="form-group">
                    <label for="cliente_nombres">Nombres <span class="required">*</span></label>
                    <input type="text" name="cliente[nombres]" id="cliente_nombres" class="form-control" required>
                    @error('cliente.nombres') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="cliente_apellidos">Apellidos <span class="required">*</span></label>
                    <input type="text" name="cliente[apellidos]" id="cliente_apellidos" class="form-control" required>
                    @error('cliente.apellidos') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="cliente_edad">Edad <span class="required">*</span></label>
                    <input type="number" name="cliente[edad]" id="cliente_edad" class="form-control" min="18" required>
                    @error('cliente.edad') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="cliente_estado_civil">Estado Civil <span class="required">*</span></label>
                    <select name="cliente[estado_civil]" id="cliente_estado_civil" class="form-control" required>
                        <option value="">Seleccione</option>
                        <option value="soltero">Soltero(a)</option>
                        <option value="casado">Casado(a)</option>
                        <option value="divorciado">Divorciado(a)</option>
                        <option value="viudo">Viudo(a)</option>
                        <option value="unión libre">Unión Libre</option>
                    </select>
                    @error('cliente.estado_civil') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="cliente_lugar_origen">Lugar de Origen <span class="required">*</span></label>
                    <input type="text" name="cliente[lugar_origen]" id="cliente_lugar_origen" class="form-control" required>
                    @error('cliente.lugar_origen') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="cliente_ocupacion">Ocupación <span class="required">*</span></label>
                    <input type="text" name="cliente[ocupacion]" id="cliente_ocupacion" class="form-control" required>
                    @error('cliente.ocupacion') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="cliente_clave_elector">Clave de Elector</label>
                    <input type="text" name="cliente[clave_elector]" id="cliente_clave_elector" class="form-control" pattern="[A-Z0-9]{18}">
                    <div class="helper-text">18 caracteres alfanuméricos</div>
                    @error('cliente.clave_elector') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="cliente_ine_frente">INE Frente <span class="required">*</span></label>
                    <input type="file" name="cliente[ine_frente]" id="cliente_ine_frente" class="form-control" accept=".jpg,.png" required>
                    @error('cliente.ine_frente') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="cliente_ine_reverso">INE Reverso <span class="required">*</span></label>
                    <input type="file" name="cliente[ine_reverso]" id="cliente_ine_reverso" class="form-control" accept=".jpg,.png" required>
                    @error('cliente.ine_reverso') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <!-- Contacto -->
        <div class="card" id="card-contacto">
            <div class="card-header" data-target="card-contacto-body">
                <h3><i class="fas fa-address-book"></i> Contacto</h3>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="card-body" id="card-contacto-body">
                <div class="form-group">
                    <label for="contacto_telefono">Teléfono <span class="required">*</span></label>
                    <input type="tel" name="contacto[telefono]" id="contacto_telefono" class="form-control" pattern="[0-9]{10}" required>
                    @error('contacto.telefono') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="contacto_email">Email <span class="required">*</span></label>
                    <input type="email" name="contacto[email]" id="contacto_email" class="form-control" required>
                    @error('contacto.email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <!-- Dirección -->
        <div class="card" id="card-direccion">
            <div class="card-header" data-target="card-direccion-body">
                <h3><i class="fas fa-map-marker-alt"></i> Dirección</h3>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="card-body" id="card-direccion-body">
                <div class="form-group">
                    <label for="direccion_nacionalidad">Nacionalidad <span class="required">*</span></label>
                    <input type="text" name="direccion[nacionalidad]" id="direccion_nacionalidad" class="form-control" value="Mexicana" required>
                    @error('direccion.nacionalidad') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="direccion_estado">Estado <span class="required">*</span></label>
                    <input type="text" name="direccion[estado]" id="direccion_estado" class="form-control" required>
                    @error('direccion.estado') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="direccion_municipio">Municipio <span class="required">*</span></label>
                    <input type="text" name="direccion[municipio]" id="direccion_municipio" class="form-control" required>
                    @error('direccion.municipio') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="direccion_localidad">Localidad <span class="required">*</span></label>
                    <input type="text" name="direccion[localidad]" id="direccion_localidad" class="form-control" required>
                    @error('direccion.localidad') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
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
                    <input type="text" name="beneficiario[nombres]" id="beneficiario_nombres" class="form-control">
                </div>
                <div class="form-group">
                    <label for="beneficiario_apellidos">Apellidos</label>
                    <input type="text" name="beneficiario[apellidos]" id="beneficiario_apellidos" class="form-control">
                </div>
                <div class="form-group">
                    <label for="beneficiario_telefono">Teléfono</label>
                    <input type="tel" name="beneficiario[telefono]" id="beneficiario_telefono" class="form-control" pattern="[0-9]{10}">
                </div>
                <div class="form-group">
                    <label for="beneficiario_ine_frente">INE Frente</label>
                    <input type="file" name="beneficiario[ine_frente]" id="beneficiario_ine_frente" class="form-control" accept=".jpg,.png">
                </div>
                <div class="form-group">
                    <label for="beneficiario_ine_reverso">INE Reverso</label>
                    <input type="file" name="beneficiario[ine_reverso]" id="beneficiario_ine_reverso" class="form-control" accept=".jpg,.png">
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Crear Venta Directa
            </button>
            <a href="{{ route('ventas.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('venta-form');
    const inputs = form.querySelectorAll('input, select, textarea');
    const progressBar = document.getElementById('progress-bar');
    const progressPercentage = document.getElementById('progress-percentage');
    const engancheInput = document.getElementById('enganche');
    const engancheGroup = document.getElementById('enganche-group');
    const totalInput = document.getElementById('total');
    const financialSummary = document.getElementById('financial-summary');
    const fraccionamientoSelect = document.getElementById('fraccionamiento_id');
    const lotesSelector = document.getElementById('lotes-selector');
    const lotesSelect = document.getElementById('lotes-select');
    const addLotesBtn = document.getElementById('add-lotes-btn');
    const lotesSelected = document.getElementById('lotes-selected');
    const pagoContado = document.getElementById('pago-contado');
    const pagoCredito = document.getElementById('pago-credito');
    const cardCredito = document.getElementById('card-credito');
    const plazoSelect = document.getElementById('credito_plazo_financiamiento');
    const customPlazoGroup = document.getElementById('custom_plazo_group');

    const lotesData = @json($fraccionamientos->pluck('lotes', 'id_fraccionamiento'));
    const selectedLotes = new Set();

    // === TIPO DE PAGO: Contado vs Crédito ===
    function toggleEngancheAndCredito() {
        const isCredito = pagoCredito.checked;
        engancheGroup.style.display = isCredito ? 'block' : 'none';
        cardCredito.style.display = isCredito ? 'block' : 'none';

        if (isCredito) {
            engancheInput.setAttribute('required', 'required');
        } else {
            engancheInput.removeAttribute('required');
            engancheInput.value = '';
        }

        updateFinancialSummary();
        updateProgress();
    }

    pagoContado.addEventListener('change', toggleEngancheAndCredito);
    pagoCredito.addEventListener('change', toggleEngancheAndCredito);

    // === Lotes ===
    fraccionamientoSelect.addEventListener('change', function() {
        const fracId = this.value;
        lotesSelect.innerHTML = '';
        if (fracId && lotesData[fracId]) {
            lotesData[fracId].forEach(lote => {
                if (!selectedLotes.has(lote.id_lote)) {
                    const option = document.createElement('option');
                    option.value = lote.id_lote;
                    option.textContent = `Lote ${lote.id_lote}`;
                    lotesSelect.appendChild(option);
                }
            });
            lotesSelector.style.display = lotesSelect.options.length ? 'block' : 'none';
        } else {
            lotesSelector.style.display = 'none';
        }
    });

    addLotesBtn.addEventListener('click', function() {
        Array.from(lotesSelect.selectedOptions).forEach(option => {
            const id = option.value;
            if (!selectedLotes.has(id)) {
                selectedLotes.add(id);
                option.disabled = true;
                option.selected = false;
            }
        });
        updateLotesDisplay();
        updateProgress();
    });

    function updateLotesDisplay() {
        if (selectedLotes.size === 0) {
            lotesSelected.innerHTML = '<em class="text-muted">Ningún lote seleccionado</em>';
            return;
        }
        lotesSelected.innerHTML = Array.from(selectedLotes)
            .map(id => `<span class="badge badge-primary">Lote ${id}</span>`)
            .join(' ');
    }

    // === Financial Summary ===
    function updateFinancialSummary() {
        const enganche = parseFloat(engancheInput.value) || 0;
        const total = parseFloat(totalInput.value) || 0;
        const saldo = total - enganche;

        if ((pagoCredito.checked && enganche > 0) || total > 0) {
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
        return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(amount);
    }

    engancheInput.addEventListener('input', updateFinancialSummary);
    totalInput.addEventListener('input', updateFinancialSummary);

    // === Plazo personalizado ===
    plazoSelect.addEventListener('change', function() {
        customPlazoGroup.style.display = this.value === 'otro' ? 'block' : 'none';
    });

    // === Validación ===
    function validateField(field) {
        const value = field.value.trim();
        const isRequired = field.hasAttribute('required');
        const type = field.getAttribute('type');

        field.classList.remove('is-invalid', 'is-valid');

        if (isRequired && (value === '' || (type === 'file' && !field.files.length))) {
            field.classList.add('is-invalid');
            return false;
        }

        if (value !== '' || (type === 'file' && field.files.length)) {
            field.classList.add('is-valid');
        }
        return true;
    }

    inputs.forEach(input => {
        input.addEventListener('blur', () => validateField(input));
        input.addEventListener('input', () => { if (input.classList.contains('is-invalid')) validateField(input); });
        if (input.type === 'file') input.addEventListener('change', updateProgress);
        input.addEventListener('input', updateProgress);
    });

    // === Progreso ===
    function updateProgress() {
        const required = form.querySelectorAll('[required]:not([style*="display: none"])');
        let completed = 0;

        required.forEach(field => {
            if (field.type === 'radio' && document.querySelector(`input[name="${field.name}"]:checked`)) completed++;
            else if (field.type === 'file' && field.files.length > 0) completed++;
            else if (field.value.trim() !== '') completed++;
        });

        if (selectedLotes.size > 0) completed++;

        const totalRequired = required.length + 1; // +1 por lotes
        const percentage = Math.round((completed / totalRequired) * 100);
        progressBar.style.width = `${percentage}%`;
        progressPercentage.textContent = `${percentage}%`;
    }

    // === Submit ===
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        let valid = true;
        inputs.forEach(input => { if (!validateField(input)) valid = false; });

        if (selectedLotes.size === 0) {
            lotesSelected.classList.add('is-invalid');
            valid = false;
        } else {
            lotesSelected.classList.remove('is-invalid');
        }

        if (valid) {
            selectedLotes.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'lotes[]';
                input.value = id;
                form.appendChild(input);
            });
            this.submit();
        } else {
            const firstError = form.querySelector('.is-invalid') || lotesSelected;
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

    // Inicializar
    toggleEngancheAndCredito();
    updateProgress();
});
</script>
@endsection