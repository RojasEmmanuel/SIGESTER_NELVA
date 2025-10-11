@extends('admin.navbar')

@section('title', 'Nelva Bienes Raíces - Admin Fraccionamiento')

@push('styles')
<link href="{{ asset('css/fraccionamientoAdmin.css') }}" rel="stylesheet">
@endpush

@section('content')
    <!-- Main Content -->
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-map-marked-alt"></i>
                <span>Administrar Fraccionamiento</span>
            </h1>
            <div class="page-actions">
                <button class="btn btn-outline" onclick="window.location.href='/admin/inicio'">
                    <i class="fas fa-arrow-left"></i> Volver
                </button>
            </div>
        </div>

        <!-- Development Header -->
        <div class="form-section">
            <h3 class="info-title">
                <i class="fas fa-home"></i>
                <span>Editar Información del Fraccionamiento</span>
            </h3>
            <form action="{{ route('admin.fraccionamiento.update', $datosFraccionamiento['id']) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" value="{{ $datosFraccionamiento['nombre'] }}" required>
                </div>
                <div class="form-group">
                    <label for="ubicacion" class="form-label">Ubicación</label>
                    <input type="text" id="ubicacion" name="ubicacion" class="form-control" value="{{ $datosFraccionamiento['ubicacion'] }}" required>
                </div>
                <div class="form-group">
                    <label for="path_imagen" class="form-label">Imagen del Fraccionamiento</label>
                    <input type="file" id="path_imagen" name="path_imagen" class="form-control" accept="image/*">
                    @if($datosFraccionamiento['path_imagen'])
                        <img src="{{ asset('storage/' . $datosFraccionamiento['path_imagen']) }}" alt="Imagen actual" style="max-width: 200px; margin-top: 1rem;">
                    @endif
                </div>
                <div class="form-group">
                    <label for="estatus" class="form-label">Estatus</label>
                    <select id="estatus" name="estatus" class="form-control" required>
                        <option value="1" {{ $datosFraccionamiento['estatus'] ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ !$datosFraccionamiento['estatus'] ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Actualizar Fraccionamiento</button>
                </div>
            </form>
        </div>

        <!-- Información Adicional -->
        <div class="form-section">
            <h3 class="info-title">
                <i class="fas fa-info-circle"></i>
                <span>Editar Información Adicional</span>
            </h3>
            <form action="{{ route('admin.fraccionamiento.update-info', $datosFraccionamiento['id']) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea id="descripcion" name="descripcion" class="form-control">{{ $datosFraccionamiento['descripcion'] ?? '' }}</textarea>
                </div>
                <div class="form-group">
                    <label for="precio_metro_cuadrado" class="form-label">Precio por m² (MXN)</label>
                    <input type="number" id="precio_metro_cuadrado" name="precio_metro_cuadrado" step="0.01" class="form-control" value="{{ $datosFraccionamiento['precio_metro_cuadrado'] ?? '' }}">
                </div>
                <div class="form-group">
                    <label for="tipo_propiedad" class="form-label">Tipo de Propiedad</label>
                    <input type="text" id="tipo_propiedad" name="tipo_propiedad" class="form-control" value="{{ $datosFraccionamiento['tipo_propiedad'] ?? '' }}">
                </div>
                <div class="form-group">
                    <label for="precioGeneral" class="form-label">Precio General (MXN)</label>
                    <input type="number" id="precioGeneral" name="precioGeneral" step="0.01" class="form-control" value="{{ $datosFraccionamiento['precioGeneral'] ?? '' }}">
                </div>
                <div class="form-group">
                    <label for="ubicacionMaps" class="form-label">URL de Google Maps</label>
                    <input type="text" id="ubicacionMaps" name="ubicacionMaps" class="form-control" value="{{ $datosFraccionamiento['ubicacionMaps'] ?? '' }}">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Actualizar Información</button>
                </div>
            </form>
        </div>

        <!-- Estadísticas Minimalistas -->
        <div class="stats-section">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ $totalLotes }}</div>
                    <div class="stat-label">Total</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $lotesDisponibles }}</div>
                    <div class="stat-label">Disponibles</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $lotesApartados }}</div>
                    <div class="stat-label">Apartados</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $lotesVendidos }}</div>
                    <div class="stat-label">Vendidos</div>
                </div>
            </div>
        </div>

        <!-- Amenidades -->
        <div class="form-section">
            <h3 class="info-title">
                <i class="fas fa-bolt"></i>
                <span>Gestionar Amenidades</span>
            </h3>
            <!-- Formulario para agregar nueva amenidad -->
            <form action="{{ route('admin.fraccionamiento.add-amenidad', $datosFraccionamiento['id']) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="amenidad_nombre" class="form-label">Nombre de la Amenidad</label>
                    <input type="text" id="amenidad_nombre" name="nombre" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="amenidad_descripcion" class="form-label">Descripción</label>
                    <textarea id="amenidad_descripcion" name="descripcion" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label for="amenidad_tipo" class="form-label">Tipo</label>
                    <input type="text" id="amenidad_tipo" name="tipo" class="form-control">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Agregar Amenidad</button>
                </div>
            </form>

            <!-- Lista de amenidades existentes -->
            @if($amenidades->count() > 0)
            <div class="services-list mt-3">
                @foreach($amenidades as $amenidad)
                <div class="service-tag">
                    <span>{{ $amenidad['nombre'] }}</span>
                    <form action="{{ route('admin.fraccionamiento.delete-amenidad', [$datosFraccionamiento['id'], $amenidad['id']]) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta amenidad?')">Eliminar</button>
                    </form>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Galería -->
        <div class="form-section gallery-section">
            <h3 class="info-title">
                <i class="fas fa-images"></i>
                <span>Gestionar Galería</span>
            </h3>
            <form action="{{ route('admin.fraccionamiento.add-foto', $datosFraccionamiento['id']) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="foto_nombre" class="form-label">Nombre de la Foto</label>
                    <input type="text" id="foto_nombre" name="nombre" class="form-control">
                </div>
                <div class="form-group">
                    <label for="fotografia_path" class="form-label">Subir Foto</label>
                    <input type="file" id="fotografia_path" name="fotografia_path" class="form-control" accept="image/*" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Agregar Foto</button>
                </div>
            </form>

            @if($galeria->count() > 0)
            <div class="gallery-grid">
                @foreach($galeria as $foto)
                <div class="gallery-item">
                    <img src="{{ asset('storage/' . $foto['fotografia_path']) }}" alt="{{ $foto['nombre'] ?? 'Foto del fraccionamiento' }}">
                    <div class="gallery-info">
                        <h5>{{ $foto['nombre'] ?? 'Sin título' }}</h5>
                        <p>Subido: {{ $foto['fecha_subida'] }}</p>
                        <form action="{{ route('admin.fraccionamiento.delete-foto', [$datosFraccionamiento['id'], $foto['id']]) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete" onclick="return confirm('¿Estás seguro de eliminar esta foto?')">Eliminar</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Archivos -->
        <div class="form-section files-section">
            <h3 class="info-title">
                <i class="fas fa-file-download"></i>
                <span>Gestionar Archivos</span>
            </h3>
            <form action="{{ route('admin.fraccionamiento.add-archivo', $datosFraccionamiento['id']) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="archivo_nombre" class="form-label">Nombre del Archivo</label>
                    <input type="text" id="archivo_nombre" name="nombre_archivo" class="form-control">
                </div>
                <div class="form-group">
                    <label for="archivo_path" class="form-label">Subir Archivo (PDF)</label>
                    <input type="file" id="archivo_path" name="archivo_path" class="form-control" accept="application/pdf" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Agregar Archivo</button>
                </div>
            </form>

            @if($archivos->count() > 0)
            <div class="files-list">
                @foreach($archivos as $archivo)
                <div class="file-item">
                    <div class="file-info">
                        <h5>{{ $archivo['nombre_archivo'] ?? 'Sin título' }}</h5>
                        <p>Subido: {{ $archivo['fecha_subida'] }}</p>
                    </div>
                    <a href="{{ route('admin.fraccionamiento.download-archivo', [$datosFraccionamiento['id'], $archivo['id']]) }}" class="btn btn-download" target="_blank">
                        <i class="fas fa-download"></i> Descargar
                    </a>
                    <form action="{{ route('admin.fraccionamiento.delete-archivo', [$datosFraccionamiento['id'], $archivo['id']]) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete" onclick="return confirm('¿Estás seguro de eliminar este archivo?')">Eliminar</button>
                    </form>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Development Map -->
        @if(isset($datosFraccionamiento['ubicacionMaps']) && !empty($datosFraccionamiento['ubicacionMaps']))
        <div class="development-map">
            <h3 class="info-title">
                <i class="fas fa-map-marked-alt"></i>
                <span>Ubicación en Mapa</span>
            </h3>
            <div class="map-container">
                <iframe 
                    class="map-iframe" 
                    src="{{ $datosFraccionamiento['ubicacionMaps'] }}" 
                    width="600" 
                    height="450" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
        @endif
    </div>
@endsection