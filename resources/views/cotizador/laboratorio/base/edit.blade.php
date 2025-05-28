@extends('layouts.app')

@section('content')
<div class="container">
     <div class="form-check mb-3">
            <h1 class="text-center"><a class="float-start" title="Volver" href="{{ route('bases.index') }}">
            <i class="bi bi-arrow-left-circle"></i></a>
            Editar Base</h1>
        </div>

<form method="POST" action="{{ route('bases.update', $base->id) }}">
    @csrf
    @method('PUT')

    <div class="row">
        <!-- Columna izquierda: datos de la base -->
        <div class="col-md-6">
            <div class="mb-3">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" name="nombre" value="{{ $base->nombre }}" required>
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
                                {{ $base->clasificacion_id == $c->id ? 'selected' : '' }}>
                            {{ $c->nombre_clasificacion }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="unidad_medida">Unidad de Medida</label>
                <input type="text" class="form-control" id="unidad_medida" 
                       value="{{ $base->clasificacion->unidadMedida->nombre_unidad_de_medida ?? '' }}" readonly>
            </div>

            <div class="mb-3">
                <label for="volumen_id">Volumen</label>
                <select class="form-control" name="volumen_id" id="volumen_id" required>
                    <option value="">-- Selecciona una Clasificación primero --</option>
                    @foreach($volumenes as $volumen)
                        <option value="{{ $volumen->id }}" 
                                {{ $base->volumen_id == $volumen->id ? 'selected' : '' }}>
                            {{ $volumen->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            @if($errors->has('tipo'))
                <div class="alert alert-danger">
                    {{ $errors->first('tipo') }}
                </div>
            @endif
        </div>

        <!-- Columna derecha: insumos -->
        <div class="col-md-6">
            <div class="mb-3">
                <label for="tipoBase">Tipo de Base</label>
                <select class="form-control" name="tipo" id="tipoBase" required>
                    <option value="final" {{ $base->tipo == 'final' ? 'selected' : '' }}>Base</option>
                    <option value="prebase" {{ $base->tipo == 'prebase' ? 'selected' : '' }}>Prebase</option>
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
                    <input type="number" id="insumoCantidad" class="form-control" placeholder="Cantidad" step="any">
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
                    @foreach($base->insumos as $insumo)
                        <tr data-id="{{ $insumo->id }}">
                            <td>{{ $insumo->nombre }}</td>
                            <td>
                                <input type="hidden" name="insumos[{{ $insumo->id }}][id]" value="{{ $insumo->id }}">
                                <input type="number" class="form-control" name="insumos[{{ $insumo->id }}][cantidad]" 
                                       value="{{ $insumo->pivot->cantidad }}" step="any" required>
                            </td>
                            <td>S/ {{ number_format($insumo->precio, 2) }}</td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm eliminar-insumo">Eliminar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div id="subtotalInsumosPrebase" class="text-end mt-2 {{ $base->tipo == 'prebase' ? '' : 'd-none' }}">
                <h6>Total de insumos: <span id="subtotalInsumosTexto" class="text-primary">S/ {{ number_format($base->precio, 2) }}</span></h6>
            </div>

            <div id="seccionEmpaques" style="{{ $base->tipo == 'prebase' ? 'display:none;' : '' }}">
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
                    <input type="number" id="prebaseCantidad" class="form-control" placeholder="Cantidad" step="any">
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn_crear w-100" id="agregarPrebase"><i class="fa-solid fa-circle-plus"></i></button>
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
                <tbody id="tablaPrebases">
                    @if($base->tipo == 'final')
                        @foreach($base->prebases as $prebase)
                            <tr data-id="{{ $prebase->id }}">
                                <td>{{ $prebase->nombre }}</td>
                                <td>
                                    <input type="hidden" name="prebases[{{ $prebase->id }}][id]" value="{{ $prebase->id }}">
                                    <input type="number" class="form-control" name="prebases[{{ $prebase->id }}][cantidad]" 
                                           value="{{ $prebase->pivot->cantidad }}" step="any" required>
                                </td>
                                <td>S/ {{ number_format($prebase->precio, 2) }}</td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm eliminar-prebase">Eliminar</button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
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
                                        data-precio="{{ $empaque->precio }}"
                                        data-tipo="{{ $empaque->tipo }}">
                                    {{ $empaque->nombre }} ({{ ucfirst($empaque->tipo) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        <input type="number" id="empaqueCantidad" class="form-control" placeholder="Cantidad" step="any">
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn_crear w-100" id="agregarEmpaque"><i class="fa-solid fa-circle-plus"></i></button>
                    </div>
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Empaque</th>
                            <th>Cantidad</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="tablaEmpaques">
                        @if($base->tipo == 'final')
                            @foreach($base->empaques as $empaque)
                                <tr data-id="{{ $empaque->id }}" data-tipo="{{ $empaque->tipo }}">
                                    <td>{{ $empaque->nombre }} ({{ ucfirst($empaque->tipo) }})</td>
                                    <td>
                                        <input type="hidden" name="empaques[{{ $empaque->id }}][id]" value="{{ $empaque->id }}">
                                        <input type="hidden" name="empaques[{{ $empaque->id }}][tipo]" value="{{ $empaque->tipo }}">
                                        <input type="number" class="form-control" name="empaques[{{ $empaque->id }}][cantidad]" 
                                               value="{{ $empaque->pivot->cantidad }}" step="any" required>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm eliminar-empaque">Eliminar</button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

                <div class="text-end mt-2">
                    <h5>Precio Total de la Base: <span id="precioTotal" class="text-success">S/ {{ number_format($base->precio, 2) }}</span></h5>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn_crear mt-3">Actualizar Base</button>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
        // Inicializar Select2
        $('.select2-empaque, .select2-prebase, .select2-clasificacion, .select2-insumo').select2({
            width: '100%', // o un valor fijo como '300px'
            dropdownAutoWidth: true
        });
        // Mostrar unidad de medida cuando se selecciona clasificación
        $('#clasificacion_id').change(function() {
            var unidad = $(this).find(':selected').data('unidad');
            $('#unidad_medida').val(unidad);
            
            // Cargar volúmenes para esta clasificación
            var clasificacionId = $(this).val();
            if(clasificacionId) {
                $.get('/volumenes-por-clasificacion/' + clasificacionId, function(data) {
                    $('#volumen_id').empty();
                    $('#volumen_id').append('<option value="">-- Selecciona un Volumen --</option>');
                    $.each(data, function(key, value) {
                        $('#volumen_id').append('<option value="'+key+'">'+value+'</option>');
                    });
                });
            }
        });

        $('#tablaEmpaques tr').each(function() {
            var empaqueId = $(this).data('id');
            var precio = parseFloat($('#empaqueSelect option[value="'+empaqueId+'"]').data('precio')) || 0;
            $(this).attr('data-precio', precio);
        });
    
    // Calcular precio inicial
    if($('#tipoBase').val() == 'prebase') {
        calcularSubtotalInsumos();
    } else {
        calcularPrecioTotal();
    }

        // Mostrar/ocultar secciones según tipo de base
        $('#tipoBase').change(function() {
            if($(this).val() == 'prebase') {
                $('#seccionEmpaques').hide();
                $('#subtotalInsumosPrebase').removeClass('d-none');
                calcularSubtotalInsumos();
            } else {
                $('#seccionEmpaques').show();
                $('#subtotalInsumosPrebase').addClass('d-none');
                calcularPrecioTotal();
            }
        });

        // Agregar insumo
        $('#agregarInsumo').click(function() {
            var insumoId = $('#insumoSelect').val();
            var insumoNombre = $('#insumoSelect option:selected').data('nombre');
            var insumoPrecio = $('#insumoSelect option:selected').data('precio');
            var cantidad = $('#insumoCantidad').val();

            if(insumoId && cantidad) {
                // Verificar si ya existe
                if($('#tablaInsumos tr[data-id="'+insumoId+'"]').length == 0) {
                    var row = '<tr data-id="'+insumoId+'">' +
                        '<td>'+insumoNombre+'</td>' +
                        '<td>' +
                            '<input type="hidden" name="insumos['+insumoId+'][id]" value="'+insumoId+'">' +
                            '<input type="number" class="form-control" name="insumos['+insumoId+'][cantidad]" value="'+cantidad+'" step="any" required>' +
                        '</td>' +
                        '<td>S/ '+parseFloat(insumoPrecio).toFixed(2)+'</td>' +
                        '<td><button type="button" class="btn btn-danger btn-sm eliminar-insumo">Eliminar</button></td>' +
                    '</tr>';
                    $('#tablaInsumos').append(row);
                    
                    if($('#tipoBase').val() == 'prebase') {
                        calcularSubtotalInsumos();
                    } else {
                        calcularPrecioTotal();
                    }
                }
                $('#insumoSelect').val('').trigger('change');
                $('#insumoCantidad').val('');
            }
        });

        // Agregar prebase
        $('#agregarPrebase').click(function() {
            var prebaseId = $('#prebaseSelect').val();
            var prebaseNombre = $('#prebaseSelect option:selected').data('nombre');
            var prebasePrecio = $('#prebaseSelect option:selected').data('precio');
            var cantidad = $('#prebaseCantidad').val();

            if(prebaseId && cantidad) {
                // Verificar si ya existe
                if($('#tablaPrebases tr[data-id="'+prebaseId+'"]').length == 0) {
                    var row = '<tr data-id="'+prebaseId+'">' +
                        '<td>'+prebaseNombre+'</td>' +
                        '<td>' +
                            '<input type="hidden" name="prebases['+prebaseId+'][id]" value="'+prebaseId+'">' +
                            '<input type="number" class="form-control" name="prebases['+prebaseId+'][cantidad]" value="'+cantidad+'" step="any" required>' +
                        '</td>' +
                        '<td>S/ '+parseFloat(prebasePrecio).toFixed(2)+'</td>' +
                        '<td><button type="button" class="btn btn-danger btn-sm eliminar-prebase">Eliminar</button></td>' +
                    '</tr>';
                    $('#tablaPrebases').append(row);
                    calcularPrecioTotal();
                }
                $('#prebaseSelect').val('').trigger('change');
                $('#prebaseCantidad').val('');
            }
        });
            // Agregar empaque (modificado para incluir el precio)
    $('#agregarEmpaque').click(function() {
        var empaqueId = $('#empaqueSelect').val();
        var empaqueNombre = $('#empaqueSelect option:selected').data('nombre');
        var empaquePrecio = $('#empaqueSelect option:selected').data('precio');
        var empaqueTipo = $('#empaqueSelect option:selected').data('tipo');
        var cantidad = $('#empaqueCantidad').val();

        if(empaqueId && cantidad) {
            if($('#tablaEmpaques tr[data-id="'+empaqueId+'"]').length == 0) {
                var row = '<tr data-id="'+empaqueId+'" data-tipo="'+empaqueTipo+'" data-precio="'+empaquePrecio+'">' +
                    '<td>'+empaqueNombre+' ('+empaqueTipo+')</td>' +
                    '<td>' +
                        '<input type="hidden" name="empaques['+empaqueId+'][id]" value="'+empaqueId+'">' +
                        '<input type="hidden" name="empaques['+empaqueId+'][tipo]" value="'+empaqueTipo+'">' +
                        '<input type="number" class="form-control" name="empaques['+empaqueId+'][cantidad]" value="'+cantidad+'" step="any" required>' +
                    '</td>' + 
                    '<td><button type="button" class="btn btn-danger btn-sm eliminar-empaque">Eliminar</button></td>' +
                '</tr>';
                $('#tablaEmpaques').append(row);
                calcularPrecioTotal();
            }
            $('#empaqueSelect').val('').trigger('change');
            $('#empaqueCantidad').val('');
        }
    });
        // Eliminar insumo
        $(document).on('click', '.eliminar-insumo', function() {
            $(this).closest('tr').remove();
            if($('#tipoBase').val() == 'prebase') {
                calcularSubtotalInsumos();
            } else {
                calcularPrecioTotal();
            }
        });

        // Eliminar prebase
        $(document).on('click', '.eliminar-prebase', function() {
            $(this).closest('tr').remove();
            calcularPrecioTotal();
        });

        // Eliminar empaque
        $(document).on('click', '.eliminar-empaque', function() {
            $(this).closest('tr').remove();
            calcularPrecioTotal();
        });
        // Calcular subtotal para prebases (solo insumos)
        function calcularSubtotalInsumos() {
            var subtotal = 0;
            $('#tablaInsumos tr').each(function() {
                var cantidad = parseFloat($(this).find('input[name*="[cantidad]"]').val()) || 0;
                var precio = parseFloat($(this).find('td').eq(2).text().replace('S/ ', '')) || 0;
                subtotal += cantidad * precio;
            });
            $('#subtotalInsumosTexto').text('S/ '+subtotal.toFixed(2));
            $('input[name="precio"]').val(subtotal);
        }

        // Calcular precio total para bases (insumos + prebases + empaques)
       function calcularPrecioTotal() {
        var total = 0;
        
        // Sumar insumos
        $('#tablaInsumos tr').each(function() {
            var cantidad = parseFloat($(this).find('input[name*="[cantidad]"]').val()) || 0;
            var precio = parseFloat($(this).find('td').eq(2).text().replace('S/ ', '')) || 0;
            total += cantidad * precio;
        });
        
        // Sumar prebases
        $('#tablaPrebases tr').each(function() {
            var cantidad = parseFloat($(this).find('input[name*="[cantidad]"]').val()) || 0;
            var precio = parseFloat($(this).find('td').eq(2).text().replace('S/ ', '')) || 0;
            total += cantidad * precio;
        });
        
        // Sumar empaques (versión corregida)
        $('#tablaEmpaques tr').each(function() {
            var cantidad = parseFloat($(this).find('input[name*="[cantidad]"]').val()) || 0;
            var precio = parseFloat($(this).data('precio')) || 0;
            total += cantidad * precio;
        });
        
        // Actualizar el display y el campo hidden
        $('#precioTotal').text('S/ '+total.toFixed(2));
        $('input[name="precio"]').val(total.toFixed(2));
    }

        // Actualizar cálculos cuando cambian cantidades
        $(document).on('change', 'input[name*="[cantidad]"]', function() {
            if($('#tipoBase').val() == 'prebase') {
                calcularSubtotalInsumos();
            } else {
                calcularPrecioTotal();
            }
        });

        // Inicializar según el tipo de base
        if($('#tipoBase').val() == 'prebase') {
            $('#seccionEmpaques').hide();
            $('#subtotalInsumosPrebase').removeClass('d-none');
            calcularSubtotalInsumos();
        } else {
            $('#seccionEmpaques').show();
            $('#subtotalInsumosPrebase').addClass('d-none');
            calcularPrecioTotal();
        }
        const volumenesPorClasificacion = @json($volumenesAgrupados);

        $('#clasificacion_id').on('change', function() {
            const clasificacionId = $(this).val();
            const $volumenSelect = $('#volumen_id');
            const $unidadInput = $('#unidad_medida');

            // 1. Actualizar unidad de medida
            const selectedOption = $(this).find('option:selected');
            $unidadInput.val(selectedOption.data('unidad') || '');

            // 2. Limpiar y cargar volúmenes
            $volumenSelect.empty();

            if (!clasificacionId) {
                $volumenSelect.append('<option value="">-- Seleccione una clasificación primero --</option>');
                return;
            }

            const volúmenes = volumenesPorClasificacion[clasificacionId];
            
            if (!volúmenes || volúmenes.length === 0) {
                $volumenSelect.append('<option value="">-- No hay volúmenes disponibles --</option>');
                return;
            }

            $volumenSelect.append('<option value="">-- Seleccionar Volumen --</option>');
            
            $.each(volúmenes, function(index, vol) {
                $volumenSelect.append($('<option>', {
                    value: vol.id,
                    text: vol.nombre // Asegúrate que esta propiedad coincide con tu estructura de datos
                }));
            });

            // 3. Si hay solo un volumen, seleccionarlo automáticamente
            if (volúmenes.length === 1) {
                $volumenSelect.val(volúmenes[0].id).trigger('change');
            }
        });
        
});
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
</script>

@endsection
