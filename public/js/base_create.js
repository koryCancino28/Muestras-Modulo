src="https://code.jquery.com/jquery-3.6.0.min.js"
<!-- Incluir JS de Select2 -->
src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"


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
                    <input type="hidden" name="insumos[][id]" value="${insumoId}">
                </td>
                <td>
                    <input type="number" name="insumos[][cantidad]" class="form-control cantidad-input"
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
                <input type="hidden" name="empaques[][id]" value="${empaqueId}">
            </td>
            <td>
                <input type="number" name="empaques[][cantidad]" class="form-control cantidad-input"
                       data-empaque-id="${empaqueId}" value="${cantidad}" step="any" required>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="eliminarEmpaque('${empaqueId}')">X</button>
            </td>
        `;
        tbody.appendChild(row);

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
                <input type="hidden" name="prebases[][id]" value="${id}">
            </td>
            <td>
                <input type="number" name="prebases[][cantidad]" class="form-control cantidad-input"
                    data-prebase-id="${id}" value="${cantidad}" step="any" required>
            </td>
            <td>S/ ${precio.toFixed(2)}</td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="eliminarPrebase('${id}')">X</button>
            </td>
        `;
        tbody.appendChild(row);

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
    const volumenesPorClasificacion = window.volumenesPorClasificacion;



    $('#clasificacion_id').on('change', function () {
        const clasificacionId = this.value;
        const volumenSelect = document.getElementById('volumen_id');
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

    //MANEJA EL FORMULARIO Poducto final
    const togglePrebase = document.getElementById('toggle-producto_final');
    const normalForm = document.getElementById('formulario-normal');
    const prebaseForm = document.getElementById('formulario-producto_final');

    togglePrebase.addEventListener('change', function () {
        if (this.checked) {
            normalForm.classList.add('d-none');
            prebaseForm.classList.remove('d-none');
        } else {
            prebaseForm.classList.add('d-none');
            normalForm.classList.remove('d-none');
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