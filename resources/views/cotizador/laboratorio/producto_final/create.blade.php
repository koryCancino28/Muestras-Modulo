 @extends('layouts.app')

@section('content')
 <!-- Incluir CSS de Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <div class="form-check mb-3">
            <h1 class="text-center"><a class="float-start text-secondary" title="Volver" href="{{ route('producto_final.index') }}">
            <i class="bi bi-arrow-left-circle"></i></a>
            Crear Producto Final</h1>
    </div>
        <form method="POST" action="{{ route('producto_final.store') }}">
            @csrf

            <div class="row">
                <!-- Columna izquierda: datos de la base -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" name="nombre" required>
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
                            <option value="">-- Selecciona una Clasificación primero --</option>
                        </select>
                        <div class="text-success" style="font-size: 0.7rem;">
                            <i class="fa-solid fa-triangle-exclamation"></i> Si no existe un volumen 
                            asociado a la clasificación registrar mediante el módulo "Volúmenes"
                        </div>
                    </div>
                        <!-- aviso para que llene todos los campos -->
                     @if($errors->has('llenar'))
                    <div class="alert alert-danger">
                        {{ $errors->first('llenar') }}
                    </div>
                    @endif
                </div>

                <!-- Columna derecha: insumos -->
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
                        <tbody id="tablaInsumos"></tbody>
                    </table>
                    <div id="subtotalInsumosPrebase" class="text-end mt-2 d-none">
                        <h6>Total de insumos: <span id="subtotalInsumosTexto" class="text-primary">S/ 0.00</span></h6>
                    </div>

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
                                <th>base</th>
                                <th>Cantidad</th>
                                <th>Precio (S/)</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody id="tablabase"></tbody>
                    </table>
                        
                        <div class="text-end mt-2">
                            <h5>Precio Total de Componentes: <span id="precioTotal" class="text-success">S/ 0.00</span></h5>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="costo_total_produccion" id="costo_total_produccion" value="0">
            <input type="hidden" name="costo_total_real" id="costo_total_real" value="0">
            <button type="submit" class="btn btn_crear mt-3">Guardar Producto Final</button>
        </form>
    </div>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
                const precio = insumo.precio;
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
                        <td>S/ ${precio.toFixed(2)}</td>
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
                const precio = base.precio;
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
                        <td>S/ ${precio.toFixed(2)}</td>
                        <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarBase(${index})">X</button></td>
                    </tr>
                `);
            });

            calcularTotalFinal();
        }

        function calcularTotalFinal() {
            const insumoSubtotal = insumosSeleccionados.reduce((sum, i) => sum + (i.precio * i.cantidad), 0);
            const baseSubtotal = basesSeleccionadas.reduce((sum, b) => sum + (b.precio * b.cantidad), 0);
            const total = insumoSubtotal + baseSubtotal;

            $('#precioTotal').text(`S/ ${total.toFixed(2)}`);
            // Actualiza los campos ocultos
            $('#costo_total_produccion').val(total.toFixed(2)); 
            $('#costo_total_real').val((total * 1.18).toFixed(2));
        }

        $('#agregarInsumo').on('click', function () {
            const selected = $('#insumoSelect option:selected');
            const id = parseInt(selected.val());
            const nombre = selected.data('nombre');
            const precio = parseFloat(selected.data('precio'));
            const cantidad = parseFloat($('#insumoCantidad').val());

            if (!id || isNaN(cantidad) || cantidad <= 0) {
                alert('Selecciona un insumo válido y una cantidad mayor a 0');
                return;
            }

            const existente = insumosSeleccionados.find(insumo => insumo.id === id);
            if (existente) {
                existente.cantidad += cantidad;
            } else {
                insumosSeleccionados.push({ id, nombre, precio, cantidad });
            }

            $('#insumoSelect').val(null).trigger('change');
            $('#insumoCantidad').val('');
            actualizarTablaInsumos();
        });

        $('#agregarBase').on('click', function () {
            const selected = $('#baseSelect option:selected');
            const id = parseInt(selected.val());
            const nombre = selected.data('nombre');
            const precio = parseFloat(selected.data('precio'));
            const cantidad = parseFloat($('#baseCantidad').val());

            if (!id || isNaN(cantidad) || cantidad <= 0) {
                alert('Selecciona una base válida y una cantidad mayor a 0');
                return;
            }

            const existente = basesSeleccionadas.find(base => base.id === id);
            if (existente) {
                existente.cantidad += cantidad;
            } else {
                basesSeleccionadas.push({ id, nombre, precio, cantidad });
            }

            $('#baseSelect').val(null).trigger('change');
            $('#baseCantidad').val('');
            actualizarTablaBases();
        });

        window.eliminarInsumo = function (index) {
            insumosSeleccionados.splice(index, 1);
            actualizarTablaInsumos();
        }

        window.eliminarBase = function (index) {
            basesSeleccionadas.splice(index, 1);
            actualizarTablaBases();
        }
    });
</script>

 @endsection