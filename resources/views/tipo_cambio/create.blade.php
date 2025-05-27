<div class="modal fade" id="crearTipoCambioModal" tabindex="-1" aria-labelledby="crearTipoCambioLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('tipo_cambio.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h1 class="modal-title" id="crearTipoCambioLabel">Actualizar</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          @if ($errors->any())
              <div class="alert alert-danger">
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
          @endif

          <div class="mb-3">
            <label for="tipo_moneda_id" class="form-label">Moneda</label>
            <select name="tipo_moneda_id" id="tipo_moneda_id" class="form-select" required>
              <option value="">-- Selecciona una moneda --</option>
              @foreach ($monedas as $moneda)
                  <option value="{{ $moneda->id }}">{{ $moneda->nombre }} ({{ $moneda->codigo_iso }})</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label for="valor_cambio" class="form-label">Valor de cambio</label>
            <input type="number" min="1" step="0.0001" name="valor_cambio" id="valor_cambio" class="form-control" required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn_crear">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>
