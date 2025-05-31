@extends('layouts.app')

@section('content')
  <div class="form-check mb-3">
        <h1 class="text-center"><a class="float-start" title="Volver" href="{{ route('producto_final.index') }}">
        <i class="bi bi-arrow-left-circle"></i></a>
        Editar {{ $producto->nombre }}</h1>
    </div>
<form method="POST" action="{{ route('producto_final.update', $producto->id) }}">
    @csrf
    @method('PUT')

    <div class="row">
        <!-- Columna izquierda -->
        <div class="col-md-6">
            <div class="mb-3">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" name="nombre" value="{{ old('nombre', $producto->articulo->nombre) }}" required>
                @error('nombre')
                    <div class="text-success">
                        <i class="fa-solid fa-triangle-exclamation"></i> {{ $message }}
                    </div>
                @enderror
            </div>

               <div class="mb-3">
                        <label for="clasificacion_id">Clasificación</label>
                        <select class="form-control select2-clasificacion" name="clasificacion_id" id="clasificacion_id" required>
                            <option value="">-- Seleccionar Clasificación --</option>
                            @foreach($clasificaciones as $c)
                                <option value="{{ $c->id }}"
                                        data-unidad="{{ $c->unidadMedida->nombre_unidad_de_medida ?? '' }}"
                                        data-unidad-id="{{ $c->unidadMedida->id ?? '' }}">
                                    {{ $c->nombre_clasificacion }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="unidad_medida">Unidad de Medida</label>
                        <input type="text" class="form-control" id="unidad_medida" readonly>
                        <input type="hidden" name="unidad_de_medida_id" id="unidad_de_medida_id" value="">
                    </div>

            <div class="mb-3">
                <label for="volumen_id">Volumen</label>
                <select class="form-control" name="volumen_id" id="volumen_id" required>
                    <option value="">-- Seleccionar Volumen --</option>
                    @foreach($volumenesAgrupados[$producto->clasificacion_id] ?? [] as $volumen)
                        <option value="{{ $volumen->id }}" {{ $producto->volumen_id == $volumen->id ? 'selected' : '' }}>
                            {{ $volumen->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                @if($producto->articulo->estado === 'inactivo')
                <label for="estado" class="form-label">Estado del producto</label><br>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="estado" id="estado"
                            value="activo" {{ $producto->articulo->estado === 'activo' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="estado">Activo</label>
                    </div>
                @endif
            </div>


            @if($errors->has('llenar'))
                <div class="alert alert-danger">
                    {{ $errors->first('llenar') }}
                </div>
            @endif
        </div>

        <!-- Columna derecha: insumos y base -->
        <div class="col-md-6">
            <h5><label>Agregar Insumos</label></h5>

            <div class="row mb-2">
                <div class="col-7">
                    <select id="insumoSelect" class="form-control select2-insumo">
                        <option value="">-- Seleccionar insumo --</option>
                        @foreach($insumos as $insumo)
                            <option value="{{ $insumo->id }}"
                                    data-nombre="{{ $insumo->articulo->nombre }}"
                                    data-precio="{{ $insumo->precio }}">
                                {{ $insumo->articulo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3">
                    <input type="number" id="insumoCantidad" min="1" class="form-control" placeholder="Cantidad" step="any">
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn_crear w-100" id="agregarInsumo"><i class="fa-solid fa-circle-plus"></i></button>
                </div>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Insumo</th>
                        <th>Cantidad</th>
                        <th>Precio (S/)</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="tablaInsumos">
                    @foreach($producto->insumos as $insumo)
                        <tr data-id="{{ $insumo->id }}">
                            <td>{{ $insumo->articulo->nombre }}</td>
                            <td>{{ $insumo->pivot->cantidad }}</td>
                            <td>S/ {{ $insumo->precio }}</td>
                            <td><button type="button" class="btn btn-danger btn-sm eliminarInsumo">×</button></td>
                            <input type="hidden" name="insumos[{{ $insumo->id }}][cantidad]" value="{{ $insumo->pivot->cantidad }}">
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div id="seccionbase">
                <h5><label>Agregar base</label></h5>

                <div class="row mb-2">
                    <div class="col-7">
                        <select id="baseSelect" class="form-control select2-base">
                            <option value="">-- Seleccionar base --</option>
                            @foreach($bases as $base)
                                <option value="{{ $base->id }}"
                                        data-nombre="{{ $base->articulo->nombre }}"
                                        data-precio="{{ $base->precio }}">
                                    {{ $base->articulo->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        <input type="number" min="1" id="baseCantidad" class="form-control" placeholder="Cantidad" step="any">
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn_crear w-100" id="agregarBase"><i class="fa-solid fa-circle-plus"></i></button>
                    </div>
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Base</th>
                            <th>Cantidad</th>
                            <th>Precio (S/)</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="tablabase">
                        @foreach($producto->bases as $base)
                            <tr data-id="{{ $base->id }}">
                                <td>{{ $base->articulo->nombre }}</td>
                                <td>{{ $base->pivot->cantidad }}</td>
                                <td>S/ {{ $base->precio }}</td>
                                <td><button type="button" class="btn btn-danger btn-sm eliminarBase">×</button></td>
                                <input type="hidden" name="bases[{{ $base->id }}][cantidad]" value="{{ $base->pivot->cantidad }}">
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="text-end mt-2">
                    <h5>Precio Total de Producción del producto final:
                        <span id="precioTotal" class="text-success">
                            S/ {{ number_format($producto->costo_total_produccion, 2) }}
                        </span>
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="costo_total_produccion" id="costo_total_produccion" value="{{ $producto->costo_total_produccion }}">
    <input type="hidden" name="costo_total_real" id="costo_total_real" value="{{ $producto->costo_total_real }}">
    <button type="submit" class="btn btn_crear mt-3">Actualizar Producto Final</button>
</form>
 <script>
       const volumenesPorClasificacion = @json($volumenesAgrupados);

$('#clasificacion_id').on('change', function () {
    const clasificacionId = this.value;
    const $volumenSelect = $('#volumen_id');
    const $unidadInput = $('#unidad_medida');
    const selectedOption = $(this).find('option:selected');

    // Actualizar unidad de medida
    $unidadInput.val(selectedOption.data('unidad') || '');
    $('#unidad_de_medida_id').val(selectedOption.data('unidad-id'));

    // Limpiar y cargar volúmenes
    $volumenSelect.empty().append('<option value="">-- Seleccionar Volumen --</option>');

    if (!clasificacionId || !volumenesPorClasificacion[clasificacionId]) {
        $volumenSelect.append('<option value="">-- No hay volúmenes disponibles --</option>');
        return;
    }

    // Agregar volúmenes al select
    volumenesPorClasificacion[clasificacionId].forEach(vol => {
        $volumenSelect.append(new Option(vol.nombre, vol.id));
    });

    // Si solo hay un volumen, seleccionarlo automáticamente
    if (volumenesPorClasificacion[clasificacionId].length === 1) {
        $volumenSelect.val(volumenesPorClasificacion[clasificacionId][0].id);
    }
});

    </script>
<script>
$(document).ready(function () {
        $('.select2-clasificacion, .select2-insumo, .select2-base').select2({allowClear: true,
                width: '100%'});

        const insumosSeleccionados = [];
        const basesSeleccionadas = [];

        function actualizarTablaInsumos() {
            const tbody = $('#tablaInsumos');
            tbody.empty();

            let subtotal = 0;

            insumosSeleccionados.forEach((insumo, index) => {
                const total = insumo.precio * insumo.cantidad;
                subtotal += total;

                tbody.append(`
                    <tr>
                        <td>
                            ${insumo.nombre}
                            <input type="hidden" name="insumos[${insumo.id}][id]" value="${insumo.id}">
                        </td>
                        <td>
                            ${insumo.cantidad}
                            <input type="hidden" name="insumos[${insumo.id}][cantidad]" value="${insumo.cantidad}">
                        </td>
                        <td>S/ ${total.toFixed(2)}</td>
                        <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarInsumo(${index})">X</button></td>
                    </tr>
                `);
            });

            $('#subtotalInsumosTexto').text(`S/ ${subtotal.toFixed(2)}`);
            $('#subtotalInsumosPrebase').toggle(subtotal > 0);

            calcularTotalFinal();
        }

        function actualizarTablaBases() {
            const tbody = $('#tablabase');
            tbody.empty();

            let subtotal = 0;

            basesSeleccionadas.forEach((base, index) => {
                const total = base.precio * base.cantidad;
                subtotal += total;

                tbody.append(`
                    <tr>
                        <td>
                            ${base.nombre}
                            <input type="hidden" name="bases[${base.id}][id]" value="${base.id}">
                        </td>
                        <td>
                            ${base.cantidad}
                            <input type="hidden" name="bases[${base.id}][cantidad]" value="${base.cantidad}">
                        </td>
                        <td>S/ ${total.toFixed(2)}</td>
                        <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarBase(${index})">X</button></td>
                    </tr>
                `);
            });

            calcularTotalFinal();
        }

        function calcularTotalFinal() {
        let insumoSubtotal = 0;
        let baseSubtotal = 0;

        // Sumar insumos de la tabla
        $('#tablaInsumos tr').each(function () {
            const cantidad = parseFloat($(this).find('td').eq(1).text()) || 0;
            const precioTexto = $(this).find('td').eq(2).text().replace('S/', '').trim();
            const precio = parseFloat(precioTexto) || 0;
            insumoSubtotal += cantidad * precio;
        });

        // Sumar bases de la tabla
        $('#tablabase tr').each(function () {
            const cantidad = parseFloat($(this).find('td').eq(1).text()) || 0;
            const precioTexto = $(this).find('td').eq(2).text().replace('S/', '').trim();
            const precio = parseFloat(precioTexto) || 0;
            baseSubtotal += cantidad * precio;
        });

        const total = insumoSubtotal + baseSubtotal;

        $('#precioTotal').text(`S/ ${total.toFixed(2)}`);
        $('#costo_total_produccion').val(total.toFixed(2));
        $('#costo_total_real').val((total * 1.18).toFixed(2));
    }


        $('#agregarInsumo').on('click', function () {
        const selected = $('#insumoSelect option:selected');
        const id = selected.val();
        const nombre = selected.data('nombre');
        const precio = parseFloat(selected.data('precio'));
        const cantidadNueva = parseFloat($('#insumoCantidad').val());

        if (!id || isNaN(cantidadNueva) || cantidadNueva <= 0) {
            alert('Selecciona un insumo válido y una cantidad mayor a 0');
            return;
        }

        const filaExistente = $('#tablaInsumos tr[data-id="' + id + '"]');

        if (filaExistente.length) {
            // Incrementar cantidad
            const inputCantidad = filaExistente.find('input[name="insumos[' + id + '][cantidad]"]');
            const cantidadActual = parseFloat(inputCantidad.val());
            const nuevaCantidad = cantidadActual + cantidadNueva;

            inputCantidad.val(nuevaCantidad);
            filaExistente.find('td').eq(1).text(nuevaCantidad);
        } else {
            // Agregar nueva fila
            $('#tablaInsumos').append(`
                <tr data-id="${id}">
                    <td>${nombre}</td>
                    <td>${cantidadNueva}</td>
                    <td>S/ ${precio.toFixed(2)}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm eliminarInsumo">×</button>
                    </td>
                    <input type="hidden" name="insumos[${id}][cantidad]" value="${cantidadNueva}">
                </tr>
            `);
        }

        $('#insumoSelect').val(null).trigger('change');
        $('#insumoCantidad').val('');
        calcularTotalFinal();
    });


    $('#agregarBase').on('click', function () {
        const selected = $('#baseSelect option:selected');
        const id = selected.val();
        const nombre = selected.data('nombre');
        const precio = parseFloat(selected.data('precio'));
        const cantidadNueva = parseFloat($('#baseCantidad').val());

        if (!id || isNaN(cantidadNueva) || cantidadNueva <= 0) {
            alert('Selecciona una base válida y una cantidad mayor a 0');
            return;
        }

        const filaExistente = $('#tablabase tr[data-id="' + id + '"]');

        if (filaExistente.length) {
            const inputCantidad = filaExistente.find('input[name="bases[' + id + '][cantidad]"]');
            const cantidadActual = parseFloat(inputCantidad.val());
            const nuevaCantidad = cantidadActual + cantidadNueva;

            inputCantidad.val(nuevaCantidad);
            filaExistente.find('td').eq(1).text(nuevaCantidad);
        } else {
            $('#tablabase').append(`
                <tr data-id="${id}">
                    <td>${nombre}</td>
                    <td>${cantidadNueva}</td>
                    <td>S/ ${precio.toFixed(2)}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm eliminarBase">×</button>
                    </td>
                    <input type="hidden" name="bases[${id}][cantidad]" value="${cantidadNueva}">
                </tr>
            `);
        }

        $('#baseSelect').val(null).trigger('change');
        $('#baseCantidad').val('');
        calcularTotalFinal();
    });

    $(document).on('click', '.eliminarInsumo', function () {
        $(this).closest('tr').remove();
        calcularTotalFinal();
    });

    $(document).on('click', '.eliminarBase', function () {
        $(this).closest('tr').remove();
        calcularTotalFinal();
    });

});
</script>
@endsection