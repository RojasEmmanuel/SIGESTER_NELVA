<div class="modal" id="modalEditarMedidas">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h3><span class="material-icons">edit</span> Editar Medidas</h3>
            <button class="close-btn" onclick="closeModal('modalEditarMedidas')">×</button>
        </div>
        <form id="formUpdateMedidas">
            @csrf @method('PUT')
            <input type="hidden" id="edit_id_lote" name="id_lote">
            <div class="modal-body">
                <div class="form-grid">
                    <div><label>Manzana</label><input type="text" id="edit_manzana" name="manzana"></div>
                    <div><label>Área (m²)</label><input type="number" step="0.01" id="edit_area" name="area_metros"></div>
                    <div><label>Norte</label><input type="number" id="edit_norte" name="norte"></div>
                    <div><label>Sur</label><input type="number" id="edit_sur" name="sur"></div>
                    <div><label>Oriente</label><input type="number" id="edit_oriente" name="oriente"></div>
                    <div><label>Poniente</label><input type="number" id="edit_poniente" name="poniente"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditarMedidas')">Cancelar</button>
                <button type="submit" class="btn btn-success">Guardar</button>
            </div>
        </form>
    </div>
</div>