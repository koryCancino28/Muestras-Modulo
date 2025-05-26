<!-- Modal de edición -->
<div class="modal fade" id="editarVolumenModal" tabindex="-1" aria-labelledby="editarVolumenModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editarVolumenForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h1 class="modal-title" id="editarVolumenModalLabel">Editar Volumen</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="editar_clasificacion_id" class="form-label">Clasificación</label>
            <select name="clasificacion_id" id="editar_clasificacion_id" class="form-control" required>
              @foreach ($clasificaciones as $clasificacion)
                <option value="{{ $clasificacion->id }}">{{ $clasificacion->nombre_clasificacion }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="editar_nombre" class="form-label">Volumen</label>
            <input type="number" name="nombre" id="editar_nombre" class="form-control" min="1" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn_crear">Actualizar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Script para rellenar el formulario -->
<script>
  function abrirModalEditar(volumen) {
    const form = document.getElementById('editarVolumenForm');
    form.action = `/volumen/${volumen.id}`; // Asegúrate de usar la ruta correcta
    document.getElementById('editar_nombre').value = volumen.nombre;
    document.getElementById('editar_clasificacion_id').value = volumen.clasificacion_id;
    const modal = new bootstrap.Modal(document.getElementById('editarVolumenModal'));
    modal.show();
  }
</script>
