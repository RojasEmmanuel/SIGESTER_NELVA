<div class="modal" id="modalImportarLotes">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h3><span class="material-icons">upload_file</span> Importar Lotes desde CSV</h3>
            <button class="close-btn" onclick="closeModal('modalImportarLotes')">×</button>
        </div>

        {{-- AGREGAR action AL FORM --}}
        <form id="formImportarLotes" 
              action="{{ route('ing.lotes.importar', $fraccionamiento->id_fraccionamiento) }}" 
              method="POST" 
              enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id_fraccionamiento" value="{{ $fraccionamiento->id_fraccionamiento }}">

            <div class="modal-body">
                <!-- ALERTA INFORMATIVA -->
                <div class="alert alert-info">
                    <strong>Sube un archivo CSV</strong> con las columnas exactas:<br>
                    <code>numerolote,manzana,area_metros,norte,sur,oriente,poniente</code>
                </div>

                <!-- INPUT ARCHIVO ESTILIZADO -->
                <div class="file-input-container mb-4">
                    <label class="form-label">
                        Archivo CSV <span class="req">*</span>
                    </label>
                    
                    <div class="file-input-wrapper">
                        <div class="file-input-display" id="fileDisplay">
                            Ningún archivo seleccionado
                        </div>
                        <button type="button" class="file-input-btn" onclick="document.getElementById('csvFile').click()">
                            <span class="material-icons">folder_open</span>
                            Seleccionar
                        </button>
                        <input type="file" 
                               name="csv_file" 
                               id="csvFile"
                               accept=".csv,text/csv" 
                               required 
                               class="file-input-hidden"
                               onchange="updateFileDisplay(this)">
                    </div>
                    
                    <div class="file-info">
                        <span class="material-icons">info</span>
                        Máximo 2MB | Separador: coma (,) | Decimales con punto: <code>211.9</code>
                    </div>
                </div>

                <!-- TABLA EJEMPLO -->
                <div class="bg-light p-3 rounded mb-3">
                    <p class="fw-bold mb-2">Formato esperado:</p>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered" style="font-size:0.85rem;">
                            <thead class="table-light">
                                <tr>
                                    <th>numerolote</th>
                                    <th>manzana</th>
                                    <th>area_metros</th>
                                    <th>norte</th>
                                    <th>sur</th>
                                    <th>oriente</th>
                                    <th>poniente</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <tr>
                                    <td>26</td>
                                    <td>A</td>
                                    <td>211.9</td>
                                    <td>15.5</td>
                                    <td>15.5</td>
                                    <td>14.2</td>
                                    <td>14.2</td>
                                </tr>
                                <tr>
                                    <td>27</td>
                                    <td>A</td>
                                    <td>215.3</td>
                                    <td>15.5</td>
                                    <td>15.5</td>
                                    <td>14.3</td>
                                    <td>14.3</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- BOTÓN DESCARGAR EJEMPLO (CORREGIDO) -->
                <div class="text-center mb-3">
                    <a href="{{ route('ing.lotes.csv.example', $fraccionamiento->id_fraccionamiento) }}" 
                       class="btn btn-outline ripple d-inline-flex align-items-center gap-2"
                       download>
                        <span class="material-icons">file_download</span>
                        Descargar archivo de ejemplo
                    </a>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalImportarLotes')">
                    Cancelar
                </button>
                <button type="submit" 
                        class="btn btn-success ripple d-inline-flex align-items-center gap-2" 
                        id="btnImportarCsv">
                    <span class="material-icons" id="btnIcon">upload</span>
                    <span id="btnText">Importar CSV</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- SCRIPT LOCAL --}}
<script>
function updateFileDisplay(input) {
    const display = document.getElementById('fileDisplay');
    if (input.files && input.files[0]) {
        display.textContent = input.files[0].name;
        display.classList.add('has-file');
    } else {
        display.textContent = 'Ningún archivo seleccionado';
        display.classList.remove('has-file');
    }
}
</script>