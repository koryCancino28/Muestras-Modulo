
<!-- Modal -->
<div class="modal fade" id="crearVolumenModal" tabindex="-1" aria-labelledby="crearVolumenModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('volumen.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h1 class="modal-title" id="crearVolumenModalLabel">Crear Volumen</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="clasificacion_id" class="form-label">Clasificación</label>
            <select name="clasificacion_id" class="form-control" required>
              @foreach ($clasificaciones as $clasificacion)
                <option value="{{ $clasificacion->id }}">{{ $clasificacion->nombre_clasificacion }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="nombre" class="form-label">Volumen (ej. 100, 250)</label>
            <input type="number" name="nombre" min="1" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn_crear">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>
