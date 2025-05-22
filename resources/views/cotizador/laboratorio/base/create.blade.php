@extends('layouts.app')

@section('content')
<div class="container">
    
<div class="form-check mb-3">
    <input type="checkbox" class="form-check-input" id="toggle-producto_final" name="agregar_producto_final">
    <label class="form-check-label" for="toggle-producto_final">Agregar PRODUCTO FINAL</label>
</div>

    <div id="formulario-normal">
        <div class="form-check mb-3">
            <h1 class="text-center"><a class="float-start" title="Volver" href="{{ route('bases.index') }}">
            <i class="bi bi-arrow-left-circle"></i></a>
            Crear Formulación</h1>
        </div>
    <!-- Incluir CSS de Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <form method="POST" action="{{ route('bases.store') }}">
            @csrf

            <div class="row">
                <!-- Columna izquierda: datos de la base -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>

                    <div class="mb-3">
                        <label for="clasificacion_id">Clasificación</label>
                        <select class="form-control select2-clasificacion" name="clasificacion_id" id="clasificacion_id" required>
                            <option value="">-- Seleccionar Clasificación --</option>
                            @foreach($clasificaciones as $c)
                                <option value="{{ $c->id }}"
                                        data-unidad="{{ $c->unidadMedida->nombre_unidad_de_medida ?? '' }}">
                                    {{ $c->nombre_clasificacion }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="unidad_medida">Unidad de Medida</label>
                        <input type="text" class="form-control" id="unidad_medida" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="volumen_id">Volumen</label>
                        <select class="form-control" name="volumen_id" id="volumen_id" >
                            <option value="">-- Selecciona una Clasificación primero --</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="cantidad">Stock de la base</label>
                        <input type="number" step="any" min="1" class="form-control" name="cantidad" required>
                    </div>
                     @if($errors->has('llenar'))
                    <div class="alert alert-danger">
                        {{ $errors->first('llenar') }}
                    </div>
                    @endif
                </div>

                <!-- Columna derecha: insumos -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tipoBase">Tipo de Base</label>
                        <select class="form-control" name="tipo" id="tipoBase" required>
                            <option value="final" selected>Base</option>
                            <option value="prebase">Prebase</option>
                        </select>
                    </div>
               
                    <h5><label>Agregar Insumos</label></h5>

                    <div class="row mb-2">
                        <div class="col-7">
                            <select id="insumoSelect" class="form-control select2-insumo">
                                <option value="">-- Seleccionar insumo --</option>
                                @foreach($insumos as $insumo)
                                    <option value="{{ $insumo->id }}"
                                            data-nombre="{{ $insumo->nombre }}"
                                            data-precio="{{ $insumo->precio }}">
                                        {{ $insumo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3">
                            <input type="number" id="insumoCantidad" min="1" class="form-control" placeholder="Cantidad" step="any">
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-primary w-100" id="agregarInsumo">+</button>
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

                    <div id="seccionEmpaques">
                         <h5><label>Agregar Prebases</label></h5>

                    <div class="row mb-2">
                        <div class="col-7">
                            <select id="prebaseSelect" class="form-control select2-prebase">
                                <option value="">-- Seleccionar Prebase --</option>
                                @foreach($prebases as $prebase)
                                    <option value="{{ $prebase->id }}"
                                            data-nombre="{{ $prebase->nombre }}"
                                            data-precio="{{ $prebase->precio }}">
                                        {{ $prebase->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3">
                            <input type="number" min="1" id="prebaseCantidad" class="form-control" placeholder="Cantidad" step="any">
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-primary w-100" id="agregarPrebase">+</button>
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Prebase</th>
                                <th>Cantidad</th>
                                <th>Precio (S/)</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody id="tablaPrebases"></tbody>
                    </table>
                        <div class="mb-3">
                            <label for="empaqueTipo">Tipo de Empaque</label>
                            <select id="empaqueTipo" class="form-control">
                                <option value="">-- Seleccionar Tipo de Empaque --</option>
                                <option value="material">Material</option>
                                <option value="envase">Envase</option>
                            </select>
                        </div>

                        <div class="row mb-2">
                            <div class="col-7">
                                <select id="empaqueSelect" class="form-control select2-empaque">
                                    <option value="">-- Seleccionar Empaque --</option>
                                    @foreach($empaques as $empaque)
                                        <option value="{{ $empaque->id }}"
                                                data-nombre="{{ $empaque->nombre }}"
                                                data-precio="{{ $empaque->costo }}"
                                                data-tipo="{{ $empaque->tipo }}">
                                            {{ $empaque->nombre }} ({{ ucfirst($empaque->tipo) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <input type="number" min="1" id="empaqueCantidad" class="form-control" placeholder="Cantidad" step="any">
                            </div>
                            <div class="col-2">
                                <button type="button" class="btn btn-primary w-100" id="agregarEmpaque">+</button>
                            </div>
                        </div>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Empaque</th>
                                    <th>Cantidad</th>
                                    <th>Precio (S/)</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="tablaEmpaques"></tbody>
                        </table>

    
                        <div class="text-end mt-2">
                            <h5>Precio Total de la Base: <span id="precioTotal" class="text-success">S/ 0.00</span></h5>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success mt-3">Guardar Base</button>
        </form>
    </div>

    
<!-- Incluir jQuery (requerido por Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Incluir JS de Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // Inicializar Select2 cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        // Selector de insumos
         $('.select2-clasificacion').select2({
            placeholder: 'Seleccionar Clasificacion',
            allowClear: true,
            width: '100%'
        });
        $('.select2-insumo').select2({
            placeholder: 'Seleccionar insumo',
            allowClear: true,
            width: '100%'
        });

        $('.select2-prebase').select2({
            placeholder: 'Seleccionar prebase',
            allowClear: true,
            width: '100%'
        });

        // Selector de empaques
        $('.select2-empaque').select2({
            placeholder: 'Seleccionar empaque',
            allowClear: true,
            width: '100%'
        });
    });

        const insumosData = {};
        const empaquesData = {};
        let insumoIndex = 0;
        document.getElementById('agregarInsumo').addEventListener('click', function () {
            const insumoSelect = document.getElementById('insumoSelect');
            const cantidadInput = document.getElementById('insumoCantidad');
            const insumoId = insumoSelect.value;
            const insumoNombre = insumoSelect.options[insumoSelect.selectedIndex]?.dataset.nombre;
            const insumoPrecio = parseFloat(insumoSelect.options[insumoSelect.selectedIndex]?.dataset.precio);
            const cantidad = parseFloat(cantidadInput.value);

            if (!insumoId || !cantidad || cantidad <= 0) return;
            if (document.querySelector(`#row-insumo-${insumoId}`)) return;

            insumosData[insumoId] = insumoPrecio;

            const tbody = document.getElementById('tablaInsumos');
            const row = document.createElement('tr');
            row.id = `row-insumo-${insumoId}`;
            row.innerHTML = `
                <td>
                    ${insumoNombre}
                    <input type="hidden" name="insumos[${insumoIndex}][id]" value="${insumoId}">
                </td>
                <td>
                    <input type="number" name="insumos[${insumoIndex}][cantidad]" class="form-control cantidad-input"
                        data-insumo-id="${insumoId}" value="${cantidad}" step="any" required>
                </td>
                <td>
                    S/ ${insumoPrecio.toFixed(2)}
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarInsumo('${insumoId}')">X</button>
                </td>
            `;
            tbody.appendChild(row);

            insumoIndex++; // ✅ Aumentamos el índice para el siguiente

            // Resetear el select2
            $('.select2-insumo').val(null).trigger('change');
            cantidadInput.value = '';

            actualizarPrecioTotal();
        });


        function eliminarInsumo(insumoId) {
            document.getElementById(`row-insumo-${insumoId}`)?.remove();
            delete insumosData[insumoId];
            actualizarPrecioTotal();
        }

        let empaqueIndex = 0;
        document.getElementById('agregarEmpaque').addEventListener('click', function () {
            const empaqueSelect = document.getElementById('empaqueSelect');
            const cantidadInput = document.getElementById('empaqueCantidad');
            const empaqueId = empaqueSelect.value;
            const empaqueNombre = empaqueSelect.options[empaqueSelect.selectedIndex]?.dataset.nombre;
            const empaquePrecio = parseFloat(empaqueSelect.options[empaqueSelect.selectedIndex]?.dataset.precio);
            const cantidad = parseFloat(cantidadInput.value);

            if (!empaqueId || !cantidad || cantidad <= 0) return;
            if (document.querySelector(`#row-empaque-${empaqueId}`)) return;

            empaquesData[empaqueId] = empaquePrecio;

            const tbody = document.getElementById('tablaEmpaques');
            const row = document.createElement('tr');
            row.id = `row-empaque-${empaqueId}`;
            row.innerHTML = `
                <td>
                    ${empaqueNombre}
                    <input type="hidden" name="empaques[${empaqueIndex}][id]" value="${empaqueId}">
                </td>
                <td>
                    <input type="number" name="empaques[${empaqueIndex}][cantidad]" class="form-control cantidad-input"
                        data-empaque-id="${empaqueId}" value="${cantidad}" step="any" required>
                </td>
                <td>
                    S/ ${empaquePrecio.toFixed(2)}
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarEmpaque('${empaqueId}')">X</button>
                </td>
            `;
            tbody.appendChild(row);
            empaqueIndex++;
            // Resetear el select2
            $('.select2-empaque').val(null).trigger('change');
            cantidadInput.value = '';

            actualizarPrecioTotal();
        });

        function eliminarEmpaque(empaqueId) {
            document.getElementById(`row-empaque-${empaqueId}`)?.remove();
            delete empaquesData[empaqueId];
            actualizarPrecioTotal();
        }

        const prebasesData = {};
        let prebaseIndex = 0;
        document.getElementById('agregarPrebase').addEventListener('click', function () {
            const select = document.getElementById('prebaseSelect');
            const cantidadInput = document.getElementById('prebaseCantidad');
            const id = select.value;
            const nombre = select.options[select.selectedIndex]?.dataset.nombre;
            const precio = parseFloat(select.options[select.selectedIndex]?.dataset.precio);
            const cantidad = parseFloat(cantidadInput.value);

            if (!id || !cantidad || cantidad <= 0) return;
            if (document.querySelector(`#row-prebase-${id}`)) return;

            prebasesData[id] = precio;

            const tbody = document.getElementById('tablaPrebases');
            const row = document.createElement('tr');
            row.id = `row-prebase-${id}`;
            row.innerHTML = `
                <td>
                    ${nombre}
                    <input type="hidden" name="prebases[${prebaseIndex}][id]" value="${id}">
                </td>
                <td>
                    <input type="number" name="prebases[${prebaseIndex}][cantidad]" class="form-control cantidad-input"
                        data-prebase-id="${id}" value="${cantidad}" step="any" required>
                </td>
                <td>S/ ${precio.toFixed(2)}</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarPrebase('${id}')">X</button>
                </td>
            `;
            tbody.appendChild(row);
            prebaseIndex++;
            $('.select2-prebase').val(null).trigger('change');
            cantidadInput.value = '';

            actualizarPrecioTotal();
        });

        function eliminarPrebase(id) {
            document.getElementById(`row-prebase-${id}`)?.remove();
            delete prebasesData[id];
            actualizarPrecioTotal();
        }

        function actualizarPrecioTotal() {
            let total = 0;
            let subtotalInsumos = 0;

            // Insumos
            document.querySelectorAll('[data-insumo-id]').forEach(input => {
                const id = input.dataset.insumoId;
                const cantidad = parseFloat(input.value);
                const precio = insumosData[id];
                if (precio && cantidad > 0) {
                    subtotalInsumos += precio * cantidad;
                    total += precio * cantidad;
                }
            });

            // Empaques
            document.querySelectorAll('[data-empaque-id]').forEach(input => {
                const id = input.dataset.empaqueId;
                const cantidad = parseFloat(input.value);
                const precio = empaquesData[id];
                if (precio && cantidad > 0) {
                    total += precio * cantidad;
                }
            });
                // Prebases
                document.querySelectorAll('[data-prebase-id]').forEach(input => {
                    const id = input.dataset.prebaseId;
                    const cantidad = parseFloat(input.value);
                    const precio = prebasesData[id];
                    if (precio && cantidad > 0) {
                        total += precio * cantidad;
                    }
                });

            document.getElementById('precioTotal').textContent = `S/ ${total.toFixed(2)}`;

            // Mostrar subtotal de insumos si es prebase
            const tipoBase = document.getElementById('tipoBase').value;
            const subtotalContainer = document.getElementById('subtotalInsumosPrebase');
            const subtotalTexto = document.getElementById('subtotalInsumosTexto');

            if (tipoBase === 'prebase') {
                subtotalContainer.classList.remove('d-none');
                subtotalTexto.textContent = `S/ ${subtotalInsumos.toFixed(2)}`;
            } else {
                subtotalContainer.classList.add('d-none');
            }
        }

        // Filtro por tipo de empaque - Versión mejorada
    document.addEventListener('DOMContentLoaded', function() {
        const $empaqueSelect = $('#empaqueSelect');
        const $tipoSelect = $('#empaqueTipo');
        
        // Guardar opciones originales
        $empaqueSelect.data('originalOptions', $empaqueSelect.find('option').clone());
        
        $tipoSelect.on('change', function() {
            const tipoSeleccionado = this.value;
            let placeholderText = '-- Seleccionar Empaque --';
            
            if (tipoSeleccionado === 'envase') {
                placeholderText = '-- Seleccionar Envase --';
            } else if (tipoSeleccionado === 'material') {
                placeholderText = '-- Seleccionar Material --';
            }
            
            // Cerrar Select2 si está abierto
            $empaqueSelect.select2('close');
            
            // Limpiar y establecer nuevo placeholder
            $empaqueSelect.empty().append(`<option value="">${placeholderText}</option>`);
            
            // Agregar opciones filtradas
            $empaqueSelect.data('originalOptions').each(function() {
                const $option = $(this);
                if ($option.val() && (!tipoSeleccionado || $option.data('tipo') === tipoSeleccionado)) {
                    $empaqueSelect.append($option.clone());
                }
            });
            
            // Re-inicializar Select2
            $empaqueSelect.select2({
                placeholder: placeholderText,
                allowClear: true,
                width: '100%'
            });
            
            // Abrir dropdown
            setTimeout(() => $empaqueSelect.select2('open'), 100);
        });
        
        // Inicializar Select2 por primera vez
        $empaqueSelect.select2({
            placeholder: '-- Seleccionar Empaque --',
            allowClear: true,
            width: '100%'
        });
    });

    // Actualizar en cambios manuales
    document.addEventListener('input', function (e) {
        if (e.target.classList.contains('cantidad-input')) {
            actualizarPrecioTotal();
        }
    });

     const volumenesPorClasificacion = @json($volumenesAgrupados);

    $('#clasificacion_id').on('change', function () {
    const clasificacionId = this.value;
    const volumenSelect = document.getElementById('volumen_id');
    const unidadInput = document.getElementById('unidad_medida');

    // 🔹 Obtener la unidad de medida usando jQuery
    const selectedOption = $(this).find('option:selected');
    unidadInput.value = selectedOption.data('unidad') || '';

    volumenSelect.innerHTML = '';

    if (!clasificacionId || !volumenesPorClasificacion[clasificacionId]) {
        volumenSelect.innerHTML = '<option value="">-- No hay volúmenes disponibles --</option>';
        return;
    }

    volumenSelect.innerHTML = '<option value="">-- Seleccionar Volumen --</option>';
    volumenesPorClasificacion[clasificacionId].forEach(function (vol) {
        const option = document.createElement('option');
        option.value = vol.id;
        option.textContent = vol.nombre;
        volumenSelect.appendChild(option);
    });
});

</script>
<script>
    const checkbox = document.getElementById('toggle-producto_final');
    window.addEventListener('DOMContentLoaded', function () {
        checkbox.checked = false;
    });
    window.addEventListener('pageshow', function () {
        checkbox.checked = false;
    });
     document.getElementById('toggle-producto_final').addEventListener('change', function () {
        if (this.checked) {
            window.location.href = "{{ route('producto_final.index') }}";
        }
    });

    //oculta empaques si selecciona prebase
  document.getElementById('tipoBase').addEventListener('change', function () {
    const seccionEmpaques = document.getElementById('seccionEmpaques');
    const tablaInsumos = document.getElementById('tablaInsumos');

    // 🔄 Limpiar insumos al cambiar tipo
    tablaInsumos.innerHTML = '';
    for (const id in insumosData) {
        delete insumosData[id];
    }
    // 🧮 Recalcular precio
    actualizarPrecioTotal();

    // 🎯 Mostrar u ocultar empaques según tipo
    if (this.value === 'prebase') {
        seccionEmpaques.classList.add('d-none');
    } else {
        seccionEmpaques.classList.remove('d-none');
    }
});

</script>
@endsection