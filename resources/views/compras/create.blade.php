@extends('layouts.app')

@section('content')
<div class="container-fluid" style="user-select: none;">
        <div class="d-flex mb-4 justify-content-between align-items-center">
            <h1 class="text-center flex-grow-1"><a class="float-start text-secondary" title="Volver" href="{{ route('compras.index') }}">
            <i class="bi bi-arrow-left-circle"></i></a>
                <i class="fa-solid fa-basket-shopping"></i>
                Registro de Compra
            </h1>
        </div>
        <form method="POST" action="{{ route('compras.store') }}" id="compraForm">
            @csrf
            
            <!-- Datos principales de la compra -->
            <div class="row">
                <div class="col-md-4 mb-2">
                    <div class="form-group">
                        <label for="serie">Serie (Referencia)</label>
                        <input type="text" class="form-control @error('serie') is-invalid @enderror" 
                            id="serie" name="serie" value="{{ old('serie') }}" placeholder="F001" required>
                        @error('serie')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="numero">Número (Referencia)</label>
                        <input type="number" class="form-control @error('numero') is-invalid @enderror" 
                            id="numero" name="numero" value="{{ old('numero') }}" placeholder="000001" required>
                        @error('numero')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="condicion_pago">Condición de Pago</label>
                        <select class="form-control @error('condicion_pago') is-invalid @enderror" 
                            id="condicion_pago" name="condicion_pago" required>
                            <option value="">Seleccionar condición</option>
                            <option value="Contado" {{ old('condicion_pago') == 'Contado' ? 'selected' : '' }}>Contado</option>
                            <option value="Crédito" {{ old('condicion_pago') == 'Crédito' ? 'selected' : '' }}>Crédito</option>
                        </select>
                        @error('condicion_pago')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="proveedor_id">Proveedor</label>
                        <select class="form-control select2 @error('proveedor_id') is-invalid @enderror" 
                            id="proveedor_id" name="proveedor_id" required>
                            <option value="">Seleccionar proveedor</option>
                            @foreach ($proveedores as $proveedor)
                                <option value="{{ $proveedor->id }}" {{ old('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                                    {{ $proveedor->razon_social }}
                                </option>
                            @endforeach
                        </select>
                        @error('proveedor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="moneda_id">Tipo de Moneda</label>
                        <select class="form-control @error('moneda_id') is-invalid @enderror" 
                            id="moneda_id" name="moneda_id" required>
                            <option value="">Seleccionar moneda</option>
                            @foreach ($monedas as $moneda)
                                <option value="{{ $moneda->id }}" data-simbolo="{{ $moneda->simbolo ?? 'S/' }}" {{ old('moneda_id') == $moneda->id ? 'selected' : '' }}>
                                    {{ $moneda->codigo }} - {{ $moneda->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('moneda_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="form-group">
                        <label for="fecha_emision">Fecha de Emisión</label>
                        <input type="date" class="form-control @error('fecha_emision') is-invalid @enderror" 
                            id="fecha_emision" name="fecha_emision" value="{{ old('fecha_emision') ?? date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required>
                        @error('fecha_emision')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="igv">¿Incluye IGV?</label>
                        <select class="form-control @error('igv') is-invalid @enderror" 
                            id="igv" name="igv" required>
                            <option value="1" {{ old('igv', '1') == '1' ? 'selected' : '' }}>Agregar IGV</option>
                            <option value="0" {{ old('igv') == '0' ? 'selected' : '' }}>El precio incluye IGV</option>
                        </select>
                        @error('igv')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
            </div>

            <!-- Sección de artículos -->
            <div class="mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
    
                    <h5 class="font-weight-bold" style="color:rgb(243, 113, 128); font-weight: bold; margin: 0;">Artículos de la Compra</h5>
                    <!-- Botón con Bootstrap 5 -->
                    <button type="button" class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#modalArticulos">
                        <i class="fas fa-plus mr-1"></i>
                        Agregar Artículo
                    </button>
                </div>

                <!-- Tabla del carrito -->
                <div class="table-responsive border rounded">
                    <table class="table mb-0" id="tablaArticulos">
                        <thead class="thead-light">
                            <tr>
                                <th>SKU</th>
                                <th>Producto</th>
                                <th>Unidad</th>
                                <th>Cantidad</th>
                                <th>Precio Unit.</th>
                                <th>Lote</th>
                                <th>Vencimiento</th>
                                <th>Subtotal</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="fila-vacia">
                                <td colspan="9" class="text-center text-muted py-4">
                                    No hay artículos agregados. Haga clic en "Agregar Artículo" para comenzar.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Totales -->
                <div class="d-flex justify-content-end mt-3" id="totales-container" style="display: none !important;">
                    <div class="subtotal-container">
                        <div class="subtotal-row">
                            <span>Subtotal:</span>
                            <span id="subtotal-valor">S/ 0.00</span>
                        </div>
                        <div class="subtotal-row" id="igv-container">
                            <span>IGV (18%):</span>
                            <span id="igv-valor">S/ 0.00</span>
                        </div>
                        <div class="total-row">
                            <span>Total:</span>
                            <span id="total-valor">S/ 0.00</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn_crear flex-grow-1" id="btnRegistrar" disabled>
                    Registrar Compra
                </button>
                <button type="button" class="btn btn-outline-secondary" id="btnLimpiar">
                    Limpiar Formulario
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Artículos -->
<div class="modal fade" id="modalArticulos" tabindex="-1" role="dialog" aria-labelledby="modalArticulosLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalArticulosLabel">Seleccionar Artículo</h5>
            </div>
            <div class="modal-body">
                <!-- Filtro por tipo de artículo -->
                <div class="form-group mb-2">
                <label for="tipoArticulo" class="mb-1">Filtrar por Tipo de Artículo</label>
                <select class="form-control" id="tipoArticulo">
                    <option value="">Todos los tipos</option>
                    <option value="insumo">Insumo</option>
                    <option value="material">Material</option>
                    <option value="envase">Envase</option>
                    <option value="merchandise">Merchandise</option>
                    <option value="util">Útil</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="searchArticulo" class="mb-1">Buscar por nombre de artículo</label>
                <input type="text" class="form-control" id="searchArticulo" placeholder="Buscar artículo">
            </div>

                <!-- Tabla de artículos -->
                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-bordered table-hover" id="tablaModalArticulos">
                        <thead class="thead-light">
                            <tr>
                                <th>SKU</th>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Stock</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($articulos as $articulo)
                            <tr class="fila-articulo" 
                                data-id="{{ $articulo->id }}" 
                                data-sku="{{ $articulo->sku }}" 
                                data-nombre="{{ $articulo->nombre }}" 
                                data-tipo="{{ $articulo->tipo }}"
                                data-unidad="{{ $articulo->unidad_medida ?? 'unidad' }}">
                                <td>{{ $articulo->sku }}</td>
                                <td>{{ $articulo->nombre }}</td>
                                <td>
                                    <span class="badge" style="background-color: transparent; color: #fe495f; border: 1px solid #fe495f;">
                                        {{ ucfirst($articulo->tipo) }}
                                    </span>
                                </td>
                                <td>{{ $articulo->stock }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn_crear btn-seleccionar" data-articulo-id="{{ $articulo->id }}">
                                        Seleccionar
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Formulario para completar datos del artículo -->
                <div id="articulo-form" style="display: none;" class="border-top pt-3 mt-3">
                    <h5 class="mb-3">Completar datos del artículo: <span id="articulo-nombre-seleccionado" class="text-primary"></span></h5>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-2">
                            <div class="form-group">
                                <label>Cantidad <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="articulo-cantidad" min="1" value="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Precio Unitario <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="articulo-precio" step="0.01" min="0" value="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Lote</label>
                                <input type="text" class="form-control" id="articulo-lote" placeholder="Número de lote" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha de Vencimiento</label>
                                <input type="date" class="form-control" id="articulo-vencimiento">
                            </div>
                        </div>
                    </div>
                    <div class="gap-2">
                        <button type="button" class="btn btn_crear" id="btn-agregar-carrito">
                            <i class="fas fa-cart-plus mr-1"></i> Agregar al Carrito
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="btn-cancelar-articulo">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .btn-sm{
        border: 1px solid#fe495f !important;
        background-color:rgb(255, 113, 130); 
        color: white;
        padding: 5px 16px; 
        font-size: 15px;  
        border-radius: 4px;
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Guardar datos del formulario en localStorage al cambiarlos
    $('#compraForm :input').on('change keyup', function() {
        const datosFormulario = {};
        $('#compraForm :input').each(function() {
            if (this.name && this.type !== 'submit' && this.type !== 'button') {
                datosFormulario[this.name] = $(this).val();
            }
        });
        localStorage.setItem('formularioCompra', JSON.stringify(datosFormulario));
    });

    $('#proveedor_id').select2({
                placeholder: "Seleccionar proveedor",
                allowClear: true
            });
     document.getElementById('compraForm').addEventListener('submit', function (e) {
            const btn = document.getElementById('btnRegistrar');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Registrando...'; // opcional
        });
    // Variables globales
    let articuloSeleccionado = null;
    let carrito = [];
    let simboloMoneda = 'S/';
    // Intentar recuperar carrito del localStorage
    const carritoGuardado = localStorage.getItem('carritoCompra');
    if (carritoGuardado) {
        carrito = JSON.parse(carritoGuardado);
    }
    // Recuperar datos del formulario
    const formularioGuardado = localStorage.getItem('formularioCompra');
    if (formularioGuardado) {
        const datos = JSON.parse(formularioGuardado);
        for (const campo in datos) {
            const $campo = $('[name="' + campo + '"]');
            if ($campo.length) {
                $campo.val(datos[campo]).trigger('change');
            }
        }
    }

    actualizarTablaCarrito();

    if (carrito.length > 0) {
        $('#btnRegistrar').prop('disabled', false);
    }

        // Filtrar artículos por tipo y nombre
    $('#tipoArticulo, #searchArticulo').on('input', function() {
        const tipoSeleccionado = $('#tipoArticulo').val().toLowerCase();
        const nombreBusqueda = $('#searchArticulo').val().toLowerCase();

        console.log('Filtro de tipo seleccionado:', tipoSeleccionado);
        console.log('Búsqueda por nombre:', nombreBusqueda);

        $('.fila-articulo').each(function() {
            const tipoArticulo = $(this).data('tipo').toLowerCase();
            const nombreArticulo = $(this).data('nombre').toLowerCase();  

            console.log('Tipo artículo:', tipoArticulo, 'Comparando con:', tipoSeleccionado);
            console.log('Nombre artículo:', nombreArticulo, 'Comparando con:', nombreBusqueda);

            // Comprobamos ambos filtros
            if (
                (!tipoSeleccionado || tipoArticulo === tipoSeleccionado) && 
                (!nombreBusqueda || nombreArticulo.includes(nombreBusqueda))
            ) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Seleccionar artículo
    $(document).on('click', '.btn-seleccionar', function() {
        console.log('Botón seleccionar clickeado');
        
        const fila = $(this).closest('tr');
        const id = parseInt(fila.data('id'));
        const sku = fila.data('sku');
        const nombre = fila.data('nombre');
        const tipo = fila.data('tipo');
        const unidad = fila.data('unidad');

        console.log('Datos del artículo:', { id, sku, nombre, tipo, unidad });

        // Verificar si el artículo ya está en el carrito
        const yaExiste = carrito.some(item => item.id === id);
        if (yaExiste) {
            alert('Este artículo ya está en el carrito');
            return;
        }

        // Remover selección anterior
        $('.fila-articulo').removeClass('articulo-seleccionado');
        
        // Marcar fila como seleccionada
        fila.addClass('articulo-seleccionado');

        // Guardar artículo seleccionado
        articuloSeleccionado = { id, sku, nombre, tipo, unidad };
        
        // Mostrar nombre del artículo seleccionado
        $('#articulo-nombre-seleccionado').text(`${sku} - ${nombre}`);
        
        // Mostrar formulario para completar datos
        $('#articulo-form').show();
        
        // Limpiar campos del formulario
        $('#articulo-cantidad').val(1);
        $('#articulo-precio').val('');
        $('#articulo-lote').val('');
        $('#articulo-vencimiento').val('');
        
        // Enfocar en el campo cantidad
        $('#articulo-cantidad').focus();
    });

    // Cancelar selección de artículo
    $('#btn-cancelar-articulo').on('click', function() {
        $('#articulo-form').hide();
        $('.fila-articulo').removeClass('articulo-seleccionado');
        articuloSeleccionado = null;
    });

    // Agregar artículo al carrito
    $('#btn-agregar-carrito').on('click', function() {
        if (!articuloSeleccionado) {
            alert('No hay artículo seleccionado');
            return;
        }

        const cantidad = parseInt($('#articulo-cantidad').val()) || 0;
        const precio = parseFloat($('#articulo-precio').val()) || 0;
        const lote = $('#articulo-lote').val().trim();
        const vencimiento = $('#articulo-vencimiento').val();

        // Validaciones
        if (cantidad <= 0) {
            alert('La cantidad debe ser mayor a 0');
            $('#articulo-cantidad').focus();
            return;
        }

        if (precio < 0) {
            alert('El precio no puede ser negativo');
            $('#articulo-precio').focus();
            return;
        }

        const subtotal = cantidad * precio;

        // Crear objeto para el carrito
        const nuevoArticulo = {
            id: articuloSeleccionado.id,
            sku: articuloSeleccionado.sku,
            nombre: articuloSeleccionado.nombre,
            tipo: articuloSeleccionado.tipo,
            unidad: articuloSeleccionado.unidad,
            cantidad: cantidad,
            precio: precio,
            lote: lote,
            vencimiento: vencimiento,
            subtotal: subtotal
        };

        console.log('Agregando al carrito:', nuevoArticulo);

        // Agregar al carrito
        carrito.push(nuevoArticulo);
        
        // Actualizar tabla
        actualizarTablaCarrito();
        
        // Cerrar modal y limpiar
        $('#modalArticulos').modal('hide');
        $('#articulo-form').hide();
        $('.fila-articulo').removeClass('articulo-seleccionado');
        articuloSeleccionado = null;
        
        // Habilitar botón de registro
        $('#btnRegistrar').prop('disabled', false);
    });

    // Eliminar artículo del carrito
    $(document).on('click', '.btn-eliminar-articulo', function() {
        const index = parseInt($(this).data('index'));
        console.log('Eliminando artículo en índice:', index);
        
        if (confirm('¿Está seguro de eliminar este artículo del carrito?')) {
            carrito.splice(index, 1);
            actualizarTablaCarrito();
            
            // Deshabilitar botón de registro si no hay artículos
            if (carrito.length === 0) {
                $('#btnRegistrar').prop('disabled', true);
            }
        }
    });

    // Actualizar cantidad o precio
    $(document).on('change', '.articulo-cantidad, .articulo-precio', function() {
        const index = parseInt($(this).data('index'));
        const campo = $(this).hasClass('articulo-cantidad') ? 'cantidad' : 'precio';
        let valor = campo === 'cantidad' ? parseInt($(this).val()) || 1 : parseFloat($(this).val()) || 0;
        
        // Validaciones
        if (campo === 'cantidad' && valor <= 0) {
            valor = 1;
            $(this).val(1);
        }
        if (campo === 'precio' && valor < 0) {
            valor = 0;
            $(this).val(0);
        }
        
        carrito[index][campo] = valor;
        carrito[index].subtotal = carrito[index].cantidad * carrito[index].precio;
        
        actualizarTablaCarrito();
    });

    // Actualizar lote o vencimiento
    $(document).on('change', '.articulo-lote, .articulo-vencimiento', function() {
        const index = parseInt($(this).data('index'));
        const campo = $(this).hasClass('articulo-lote') ? 'lote' : 'vencimiento';
        carrito[index][campo] = $(this).val();
    });

    // Cambiar IGV
    $('#igv').on('change', function() {
        calcularTotales();
    });

    // Cambiar símbolo de moneda
    $('#moneda_id').on('change', function() {
        const simbolo = $(this).find('option:selected').data('simbolo');
        if (simbolo) {
            simboloMoneda = simbolo;
            actualizarTablaCarrito();
        }
    });

    // Limpiar formulario
    $('#btnLimpiar').on('click', function() {
        if (confirm('¿Está seguro de limpiar el formulario? Se perderán todos los datos ingresados.')) {
            $('#compraForm')[0].reset();
            carrito = [];
            localStorage.removeItem('carritoCompra');
            localStorage.removeItem('formularioCompra');
            actualizarTablaCarrito();
            $('#proveedor_id').val(null).trigger('change');
            $('#btnRegistrar').prop('disabled', true);
            simboloMoneda = 'S/';
        }
    });

    // Función para actualizar la tabla del carrito
    function actualizarTablaCarrito() {
        const tbody = $('#tablaArticulos tbody');
        tbody.empty();
        
        if (carrito.length === 0) {
            tbody.append(`
                <tr id="fila-vacia">
                    <td colspan="9" class="text-center text-muted py-4">
                        No hay artículos agregados. Haga clic en "Agregar Artículo" para comenzar.
                    </td>
                </tr>
            `);
            $('#totales-container').hide();
        } else {
            carrito.forEach((item, index) => {
                const fila = `
                    <tr>
                        <td>${item.sku}</td>
                        <td>${item.nombre}</td>
                        <td><span class="badge badge-info badge-tipo">${item.unidad}</span></td>
                        <td>
                            <input type="number" class="form-control form-control-sm articulo-cantidad" 
                                data-index="${index}" name="cantidades[]" value="${item.cantidad}" min="1" style="width: 80px">
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm articulo-precio" 
                                data-index="${index}" name="precios[]" value="${item.precio}" min="0" step="0.01" style="width: 100px">
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm articulo-lote" 
                                data-index="${index}" name="lotes[]" value="${item.lote}" style="width: 100px" required>
                        </td>
                        <td>
                            <input type="date" class="form-control form-control-sm articulo-vencimiento" 
                                data-index="${index}" name="vencimientos[]" value="${item.vencimiento}" style="width: 150px">
                        </td>
                        <td class="font-weight-bold">${simboloMoneda} ${item.subtotal.toFixed(2)}</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm btn-eliminar-articulo" data-index="${index}" title="Eliminar artículo">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                        <input type="hidden" name="articulos[]" value="${item.id}">
                    </tr>
                `;
                tbody.append(fila);
            });
            $('#totales-container').show();
        }
        
        calcularTotales();
        localStorage.setItem('carritoCompra', JSON.stringify(carrito));
    }

    // Función para calcular totales
    function calcularTotales() {
        const subtotal = carrito.reduce((sum, item) => sum + item.subtotal, 0);
        const conIgv = $('#igv').val() === '1';
        const igv = conIgv ? subtotal * 0.18 : 0;
        const total = subtotal + igv;
        
        $('#subtotal-valor').text(`${simboloMoneda} ${subtotal.toFixed(2)}`);
        $('#igv-valor').text(`${simboloMoneda} ${igv.toFixed(2)}`);
        $('#total-valor').text(`${simboloMoneda} ${total.toFixed(2)}`);
        
        // Mostrar u ocultar fila de IGV
        if (conIgv) {
            $('#igv-container').show();
        } else {
            $('#igv-container').hide();
        }
    }

    // Validación del formulario antes de enviar
    $('#compraForm').on('submit', function(e) {
        if (carrito.length === 0) {
            e.preventDefault();
            alert('Debe agregar al menos un artículo a la compra');
            localStorage.removeItem('carritoCompra');
            localStorage.removeItem('formularioCompra');
            return false;
        }
        
        // Validar que todos los artículos tengan cantidad y precio válidos
        let validacionOk = true;
        carrito.forEach((item, index) => {
            if (item.cantidad <= 0 || item.precio < 0) {
                validacionOk = false;
            }
        });
        
        if (!validacionOk) {
            e.preventDefault();
            alert('Todos los artículos deben tener cantidad mayor a 0 y precio válido');
            return false;
        }
        
        return true;
    });

    // Limpiar modal al cerrarlo
    $('#modalArticulos').on('hidden.bs.modal', function() {
        $('#articulo-form').hide();
        $('.fila-articulo').removeClass('articulo-seleccionado');
        $('#tipoArticulo').val('');
        $('.fila-articulo').show();
        articuloSeleccionado = null;
    });

    // Inicializar
    calcularTotales();
});
</script>
@endsection