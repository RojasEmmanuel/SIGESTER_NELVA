<!-- resources/views/admin/apartados/show.blade.php -->
@extends('admin.navbar')

@section('title', 'Nelva Bienes Raíces - Apartado')

@push('styles')
<style>
    :root {
        --primary-color: #1e478a;
        --primary-light: #6366f1;
        --secondary-color: #3d86df;
        --accent-color: #e1f3fd;
        --text-color: #334155;
        --text-light: #64748b;
        --light-gray: #f8fafc;
        --medium-gray: #e2e8f0;
        --dark-gray: #94a3b8;
        --success-color: #10b981;
        --success-light: #a7f3d0;
        --warning-color: #f59e0b;
        --warning-light: #fde68a;
        --white: #ffffff;
        --dark-bg: #1e293b;
        --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
        --rounded: 12px;
        --rounded-sm: 8px;
        --blue-accent: #3b82f6;
        --transition: all 0.2s ease;
    }

    /* Layout mejorado */
    .dashboard-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 1024px) {
        .dashboard-container {
            grid-template-columns: 1fr;
        }
    }

    .info-card, .comprobante-card, .form-card {
        background: var(--white);
        border-radius: var(--rounded);
        box-shadow: var(--shadow-md);
        padding: 1.5rem;
        border: 1px solid var(--medium-gray);
    }

    .comprobante-card {
        display: flex;
        flex-direction: column;
    }

    /* Encabezados */
    .section-title {
        color: var(--primary-color);
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--medium-gray);
        position: relative;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 60px;
        height: 2px;
        background-color: var(--primary-color);
    }

    /* Información del apartado */
    .info-grid {
        display: grid;
        gap: 1rem;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: var(--light-gray);
        border-radius: var(--rounded-sm);
        border-left: 4px solid var(--primary-color);
        transition: var(--transition);
    }

    .info-item:hover {
        background: var(--accent-color);
        transform: translateX(4px);
    }

    .info-label {
        font-weight: 600;
        color: var(--primary-color);
        min-width: 180px;
    }

    .info-value {
        color: var(--text-color);
        text-align: right;
        flex: 1;
    }

    /* Comprobante visualización */
    .comprobante-preview {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: var(--light-gray);
        border-radius: var(--rounded-sm);
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 2px dashed var(--medium-gray);
        min-height: 300px;
    }

    .comprobante-image {
        max-width: 100%;
        max-height: 400px;
        border-radius: var(--rounded-sm);
        box-shadow: var(--shadow-lg);
        transition: var(--transition);
        cursor: zoom-in;
    }

    .comprobante-image:hover {
        transform: scale(1.02);
        box-shadow: var(--shadow-lg);
    }

    .comprobante-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }

    .comprobante-placeholder {
        text-align: center;
        color: var(--text-light);
        padding: 2rem;
    }

    .comprobante-placeholder i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--dark-gray);
    }

    /* Modal para imagen ampliada */
    .image-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }

    .image-modal.active {
        display: flex;
        animation: fadeIn 0.3s ease;
    }

    .modal-content {
        max-width: 90%;
        max-height: 90%;
        position: relative;
    }

    .modal-image {
        max-width: 100%;
        max-height: 100%;
        border-radius: var(--rounded-sm);
    }

    .modal-close {
        position: absolute;
        top: -40px;
        right: 0;
        background: var(--white);
        border: none;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1.25rem;
        color: var(--text-color);
        transition: var(--transition);
    }

    .modal-close:hover {
        background: var(--primary-color);
        color: var(--white);
    }

    /* Formulario */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 0.5rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid var(--medium-gray);
        border-radius: var(--rounded-sm);
        background: var(--white);
        transition: var(--transition);
        font-size: 0.875rem;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(30, 71, 138, 0.1);
        background: var(--accent-color);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    /* Grupos condicionales */
    .conditional-group {
        background: var(--accent-color);
        padding: 1.5rem;
        border-radius: var(--rounded-sm);
        border-left: 4px solid var(--secondary-color);
        margin-bottom: 1.5rem;
        display: none;
        animation: slideDown 0.3s ease;
    }

    .conditional-group.active {
        display: block;
    }

    /* Botones */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: var(--rounded-sm);
        text-decoration: none;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        font-size: 0.875rem;
        margin-right: 0.75rem;
        gap: 0.5rem;
    }

    .btn-primary {
        background: var(--primary-color);
        color: var(--white);
    }

    .btn-primary:hover {
        background: var(--secondary-color);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-secondary {
        background: var(--medium-gray);
        color: var(--text-color);
    }

    .btn-secondary:hover {
        background: var(--dark-gray);
        color: var(--white);
        transform: translateY(-2px);
    }

    .btn-success {
        background: var(--success-color);
        color: var(--white);
    }

    .btn-success:hover {
        background: #0da271;
        transform: translateY(-2px);
    }

    /* Alertas */
    .alert {
        padding: 1rem 1.25rem;
        border-radius: var(--rounded-sm);
        margin-bottom: 1.5rem;
        border: 1px solid transparent;
        font-weight: 500;
    }

    .alert-success {
        color: #065f46;
        background: var(--success-light);
        border-color: var(--success-color);
        border-left: 4px solid var(--success-color);
    }

    .alert-danger {
        color: #7f1d1d;
        background: #fecaca;
        border-color: #f87171;
        border-left: 4px solid #dc2626;
    }

    /* Badges de estado */
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-solicitud {
        background: var(--warning-light);
        color: var(--warning-color);
    }

    .status-aceptado {
        background: var(--success-light);
        color: var(--success-color);
    }

    .status-rechazado {
        background: #fecaca;
        color: #dc2626;
    }

    /* Animaciones */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideDown {
        from { 
            opacity: 0;
            transform: translateY(-10px);
        }
        to { 
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .info-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .info-value {
            text-align: left;
        }

        .comprobante-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            margin-right: 0;
            margin-bottom: 0.5rem;
        }

        .modal-content {
            max-width: 95%;
            max-height: 95%;
        }
    }
</style>
@endpush

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header con título y acciones -->
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                        Detalles del Apartado #{{ $apartado->id_apartado }}
                    </h2>
                    <p class="text-gray-600 mt-1">Gestión y revisión del apartado</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.apartados-pendientes.index') }}" class="btn btn-secondary">
                        ← Volver a la lista
                    </a>
                </div>
            </div>

            <!-- Alertas -->
            @if (session('success'))
                <div class="alert alert-success">
                    ✅ {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    ❌ {{ session('error') }}
                </div>
            @endif

            <!-- Grid principal -->
            <div class="dashboard-container">
                <!-- Columna izquierda: Información y Comprobante -->
                <div class="space-y-6">
                    <!-- Información del apartado -->
                    <div class="info-card">
                        <h3 class="section-title">Información del Apartado</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Cliente:</span>
                                <span class="info-value">{{ $apartado->cliente_nombre }} {{ $apartado->cliente_apellidos }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Fraccionamiento:</span>
                                <span class="info-value">{{ $apartado->lotesApartados->first()->lote->fraccionamiento->nombre ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Lotes:</span>
                                <span class="info-value">{{ $apartado->lotesApartados->pluck('lote.numeroLote')->implode(', ') }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Monto del depósito:</span>
                                <span class="info-value">${{ number_format($apartado->deposito->cantidad, 2) }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Fecha de Apartado:</span>
                                <span class="info-value">{{ \Carbon\Carbon::parse($apartado->fechaApartado)->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Fecha de Vencimiento:</span>
                                <span class="info-value">{{ \Carbon\Carbon::parse($apartado->fechaVencimiento)->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Estatus del Apartado:</span>
                                <span class="info-value">
                                    <span class="status-badge status-{{ str_replace(' ', '-', $apartado->estatus) }}">
                                        {{ $apartado->estatus }}
                                    </span>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Estatus del Ticket:</span>
                                <span class="info-value">
                                    <span class="status-badge status-{{ $apartado->deposito->ticket_estatus }}">
                                        {{ $apartado->deposito->ticket_estatus }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Comprobante -->
                    <div class="comprobante-card">
                        <h3 class="section-title">Comprobante de Depósito</h3>
                        <div class="comprobante-preview">
                            @if ($apartado->deposito->path_ticket)
                                <img src="{{ Storage::url($apartado->deposito->path_ticket) }}" 
                                     alt="Comprobante de depósito" 
                                     class="comprobante-image"
                                     id="comprobanteImage">
                                <div class="comprobante-actions">
                                    <a href="{{ Storage::url($apartado->deposito->path_ticket) }}" 
                                       target="_blank" 
                                       class="btn btn-success">
                                        📥 Descargar
                                    </a>
                                    <button type="button" class="btn btn-primary" onclick="openModal()">
                                        🔍 Ampliar
                                    </button>
                                </div>
                            @else
                                <div class="comprobante-placeholder">
                                    <div>📄</div>
                                    <p>No hay comprobante disponible</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Columna derecha: Formulario de actualización -->
                <div class="form-card">
                    <h3 class="section-title">Actualizar Estatus</h3>
                    <form action="{{ route('admin.apartados-pendientes.updateTicketStatus', $apartado->id_apartado) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="ticket_estatus" class="form-label">Estatus del Ticket</label>
                            <select name="ticket_estatus" id="ticket_estatus" class="form-control">
                                <option value="solicitud" {{ $apartado->deposito->ticket_estatus === 'solicitud' ? 'selected' : '' }}>Solicitud</option>
                                <option value="aceptado" {{ $apartado->deposito->ticket_estatus === 'aceptado' ? 'selected' : '' }}>Aceptado</option>
                                <option value="rechazado" {{ $apartado->deposito->ticket_estatus === 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                            </select>
                            @error('ticket_estatus')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="conditional-group" id="fechaVencimientoGroup">
                            <div class="form-group">
                                <label for="fechaVencimiento" class="form-label">Fecha de Vencimiento</label>
                                <input type="datetime-local" 
                                       name="fechaVencimiento" 
                                       id="fechaVencimiento" 
                                       class="form-control"
                                       value="{{ \Carbon\Carbon::parse($apartado->fechaVencimiento)->format('Y-m-d\TH:i') }}"
                                       min="{{ \Carbon\Carbon::today()->format('Y-m-d\TH:i') }}">
                                @error('fechaVencimiento')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="conditional-group" id="estatusGroup">
                            <div class="form-group">
                                <label for="estatus" class="form-label">Estatus del Apartado</label>
                                <select name="estatus" id="estatus" class="form-control">
                                    <option value="en curso" {{ $apartado->estatus === 'en curso' ? 'selected' : '' }}>En curso</option>
                                    <option value="venta" {{ $apartado->estatus === 'venta' ? 'selected' : '' }}>Venta</option>
                                    <option value="vencido" {{ $apartado->estatus === 'vencido' ? 'selected' : '' }}>Vencido</option>
                                </select>
                                @error('estatus')
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea name="observaciones" 
                                      id="observaciones" 
                                      rows="4" 
                                      class="form-control"
                                      placeholder="Agregue observaciones relevantes sobre el apartado...">{{ $apartado->deposito->observaciones }}</textarea>
                            @error('observaciones')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex gap-2 mt-6">
                            <button type="submit" class="btn btn-primary">
                                💾 Actualizar Estatus
                            </button>
                            <a href="{{ route('admin.apartados-pendientes.index') }}" class="btn btn-secondary">
                                ↩️ Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para imagen ampliada -->
    <div class="image-modal" id="imageModal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal()">×</button>
            @if ($apartado->deposito->path_ticket)
                <img src="{{ Storage::url($apartado->deposito->path_ticket) }}" 
                     alt="Comprobante de depósito - Vista ampliada" 
                     class="modal-image">
            @endif
        </div>
    </div>

    <script>
        // Control de visibilidad de grupos condicionales
        document.getElementById('ticket_estatus').addEventListener('change', function () {
            const fechaVencimientoGroup = document.getElementById('fechaVencimientoGroup');
            const estatusGroup = document.getElementById('estatusGroup');
            
            if (this.value === 'aceptado') {
                fechaVencimientoGroup.classList.add('active');
                estatusGroup.classList.add('active');
            } else {
                fechaVencimientoGroup.classList.remove('active');
                estatusGroup.classList.remove('active');
            }
        });

        // Inicializar visibilidad al cargar la página
        window.addEventListener('DOMContentLoaded', function () {
            const ticketEstatus = document.getElementById('ticket_estatus').value;
            const fechaVencimientoGroup = document.getElementById('fechaVencimientoGroup');
            const estatusGroup = document.getElementById('estatusGroup');
            
            if (ticketEstatus === 'aceptado') {
                fechaVencimientoGroup.classList.add('active');
                estatusGroup.classList.add('active');
            }

            // Hacer la imagen clickeable para abrir modal
            const comprobanteImage = document.getElementById('comprobanteImage');
            if (comprobanteImage) {
                comprobanteImage.addEventListener('click', openModal);
            }
        });

        // Funciones del modal
        function openModal() {
            document.getElementById('imageModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('imageModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Cerrar modal con ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });

        // Cerrar modal haciendo click fuera de la imagen
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
@endsection