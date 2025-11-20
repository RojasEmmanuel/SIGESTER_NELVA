@php
    $navbarMap = [
        'Administrador' => 'admin.navbar',
        'Asesor' => 'asesor.navbar',
        'Cobranza' => 'cobranza.navbar',
        'Ingeniero' => 'ingeniero.navbar',
    ];

    // Obtener el usuario autenticado directamente (asumiendo Auth::user() es instancia de App\Models\Usuario)
    $usuario = Auth::user();
    
    // Cargar la relación 'tipo' si no está ya cargada para evitar errores
    if (! $usuario->relationLoaded('tipo')) {
        $usuario->load('tipo');
    }
    
    $tipoNombre = $usuario->tipo->tipo ?? 'Asesor'; // Fallback a Asesor si no hay tipo
    $navbar = $navbarMap[$tipoNombre] ?? 'asesor.navbar';
@endphp

@extends($navbar)

@section('title', 'Nelva Bienes Raíces - Crear Venta')

@push('styles')
<link href="{{ asset('css/ventaForm.css') }}" rel="stylesheet">

<style>
    /* ========================================
    RADIOS: TIPO DE VENTA (Contado / Crédito)
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
</style>

@endpush

@section('content')
<body>
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-plus-circle"></i>
                <span>Crear Nueva Venta</span>
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

        <form action="{{ route('ventas.store') }}" method="POST" enctype="multipart/form-data" id="venta-form">
            @csrf
            <input type="hidden" name="tipo_pago" value="contado" id="tipo_pago_hidden">

            <!-- Tipo de Pago -->
            <div class="card" id="card-tipo-pago">
                <div class="card-header" data-target="card-tipo-pago-body">
                    <h3><i class="fas fa-money-check-alt"></i> Tipo de Pago</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="card-body" id="card-tipo-pago-body">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_pago" id="pago-contado" value="contado" checked>
                        <label class="form-check-label" for="pago-contado">Contado (pago total inmediato)</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo_pago" id="pago-credito" value="credito">
                        <label class="form-check-label" for="pago-credito">Crédito (enganche + pagos)</label>
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
                        <label for="ticket_path">Ticket de Pago (PDF, JPG, PNG) <span class="required">*</span></label>
                        <input type="file" name="ticket_path" id="ticket_path" class="form-control" accept=".pdf,.jpg,.png" required>
                        <div class="helper-text">Formatos aceptados: PDF, JPG, PNG. Tamaño máximo: 5MB</div>
                        @error('ticket_path')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="enganche">Enganche <span class="required">*</span></label>
                        <input type="number" name="enganche" id="enganche" class="form-control" value="" step="0.01" min="0" required>
                        @error('enganche')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="total">Precio Total <span class="required">*</span></label>
                        <input type="number" name="total" id="total" class="form-control" value="" step="0.01" min="0" required>
                        @error('total')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
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
                        <label for="id_apartado">Apartado <span class="required">*</span></label>
                        <select name="id_apartado" id="id_apartado" class="form-control" required>
                            <option value="">Seleccione un apartado en curso</option>
                            @foreach ($apartados as $apartado)
                                <option value="{{ $apartado->id_apartado }}"
                                        data-nombre="{{ $apartado->cliente_nombre }}"
                                        data-apellidos="{{ $apartado->cliente_apellidos }}"
                                        data-tipo="{{ $apartado->tipoApartado }}"
                                        data-fecha-apartado="{{ $apartado->fechaApartado }}"
                                        data-fecha-vencimiento="{{ $apartado->fechaVencimiento }}"
                                        data-asesor="{{ $apartado->usuario->nombre }}"
                                        data-lotes="{{ $apartado->lotesApartados->pluck('lote.id_lote')->toJson() }}">
                                    {{ $apartado->cliente_nombre }} {{ $apartado->cliente_apellidos }} - {{ $apartado->tipoApartado }} (Vence: {{ $apartado->fechaVencimiento->format('d/m/Y') }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_apartado')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Tipo de Apartado</label>
                        <input type="text" id="tipo_apartado_display" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Fecha de Apartado</label>
                        <input type="text" id="fecha_apartado_display" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Fecha de Vencimiento</label>
                        <input type="text" id="fecha_vencimiento_display" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Asesor</label>
                        <input type="text" id="asesor_display" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Lotes Apartados</label>
                        <ul id="lotes_display" class="list-group"></ul>
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
                        @error('cliente.nombres')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="cliente_apellidos">Apellidos <span class="required">*</span></label>
                        <input type="text" name="cliente[apellidos]" id="cliente_apellidos" class="form-control" value="" required>
                        @error('cliente.apellidos')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="cliente_edad">Edad <span class="required">*</span></label>
                        <input type="number" name="cliente[edad]" id="cliente_edad" class="form-control" value="" min="18" max="100" required>
                        @error('cliente.edad')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="cliente_estado_civil">Estado Civil <span class="required">*</span></label>
                        <select name="cliente[estado_civil]" id="cliente_estado_civil" class="form-control" required>
                            <option value="">Seleccione un estado civil</option>
                            <option value="soltero">Soltero(a)</option>
                            <option value="casado">Casado(a)</option>
                            <option value="divorciado">Divorciado(a)</option>
                            <option value="viudo">Viudo(a)</option>
                            <option value="unión libre">Unión Libre</option>
                        </select>
                        @error('cliente.estado_civil')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="cliente_lugar_origen">Lugar de Origen <span class="required">*</span></label>
                        <input type="text" name="cliente[lugar_origen]" id="cliente_lugar_origen" class="form-control" value="" required>
                        @error('cliente.lugar_origen')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="cliente_ocupacion">Ocupación <span class="required">*</span></label>
                        <input type="text" name="cliente[ocupacion]" id="cliente_ocupacion" class="form-control" value="" required>
                        @error('cliente.ocupacion')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="cliente_clave_elector">Clave de Elector</label>
                        <input type="text" name="cliente[clave_elector]" id="cliente_clave_elector" class="form-control" value="" pattern="[A-Z0-9]{18}">
                        <div class="helper-text">Formato: 18 caracteres alfanuméricos (ejemplo: GOMP920715HDFRRN08)</div>
                        @error('cliente.clave_elector')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="cliente_ine_frente">Foto de Identificación Oficial (Frente) <span class="required">*</span></label>
                        <input type="file" name="cliente[ine_frente]" id="cliente_ine_frente" class="form-control" accept=".jpg,.png" required>
                        <div class="helper-text">Formatos aceptados: JPG, PNG.</div>
                        @error('cliente.ine_frente')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="cliente_ine_reverso">Foto de Identificación Oficial (Reverso) <span class="required">*</span></label>
                        <input type="file" name="cliente[ine_reverso]" id="cliente_ine_reverso" class="form-control" accept=".jpg,.png" required>
                        <div class="helper-text">Formatos aceptados: JPG, PNG.</div>
                        @error('cliente.ine_reverso')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
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
                        @error('contacto.telefono')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="contacto_email">Email <span class="required">*</span></label>
                        <input type="email" name="contacto[email]" id="contacto_email" class="form-control" value="" required>
                        @error('contacto.email')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
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
                        @error('direccion.nacionalidad')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="direccion_estado">Estado <span class="required">*</span></label>
                        <input type="text" name="direccion[estado]" id="direccion_estado" class="form-control" value="" required>
                        @error('direccion.estado')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="direccion_municipio">Municipio <span class="required">*</span></label>
                        <input type="text" name="direccion[municipio]" id="direccion_municipio" class="form-control" value="" required>
                        @error('direccion.municipio')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="direccion_localidad">Localidad <span class="required">*</span></label>
                        <input type="text" name="direccion[localidad]" id="direccion_localidad" class="form-control" value="" required>
                        @error('direccion.localidad')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
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
                        @error('beneficiario.nombres')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="beneficiario_apellidos">Apellidos</label>
                        <input type="text" name="beneficiario[apellidos]" id="beneficiario_apellidos" class="form-control" value="">
                        @error('beneficiario.apellidos')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="beneficiario_telefono">Teléfono</label>
                        <input type="tel" name="beneficiario[telefono]" id="beneficiario_telefono" class="form-control" value="" pattern="[0-9]{10}">
                        <div class="helper-text">Formato: 10 dígitos sin espacios ni guiones</div>
                        @error('beneficiario.telefono')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="beneficiario_ine_frente">Foto de Identificación Oficial (Frente)</label>
                        <input type="file" name="beneficiario[ine_frente]" id="beneficiario_ine_frente" class="form-control" accept=".jpg,.png">
                        <div class="helper-text">Formatos aceptados: JPG, PNG. Tamaño máximo: 5MB</div>
                        @error('beneficiario.ine_frente')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="beneficiario_ine_reverso">Foto de Identificación Oficial (Reverso)</label>
                        <input type="file" name="beneficiario[ine_reverso]" id="beneficiario_ine_reverso" class="form-control" accept=".jpg,.png">
                        <div class="helper-text">Formatos aceptados: JPG, PNG. Tamaño máximo: 5MB</div>
                        @error('beneficiario.ine_reverso')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Crédito (Opcional - solo en Crédito) -->
            <div class="card optional" id="card-credito" style="display: none;">
                <div class="card-header" data-target="card-credito-body">
                    <h3><i class="fas fa-credit-card"></i> Detalles del Crédito</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="card-body" id="card-credito-body">
                    <div class="form-group">
                        <label for="credito_fecha_inicio">Fecha de Inicio <span class="required">*</span></label>
                        <input type="date" name="credito[fecha_inicio]" id="credito_fecha_inicio" class="form-control" value="">
                        @error('credito.fecha_inicio')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="credito_plazo_financiamiento">Plazo de Financiamiento (meses) <span class="required">*</span></label>
                        <select name="credito[plazo_financiamiento]" id="credito_plazo_financiamiento" class="form-control">
                            <option value="">Seleccione un plazo</option>
                            <option value="12 meses">12 meses</option>
                            <option value="24 meses">24 meses</option>
                            <option value="36 meses">36 meses</option>
                            <option value="48 meses">48 meses</option>
                            <option value="otro">Otro</option>
                        </select>
                        @error('credito.plazo_financiamiento')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group" id="custom_plazo_group" style="display: none;">
                        <label for="credito_otro_plazo">Especificar Plazo (meses) <span class="required">*</span></label>
                        <input type="number" name="credito[otro_plazo]" id="credito_otro_plazo" class="form-control" value="" min="1" max="360">
                        @error('credito.otro_plazo')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="credito_modalidad_pago">Modalidad de Pago <span class="required">*</span></label>
                        <select name="credito[modalidad_pago]" id="credito_modalidad_pago" class="form-control">
                            <option value="">Seleccione una modalidad</option>
                            <option value="mensual">Mensual</option>
                            <option value="bimestral">Bimestral</option>
                            <option value="trimestral">Trimestral</option>
                            <option value="semestral">Semestral</option>
                            <option value="anual">Anual</option>
                        </select>
                        @error('credito.modalidad_pago')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="credito_formas_pago">Formas de Pago <span class="required">*</span></label>
                        <select name="credito[formas_pago]" id="credito_formas_pago" class="form-control">
                            <option value="">Seleccione una forma de pago</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="transferencia">Transferencia</option>
                            <option value="cheque">Cheque</option>
                            <option value="tarjeta credito/debito">Tarjeta de Crédito/Débito</option>
                            <option value="otro">Otro</option>
                        </select>
                        @error('credito.formas_pago')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="credito_dia_pago">Día de Pago <span class="required">*</span></label>
                        <input type="number" name="credito[dia_pago]" id="credito_dia_pago" class="form-control" value="" min="1" max="31">
                        @error('credito.dia_pago')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="pago">Monto a pagar <span class="required">*</span></label>
                        <input type="number" name="credito[monto_Pago]" id="credito_monto_Pago" class="form-control" value="" min="1">
                        @error('montoPago')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="credito_observaciones">Observaciones</label>
                        <textarea name="credito[observaciones]" id="credito_observaciones" class="form-control"></textarea>
                        @error('credito.observaciones')
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Crear Venta
                </button>
                <a href="{{ route('ventas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // === TOGGLE PAGO CONTADO / CRÉDITO ===
            const pagoContado = document.getElementById('pago-contado');
            const pagoCredito = document.getElementById('pago-credito');
            const tipoPagoHidden = document.getElementById('tipo_pago_hidden');
            const engancheInput = document.getElementById('enganche');
            const totalInput = document.getElementById('total');
            const cardCredito = document.getElementById('card-credito');
            const financialSummary = document.getElementById('financial-summary');
            const creditoFields = document.querySelectorAll('#card-credito input, #card-credito select, #card-credito textarea');

            function togglePago() {
                const isCredito = pagoCredito.checked;
                tipoPagoHidden.value = isCredito ? 'credito' : 'contado';
                cardCredito.style.display = isCredito ? 'block' : 'none';

                if (!isCredito) {
                    // CONTADO: enganche = total y readonly
                    engancheInput.value = totalInput.value || 0;
                    engancheInput.setAttribute('readonly', 'readonly');
                    creditoFields.forEach(f => {
                        f.disabled = true;
                        f.removeAttribute('required');
                    });
                } else {
                    // CRÉDITO: enganche editable
                    engancheInput.removeAttribute('readonly');
                    creditoFields.forEach(f => {
                        f.disabled = false;
                        if (f.hasAttribute('data-required')) {
                            f.setAttribute('required', 'required');
                        }
                    });
                }
                updateSummary();
                updateProgress();
            }

            function updateSummary() {
                const total = parseFloat(totalInput.value) || 0;
                const enganche = parseFloat(engancheInput.value) || 0;
                const saldo = total - enganche;

                if (total > 0 || enganche > 0) {
                    financialSummary.style.display = 'grid';
                    document.getElementById('enganche-display').textContent = formatCurrency(enganche);
                    document.getElementById('total-display').textContent = formatCurrency(total);
                    document.getElementById('saldo-display').textContent = formatCurrency(saldo);
                } else {
                    financialSummary.style.display = 'none';
                }
            }

            function formatCurrency(amount) {
                return new Intl.NumberFormat('es-MX', {
                    style: 'currency',
                    currency: 'MXN'
                }).format(amount);
            }

            // Marcar campos requeridos del crédito
            document.querySelectorAll('#card-credito [required]').forEach(f => f.setAttribute('data-required', 'true'));

            // Eventos de pago
            pagoContado.addEventListener('change', togglePago);
            pagoCredito.addEventListener('change', togglePago);
            totalInput.addEventListener('input', () => {
                if (pagoContado.checked) engancheInput.value = totalInput.value;
                updateSummary();
                updateProgress();
            });
            engancheInput.addEventListener('input', updateSummary);

            // === PLAZO FINANCIAMIENTO PERSONALIZADO ===
            const plazoSelect = document.getElementById('credito_plazo_financiamiento');
            const customPlazoGroup = document.getElementById('custom_plazo_group');
            const customPlazoInput = document.getElementById('credito_otro_plazo');

            plazoSelect.addEventListener('change', function() {
                if (this.value === 'otro') {
                    customPlazoGroup.style.display = 'block';
                    customPlazoInput.setAttribute('required', 'required');
                } else {
                    customPlazoGroup.style.display = 'none';
                    customPlazoInput.removeAttribute('required');
                    customPlazoInput.value = '';
                }
                updateProgress();
            });

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
                
                field.classList.remove('is-invalid', 'is-valid');
                
                if (isRequired && (value === '' || (type === 'file' && !field.files.length))) {
                    field.classList.add('is-invalid');
                    return false;
                }
                
                if (pattern && value !== '') {
                    const regex = new RegExp(pattern);
                    if (!regex.test(value)) {
                        field.classList.add('is-invalid');
                        return false;
                    }
                }
                
                if (type === 'email' && value !== '') {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(value)) {
                        field.classList.add('is-invalid');
                        return false;
                    }
                }
                
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
                
                if (value !== '' || (type === 'file' && field.files.length)) {
                    field.classList.add('is-valid');
                }
                
                return true;
            }

            // Handle apartado selection
            const apartadoSelect = document.getElementById('id_apartado');
            const tipoApartadoDisplay = document.getElementById('tipo_apartado_display');
            const fechaApartadoDisplay = document.getElementById('fecha_apartado_display');
            const fechaVencimientoDisplay = document.getElementById('fecha_vencimiento_display');
            const asesorDisplay = document.getElementById('asesor_display');
            const lotesDisplay = document.getElementById('lotes_display');
            const clienteNombres = document.getElementById('cliente_nombres');
            const clienteApellidos = document.getElementById('cliente_apellidos');

            apartadoSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.value) {
                    tipoApartadoDisplay.value = selectedOption.dataset.tipo;
                    fechaApartadoDisplay.value = selectedOption.dataset.fechaApartado;
                    fechaVencimientoDisplay.value = selectedOption.dataset.fechaVencimiento;
                    asesorDisplay.value = selectedOption.dataset.asesor;
                    clienteNombres.value = selectedOption.dataset.nombre;
                    clienteApellidos.value = selectedOption.dataset.apellidos;

                    // Display lotes
                    const lotes = JSON.parse(selectedOption.dataset.lotes);
                    lotesDisplay.innerHTML = '';
                    lotes.forEach(lote => {
                        const li = document.createElement('li');
                        li.classList.add('list-group-item');
                        li.textContent = `Lote ${lote}`;
                        lotesDisplay.appendChild(li);
                    });
                } else {
                    tipoApartadoDisplay.value = '';
                    fechaApartadoDisplay.value = '';
                    fechaVencimientoDisplay.value = '';
                    asesorDisplay.value = '';
                    clienteNombres.value = '';
                    clienteApellidos.value = '';
                    lotesDisplay.innerHTML = '';
                }
                updateProgress();
            });

            // Progress tracking
            function updateProgress() {
                const requiredFields = form.querySelectorAll('[required]:not([disabled])');
                let completedFields = 0;
                
                requiredFields.forEach(field => {
                    if (field.type === 'file') {
                        if (field.files.length > 0) {
                            completedFields++;
                        }
                    } else if (field.value.trim() !== '') {
                        completedFields++;
                    }
                });
                
                const progressPercentage = Math.round((completedFields / requiredFields.length) * 100);
                const progressBar = document.getElementById('progress-bar');
                const progressPercentageDisplay = document.getElementById('progress-percentage');
                
                progressBar.style.width = `${progressPercentage}%`;
                progressPercentageDisplay.textContent = `${progressPercentage}%`;
                
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
            
            inputs.forEach(input => {
                input.addEventListener('input', updateProgress);
                if (input.type === 'file') {
                    input.addEventListener('change', updateProgress);
                }
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
                    this.submit();
                } else {
                    const firstError = form.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstError.focus();
                    }
                }
            });
            
            togglePago();
            updateProgress();
        });
    </script>
</body>
@endsection