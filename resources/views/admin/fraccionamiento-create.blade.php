@extends('admin.navbar')

@section('title', 'Nelva Bienes Raíces - Crear Fraccionamiento')

@push('styles')
<link href="{{ asset('css/formFraccionamiento.css') }}" rel="stylesheet"> <!-- Ajusta si usas fraccionamientoAdmin.css -->
<link::selection { background: #007bff; color: #fff; }
@endpush

@section('content')
<div class="container mt-5">
    <div class="page-header mb-4">
        <h1 class="mb-3">
            <i class="fas fa-map-marked-alt"></i> Registrar Nuevo Fraccionamiento
        </h1>
        <p class="header-subtitle">Completa los datos básicos y adicionales. Después podrás agregar amenidades, galería y archivos.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> Por favor corrige los errores abajo.
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <form action="{{ route('admin.fraccionamiento.store') }}" method="POST" enctype="multipart/form-data" class="form-section border p-4 rounded bg-light">
        @csrf

        <!-- Información Básica -->
        <div class="section-header mb-4">
            <h3 class="section-title">
                <i class="fas fa-home"></i> Información Básica
            </h3>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label for="nombre" class="form-label">Nombre del Fraccionamiento *</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                @error('nombre')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="ubicacion" class="form-label">Ubicación *</label>
                <input type="text" id="ubicacion" name="ubicacion" class="form-control" value="{{ old('ubicacion') }}" required>
                @error('ubicacion')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="zona" class="form-label">Zona *</label>
                <select id="zona" name="zona" class="form-control" required>
                    <option value="">Seleccionar zona</option>
                    <option value="costa" {{ old('zona') == 'costa' ? 'selected' : '' }}>Costa</option>
                    <option value="istmo" {{ old('zona') == 'istmo' ? 'selected' : '' }}>Istmo</option>
                </select>
                @error('zona')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="estatus" class="form-label">Estatus *</label>
                <select id="estatus" name="estatus" class="form-control" required>
                    <option value="1" {{ old('estatus', 1) ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ old('estatus') == '0' ? 'selected' : '' }}>Inactivo</option>
                </select>
                @error('estatus')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <!-- Subida de imagen -->
             <!-- Subida de imagen -->
            <div class="form-group full-width">
                <label class="form-label">Imagen Principal</label>
                <div class="image-upload-container">
                    <div class="image-preview" id="imagePreview">
                        <div class="image-placeholder">
                            <i class="fas fa-image"></i>
                            <span>Vista previa de la imagen</span>
                            <small class="text-muted">Formatos: JPG, PNG, GIF</small>
                        </div>
                    </div>
                    <div class="file-upload-container">
                        <input type="file" id="path_imagen" name="path_imagen" 
                            class="file-input" accept="image/jpeg,image/png,image/jpg,image/gif" 
                            onchange="previewImage(this)">
                        <label for="path_imagen" class="file-upload-label">
                            <i class="fas fa-folder-open"></i>
                            Seleccionar Archivo
                        </label>
                    </div>
                </div>
                @error('path_imagen')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <!-- Información Adicional -->
        <div class="section-header mb-4 mt-5">
            <h3 class="section-title">
                <i class="fas fa-info-circle"></i> Información Adicional
            </h3>
        </div>

        <div class="form-grid">
            <div class="form-group full-width">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea id="descripcion" name="descripcion" class="form-control" rows="4" placeholder="Describe las características principales...">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="precio_metro_cuadrado" class="form-label">Precio por m² (MXN)</label>
                <div class="input-with-icon">
                    <i class="fas fa-dollar-sign"></i>
                    <input type="number" id="precio_metro_cuadrado" name="precio_metro_cuadrado" step="0.01" class="form-control" value="{{ old('precio_metro_cuadrado') }}" placeholder="0.00">
                </div>
                @error('precio_metro_cuadrado')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="tipo_propiedad" class="form-label">Tipo de Propiedad</label>
                <select id="tipo_propiedad" name="tipo_propiedad" class="form-control">
                    <option value="">Seleccionar tipo</option>
                    <option value="Comunal" {{ old('tipo_propiedad') == 'Comunal' ? 'selected' : '' }}>Comunal</option>
                    <option value="Ejidal" {{ old('tipo_propiedad') == 'Ejidal' ? 'selected' : '' }}>Ejidal</option>
                    <option value="Privada" {{ old('tipo_propiedad') == 'Privada' ? 'selected' : '' }}>Privada</option>
                </select>
                @error('tipo_propiedad')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="precioGeneral" class="form-label">Precio General (MXN)</label>
                <div class="input-with-icon">
                    <i class="fas fa-dollar-sign"></i>
                    <input type="number" id="precioGeneral" name="precioGeneral" step="0.01" class="form-control" value="{{ old('precioGeneral') }}" placeholder="0.00">
                </div>
                @error('precioGeneral')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group full-width">
                <label for="ubicacionMaps" class="form-label">URL de Google Maps</label>
                <div class="input-with-icon">
                    <i class="fas fa-map-marker-alt"></i>
                    <input type="text" id="ubicacionMaps" name="ubicacionMaps" class="form-control" value="{{ old('ubicacionMaps') }}" placeholder="https://...">
                </div>
                @error('ubicacionMaps')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group full-width mt-4">
                <button type=";y" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Crear Fraccionamiento
                </button>
                <a href="{{ url()->previous() }}" class="btn btn-outline ml-2">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>
        </div>
    </form>
</div>

<script>
   // Image Preview
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const fileLabel = document.querySelector('.file-upload-label');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" alt="Vista previa">`;
                // Opcional: Cambiar el texto del botón
                fileLabel.innerHTML = '<i class="fas fa-check"></i> Archivo Seleccionado';
                fileLabel.style.background = 'var(--success-color)';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            // Restaurar estado original si no hay archivo
            preview.innerHTML = `
                <div class="image-placeholder">
                    <i class="fas fa-image"></i>
                    <span>Vista previa de la imagen</span>
                    <small class="text-muted">Formatos: JPG, PNG, GIF</small>
                </div>
            `;
            fileLabel.innerHTML = '<i class="fas fa-folder-open"></i> Seleccionar Archivo';
            fileLabel.style.background = 'var(--primary-color)';
        }
    }

    // Dismiss alerts
    document.querySelectorAll('.alert .close').forEach(button => {
        button.addEventListener('click', function() {
            this.parentElement.style.display = 'none';
        });
    });
</script>
@endsection