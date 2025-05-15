@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Crear Producto Final</h2>
    <form action="{{ route('productos-finales.store') }}" method="POST">
        @csrf
        <div class="row">
            <!-- Columna Izquierda -->
            <div class="col-md-6">
                <!-- Nombre -->
                <div class="form-group">
                    <label for="nombre">Nombre del Producto</label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

               <!-- Clasificación -->
                <div class="form-group">
                    <label for="clasificacion_id">FORMA FARMACEUTICA</label>
                    <select class="form-control select2 @error('clasificacion_id') is-invalid @enderror" id="clasificacion_id" name="clasificacion_id" required>
                        <option value="">Seleccionar Clasificación</option>
                        @foreach($clasificaciones as $clasificacion)
                            <option value="{{ $clasificacion->id }}" {{ old('clasificacion_id') == $clasificacion->id ? 'selected' : '' }}>{{ $clasificacion->nombre_clasificacion }}</option>
                        @endforeach
                    </select>
                    @error('clasificacion_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Unidad de medida -->
                <div class="form-group">
                    <label for="unidad_de_medida_id">Unidad de Medida</label>
                    <select class="form-control @error('unidad_de_medida_id') is-invalid @enderror" id="unidad_de_medida_id" name="unidad_de_medida_id" required>
                        <option value="">Seleccionar Unidad de Medida</option>
                        @foreach($unidadMedida as $unidad)
                            <option value="{{ $unidad->id }}" {{ old('unidad_de_medida_id') == $unidad->id ? 'selected' : '' }}>{{ $unidad->nombre_unidad_de_medida }}</option>
                        @endforeach
                    </select>
                    @error('unidad_de_medida_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Columna Derecha -->
            <div class="col-md-6">
                <!-- Selector tipo -->
                <div class="form-group">
                    <label for="tipoSelector">Agregar Componente</label>
                    <select class="form-control" id="tipoSelector">
                        <option value="">-- Seleccionar Tipo --</option>
                        <option value="insumos">Insumo</option>
                        <option value="bases">Base</option>
                    </select>
                </div>

                <!-- Selector dinámico (este tendrá Select2) -->
                <div class="form-group" id="selectorElemento" style="display: none;">
                    <label for="elemento_id">Seleccionar</label>
                    <select class="form-control select2" id="elemento_id">
                        <!-- Opciones se llenarán con JS -->
                    </select>
                </div>

                <!-- Tabla de componentes agregados -->
                <div class="form-group">
                    <label>Componentes Seleccionados</label>
                    <table class="table table-bordered" id="tabla-componentes">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Cantidad</th>
                                <th>Tipo</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Guardar Producto Final</button>
    </form>
</div>

<!-- Incluir CDN de Select2 (solo necesario para el selector dinámico) -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Inicializar solo el select2 que necesitamos
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });

        const insumos = @json($insumos);
        const bases = @json($bases->flatten());
        const tipoSelector = $('#tipoSelector');
        const elementoSelect = $('#elemento_id');
        const selectorElemento = $('#selectorElemento');
        const tablaComponentes = $('#tabla-componentes tbody');

        tipoSelector.on('change', function() {
            const tipo = $(this).val();
            selectorElemento.toggle(!!tipo);
            elementoSelect.empty().trigger('change'); // Limpiar y notificar a Select2

            const lista = tipo === 'insumos' ? insumos : bases;
            lista.forEach(item => {
                const option = new Option(item.nombre, item.id);
                elementoSelect.append(option);
            });

            // Actualizar Select2 con las nuevas opciones
            elementoSelect.trigger('change');
        });

        elementoSelect.on('change', function() {
            const tipo = tipoSelector.val();
            const selectedId = $(this).val();
            const selectedText = $(this).find('option:selected').text();

            if (!selectedId) return;

            // Evitar duplicados
            if ($(`[data-id="${tipo}-${selectedId}"]`).length > 0) return;

            const row = `
                <tr data-id="${tipo}-${selectedId}">
                    <td>
                        <input type="hidden" name="${tipo}[${selectedId}][id]" value="${selectedId}">
                        ${selectedText}
                    </td>
                    <td>
                        <input type="number" name="${tipo}[${selectedId}][cantidad]" class="form-control" required min="0.01" step="any" value="1">
                    </td>
                    <td>${tipo}</td>
                    <td><button type="button" class="btn btn-danger btn-sm remove">X</button></td>
                </tr>
            `;
            tablaComponentes.append(row);

            // Reset select
            $(this).val(null).trigger('change');
        });

        $(document).on('click', '.remove', function() {
            $(this).closest('tr').remove();
        });
    });
</script>
@endsection