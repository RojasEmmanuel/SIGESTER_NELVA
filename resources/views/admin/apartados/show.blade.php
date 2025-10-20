
<!-- resources/views/admin/apartados/show.blade.php -->
@extends('admin.navbar')

@section('title', 'Nelva Bienes Raíces - Apartado')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/apartadosShow.css') }}">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@endpush

@section('content')
    <div class="apartado-details-container">
        <div class="apartado-max-width">
            <!-- Header con título y acciones -->
            <div class="apartado-header-section">
                <div class="apartado-header-content">
                    <h1 class="apartado-main-title">
                        Detalles del Apartado #{{ $apartado->id_apartado }}
                    </h1>
                    <p class="apartado-subtitle">Gestión y revisión del apartado</p>
                </div>
                <div class="apartado-header-actions">
                    <a href="{{ route('admin.apartados-pendientes.index') }}" class="apartado-btn apartado-btn-outlined">
                        <span class="material-icons apartado-btn-icon">arrow_back</span>
                        Volver a la lista
                    </a>
                </div>
            </div>

            <!-- Alertas -->
            @if (session('success'))
                <div class="apartado-alert apartado-alert-success">
                    <span class="material-icons apartado-alert-icon">check_circle</span>
                    <div class="apartado-alert-content">
                        <h4 class="apartado-alert-title">Éxito</h4>
                        <p class="apartado-alert-message">{{ session('success') }}</p>
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div class="apartado-alert apartado-alert-error">
                    <span class="material-icons apartado-alert-icon">error</span>
                    <div class="apartado-alert-content">
                        <h4 class="apartado-alert-title">Error</h4>
                        <p class="apartado-alert-message">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Grid principal -->
            <div class="apartado-dashboard-grid">
                <!-- Columna izquierda: Información y Comprobante -->
                <div class="apartado-left-column">
                    <!-- Información del apartado -->
                    <div class="apartado-card apartado-info-card">
                        <div class="apartado-card-header">
                            <span class="material-icons apartado-card-icon">info</span>
                            <h2 class="apartado-card-title">Información del Apartado</h2>
                        </div>
                        <div class="apartado-card-content">
                            <div class="apartado-info-grid">
                                <div class="apartado-info-item">
                                    <div class="apartado-info-label">
                                        <span class="material-icons apartado-info-icon">person</span>
                                        Cliente
                                    </div>
                                    <div class="apartado-info-value">{{ $apartado->cliente_nombre }} {{ $apartado->cliente_apellidos }}</div>
                                </div>
                                <div class="apartado-info-item">
                                    <div class="apartado-info-label">
                                        <span class="material-icons apartado-info-icon">apartment</span>
                                        Fraccionamiento
                                    </div>
                                    <div class="apartado-info-value">{{ $apartado->lotesApartados->first()->lote->fraccionamiento->nombre ?? 'N/A' }}</div>
                                </div>
                                <div class="apartado-info-item">
                                    <div class="apartado-info-label">
                                        <span class="material-icons apartado-info-icon">map</span>
                                        Lotes
                                    </div>
                                    <div class="apartado-info-value">{{ $apartado->lotesApartados->pluck('lote.numeroLote')->implode(', ') }}</div>
                                </div>
                                <div class="apartado-info-item">
                                    <div class="apartado-info-label">
                                        <span class="material-icons apartado-info-icon">payments</span>
                                        Monto del depósito
                                    </div>
                                    <div class="apartado-info-value apartado-amount">${{ number_format($apartado->deposito->cantidad, 2) }}</div>
                                </div>
                                <div class="apartado-info-item">
                                    <div class="apartado-info-label">
                                        <span class="material-icons apartado-info-icon">event_available</span>
                                        Fecha de Apartado
                                    </div>
                                    <div class="apartado-info-value">{{ \Carbon\Carbon::parse($apartado->fechaApartado)->format('d/m/Y H:i') }}</div>
                                </div>
                                <div class="apartado-info-item">
                                    <div class="apartado-info-label">
                                        <span class="material-icons apartado-info-icon">event_busy</span>
                                        Fecha de Vencimiento
                                    </div>
                                    <div class="apartado-info-value">{{ \Carbon\Carbon::parse($apartado->fechaVencimiento)->format('d/m/Y H:i') }}</div>
                                </div>
                                <div class="apartado-info-item">
                                    <div class="apartado-info-label">
                                        <span class="material-icons apartado-info-icon">assignment</span>
                                        Estatus del Apartado
                                    </div>
                                    <div class="apartado-info-value">
                                        <span class="apartado-status-badge apartado-status-{{ str_replace(' ', '-', $apartado->estatus) }}">
                                            {{ $apartado->estatus }}
                                        </span>
                                    </div>
                                </div>
                                <div class="apartado-info-item">
                                    <div class="apartado-info-label">
                                        <span class="material-icons apartado-info-icon">receipt</span>
                                        Estatus del Ticket
                                    </div>
                                    <div class="apartado-info-value">
                                        <span class="apartado-status-badge apartado-status-{{ $apartado->deposito->ticket_estatus }}">
                                            {{ $apartado->deposito->ticket_estatus }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Comprobante -->
                    <div class="apartado-card apartado-comprobante-card">
                        <div class="apartado-card-header">
                            <span class="material-icons apartado-card-icon">receipt_long</span>
                            <h2 class="apartado-card-title">Comprobante de Depósito</h2>
                        </div>
                        <div class="apartado-card-content">
                            <div class="apartado-comprobante-preview">
                                @if ($apartado->deposito->path_ticket)
                                    <img src="{{ Storage::url($apartado->deposito->path_ticket) }}" 
                                         alt="Comprobante de depósito" 
                                         class="apartado-comprobante-image"
                                         id="apartadoComprobanteImage">
                                    <div class="apartado-comprobante-actions">
                                        <a href="{{ Storage::url($apartado->deposito->path_ticket) }}" 
                                           target="_blank" 
                                           class="apartado-btn apartado-btn-contained apartado-btn-success">
                                            <span class="material-icons apartado-btn-icon">download</span>
                                            Descargar
                                        </a>
                                        <button type="button" class="apartado-btn apartado-btn-contained" onclick="apartadoOpenModal()">
                                            <span class="material-icons apartado-btn-icon">zoom_in</span>
                                            Ampliar
                                        </button>
                                    </div>
                                @else
                                    <div class="apartado-comprobante-placeholder">
                                        <span class="material-icons apartado-comprobante-icon">receipt</span>
                                        <p class="apartado-comprobante-text">No hay comprobante disponible</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna derecha: Formulario de actualización -->
                <div class="apartado-right-column">
                    <div class="apartado-card apartado-form-card">
                        <div class="apartado-card-header">
                            <span class="material-icons apartado-card-icon">update</span>
                            <h2 class="apartado-card-title">Actualizar Estatus</h2>
                        </div>
                        <div class="apartado-card-content">
                            <form action="{{ route('admin.apartados-pendientes.updateTicketStatus', $apartado->id_apartado) }}" method="POST" class="apartado-form">
                                @csrf
                                @method('PUT')
                                
                                <div class="apartado-form-group">
                                    <label for="apartadoTicketEstatus" class="apartado-form-label">
                                        <span class="material-icons apartado-form-icon">flag</span>
                                        Estatus del Ticket
                                    </label>
                                    <div class="apartado-select-container">
                                        <select name="ticket_estatus" id="apartadoTicketEstatus" class="apartado-form-select">
                                            <option value="solicitud" {{ $apartado->deposito->ticket_estatus === 'solicitud' ? 'selected' : '' }}>Solicitud</option>
                                            <option value="aceptado" {{ $apartado->deposito->ticket_estatus === 'aceptado' ? 'selected' : '' }}>Aceptado</option>
                                            <option value="rechazado" {{ $apartado->deposito->ticket_estatus === 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                                        </select>
                                        <span class="material-icons apartado-select-arrow">arrow_drop_down</span>
                                    </div>
                                    @error('ticket_estatus')
                                        <span class="apartado-form-error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="apartado-conditional-group" id="apartadoFechaVencimientoGroup">
                                    <div class="apartado-form-group">
                                        <label for="apartadoFechaVencimiento" class="apartado-form-label">
                                            <span class="material-icons apartado-form-icon">event</span>
                                            Fecha de Vencimiento
                                        </label>
                                        <input type="datetime-local" 
                                               name="fechaVencimiento" 
                                               id="apartadoFechaVencimiento" 
                                               class="apartado-form-input"
                                               value="{{ \Carbon\Carbon::parse($apartado->fechaVencimiento)->format('Y-m-d\TH:i') }}"
                                               min="{{ \Carbon\Carbon::today()->format('Y-m-d\TH:i') }}">
                                        @error('fechaVencimiento')
                                            <span class="apartado-form-error">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="apartado-conditional-group" id="apartadoEstatusGroup">
                                    <div class="apartado-form-group">
                                        <label for="apartadoEstatus" class="apartado-form-label">
                                            <span class="material-icons apartado-form-icon">assignment</span>
                                            Estatus del Apartado
                                        </label>
                                        <div class="apartado-select-container">
                                            <select name="estatus" id="apartadoEstatus" class="apartado-form-select">
                                                <option value="en curso" {{ $apartado->estatus === 'en curso' ? 'selected' : '' }}>En curso</option>
                                                <option value="venta" {{ $apartado->estatus === 'venta' ? 'selected' : '' }}>Venta</option>
                                                <option value="vencido" {{ $apartado->estatus === 'vencido' ? 'selected' : '' }}>Vencido</option>
                                            </select>
                                            <span class="material-icons apartado-select-arrow">arrow_drop_down</span>
                                        </div>
                                        @error('estatus')
                                            <span class="apartado-form-error">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="apartado-form-group">
                                    <label for="apartadoObservaciones" class="apartado-form-label">
                                        <span class="material-icons apartado-form-icon">notes</span>
                                        Observaciones
                                    </label>
                                    <textarea name="observaciones" 
                                              id="apartadoObservaciones" 
                                              rows="4" 
                                              class="apartado-form-textarea"
                                              placeholder="Agregue observaciones relevantes sobre el apartado...">{{ $apartado->deposito->observaciones }}</textarea>
                                    @error('observaciones')
                                        <span class="apartado-form-error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="apartado-form-actions">
                                    <button type="submit" class="apartado-btn apartado-btn-contained apartado-btn-primary">
                                        <span class="material-icons apartado-btn-icon">save</span>
                                        Actualizar Estatus
                                    </button>
                                    <a href="{{ route('admin.apartados-pendientes.index') }}" class="apartado-btn apartado-btn-outlined">
                                        <span class="material-icons apartado-btn-icon">cancel</span>
                                        Cancelar
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para imagen ampliada -->
    <div class="apartado-image-modal" id="apartadoImageModal">
        <div class="apartado-modal-content">
            <button class="apartado-modal-close" onclick="apartadoCloseModal()">
                <span class="material-icons">close</span>
            </button>
            @if ($apartado->deposito->path_ticket)
                <img src="{{ Storage::url($apartado->deposito->path_ticket) }}" 
                     alt="Comprobante de depósito - Vista ampliada" 
                     class="apartado-modal-image">
            @endif
        </div>
    </div>

    <script>
        // Control de visibilidad de grupos condicionales
        document.getElementById('apartadoTicketEstatus').addEventListener('change', function () {
            const fechaVencimientoGroup = document.getElementById('apartadoFechaVencimientoGroup');
            const estatusGroup = document.getElementById('apartadoEstatusGroup');
            
            if (this.value === 'aceptado') {
                fechaVencimientoGroup.classList.add('apartado-active');
                estatusGroup.classList.add('apartado-active');
            } else {
                fechaVencimientoGroup.classList.remove('apartado-active');
                estatusGroup.classList.remove('apartado-active');
            }
        });

        // Inicializar visibilidad al cargar la página
        window.addEventListener('DOMContentLoaded', function () {
            const ticketEstatus = document.getElementById('apartadoTicketEstatus').value;
            const fechaVencimientoGroup = document.getElementById('apartadoFechaVencimientoGroup');
            const estatusGroup = document.getElementById('apartadoEstatusGroup');
            
            if (ticketEstatus === 'aceptado') {
                fechaVencimientoGroup.classList.add('apartado-active');
                estatusGroup.classList.add('apartado-active');
            }

            // Hacer la imagen clickeable para abrir modal
            const comprobanteImage = document.getElementById('apartadoComprobanteImage');
            if (comprobanteImage) {
                comprobanteImage.addEventListener('click', apartadoOpenModal);
            }
        });

        // Funciones del modal
        function apartadoOpenModal() {
            document.getElementById('apartadoImageModal').classList.add('apartado-active');
            document.body.style.overflow = 'hidden';
        }

        function apartadoCloseModal() {
            document.getElementById('apartadoImageModal').classList.remove('apartado-active');
            document.body.style.overflow = 'auto';
        }

        // Cerrar modal con ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                apartadoCloseModal();
            }
        });

        // Cerrar modal haciendo click fuera de la imagen
        document.getElementById('apartadoImageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                apartadoCloseModal();
            }
        });
    </script>
@endsection