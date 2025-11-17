@extends('ingeniero.navbar')

@section('title', 'Lotes - ' . $fraccionamiento->nombre)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/lotes.css') }}">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@endpush

@section('content')
<div class="lotes-container">
    <!-- Header -->
    <div class="lotes-header">
        <h2 class="lotes-title">
            <span class="material-icons">map</span> Lotes de {{ $fraccionamiento->nombre }}
        </h2>
        <div class="lotes-actions">
            <button class="btn btn-outline ripple d-inline-flex align-items-center gap-2" 
                    onclick="openModal('modalImportarLotes')">
                <span class="material-icons">upload_file</span>
                Importar CSV
            </button>
            <button class="btn btn-primary" onclick="openModal('modalCrearLote')">
                <span class="material-icons">add_box</span> Nuevo Lote
            </button>
        </div>
    </div>

    <!-- Alerta de éxito -->
    @if(session('success'))
        <div class="alert alert-success">
            <span class="material-icons">check_circle</span>
            {{ session('success') }}
            <button class="alert-close" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    <!-- Búsqueda -->
    <div class="search-bar">
        <form method="GET" action="{{ route('ing.lotes.index', $fraccionamiento->id_fraccionamiento) }}" class="search-form">
            <input type="text" name="search" class="search-input" placeholder="Buscar por número de lote..." value="{{ request('search') }}">
            <button type="submit" class="search-btn">
                <span class="material-icons">search</span>
            </button>
            @if(request('search'))
                <a href="{{ route('ing.lotes.index', $fraccionamiento->id_fraccionamiento) }}" class="clear-btn">
                    <span class="material-icons">clear</span>
                </a>
            @endif
        </form>
    </div>

    <!-- Tabla -->
    <div class="table-container">
        <table class="lotes-table" id="tablaLotes">
            <thead>
                <tr>
                    <th style="width:40px">
                        <input type="checkbox" id="selectAll" class="checkbox">
                    </th>
                    <th data-sort="id_lote">ID Lote</th>
                    <th data-sort="numeroLote">Número</th>
                    <th data-sort="estatus">Estatus</th>
                    <th data-sort="manzana">Manzana</th>
                    <th data-sort="norte">Norte</th>
                    <th data-sort="sur">Sur</th>
                    <th data-sort="oriente">Oriente</th>
                    <th data-sort="poniente">Poniente</th>
                    <th data-sort="area_metros">Área (m²)</th>
                    <th style="width:100px">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lotes as $lote)
                    @php $med = $lote->loteMedida; @endphp
                    <tr data-lote="{{ $lote->id_lote }}">
                        <td><input type="checkbox" class="checkbox chk-lote" value="{{ $lote->id_lote }}"></td>
                        <td><strong>{{ $lote->id_lote }}</strong></td>
                        <td>{{ $lote->numeroLote }}</td>
                        <td>
                            <span class="badge badge-{{ $lote->estatus }}">{{ ucfirst($lote->estatus) }}</span>
                        </td>
                        <td>{{ $med?->manzana ?? '-' }}</td>
                        <td>{{ $med?->norte ?? '-' }}</td>
                        <td>{{ $med?->sur ?? '-' }}</td>
                        <td>{{ $med?->oriente ?? '-' }}</td>
                        <td>{{ $med?->poniente ?? '-' }}</td>
                        <td>{{ $med?->area_metros ?? '-' }}</td>
                        <td class="actions">
                            <button class="action-btn edit-btn"
                                    data-id="{{ $lote->id_lote }}"
                                    data-med="{{ json_encode($med) }}">
                                <span class="material-icons">edit</span>
                            </button>
                            <button class="action-btn delete-btn"
                                    data-id="{{ $lote->id_lote }}">
                                <span class="material-icons">delete</span>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="empty-state">
                            <span class="material-icons">map</span>
                            <p>No hay lotes registrados.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="pagination-info">
            <span>Mostrando {{ $lotes->firstItem() ?? 0 }} - {{ $lotes->lastItem() ?? 0 }} de {{ $lotes->total() }} lotes</span>
            <div class="pagination">
                {{ $lotes->links('pagination::simple-default') }}
            </div>
        </div>

        <!-- Footer -->
        <div class="table-footer">
            <button class="btn btn-danger" id="btnBulkDelete" disabled>
                <span class="material-icons">delete_sweep</span> Eliminar seleccionados
            </button>
            <span>{{ $lotes->total() }} lote(s) en total</span>
        </div>
    </div>
</div>

{{-- MODALES --}}
@include('ingeniero.lotes.crear')
@include('ingeniero.lotes.editar')
@include('ingeniero.lotes.coordenadas')
@include('ingeniero.lotes.confirm')
@include('ingeniero.lotes.importar')

@endsection

@push('scripts')
<!-- LEAFLET -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    @php
        // NORMALIZACIÓN CORRECTA - Mantener acentos y eliminar solo caracteres especiales no deseados
        $nombreNormalizado = mb_strtoupper(
            preg_replace('/[^\p{L}\p{N}\s\-_]/u', '', $fraccionamiento->nombre),
            'UTF-8'
        );
    @endphp

    const baseId = "{{ $nombreNormalizado }}"; // Ej: OCEÁNICA
</script>
<script>
// === JS COMPLETO UNIFICADO (ÚNICO DOMContentLoaded) ===
document.addEventListener('DOMContentLoaded', function () {
    const baseUrl = '{{ route("ing.lotes.index", $fraccionamiento->id_fraccionamiento) }}';
    const bulkDeleteUrl = '{{ route("ing.lotes.bulkDelete", $fraccionamiento->id_fraccionamiento) }}';
    
    // BASE ID CORREGIDA - Usar la variable PHP ya normalizada
    const baseId = "{{ $nombreNormalizado }}";

    // === MODALES ===
    window.openModal = (id) => {
        const modal = document.getElementById(id);
        if (modal) {
            modal.style.display = 'flex';
            document.body.classList.add('modal-open');
        }
    };

    window.closeModal = (id) => {
        const modal = document.getElementById(id);
        if (modal) {
            modal.style.display = 'none';
            document.body.classList.remove('modal-open');
        }
    };

    document.addEventListener('click', e => {
        if (e.target.classList.contains('modal')) {
            e.target.style.display = 'none';
            document.body.classList.remove('modal-open');
        }
    });

    // === TOAST ===
    window.showToast = (title, msg, type) => {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <div class="toast-icon">
                <span class="material-icons">${type === 'success' ? 'check_circle' : type === 'danger' ? 'error' : type === 'warning' ? 'warning' : 'info'}</span>
            </div>
            <div class="toast-content">
                <div class="toast-title">${title}</div>
                <div class="toast-message">${msg}</div>
            </div>
            <button class="toast-close" onclick="this.parentElement.remove()">×</button>
        `;
        document.body.appendChild(toast);
        setTimeout(() => {
            if (toast.parentElement) {
                toast.classList.add('hiding');
                setTimeout(() => toast.remove(), 300);
            }
        }, 5000);
    };

    // === ID AUTOMÁTICO + CREAR LOTE (CORREGIDO CON ACENTOS) ===
    const manzanaInput = document.getElementById('inputManzana');
    const numeroInput = document.getElementById('inputNumeroLote');
    const preview = document.getElementById('previewId');
    const hiddenId = document.getElementById('generatedId');
    const formCrear = document.getElementById('formCrearLote');

    if (manzanaInput && numeroInput && preview && hiddenId && formCrear) {
        const updateId = () => {
            const manzana = manzanaInput.value.trim().toUpperCase();
            const numero = numeroInput.value.trim();

            if (manzana && numero) {
                // Usa baseId que ahora incluye acentos correctamente
                const id_lote = `${baseId}-${manzana}-${numero}`;
                preview.textContent = id_lote;
                hiddenId.value = id_lote;
            } else {
                preview.textContent = '?';
                hiddenId.value = '';
            }
        };

        manzanaInput.addEventListener('input', updateId);
        numeroInput.addEventListener('input', updateId);
        updateId();

        formCrear.addEventListener('submit', function(e) {
            e.preventDefault();
            updateId();

            if (!hiddenId.value.trim()) {
                showToast('Error', 'Completa manzana y número de lote.', 'danger');
                return;
            }

            const data = new FormData(this);

            fetch("{{ route('ing.lotes.store', $fraccionamiento->id_fraccionamiento) }}", {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: data
            })
            .then(async r => {
                const contentType = r.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return await r.json();
                } else {
                    throw new Error('Respuesta no válida del servidor');
                }
            })
            .then(d => {
                if (d.success) {
                    closeModal('modalCrearLote');
                    showToast('Éxito', d.message, 'success');
                    setTimeout(() => location.reload(), 900);
                } else {
                    const msg = d.errors ? Object.values(d.errors).flat().join('<br>') : (d.message || 'Error al crear lote');
                    showToast('Error', msg, 'danger');
                }
            })
            .catch(err => {
                console.error('Error:', err);
                showToast('Error', err.message || 'Error de conexión', 'danger');
            });
        });
    }

    // === ORDENAMIENTO ===
    document.querySelectorAll('th[data-sort]').forEach(th => {
        th.classList.add('sortable');
        const col = th.dataset.sort;
        const current = '{{ $sortBy }}';
        const order = '{{ $sortOrder }}';

        if (current === col) {
            th.classList.add(order === 'asc' ? 'sort-asc' : 'sort-desc');
        }

        th.addEventListener('click', () => {
            const url = new URL(location);
            const newOrder = (current === col && order === 'asc') ? 'desc' : 'asc';
            url.searchParams.set('sort_by', col);
            url.searchParams.set('sort_order', newOrder);
            location = url;
        });
    });

    // === SELECCIÓN MÚLTIPLE ===
    const selectAll = document.getElementById('selectAll');
    const bulkBtn = document.getElementById('btnBulkDelete');

    selectAll?.addEventListener('change', () => {
        document.querySelectorAll('.chk-lote').forEach(c => c.checked = selectAll.checked);
        toggleBulk();
    });

    document.getElementById('tablaLotes')?.addEventListener('change', e => {
        if (e.target.classList.contains('chk-lote')) {
            const all = [...document.querySelectorAll('.chk-lote')].every(c => c.checked);
            selectAll.checked = all && document.querySelectorAll('.chk-lote').length > 0;
            toggleBulk();
        }
    });

    function toggleBulk() {
        if (bulkBtn) {
            bulkBtn.disabled = !document.querySelectorAll('.chk-lote:checked').length;
        }
    }

    // === ELIMINAR UNO ===
    document.getElementById('tablaLotes')?.addEventListener('click', function(e) {
        const btn = e.target.closest('.delete-btn');
        if (!btn) return;

        const id = btn.dataset.id;
        openConfirm('Eliminar lote', `¿Eliminar lote ${id}?`, () => {
            fetch(`${baseUrl}/${id}`, {
                method: 'DELETE',
                headers: { 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(async r => {
                const contentType = r.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return await r.json();
                }
                return { success: r.ok };
            })
            .then(d => {
                if (d.success) {
                    btn.closest('tr')?.remove();
                    showToast('Éxito', 'Lote eliminado correctamente', 'success');
                } else {
                    showToast('Error', d.message || 'No se pudo eliminar el lote', 'danger');
                }
            })
            .catch(err => {
                console.error('Error:', err);
                showToast('Error', 'Error de conexión', 'danger');
            });
        });
    });

    // === EDITAR ===
    document.getElementById('tablaLotes')?.addEventListener('click', function(e) {
        const btn = e.target.closest('.edit-btn');
        if (!btn) return;

        const id = btn.dataset.id;
        const med = JSON.parse(btn.dataset.med || '{}');

        const modal = document.getElementById('modalEditarMedidas');
        if (!modal) {
            showToast('Error', 'Modal de editar no encontrado', 'danger');
            return;
        }

        modal.querySelector('#edit_id_lote').value = id || '';
        modal.querySelector('#edit_manzana').value = med.manzana || '';
        modal.querySelector('#edit_norte').value = med.norte || '';
        modal.querySelector('#edit_sur').value = med.sur || '';
        modal.querySelector('#edit_oriente').value = med.oriente || '';
        modal.querySelector('#edit_poniente').value = med.poniente || '';
        modal.querySelector('#edit_area').value = med.area_metros || '';

        openModal('modalEditarMedidas');
    });

    // === FORMULARIO EDITAR (UPDATE MEDIDAS) ===
    document.getElementById('formUpdateMedidas')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = this.querySelector('#edit_id_lote').value;
        if (!id) {
            showToast('Error', 'ID de lote no especificado', 'danger');
            return;
        }

        const data = new FormData(this);

        fetch(`${baseUrl}/${id}`, {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: data
        })
        .then(async r => {
            const contentType = r.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return await r.json();
            }
            return { success: r.ok };
        })
        .then(d => {
            if (d.success) {
                showToast('Éxito', 'Medidas actualizadas correctamente', 'success');
                setTimeout(() => location.reload(), 700);
            } else {
                showToast('Error', d.message || 'No se pudieron actualizar las medidas', 'danger');
            }
        })
        .catch(err => {
            console.error('Error:', err);
            showToast('Error', 'Error de conexión', 'danger');
        });
    });

    // === ELIMINAR VARIOS (BULK) ===
    bulkBtn?.addEventListener('click', () => {
        const ids = [...document.querySelectorAll('.chk-lote:checked')].map(c => c.value);
        if (!ids.length) return;
        
        openConfirm(`Eliminar ${ids.length} lotes`, '¿Estás seguro de que deseas eliminar estos lotes? Esta acción no se puede deshacer.', () => {
            fetch(bulkDeleteUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ lotes: ids })
            })
            .then(async r => {
                const contentType = r.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return await r.json();
                }
                return { success: r.ok };
            })
            .then(d => {
                if (d.success) {
                    ids.forEach(id => document.querySelector(`tr[data-lote="${id}"]`)?.remove());
                    showToast('Éxito', `${ids.length} lotes eliminados correctamente`, 'success');
                    // Reset selección
                    if (selectAll) selectAll.checked = false;
                    toggleBulk();
                } else {
                    showToast('Error', d.message || 'No se pudieron eliminar los lotes', 'danger');
                }
            })
            .catch(err => {
                console.error('Error:', err);
                showToast('Error', 'Error de conexión', 'danger');
            });
        });
    });

    // === CONFIRMACIÓN ===
    window.openConfirm = (title, msg, cb) => {
        const confirmModal = document.getElementById('confirmModal');
        if (!confirmModal) {
            if (confirm(msg)) cb();
            return;
        }
        document.getElementById('confirmTitle').textContent = title;
        document.getElementById('confirmMessage').textContent = msg;
        window.confirmCallback = cb;
        openModal('confirmModal');
    };

    document.getElementById('btnCancel')?.addEventListener('click', () => closeModal('confirmModal'));
    document.getElementById('btnConfirm')?.addEventListener('click', () => {
        if (window.confirmCallback) window.confirmCallback();
        closeModal('confirmModal');
    });

    // === MAPA (LEAFLET) ===
    const mapEl = document.getElementById('map');
    if (mapEl && typeof L !== 'undefined') {
        const map = L.map(mapEl).setView([19.4326, -99.1332], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        let polygon = null;
        map.on('click', e => {
            if (!polygon) {
                polygon = L.polygon([e.latlng]).addTo(map);
            } else {
                polygon.addLatLng(e.latlng);
            }
        });
    }

}); // end DOMContentLoaded

// === IMPORTAR LOTES CSV (SIN LIBRERÍAS) ===
document.getElementById('formImportarLotes')?.addEventListener('submit', function(e) {
    e.preventDefault();

    const fileInput = document.getElementById('csvFile');
    const btn = document.getElementById('btnImportarCsv');
    const btnIcon = document.getElementById('btnIcon');
    const btnText = document.getElementById('btnText');

    if (!fileInput.files[0]) {
        showToast('Error', 'Selecciona un archivo CSV.', 'danger');
        return;
    }

    const file = fileInput.files[0];
    if (!file.name.toLowerCase().endsWith('.csv')) {
        showToast('Error', 'Solo archivos .csv', 'danger');
        return;
    }

    // Loading
    btn.disabled = true;
    btnIcon.textContent = 'hourglass_top';
    btnText.textContent = 'Procesando...';

    const formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(r => {
        if (!r.ok) throw r;
        return r.json();
    })
    .then(data => {
        if (data.success) {
            closeModal('modalImportarLotes');
            showToast('Éxito', data.message, 'success');
            setTimeout(() => location.reload(), 1200);
        } else {
            let msg = data.message || 'Error al importar.';
            if (data.errores) {
                msg += '<br><ul style="margin:8px 0;padding-left:20px;">';
                data.errores.forEach(e => msg += `<li>${e}</li>`);
                msg += '</ul>';
            }
            showToast('Error', msg, 'danger');
        }
    })
    .catch(async err => {
        let msg = 'Error de conexión.';
        try {
            const json = await err.json();
            msg = json.message || msg;
            if (json.errores) {
                msg += '<br><ul><li>' + json.errores.join('</li><li>') + '</li></ul>';
            }
        } catch {}
        showToast('Error', msg, 'danger');
    })
    .finally(() => {
        btn.disabled = false;
        btnIcon.textContent = 'upload';
        btnText.textContent = 'Importar CSV';
        fileInput.value = '';
        document.getElementById('fileDisplay').textContent = 'Ningún archivo seleccionado';
    });
});
</script>
@endpush