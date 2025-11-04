@extends('admin.navbar')

@section('title', 'Nelva Bienes Raíces - Admin Fraccionamiento')

@push('styles')
<link href="{{ asset('css/fraccionamientoAdmin.css') }}" rel="stylesheet">
@endpush

@section('content')
    <!-- Main Content -->
    <div class="container">
        <!-- Header Section -->
        <div class="page-header">
            <div class="header-content">
                <div class="header-title">
                    <i class="fas fa-map-marked-alt"></i>
                    <h1>Administrar Fraccionamiento</h1>
                    <span class="development-status {{ $datosFraccionamiento['estatus'] ? 'active' : 'inactive' }}">
                        {{ $datosFraccionamiento['estatus'] ? 'Activo' : 'Inactivo' }}
                    </span>
                    <span class="development-status badge badge-info ml-2">
                        Zona: {{ ucfirst($datosFraccionamiento['zona']) }}
                    </span>
                </div>
                <p class="header-subtitle">{{ $datosFraccionamiento['ubicacion'] }} ({{ ucfirst($datosFraccionamiento['zona']) }})</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-outline" onclick="window.location.href='/asesor/fraccionamiento/{{ $datosFraccionamiento['id'] }}'">
                    <i class="fas fa-arrow-left"></i> Volver al Panel
                </button>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="navigation-tabs">
            <div class="tabs-container">
                <button class="tab-btn active" data-tab="basic-info">
                    <i class="fas fa-info-circle"></i>
                    <span>Información Básica</span>
                </button>
                <button class="tab-btn" data-tab="amenities">
                    <i class="fas fa-bolt"></i>
                    <span>Amenidades</span>
                    <span class="badge">{{ $amenidades->count() }}</span>
                </button>
                <button class="tab-btn" data-tab="gallery">
                    <i class="fas fa-images"></i>
                    <span>Galería</span>
                    <span class="badge">{{ $galeria->count() }}</span>
                </button>
                <button class="tab-btn" data-tab="promociones">
                    <i class="fas fa-gift"></i>
                    <span>Promociones</span>
                    <span class="badge">{{ $promociones->count() }}</span>
                </button>
                <button class="tab-btn" data-tab="files">
                    <i class="fas fa-file-download"></i>
                    <span>Archivos</span>
                    <span class="badge">{{ $archivos->count() }}</span>
                </button>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Basic Information Tab -->
            <div id="basic-info" class="tab-pane active">
                <div class="content-grid">
                    <!-- Development Info Section -->
                    <div class="form-section">
                        <div class="section-header">
                            <h3 class="section-title">
                                <i class="fas fa-home"></i>
                                Información del Fraccionamiento
                            </h3>
                            <div class="section-indicator">
                                <span class="indicator-dot"></span>
                                Información básica
                            </div>
                        </div>
                        
                        <form action="{{ route('admin.fraccionamiento.update', $datosFraccionamiento['id']) }}" method="POST" enctype="multipart/form-data" class="form-grid">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group">
                                <label for="nombre" class="form-label">Nombre del Fraccionamiento</label>
                                <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre', $datosFraccionamiento['nombre']) }}" required>
                                @error('nombre')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="ubicacion" class="form-label">Ubicación</label>
                                <input type="text" id="ubicacion" name="ubicacion" class="form-control" value="{{ old('ubicacion', $datosFraccionamiento['ubicacion']) }}" required>
                                @error('ubicacion')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="zona" class="form-label">Zona *</label>
                                <select id="zona" name="zona" class="form-control" required>
                                    <option value="costa" {{ old('zona', $datosFraccionamiento['zona']) == 'costa' ? 'selected' : '' }}>Costa</option>
                                    <option value="istmo" {{ old('zona', $datosFraccionamiento['zona']) == 'istmo' ? 'selected' : '' }}>Istmo</option>
                                </select>
                                @error('zona')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="estatus" class="form-label">Estatus</label>
                                <select id="estatus" name="estatus" class="form-control" required>
                                    <option value="1" {{ old('estatus', $datosFraccionamiento['estatus']) ? 'selected' : '' }}>Activo</option>
                                    <option value="0" {{ !old('estatus', $datosFraccionamiento['estatus']) ? 'selected' : '' }}>Inactivo</option>
                                </select>
                                @error('estatus')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group full-width">
                                <label for="path_imagen" class="form-label">Imagen Principal</label>
                                <div class="image-upload-container">
                                    <div class="image-preview dark-bg" id="imagePreview">
                                        @if($datosFraccionamiento['path_imagen'])
                                            <img src="{{ asset('storage/' . $datosFraccionamiento['path_imagen']) }}" alt="Imagen actual" id="previewImage">
                                        @else
                                            <div class="image-placeholder">
                                                <i class="fas fa-image"></i>
                                                <span>No hay imagen seleccionada</span>
                                            </div>
                                        @endif
                                    </div>
                                    <input type="file" id="path_imagen" name="path_imagen" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif" onchange="previewImage(this)">
                                    @error('path_imagen')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-group full-width">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i>
                                    Actualizar
                                </button>
                            </div>

                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                </div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                </div>
                            @endif
                        </form>
                    </div>

                    <!-- Additional Information Section (MANTENER EN SU LUGAR ORIGINAL) -->
                    <div class="form-section">
                        <div class="section-header">
                            <h3 class="section-title">
                                <i class="fas fa-info-circle"></i>
                                Información Adicional
                            </h3>
                            <div class="section-indicator">
                                <span class="indicator-dot"></span>
                                Detalles adicionales
                            </div>
                        </div>
                        
                        <form action="{{ route('admin.fraccionamiento.update-info', $datosFraccionamiento['id']) }}" method="POST" class="form-grid">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group full-width">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea id="descripcion" name="descripcion" class="form-control" rows="4" placeholder="Describe las características principales del fraccionamiento...">{{ old('descripcion', $datosFraccionamiento['descripcion'] ?? '') }}</textarea>
                                @error('descripcion')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="precio_metro_cuadrado" class="form-label">Precio por m² (MXN)</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-dollar-sign"></i>
                                    <input type="number" id="precio_metro_cuadrado" name="precio_metro_cuadrado" step="0.01" class="form-control" value="{{ old('precio_metro_cuadrado', $datosFraccionamiento['precio_metro_cuadrado'] ?? '') }}" placeholder="0.00">
                                </div>
                                @error('precio_metro_cuadrado')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="tipo_propiedad" class="form-label">Tipo de Propiedad</label>
                                <select id="tipo_propiedad" name="tipo_propiedad" class="form-control">
                                    <option value="">Seleccionar tipo</option>
                                    <option value="Comunal" {{ old('tipo_propiedad', $datosFraccionamiento['tipo_propiedad'] ?? '') == 'Comunal' ? 'selected' : '' }}>Comunal</option>
                                    <option value="Ejidal" {{ old('tipo_propiedad', $datosFraccionamiento['tipo_propiedad'] ?? '') == 'Ejidal' ? 'selected' : '' }}>Ejidal</option>
                                    <option value="Privada" {{ old('tipo_propiedad', $datosFraccionamiento['tipo_propiedad'] ?? '') == 'Privada' ? 'selected' : '' }}>Privada</option>
                                </select>
                                @error('tipo_propiedad')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="precioGeneral" class="form-label">Precio General (MXN)</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-dollar-sign"></i>
                                    <input type="number" id="precioGeneral" name="precioGeneral" step="0.01" class="form-control" value="{{ old('precioGeneral', $datosFraccionamiento['precioGeneral'] ?? '') }}" placeholder="0.00">
                                </div>
                                @error('precioGeneral')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group full-width">
                                <label for="ubicacionMaps" class="form-label">URL de Google Maps</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <input type="text" id="ubicacionMaps" name="ubicacionMaps" class="form-control" value="{{ old('ubicacionMaps', $datosFraccionamiento['ubicacionMaps'] ?? '') }}" placeholder="https://maps.google.com/...">
                                </div>
                                @error('ubicacionMaps')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group full-width">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i>
                                    Actualizar
                                </button>
                            </div>

                            @if(session('success_info'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle"></i> {{ session('success_info') }}
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                </div>
                            @endif
                            @if(session('error_info'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle"></i> {{ session('error_info') }}
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                </div>
                            @endif
                        </form>
                    </div>

                    <!-- Zonas del Fraccionamiento Section (SOLO SI EXISTEN ZONAS) -->
                    @if($zonas && $zonas->count() > 0)
                    <div class="form-section">
                        <div class="section-header">
                            <h3 class="section-title">
                                <i class="fas fa-map-marker-alt"></i>
                                Zonas del Fraccionamiento
                            </h3>
                            <div class="section-indicator">
                                <span class="indicator-dot"></span>
                                {{ $zonas->count() }} zonas registradas
                            </div>
                        </div>
                        
                        <div class="zonas-list">
                            <div class="zonas-grid">
                                @foreach($zonas as $zona)
                                <div class="zona-card">
                                    <div class="zona-header">
                                        <h5 class="zona-name">{{ $zona['nombre'] }}</h5>
                                        <span class="zona-price">${{ number_format($zona['precio_m2'], 2) }} /m²</span>
                                    </div>
                                    <div class="zona-actions">
                                        <!-- Edit Zone Button -->
                                        <button type="button" class="btn btn-primary btn-sm" onclick="openEditZonaModal({{ $zona['id'] }}, '{{ addslashes($zona['nombre']) }}', {{ $zona['precio_m2'] }})">
                                            <i class="fas fa-edit"></i>
                                            Editar
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Statistics Section -->
                    <div class="stats-section">
                        <div class="section-header">
                            <h3 class="section-title">
                                <i class="fas fa-chart-bar"></i>
                                Resumen de Lotes
                            </h3>
                            <div class="section-indicator">
                                <span class="indicator-dot"></span>
                                Estado actual
                            </div>
                        </div>
                        
                        <div class="simple-stats-grid">
                            <div class="simple-stat">
                                <div class="stat-value">{{ $totalLotes }}</div>
                                <div class="stat-label">Total Lotes</div>
                            </div>
                            <div class="simple-stat">
                                <div class="stat-value">{{ $lotesDisponibles }}</div>
                                <div class="stat-label">Disponibles</div>
                            </div>
                            <div class="simple-stat">
                                <div class="stat-value">{{ $lotesApartados }}</div>
                                <div class="stat-label">Apartados</div>
                            </div>
                            <div class="simple-stat">
                                <div class="stat-value">{{ $lotesVendidos }}</div>
                                <div class="stat-label">Vendidos</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Zone Modal -->
            <div id="editZonaModal" class="modal">
                <div class="modal-content compact-modal">
                    <div class="modal-header">
                        <h3>Editar Zona</h3>
                        <button type="button" class="close" onclick="closeEditZonaModal()">×</button>
                    </div>
                    <form id="editZonaForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="edit_zona_nombre" class="form-label">Nombre de la Zona</label>
                                <input type="text" id="edit_zona_nombre" name="nombre" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_zona_precio_m2" class="form-label">Precio por m² (MXN)</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-dollar-sign"></i>
                                    <input type="number" id="edit_zona_precio_m2" name="precio_m2" step="0.01" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline" onclick="closeEditZonaModal()">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Amenities Tab -->
            <div id="amenities" class="tab-pane">
                <div class="form-section">
                    <div class="section-header">
                        <h3 class="section-title">
                            <i class="fas fa-bolt"></i>
                            Gestionar Amenidades
                        </h3>
                        <div class="section-indicator">
                            <span class="indicator-dot"></span>
                            {{ $amenidades->count() }} amenidades registradas
                        </div>
                    </div>
                    
                    <!-- Add Amenity Form -->
                    <div class="add-form-container">
                        <h4 class="form-subtitle">Agregar Nueva Amenidad</h4>
                        <form action="{{ route('admin.fraccionamiento.add-amenidad', $datosFraccionamiento['id']) }}" method="POST" class="form-grid compact">
                            @csrf

                            <div class="form-group">
                                <label for="amenidad_nombre" class="form-label">Nombre</label>
                                <input type="text" id="amenidad_nombre" name="nombre" class="form-control" placeholder="Ej. Calles de 10 mts, Energía eléctrica" value="{{ old('nombre') }}" required>
                                @error('nombre')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="amenidad_tipo" class="form-label">Tipo</label>
                                <select id="amenidad_tipo" name="tipo" class="form-control" required>
                                    <option value="Servicio" {{ old('tipo') == 'Servicio' ? 'selected' : '' }}>Servicio</option>
                                    <option value="Característica" {{ old('tipo') == 'Característica' ? 'selected' : '' }}>Característica</option>
                                </select>
                                @error('tipo')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group full-width" id="descripcion-group">
                                <label for="amenidad_descripcion" class="form-label">Descripción</label>
                                <textarea id="amenidad_descripcion" name="descripcion" class="form-control" placeholder="Describe la amenidad...">{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group2">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i>
                                    Agregar Amenidad
                                </button>
                            </div>

                            @if(session('success_amenidad'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle"></i> {{ session('success_amenidad') }}
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                </div>
                            @endif
                            @if(session('error_amenidad'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle"></i> {{ session('error_amenidad') }}
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                </div>
                            @endif
                        </form>
                    </div>
                    
                    @if($amenidades->count() > 0)
                    <div class="amenities-list">
                        <h4 class="form-subtitle">Amenidades Registradas</h4>
                        <div class="amenities-grid">
                            @foreach($amenidades as $amenidad)
                            <div class="amenity-card {{ strtolower($amenidad['tipo']) }}">
                                <div class="amenity-header">
                                    <h5 class="amenity-name">{{ $amenidad['nombre'] }}</h5>
                                    <span class="amenity-type">{{ $amenidad['tipo'] }}</span>
                                </div>
                                @if($amenidad['descripcion'])
                                <div class="amenity-description">
                                    <strong>Descripción:</strong> {{ $amenidad['descripcion'] }}
                                </div>
                                @endif
                                <div class="amenity-actions">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="showDeleteModal('amenidad', {{ $amenidad['id'] }}, '{{ $amenidad['nombre'] }}')">
                                        <i class="fas fa-trash"></i>
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="empty-state">
                        <i class="fas fa-bolt"></i>
                        <h4>No hay amenidades registradas</h4>
                        <p>Comienza agregando la primera amenidad del fraccionamiento</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Gallery Tab -->
            <div id="gallery" class="tab-pane">
                <div class="form-section">
                    <div class="section-header">
                        <h3 class="section-title">
                            <i class="fas fa-images"></i>
                            Gestionar Galería
                        </h3>
                        <div class="section-indicator">
                            <span class="indicator-dot"></span>
                            {{ $galeria->count() }} fotos en la galería
                        </div>
                    </div>
                    
                    <div class="add-form-container">
                        <h4 class="form-subtitle">Agregar Nueva Foto</h4>
                        <form action="{{ route('admin.fraccionamiento.add-foto', $datosFraccionamiento['id']) }}" method="POST" enctype="multipart/form-data" class="form-grid compact">
                            @csrf
                            <div class="form-group">
                                <label for="foto_nombre" class="form-label">Nombre de la Foto</label>
                                <input type="text" id="foto_nombre" name="nombre" class="form-control" placeholder="Ej. Foto aérea, Área común" value="{{ old('nombre') }}">
                                @error('nombre')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group full-width">
                                <label for="fotografia_path" class="form-label">Subir Foto</label>
                                <div class="gallery-preview-container" id="galleryPreviewContainer" style="display: none;">
                                    <div class="gallery-preview">
                                        <img id="galleryPreviewImage" src="" alt="Vista previa">
                                        <button type="button" class="btn-remove-preview" onclick="removeGalleryPreview()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="file-upload-container">
                                    <input type="file" id="fotografia_path" name="fotografia_path" class="file-input" accept="image/*" required onchange="previewGalleryImage(this)">
                                    <label for="fotografia_path" class="file-upload-label" id="fileUploadLabel">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Seleccionar archivo</span>
                                    </label>
                                    <small class="file-name" id="fileNameDisplay"></small>
                                </div>
                                @error('fotografia_path')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group2">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i>
                                    Agregar Foto
                                </button>
                            </div>

                            @if(session('success_foto'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle"></i> {{ session('success_foto') }}
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                </div>
                            @endif
                            @if(session('error_foto'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle"></i> {{ session('error_foto') }}
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                </div>
                            @endif
                        </form>
                    </div> 
                    
                    @if($galeria->count() > 0)
                    <div class="gallery-container">
                        <h4 class="form-subtitle">Galería de Fotos</h4>
                        <div class="gallery-grid">
                            @foreach($galeria as $foto)
                            <div class="gallery-item">
                                <div class="gallery-image">
                                    <img src="{{ asset('storage/' . $foto['fotografia_path']) }}" alt="{{ $foto['nombre'] ?? 'Foto' }}">
                                    <div class="gallery-overlay">
                                        <div class="gallery-actions">
                                            <a href="{{ asset('storage/' . $foto['fotografia_path']) }}" class="btn btn-sm btn-light" target="_blank">
                                                <i class="fas fa-expand"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="showDeleteModal('foto', {{ $foto['id'] }}, '{{ $foto['nombre'] ?? 'Sin título' }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="gallery-info">
                                    <h5>{{ $foto['nombre'] ?? 'Sin título' }}</h5>
                                    <p>Subido: {{ \Carbon\Carbon::parse($foto['fecha_subida'])->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="empty-state">
                        <i class="fas fa-images"></i>
                        <h4>No hay fotos en la galería</h4>
                        <p>Agrega fotos para mostrar el fraccionamiento</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Promociones Tab -->
            <div id="promociones" class="tab-pane">
                <div class="form-section">
                    <div class="section-header">
                        <h3 class="section-title">
                            <i class="fas fa-gift"></i>
                            Gestionar Promociones
                        </h3>
                        <div class="section-indicator">
                            <span class="indicator-dot"></span>
                            {{ $promociones->count() }} promociones asociadas
                        </div>
                    </div>

                    <!-- Botón para abrir modal de creación -->
                    <div class="add-form-container">
                        <button type="button" class="btn btn-primary btn-sm" onclick="openCreateModal()">
                            <i class="fas fa-plus"></i> Agregar Promoción
                        </button>
                    </div>

                    <!-- Lista de promociones -->
                    @if($promociones->count() > 0)
                    <div class="promociones-list mt-3">
                        <div class="promociones-grid">
                            @foreach($promociones as $promo)
                            @php
                                $hoy = \Carbon\Carbon::now();
                                $inicio = \Carbon\Carbon::parse($promo->fecha_inicio);
                                $fin = $promo->fecha_fin ? \Carbon\Carbon::parse($promo->fecha_fin) : null;
                                $activa = $inicio <= $hoy && ($fin === null || $fin >= $hoy);
                            @endphp

                            <div class="promo-card">
                                <div class="promo-image">
                                    <img src="{{ asset('storage/' . $promo->imagen_path) }}" alt="{{ $promo->titulo }}">
                                    <div class="promo-status {{ $activa ? 'active' : 'inactive' }}">
                                        {{ $activa ? 'ACTIVA' : 'INACTIVA' }}
                                    </div>
                                </div>
                                <div class="promo-info">
                                    <h5>{{ $promo->titulo }}</h5>
                                    @if($promo->descripcion)
                                        <p class="promo-desc">{{ Str::limit($promo->descripcion, 80) }}</p>
                                    @endif
                                    <p class="promo-dates">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ $inicio->format('d/m/Y H:i') }}
                                        @if($fin) → {{ $fin->format('d/m/Y H:i') }} @else → Indefinida @endif
                                    </p>
                                    <small class="text-muted">
                                        Aplica en: {{ $promo->fraccionamientos->pluck('nombre')->join(', ') }}
                                    </small>
                                </div>
                                <div class="promo-actions">
                                    <!-- BOTÓN EDITAR (COMPLETO) -->
                                    <button type="button" class="btn btn-sm btn-outline"
                                            onclick="openEditModal(
                                                {{ $promo->id_promocion }},
                                                '{{ addslashes($promo->titulo) }}',
                                                '{{ addslashes($promo->descripcion ?? '') }}',
                                                '{{ $promo->fecha_inicio }}',
                                                '{{ $promo->fecha_fin }}',
                                                '{{ $promo->imagen_path }}',
                                                @json($promo->fraccionamientos->pluck('id_fraccionamiento')->toArray())
                                            )">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <button type="button" class="btn btn-sm btn-danger" 
                                            onclick="showDeleteModal('promocion', {{ $promo->id_promocion }}, '{{ addslashes($promo->titulo) }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="empty-state">
                        <i class="fas fa-gift"></i>
                        <h4>No hay promociones asociadas</h4>
                        <p>Agrega una promoción para este fraccionamiento</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Files Tab -->
            <div id="files" class="tab-pane">
                <div class="form-section">
                    <div class="section-header">
                        <h3 class="section-title">
                            <i class="fas fa-file-download"></i>
                            Gestionar Archivos
                        </h3>
                        <div class="section-indicator">
                            <span class="indicator-dot"></span>
                            {{ $archivos->count() }} archivos disponibles
                        </div>
                    </div>
                    
                    <div class="add-form-container">
                        <h4 class="form-subtitle">Agregar Nuevo Archivo</h4>
                        <form action="{{ route('admin.fraccionamiento.add-archivo', $datosFraccionamiento['id']) }}"
                              method="POST"
                              enctype="multipart/form-data"
                              class="form-grid compact">
                            @csrf

                            <div class="form-group">
                                <label for="archivo_nombre" class="form-label">Nombre del Archivo</label>
                                <input type="text"
                                       id="archivo_nombre"
                                       name="nombre_archivo"
                                       class="form-control"
                                       placeholder="Ej. Reglamento, Plano general"
                                       value="{{ old('nombre_archivo') }}">
                                @error('nombre_archivo')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group full-width">
                                <label for="archivo_path" class="form-label">Subir Archivo (PDF)</label>
                                <div class="pdf-preview-container" id="pdfPreviewContainer" style="display:none;">
                                    <div class="pdf-preview">
                                        <iframe id="pdfPreviewIframe" src="" style="width:100%;height:100%;border:none;"></iframe>
                                        <button type="button" class="btn-remove-preview" onclick="removePdfPreview()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <small class="file-name" id="pdfFileName"></small>
                                </div>
                                <div class="file-upload-container">
                                    <input type="file"
                                           id="archivo_path"
                                           name="archivo_path"
                                           class="file-input"
                                           accept="application/pdf"
                                           required
                                           onchange="previewPdf(this)">
                                    <label for="archivo_path"
                                           class="file-upload-label"
                                           id="pdfUploadLabel">
                                        <i class="fas fa-file-pdf"></i>
                                        <span>Seleccionar archivo PDF</span>
                                    </label>
                                </div>
                                @error('archivo_path')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group full-width">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i>
                                    Agregar Archivo
                                </button>
                            </div>

                            @if(session('success_archivo'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle"></i> {{ session('success_archivo') }}
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                </div>
                            @endif
                            @if(session('error_archivo'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle"></i> {{ session('error_archivo') }}
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                </div>
                            @endif
                        </form>
                    </div>
                    
                    @if($archivos->count() > 0)
                    <div class="files-container">
                        <h4 class="form-subtitle">Archivos Disponibles</h4>
                        <div class="files-list">
                            @foreach($archivos as $archivo)
                            <div class="file-item">
                                <div class="file-icon">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div class="file-info">
                                    <h5>{{ $archivo['nombre_archivo'] ?? 'Sin título' }}</h5>
                                    <p>Subido: {{ \Carbon\Carbon::parse($archivo['fecha_subida'])->format('d/m/Y') }}</p>
                                </div>
                                <div class="file-actions">
                                    <a href="{{ route('admin.fraccionamiento.download-archivo', [$datosFraccionamiento['id'], $archivo['id']]) }}" class="btn btn-primary btn-sm" target="_blank">
                                        <i class="fas fa-download"></i>
                                        Descargar
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="showDeleteModal('archivo', {{ $archivo['id'] }}, '{{ $archivo['nombre_archivo'] ?? 'Sin título' }}')">
                                        <i class="fas fa-trash"></i>
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="empty-state">
                        <i class="fas fa-file-download"></i>
                        <h4>No hay archivos disponibles</h4>
                        <p>Agrega archivos PDF para que los asesores puedan descargarlos</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content compact-modal">
            <div class="modal-header">
                <h3>Confirmar Eliminación</h3>
                <button type="button" class="close" onclick="closeModal()">×</button>
            </div>
            <div class="modal-body">
                <i class="fas fa-exclamation-triangle warning-icon"></i>
                <p id="modalMessage">¿Estás seguro de que deseas eliminar este elemento?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Cancelar</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>


<!-- Create Promotion Modal -->
<div id="createPromoModal" class="modal">
    <div class="promo-modal-content">
        <div class="promo-modal-header">
            <h3>Nueva Promoción</h3>
            <button type="button" class="promo-modal-close" onclick="closeCreateModal()">×</button>
        </div>
        <form id="createPromoForm" action="{{ route('admin.promociones.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- ID del fraccionamiento actual (para redirección) -->
            <input type="hidden" name="current_fraccionamiento" value="{{ $datosFraccionamiento['id'] }}">

            <div class="promo-modal-body">
                <div class="promo-form-group">
                    <label class="promo-form-label">Título *</label>
                    <input type="text" name="titulo" class="promo-form-control" required>
                </div>
                <div class="promo-form-group full-width">
                    <label class="promo-form-label">Descripción</label>
                    <textarea name="descripcion" class="promo-form-control" rows="2"></textarea>
                </div>
                <div class="promo-form-group">
                    <label class="promo-form-label">Fecha Inicio *</label>
                    <input type="datetime-local" name="fecha_inicio" class="promo-form-control" required>
                </div>
                <div class="promo-form-group">
                    <label class="promo-form-label">Fecha Fin</label>
                    <input type="datetime-local" name="fecha_fin" class="promo-form-control">
                </div>
                <div class="promo-form-group full-width">
                    <label class="promo-form-label">Fraccionamientos *</label>
                    <div style="max-height:150px; overflow-y:auto; border:1px solid #ddd; padding:8px; border-radius:4px;">
                        @foreach(\App\Models\Fraccionamiento::where('estatus', 1)->get() as $frac)
                        <label style="display:block; margin:4px 0;">
                            <input type="checkbox" name="fraccionamientos[]" value="{{ $frac->id_fraccionamiento }}"
                                {{ $frac->id_fraccionamiento == $datosFraccionamiento['id'] ? 'checked' : '' }}>
                            {{ $frac->nombre }}
                            @if($frac->id_fraccionamiento == $datosFraccionamiento['id'])
                                <small class="text-muted">(este)</small>
                            @endif
                        </label>
                        @endforeach
                    </div>
                    <small class="text-muted">El fraccionamiento actual está preseleccionado</small>
                </div>
                <div class="promo-form-group full-width">
                    <label class="promo-form-label">Imagen *</label>
                    <input type="file" name="imagen" accept="image/*" required onchange="previewCreateImage(this)">
                    <div id="createPreviewContainer" style="display:none; margin-top:10px;">
                        <img id="createPreviewImage" src="" style="max-height:120px; border-radius:4px;">
                    </div>
                </div>
            </div>
            <div class="promo-modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeCreateModal()">Cancelar</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Crear Promoción
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Promotion Modal -->
<div id="editPromoModal" class="modal" style="display: none;">
    <div class="promo-modal-content">
        <div class="promo-modal-header">
            <h3>Editar Promoción</h3>
            <button type="button" class="promo-modal-close" onclick="closeEditModal()">×</button>
        </div>
        <form id="editPromoForm" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <input type="hidden" name="current_fraccionamiento" value="{{ $datosFraccionamiento['id'] }}">
            <input type="hidden" name="id_promocion" id="edit_id_promocion">

            <div class="promo-modal-body">
                <div class="promo-form-group">
                    <label class="promo-form-label">Título *</label>
                    <input type="text" id="edit_titulo" name="titulo" class="promo-form-control" required>
                </div>
                <div class="promo-form-group full-width">
                    <label class="promo-form-label">Descripción</label>
                    <textarea id="edit_descripcion" name="descripcion" class="promo-form-control" rows="2"></textarea>
                </div>
                <div class="promo-form-group">
                    <label class="promo-form-label">Fecha Inicio *</label>
                    <input type="datetime-local" id="edit_fecha_inicio" name="fecha_inicio" class="promo-form-control" required>
                </div>
                <div class="promo-form-group">
                    <label class="promo-form-label">Fecha Fin</label>
                    <input type="datetime-local" id="edit_fecha_fin" name="fecha_fin" class="promo-form-control">
                </div>
                <div class="promo-form-group full-width">
                    <label class="promo-form-label">Fraccionamientos *</label>
                    <div style="max-height:150px; overflow-y:auto; border:1px solid #ddd; padding:8px; border-radius:4px;">
                        @foreach(\App\Models\Fraccionamiento::where('estatus', 1)->get() as $frac)
                        <label style="display:block; margin:4px 0;">
                            <input type="checkbox" name="fraccionamientos[]" value="{{ $frac->id_fraccionamiento }}"
                                   id="frac-{{ $frac->id_fraccionamiento }}">
                            {{ $frac->nombre }}
                            @if($frac->id_fraccionamiento == $datosFraccionamiento['id'])
                                <small class="text-muted">(este)</small>
                            @endif
                        </label>
                        @endforeach
                    </div>
                </div>
                <div class="promo-form-group full-width">
                    <label class="promo-form-label">Imagen (dejar vacío para mantener)</label>
                    <input type="file" id="edit_imagen" name="imagen" accept="image/*" onchange="previewEditImage(this)">
                    <div id="editPreviewContainer" style="display:none; margin-top:10px;">
                        <img id="editPreviewImage" src="" style="max-height:120px; border-radius:4px;">
                    </div>
                    <div id="currentImageContainer" style="margin-top:10px; display:none;">
                        <p><strong>Imagen actual:</strong></p>
                        <img id="currentImagePreview" src="" style="max-height:100px; border-radius:4px;">
                    </div>
                </div>
            </div>
            <div class="promo-modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeEditModal()">Cancelar</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        // === TAB NAVIGATION ===
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabPanes = document.querySelectorAll('.tab-pane');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const target = this.dataset.tab;

                tabBtns.forEach(b => b.classList.remove('active'));
                tabPanes.forEach(p => p.classList.remove('active'));

                this.classList.add('active');
                document.getElementById(target)?.classList.add('active');
            });
        });

        // === MANTENER TAB ACTIVO DESPUÉS DE REDIRECCIÓN ===
        @if(session('active_tab'))
            const activeTab = "{{ session('active_tab') }}";
            const targetBtn = document.querySelector(`.tab-btn[data-tab="${activeTab}"]`);
            const targetPane = document.getElementById(activeTab);
            if (targetBtn && targetPane) {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
                targetBtn.classList.add('active');
                targetPane.classList.add('active');
            }
        @endif

        // === DISMISS ALERTS ===
        document.querySelectorAll('.alert .close').forEach(btn => {
            btn.addEventListener('click', function () {
                this.parentElement.style.display = 'none';
            });
        });

        // === OCULTAR DESCRIPCIÓN SI ES "CARACTERÍSTICA" ===
        const tipoSelect = document.getElementById('amenidad_tipo');
        const descripcionGroup = document.getElementById('descripcion-group');
        const descripcionField = document.getElementById('amenidad_descripcion');

        if (tipoSelect && descripcionGroup && descripcionField) {
            function toggleDescripcion() {
                if (tipoSelect.value === 'Característica') {
                    descripcionGroup.classList.add('collapsed');
                    descripcionField.removeAttribute('name');
                    descripcionField.value = '';
                } else {
                    descripcionGroup.classList.remove('collapsed');
                    if (!descripcionField.hasAttribute('name')) {
                        descripcionField.setAttribute('name', 'descripcion');
                    }
                }
            }
            toggleDescripcion();
            tipoSelect.addEventListener('change', toggleDescripcion);
        }
    });

    // === MODAL ELIMINAR (amenidad, foto, archivo, promoción) ===
    function showDeleteModal(type, id, name) {
        const modal = document.getElementById('deleteModal');
        const messageEl = document.getElementById('modalMessage');
        const form = document.getElementById('deleteForm');

        let message = '';
        let action = '';

        if (type === 'amenidad') {
            message = `¿Eliminar la amenidad "${name}"?`;
            action = `{{ route('admin.fraccionamiento.delete-amenidad', [$datosFraccionamiento['id'], 'ID']) }}`.replace('ID', id);
        } else if (type === 'foto') {
            message = `¿Eliminar la foto "${name}"?`;
            action = `{{ route('admin.fraccionamiento.delete-foto', [$datosFraccionamiento['id'], 'ID']) }}`.replace('ID', id);
        } else if (type === 'archivo') {
            message = `¿Eliminar el archivo "${name}"?`;
            action = `{{ route('admin.fraccionamiento.delete-archivo', [$datosFraccionamiento['id'], 'ID']) }}`.replace('ID', id);
        } else if (type === 'promocion') {
            message = `¿Eliminar la promoción "${name}"?`;
            action = `{{ route('admin.promociones.destroy', 'ID') }}`.replace('ID', id);
        }

        messageEl.textContent = message;
        form.action = action;
        modal.style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }

    window.addEventListener('click', function (e) {
        const modal = document.getElementById('deleteModal');
        if (e.target === modal) closeModal();
    });

    // === PREVIEW DE IMAGEN GENERAL (fraccionamiento) ===
    window.previewImage = function (input) {
        const preview = document.getElementById('imagePreview');
        const img = document.getElementById('previewImage') || document.createElement('img');
        img.id = 'previewImage';
        if (input.files?.[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                img.src = e.target.result;
                if (!document.getElementById('previewImage')) preview.appendChild(img);
            };
            reader.readAsDataURL(input.files[0]);
        }
    };

    // === PREVIEW DE GALERÍA ===
    window.previewGalleryImage = function (input) {
        const container = document.getElementById('galleryPreviewContainer');
        const img = document.getElementById('galleryPreviewImage');
        const nameEl = document.getElementById('fileNameDisplay');
        const label = document.getElementById('fileUploadLabel');

        if (input.files?.[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = e => {
                img.src = e.target.result;
                container.style.display = 'block';
                nameEl.textContent = file.name;
                label.innerHTML = '<i class="fas fa-check"></i> <span>Seleccionado</span>';
                label.style.backgroundColor = 'var(--success-color)';
                label.style.color = 'white';
            };
            reader.readAsDataURL(file);
        }
    };

    window.removeGalleryPreview = function () {
        document.getElementById('fotografia_path').value = '';
        document.getElementById('galleryPreviewContainer').style.display = 'none';
        document.getElementById('fileNameDisplay').textContent = '';
        const label = document.getElementById('fileUploadLabel');
        label.innerHTML = '<i class="fas fa-cloud-upload-alt"></i> <span>Seleccionar archivo</span>';
        label.style.backgroundColor = ''; label.style.color = '';
    };

    // === PREVIEW DE PDF ===
    window.previewPdf = function (input) {
        const container = document.getElementById('pdfPreviewContainer');
        const iframe = document.getElementById('pdfPreviewIframe');
        const nameEl = document.getElementById('pdfFileName');
        const label = document.getElementById('pdfUploadLabel');

        if (input.files?.[0]) {
            const file = input.files[0];
            if (file.type !== 'application/pdf') {
                alert('Solo PDF'); input.value = ''; return;
            }
            const reader = new FileReader();
            reader.onload = e => {
                iframe.src = e.target.result;
                container.style.display = 'block';
                nameEl.textContent = file.name;
                label.innerHTML = '<i class="fas fa-check"></i> <span>PDF seleccionado</span>';
                label.style.backgroundColor = 'var(--success-color)';
                label.style.color = 'white';
            };
            reader.readAsDataURL(file);
        }
    };

    window.removePdfPreview = function () {
        document.getElementById('archivo_path').value = '';
        document.getElementById('pdfPreviewContainer').style.display = 'none';
        document.getElementById('pdfFileName').textContent = '';
        const label = document.getElementById('pdfUploadLabel');
        label.innerHTML = '<i class="fas fa-file-pdf"></i> <span>Seleccionar PDF</span>';
        label.style.backgroundColor = ''; label.style.color = '';
    };

    // === MODALES DE PROMOCIÓN ===

    // Crear
    function openCreateModal() {
        const modal = document.getElementById('createPromoModal');
        modal.style.display = 'flex';
        // Preseleccionar fraccionamiento actual
        const checkbox = document.querySelector(`#createPromoForm input[value="{{ $datosFraccionamiento['id'] }}"]`);
        if (checkbox) checkbox.checked = true;
    }

    function closeCreateModal() {
        document.getElementById('createPromoModal').style.display = 'none';
        document.getElementById('createPromoForm').reset();
        document.getElementById('createPreviewContainer').style.display = 'none';
    }

    function previewCreateImage(input) {
        const preview = document.getElementById('createPreviewImage');
        const container = document.getElementById('createPreviewContainer');
        if (input.files?.[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                container.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // === MODALES DE PROMOCIÓN: EDITAR ===
    function openEditModal(id, titulo, descripcion, fecha_inicio, fecha_fin, imagen_path, fraccionamientos_ids) {
        // Configurar formulario
        const form = document.getElementById('editPromoForm');
        form.action = `/admin/promociones/${id}`;

        // Llenar campos
        document.getElementById('edit_id_promocion').value = id;
        document.getElementById('edit_titulo').value = titulo;
        document.getElementById('edit_descripcion').value = descripcion;

        // Convertir fechas: "2025-04-05 14:30:00" → "2025-04-05T14:30"
        document.getElementById('edit_fecha_inicio').value = fecha_inicio ? fecha_inicio.replace(' ', 'T').substring(0, 16) : '';
        document.getElementById('edit_fecha_fin').value = fecha_fin ? fecha_fin.replace(' ', 'T').substring(0, 16) : '';

        // Marcar fraccionamientos
        document.querySelectorAll('#editPromoForm input[name="fraccionamientos[]"]').forEach(cb => cb.checked = false);
        fraccionamientos_ids.forEach(fid => {
            const cb = document.getElementById('frac-' + fid);
            if (cb) cb.checked = true;
        });

        // Mostrar imagen actual
        const currentImg = document.getElementById('currentImagePreview');
        const currentContainer = document.getElementById('currentImageContainer');
        if (imagen_path) {
            currentImg.src = '/storage/' + imagen_path;
            currentContainer.style.display = 'block';
        } else {
            currentContainer.style.display = 'none';
        }

        // Limpiar preview nueva
        document.getElementById('editPreviewContainer').style.display = 'none';

        // ABRIR MODAL
        document.getElementById('editPromoModal').style.display = 'flex';
    }

    function closeEditModal() {
        document.getElementById('editPromoModal').style.display = 'none';
        document.getElementById('editPreviewContainer').style.display = 'none';
    }

    function previewEditImage(input) {
        const preview = document.getElementById('editPreviewImage');
        const container = document.getElementById('editPreviewContainer');
        if (input.files?.[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                container.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Cerrar modal al hacer clic fuera
    document.getElementById('editPromoModal').addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });

    // Cerrar modales al hacer clic fuera
    ['createPromoModal', 'editPromoModal'].forEach(id => {
        const modal = document.getElementById(id);
        window.addEventListener('click', e => {
            if (e.target === modal) {
                if (id === 'createPromoModal') closeCreateModal();
                if (id === 'editPromoModal') closeEditModal();
            }
        });
    });



    // === GESTIÓN DE ZONAS ===

    // Abrir modal de edición de zona
    function openEditZonaModal(id, nombre, precio_m2) {
        const form = document.getElementById('editZonaForm');
        form.action = `{{ route('admin.fraccionamiento.update-zona', [$datosFraccionamiento['id'], 'ID']) }}`.replace('ID', id);
        
        document.getElementById('edit_zona_nombre').value = nombre;
        document.getElementById('edit_zona_precio_m2').value = precio_m2;
        
        document.getElementById('editZonaModal').style.display = 'flex';
    }

    // Cerrar modal de edición de zona
    function closeEditZonaModal() {
        document.getElementById('editZonaModal').style.display = 'none';
    }

    // Cerrar modal de zona al hacer clic fuera
    document.getElementById('editZonaModal').addEventListener('click', function(e) {
        if (e.target === this) closeEditZonaModal();
    });
</script>

@endsection