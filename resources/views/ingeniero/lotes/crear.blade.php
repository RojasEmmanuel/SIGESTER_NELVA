<div class="modal" id="modalCrearLote">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h3><span class="material-icons">add_box</span> Crear Lote</h3>
            <button class="close-btn" onclick="closeModal('modalCrearLote')">×</button>
        </div>

        <form id="formCrearLote">
            @csrf

            <!-- IMPORTANTE: quitar required -->
            <input type="hidden" name="id_lote" id="generatedId">

            <div class="modal-body">
                <div class="form-grid">
                    <div>
                        <label>Manzana <span class="req">*</span></label>
                        <input type="text" name="manzana" id="inputManzana" maxlength="10" required>
                    </div>

                    <div>
                        <label>Número de Lote <span class="req">*</span></label>
                        <input type="text" name="numeroLote" id="inputNumeroLote" maxlength="50" required>
                    </div>
                </div>

                <div style="margin:20px">
                    <small class="text-muted" style="margin-bottom: 20px;">ID del lote (automático):</small>
                    <div class="font-monospace fw-bold text-primary" style="font-weight: 600;" id="previewId">?</div>
                </div>

                <hr>

                <div class="form-grid">
                    <div><label>Área (m²)</label><input type="text" step="0.01" name="area_metros"></div>
                    <div><label>Norte</label><input type="text" name="norte"></div>
                    <div><label>Sur</label><input type="text" name="sur"></div>
                    <div><label>Oriente</label><input type="text" name="oriente"></div>
                    <div><label>Poniente</label><input type="text" name="poniente"></div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('modalCrearLote')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Crear Lote</button>
            </div>
        </form>
    </div>
</div>
