<!-- Modal -->
<div class="modal fade" id="crearMerchandiseModal" tabindex="-1" aria-labelledby="crearMerchandiseLabel" aria-hidden="true">

  <div class="modal-dialog modal-lg"> <!-- Puedes cambiar modal-lg por modal-md o modal-sm -->
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title" id="crearMerchandiseLabel">Crear Merchandise</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('merchandise.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" id="nombre" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control" id="descripcion"></textarea>
            </div>
            <div class="mb-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" name="precio" class="form-control" id="precio" min="1" step="0.0001" required>
            </div>

            <div class="modal-footer">
              <button type="submit" class="btn btn_crear">Guardar</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
