<!-- Modal -->
<div class="modal fade" id="crearUtilModal" tabindex="-1" aria-labelledby="crearUtilLabel" aria-hidden="true">

  <div class="modal-dialog modal-lg"> 
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title" id="crearUtilLabel">Crear Util</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('util.store') }}" method="POST">
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
                <label for="precio" class="form-label">Precio Unitario</label>
                <input type="number" name="precio" class="form-control" id="precio" min="1" step="0.01" required>
            </div>

            <div class="modal-footer">
              <button type="submit" class="btn btn_crear">Guardar</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
