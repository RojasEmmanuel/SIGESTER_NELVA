@extends('asesor.navbar')

@section('title', 'Nelva Bienes Raíces - Crear Venta')

@push('styles')
<link href="{{ asset('css/ventaForm.css') }}" rel="stylesheet">
@endpush

@section('content')
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

        <form action="{{ route('ventas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Información de la Venta -->
            <div class="card">
                <div class="card-header">
                    <h3>Información de la Venta</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="fechaSolicitud">Fecha de Solicitud</label>
                        <input type="date" name="fechaSolicitud" id="fechaSolicitud" class="form-control @error('fechaSolicitud') is-invalid @enderror" value="{{ old('fechaSolicitud') }}" required>
                        @error('fechaSolicitud')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="estatus">Estatus</label>
                        <select name="estatus" id="estatus" class="form-control @error('estatus') is-invalid @enderror" required>
                            <option value="solicitud" {{ old('estatus') == 'solicitud' ? 'selected' : '' }}>Solicitud</option>
                            <option value="pagos" {{ old('estatus') == 'pagos' ? 'selected' : '' }}>Pagos</option>
                            <option value="retraso" {{ old('estatus') == 'retraso' ? 'selected' : '' }}>Retraso</option>
                            <option value="liquidado" {{ old('estatus') == 'liquidado' ? 'selected' : '' }}>Liquidado</option>
                            <option value="cancelado" {{ old('estatus') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                        @error('estatus')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="ticket_path">Ticket de Pago (PDF, JPG, PNG)</label>
                        <input type="file" name="ticket_path" id="ticket_path" class="form-control @error('ticket_path') is-invalid @enderror" accept=".pdf,.jpg,.png">
                        @error('ticket_path')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="enganche">Enganche</label>
                        <input type="number" name="enganche" id="enganche" class="form-control @error('enganche') is-invalid @enderror" value="{{ old('enganche') }}" step="0.01" required>
                        @error('enganche')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="total">Precio Total</label>
                        <input type="number" name="total" id="total" class="form-control @error('total') is-invalid @enderror" value="{{ old('total') }}" step="0.01" required>
                        @error('total')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Información del Apartado -->
            <div class="card">
                <div class="card-header">
                    <h3>Información del Apartado</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="tipoApartado">Tipo de Apartado</label>
                        <input type="text" name="tipoApartado" id="tipoApartado" class="form-control @error('tipoApartado') is-invalid @enderror" value="{{ old('tipoApartado') }}" required>
                        @error('tipoApartado')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="cliente_nombre">Nombre del Cliente (Apartado)</label>
                        <input type="text" name="cliente_nombre" id="cliente_nombre" class="form-control @error('cliente_nombre') is-invalid @enderror" value="{{ old('cliente_nombre') }}" required>
                        @error('cliente_nombre')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="cliente_apellidos">Apellidos del Cliente (Apartado)</label>
                        <input type="text" name="cliente_apellidos" id="cliente_apellidos" class="form-control @error('cliente_apellidos') is-invalid @enderror" value="{{ old('cliente_apellidos') }}" required>
                        @error('cliente_apellidos')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="fechaApartado">Fecha de Apartado</label>
                        <input type="date" name="fechaApartado" id="fechaApartado" class="form-control @error('fechaApartado') is-invalid @enderror" value="{{ old('fechaApartado') }}" required>
                        @error('fechaApartado')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="fechaVencimiento">Fecha de Vencimiento</label>
                        <input type="date" name="fechaVencimiento" id="fechaVencimiento" class="form-control @error('fechaVencimiento') is-invalid @enderror" value="{{ old('fechaVencimiento') }}" required>
                        @error('fechaVencimiento')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="id_usuario">Asesor</label>
                        <select name="id_usuario" id="id_usuario" class="form-control @error('id_usuario') is-invalid @enderror" required>
                            <option value="">Seleccione un asesor</option>
                            @foreach ($asesores as $asesor)
                                <option value="{{ $asesor->id_usuario }}" {{ old('id_usuario') == $asesor->id_usuario ? 'selected' : '' }}>{{ $asesor->nombre }}</option>
                            @endforeach
                        </select>
                        @error('id_usuario')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="lotes">Lotes</label>
                        <select name="lotes[]" id="lotes" class="form-control @error('lotes') is-invalid @enderror" multiple required>
                            @foreach ($lotes as $lote)
                                <option value="{{ $lote->id_lote }}" {{ in_array($lote->id_lote, old('lotes', [])) ? 'selected' : '' }}>{{ $lote->id_lote }}</option>
                            @endforeach
                        </select>
                        @error('lotes')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Información del Cliente -->
            <div class="card">
                <div class="card-header">
                    <h3>Información del Cliente</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="cliente_nombres">Nombres</label>
                        <input type="text" name="cliente[nombres]" id="cliente_nombres" class="form-control @error('cliente.nombres') is-invalid @enderror" value="{{ old('cliente.nombres') }}" required>
                        @error('cliente.nombres')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="cliente_apellidos">Apellidos</label>
                        <input type="text" name="cliente[apellidos]" id="cliente_apellidos" class="form-control @error('cliente.apellidos') is-invalid @enderror" value="{{ old('cliente.apellidos') }}" required>
                        @error('cliente.apellidos')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="cliente_edad">Edad</label>
                        <input type="number" name="cliente[edad]" id="cliente_edad" class="form-control @error('cliente.edad') is-invalid @enderror" value="{{ old('cliente.edad') }}" required>
                        @error('cliente.edad')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="cliente_estado_civil">Estado Civil</label>
                        <input type="text" name="cliente[estado_civil]" id="cliente_estado_civil" class="form-control @error('cliente.estado_civil') is-invalid @enderror" value="{{ old('cliente.estado_civil') }}" required>
                        @error('cliente.estado_civil')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="cliente_lugar_origen">Lugar de Origen</label>
                        <input type="text" name="cliente[lugar_origen]" id="cliente_lugar_origen" class="form-control @error('cliente.lugar_origen') is-invalid @enderror" value="{{ old('cliente.lugar_origen') }}" required>
                        @error('cliente.lugar_origen')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="cliente_ocupacion">Ocupación</label>
                        <input type="text" name="cliente[ocupacion]" id="cliente_ocupacion" class="form-control @error('cliente.ocupacion') is-invalid @enderror" value="{{ old('cliente.ocupacion') }}" required>
                        @error('cliente.ocupacion')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="cliente_clave_elector">Clave de Elector</label>
                        <input type="text" name="cliente[clave_elector]" id="cliente_clave_elector" class="form-control @error('cliente.clave_elector') is-invalid @enderror" value="{{ old('cliente.clave_elector') }}">
                        @error('cliente.clave_elector')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Contacto del Cliente -->
            <div class="card">
                <div class="card-header">
                    <h3>Contacto del Cliente</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="contacto_telefono">Teléfono</label>
                        <input type="text" name="contacto[telefono]" id="contacto_telefono" class="form-control @error('contacto.telefono') is-invalid @enderror" value="{{ old('contacto.telefono') }}" required>
                        @error('contacto.telefono')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="contacto_email">Email</label>
                        <input type="email" name="contacto[email]" id="contacto_email" class="form-control @error('contacto.email') is-invalid @enderror" value="{{ old('contacto.email') }}" required>
                        @error('contacto.email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Dirección del Cliente -->
            <div class="card">
                <div class="card-header">
                    <h3>Dirección del Cliente</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="direccion_nacionalidad">Nacionalidad</label>
                        <input type="text" name="direccion[nacionalidad]" id="direccion_nacionalidad" class="form-control @error('direccion.nacionalidad') is-invalid @enderror" value="{{ old('direccion.nacionalidad') }}" required>
                        @error('direccion.nacionalidad')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="direccion_estado">Estado</label>
                        <input type="text" name="direccion[estado]" id="direccion_estado" class="form-control @error('direccion.estado') is-invalid @enderror" value="{{ old('direccion.estado') }}" required>
                        @error('direccion.estado')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="direccion_municipio">Municipio</label>
                        <input type="text" name="direccion[municipio]" id="direccion_municipio" class="form-control @error('direccion.municipio') is-invalid @enderror" value="{{ old('direccion.municipio') }}" required>
                        @error('direccion.municipio')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="direccion_localidad">Localidad</label>
                        <input type="text" name="direccion[localidad]" id="direccion_localidad" class="form-control @error('direccion.localidad') is-invalid @enderror" value="{{ old('direccion.localidad') }}" required>
                        @error('direccion.localidad')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Beneficiario (Opcional) -->
            <div class="card">
                <div class="card-header">
                    <h3>Beneficiario (Opcional)</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="beneficiario_nombres">Nombres</label>
                        <input type="text" name="beneficiario[nombres]" id="beneficiario_nombres" class="form-control @error('beneficiario.nombres') is-invalid @enderror" value="{{ old('beneficiario.nombres') }}">
                        @error('beneficiario.nombres')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="beneficiario_apellidos">Apellidos</label>
                        <input type="text" name="beneficiario[apellidos]" id="beneficiario_apellidos" class="form-control @error('beneficiario.apellidos') is-invalid @enderror" value="{{ old('beneficiario.apellidos') }}">
                        @error('beneficiario.apellidos')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="beneficiario_telefono">Teléfono</label>
                        <input type="text" name="beneficiario[telefono]" id="beneficiario_telefono" class="form-control @error('beneficiario.telefono') is-invalid @enderror" value="{{ old('beneficiario.telefono') }}">
                        @error('beneficiario.telefono')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Crédito (Opcional) -->
            <div class="card">
                <div class="card-header">
                    <h3>Crédito (Opcional)</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="credito_fecha_inicio">Fecha de Inicio</label>
                        <input type="date" name="credito[fecha_inicio]" id="credito_fecha_inicio" class="form-control @error('credito.fecha_inicio') is-invalid @enderror" value="{{ old('credito.fecha_inicio') }}">
                        @error('credito.fecha_inicio')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="credito_plazo_financiamiento">Plazo de Financiamiento (meses)</label>
                        <input type="number" name="credito[plazo_financiamiento]" id="credito_plazo_financiamiento" class="form-control @error('credito.plazo_financiamiento') is-invalid @enderror" value="{{ old('credito.plazo_financiamiento') }}">
                        @error('credito.plazo_financiamiento')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="credito_modalidad_pago">Modalidad de Pago</label>
                        <input type="text" name="credito[modalidad_pago]" id="credito_modalidad_pago" class="form-control @error('credito.modalidad_pago') is-invalid @enderror" value="{{ old('credito.modalidad_pago') }}">
                        @error('credito.modalidad_pago')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="credito_formas_pago">Formas de Pago</label>
                        <input type="text" name="credito[formas_pago]" id="credito_formas_pago" class="form-control @error('credito.formas_pago') is-invalid @enderror" value="{{ old('credito.formas_pago') }}">
                        @error('credito.formas_pago')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="credito_dia_pago">Día de Pago</label>
                        <input type="number" name="credito[dia_pago]" id="credito_dia_pago" class="form-control @error('credito.dia_pago') is-invalid @enderror" value="{{ old('credito.dia_pago') }}" min="1" max="31">
                        @error('credito.dia_pago')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="credito_observaciones">Observaciones</label>
                        <textarea name="credito[observaciones]" id="credito_observaciones" class="form-control @error('credito.observaciones') is-invalid @enderror">{{ old('credito.observaciones') }}</textarea>
                        @error('credito.observaciones')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Crear Venta</button>
                <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection