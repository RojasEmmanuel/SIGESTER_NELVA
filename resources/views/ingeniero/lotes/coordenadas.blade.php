<div class="modal" id="modalCoordenadas">
    <div class="modal-content modal-xl">
        <div class="modal-header">
            <h3><span class="material-icons">add_location_alt</span> Coordenadas</h3>
            <button class="close-btn" onclick="closeModal('modalCoordenadas')">×</button>
        </div>
        <div class="modal-body">
            <p class="text-muted">Haz clic en el mapa para dibujar el polígono.</p>
            <div id="map"></div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('modalCoordenadas')">Cerrar</button>
            <button class="btn btn-primary">Guardar</button>
        </div>
    </div>
</div>