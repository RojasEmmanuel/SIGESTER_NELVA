{{-- resources/views/admin/fraccionamientos/create.blade.php --}}
@extends('admin.navbar')

@section('title', 'Nelva Bienes Raíces - Crear Fraccionamiento')

@push('styles')
<link href="{{ asset('css/formFraccionamiento.css') }}" rel="stylesheet">
<style>
    .zona-row { border: 1px dashed #007bff; padding: 15px; border-radius: 8px; background: #f8f9fa; }
    .btn-remove-zona { margin-top: 32px; }
    .step { display: none; }
    .step.active { display: block; }
</style>
@endpush

@section('content')
<div class="container mt-5">
    <div class="page-header mb-4">
        <h1 class="mb-3">
            <i class="fas fa-map-marked-alt"></i> Registrar Nuevo Fraccionamiento
        </h1>
        <p class="header-subtitle">Completa los datos básicos y adicionales. Después podrás agregar amenidades, galería y archivos.</p>
    </div>

    <!-- ALERTAS -->
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

    <form action="{{ route('admin.fraccionamiento.store') }}" method="POST" enctype="multipart/form-data" 
          class="form-section border p-4 rounded bg-light" id="formPrincipal">
        @csrf

        <!-- ==================== PASO 1: TU FORMULARIO ORIGINAL (100% INTACTO) ==================== -->
        <div id="paso1" class="step active">
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
                    @error('nombre') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="ubicacion" class="form-label">Ubicación *</label>
                    <input type="text" id="ubicacion" name="ubicacion" class="form-control" value="{{ old('ubicacion') }}" required>
                    @error('ubicacion') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="zona" class="form-label">Zona *</label>
                    <select id="zona" name="zona" class="form-control" required>
                        <option value="">Seleccionar zona</option>
                        <option value="costa" {{ old('zona') == 'costa' ? 'selected' : '' }}>Costa</option>
                        <option value="istmo" {{ old('zona') == 'istmo' ? 'selected' : '' }}>Istmo</option>
                    </select>
                    @error('zona') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="estatus" class="form-label">Estatus *</label>
                    <select id="estatus" name="estatus" class="form-control" required>
                        <option value="1" {{ old('estatus', 1) ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ old('estatus') == '0' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estatus') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

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
                                <i class="fas fa-folder-open"></i> Seleccionar Archivo
                            </label>
                        </div>
                    </div>
                    @error('path_imagen') <small class="text-danger">{{ $message }}</small> @enderror
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
                    <textarea id="descripcion" name="descripcion" class="form-control" rows="4" 
                              placeholder="Describe las características principales...">{{ old('descripcion') }}</textarea>
                    @error('descripcion') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="precio_metro_cuadrado" class="form-label">Precio por m² (MXN)</label>
                    <div class="input-with-icon">
                        <i class="fas fa-dollar-sign"></i>
                        <input type="number" id="precio_metro_cuadrado" name="precio_metro_cuadrado" 
                               step="0.01" class="form-control" value="{{ old('precio_metro_cuadrado') }}" placeholder="0.00">
                    </div>
                    @error('precio_metro_cuadrado') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="tipo_propiedad" class="form-label">Tipo de Propiedad</label>
                    <select id="tipo_propiedad" name="tipo_propiedad" class="form-control">
                        <option value="">Seleccionar tipo</option>
                        <option value="Comunal" {{ old('tipo_propiedad') == 'Comunal' ? 'selected' : '' }}>Comunal</option>
                        <option value="Ejidal" {{ old('tipo_propiedad') == 'Ejidal' ? 'selected' : '' }}>Ejidal</option>
                        <option value="Privada" {{ old('tipo_propiedad') == 'Privada' ? 'selected' : '' }}>Privada</option>
                    </select>
                    @error('tipo_propiedad') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="precioGeneral" class="form-label">Precio General (MXN)</label>
                    <div class="input-with-icon">
                        <i class="fas fa-dollar-sign"></i>
                        <input type="number" id="precioGeneral" name="precioGeneral" step="0.01" 
                               class="form-control" value="{{ old('precioGeneral') }}" placeholder="0.00">
                    </div>
                    @error('precioGeneral') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group full-width">
                    <label for="ubicacionMaps" class="form-label">URL de Google Maps</label>
                    <div class="input-with-icon">
                        <i class="fas fa-map-marker-alt"></i>
                        <input type="text" id="ubicacionMaps" name="ubicacionMaps" class="form-control" 
                               value="{{ old('ubicacionMaps') }}" placeholder="https://...">
                    </div>
                    @error('ubicacionMaps') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <!-- BOTÓN SIGUIENTE -->
            <div class="form-group2 botontesRegistro text-center mt-4">
                <button type="button" class="btn btn-primary btn-lg" onclick="irAZonas()">
                    <i class="fas fa-arrow-right"></i> Siguiente: ¿Zonas?
                </button>
            </div>
             </div>

            <!-- ==================== PASO 2: ¿AGREGAR ZONAS? ==================== -->
            <div id="paso2" class="step form-section"> <!-- Agregar form-section -->
                <div class="text-center py-5">
                    <i class="fas fa-question-circle fa-4x text-primary mb-4"></i>
                    <h2 class="header-title mb-4"> <!-- Cambiar a header-title -->
                        ¿Deseas registrar zonas con precios por m²?
                    </h2>
                    <p class="header-subtitle mb-5"> <!-- Cambiar a header-subtitle -->
                        Puedes crear zonas como "Oro", "Premium", "Plata", etc.
                    </p>

                    <div class="d-flex justify-content-center gap-4 flex-wrap">
                        <button type="button" 
                                class="btn btn-primary btn-lg px-5" 
                                onclick="mostrarZonas(true)"
                                style="min-width: 220px;">
                            <i class="fas fa-check fa-fw"></i> SÍ, agregar zonas
                        </button>

                        <button type="button" 
                                class="btn btn-outline btn-lg px-5" 
                                onclick="mostrarZonas(false)"
                                style="min-width: 220px;">
                            <i class="fas fa-times fa-fw"></i> NO, solo fraccionamiento
                        </button>
                    </div>
                </div>
            </div>

            <!-- ==================== PASO 3: ZONAS DINÁMICAS ==================== -->
            <div id="paso3" class="step form-section"> <!-- Agregar form-section -->
                <div class="section-header mb-4">
                    <h3 class="section-title">
                        <i class="fas fa-layer-group"></i> Zonas del Fraccionamiento
                    </h3>
                </div>

                <div id="contenedorZonas" class="mb-4">
                    <!-- Primera zona (clonable) -->
                    <div class="zona-row card border-primary mb-3">
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">Nombre de la zona *</label>
                                    <input type="text" name="zonas[0][nombre]" 
                                        class="form-control" placeholder="Ej. Oro, Plata, Bronce" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Precio por m² *</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-dollar-sign"></i>
                                        <input type="number" step="0.01" name="zonas[0][precio_m2]" 
                                            class="form-control" placeholder="2500.00" required>
                                    </div>
                                </div>
                                <div class="form-group d-flex align-items-end">
                                    <button type="button" class="btn btn-danger" style="color: white"
                                            onclick="this.closest('.zona-row').remove()">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mb-4">
                    <button type="button" class="btn btn-primary" onclick="agregarZona()" style="margin-top: 20px">
                        <i class="fas fa-plus"></i> Agregar otra zona
                    </button>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Registrar Zonas
                    </button>
                    <button type="button" class="btn btn-outline" onclick="volverPaso2()"> <!-- Quitar ml-3 -->
                        <i class="fas fa-arrow-left"></i> Volver
                    </button>
                </div>
            </div>

        <!-- Campos ocultos -->
        <input type="hidden" name="agregar_zonas" id="agregar_zonas" value="0">
    </form>
</div>

<script>
    // TU JS ORIGINAL (previewImage) se mantiene
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const fileLabel = document.querySelector('.file-upload-label');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" alt="Vista previa" style="max-height:200px;">`;
                fileLabel.innerHTML = '<i class="fas fa-check"></i> Archivo Seleccionado';
                fileLabel.style.background = '#28a745';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // NUEVO JS: pasos + zonas dinámicas
    let indiceZona = 1;
    function irAZonas() { cambiarPaso('paso2'); }
    function volverPaso2() { cambiarPaso('paso2'); }
    function cambiarPaso(id) {
        document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
        document.getElementById(id).classList.add('active');
    }

    function mostrarZonas(si) {
        document.getElementById('agregar_zonas').value = si ? '1' : '0';
        if (!si) return document.getElementById('formPrincipal').submit();
        cambiarPaso('paso3');
    }
    let indice = 1;
    function agregarZona() {
        const html = `
            <div class="zona-row card border-primary mb-3">
                <div class="card-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Nombre de la zona *</label>
                            <input type="text" name="zonas[${indice}][nombre]" 
                                class="form-control" placeholder="Ej. Oro, Plata, Bronce" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Precio por m² *</label>
                            <div class="input-with-icon">
                                <i class="fas fa-dollar-sign"></i>
                                <input type="number" step="0.01" name="zonas[${indice}][precio_m2]" 
                                    class="form-control" placeholder="1800.00" required>
                            </div>
                        </div>
                        <div class="form-group d-flex align-items-end">
                            <button type="button" class="btn btn-danger" 
                                    onclick="this.closest('.zona-row').remove()" style="color: white">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>`;
        document.getElementById('contenedorZonas').insertAdjacentHTML('beforeend', html);
        indice++;
    }
</script>
@endsection