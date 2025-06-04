@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex mb-3 justify-content-between align-items-center">
            <h1 class="text-center flex-grow-1">Listado de Compras</h1>
            <a href="{{ route('compras.create') }}" class="btn btn_crear">
                <i class="fas fa-plus"></i> Nueva Compra
            </a>
        </div>

        <!-- Filtros de búsqueda -->
            <div class="row mb-3">
            <form method="GET" action="{{ route('compras.index') }}" class="w-100 d-flex align-items-center">
                
                <!-- Proveedor -->
                <div class="col-md-3 mb-2 pr-3"> <!-- Agregar margen derecho con pr-3 -->
                    <div class="form-group">
                        <label for="proveedor" class="mr-2">Proveedor:</label>
                        <select name="proveedor_id" id="proveedor" class="form-control select2 w-100" id="proveedor">
                            <option value="">Todos</option>
                            @foreach($proveedores as $proveedor)
                                <option value="{{ $proveedor->id }}" {{ request('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                                    {{ $proveedor->razon_social }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Fecha Desde -->
                <div class="col-md-2 mb-2 pr-3"> <!-- Agregar margen derecho con pr-3 -->
                    <div class="form-group">
                        <label for="fecha_inicio" class="mr-2">Desde:</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">
                    </div>
                </div>

                <!-- Fecha Hasta -->
                <div class="col-md-2 mb-2 pr-3"> <!-- Agregar margen derecho con pr-3 -->
                    <div class="form-group">
                        <label for="fecha_fin" class="mr-2">Hasta:</label>
                        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
                    </div>
                </div>

                <!-- Botones Filtrar y Limpiar -->
                <div class="col-md-3 mb-2 d-flex justify-content-start">
                    <button type="submit" class="btn btn-primary mr-3">  
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                    <a href="{{ route('compras.index') }}" class="btn btn-secondary">
                        <i class="fas fa-sync-alt"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>

        <!-- Tabla de compras -->
        <div class="table-responsive">
            <table id="tablaCompras" class="table table-bordered table-striped table-hover">
                <thead class="bg-secondary text-white">
                    <tr>
                        <th width="5%">N°</th>
                        <th width="10%">Fecha</th>
                        <th>Proveedor</th>
                        <th width="10%">Moneda</th>
                        <th width="10%">Condición</th>
                        <th width="10%">IGV</th>
                        <th width="12%">Total</th>
                        <th width="10%">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($compras as $index => $compra)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $compra->fecha_emision->format('d/m/Y') }}</td>
                            <td>{{ $compra->proveedor->razon_social }}</td>
                            <td>{{ $compra->moneda->nombre }} - {{ $compra->moneda->codigo_iso}}</td>
                            <td>{{ $compra->condicion_pago }}</td>
                            <td class="text-center">
                                @if(!empty($compra->igv) && $compra->igv > 0)
                                    <span class="badge text-bg-success">Sí</span>
                                @else
                                    <span class="badge text-bg-secondary">No</span>
                                @endif
                            </td>
                            <td class="text-right">{{ number_format($compra->precio_total, 2) }}</td>
                            <td class="text-center">
                                <a href="{{ route('compras.show', $compra->id) }}" class="btn btn-sm btn-info" title="Ver detalle">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('compras.destroy', $compra->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Está seguro?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No se encontraron compras registradas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
<style>
    /* Espaciado entre elementos */
.form-group {
    margin-right: 1rem; /* Espaciado entre los campos */
    margin-left: 1rem;  /* Espaciado entre los campos */
}

/* Los campos de formulario ocupan el 100% del espacio */
select.form-control, input.form-control {
    width: 100%;
}

/* Espaciado entre columnas */
.row.mb-3 {
    display: flex;
    gap: 1rem; /* Espacio entre las columnas */
}

/* Botones con margen a la derecha */
.d-flex button, .d-flex a {
    margin-right: 1rem;
}

</style>
    <script>
        $(document).ready(function() {
            $('#proveedor').select2({
                width: '100%'  
            });
        });
         $(document).ready(function() {
            $('#tablaCompras').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json',
                },
                ordering: false,
                responsive: true,
                // quitamos "l" del DOM para eliminar el selector de cantidad de registros
                dom: '<"row"<"col-sm-12 col-md-12"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                pageLength: 10,
                initComplete: function() {
                    $('.dataTables_filter')
                        .addClass('mb-3')
                        .find('input')
                        .attr('placeholder', 'Buscar cualquier dato de la tabla') // <- aquí el placeholder
                        .end()
                        .find('label')
                        .contents().filter(function() {
                            return this.nodeType === 3;
                        }).remove()
                        .end()
                        .prepend('Buscar:');
                    }
                });
            });
    </script>
@endsection
