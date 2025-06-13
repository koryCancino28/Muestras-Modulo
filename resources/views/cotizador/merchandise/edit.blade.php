<!-- Modal de edición de merchandise -->
<div class="modal fade" id="modalEditar{{ $item->articulo_id }}" tabindex="-1" aria-labelledby="modalLabel{{ $item->articulo_id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('merchandise.update', $item->articulo_id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h1 class="modal-title">Editar Merchandise</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre{{ $item->articulo_id }}" class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" id="nombre{{ $item->articulo_id }}"
                            value="{{ old('nombre', $item->articulo->nombre) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion{{ $item->articulo_id }}" class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" id="descripcion{{ $item->articulo_id }}" required>{{ old('descripcion', $item->articulo->descripcion) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="precio{{ $item->articulo_id }}" class="form-label">Precio</label>
                        <input type="number" name="precio" class="form-control" id="precio{{ $item->articulo_id }}"
                            value="{{ old('precio', $item->precio) }}" step="0.0001" min="1" required>
                    </div>

                    <div class="form-group mb-3">
                        @if($item->articulo->estado === 'inactivo')
                        <label for="estado" class="form-label">Estado del Merchandise</label><br>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="estado" id="estado"
                                    value="activo" {{ $item->articulo->estado === 'activo' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="estado">Activo</label>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn_crear">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
