@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container">
    <div class="row mb-4">
        <div class="form-check mb-6">
            <h1 class="text-center"><a class="float-start" title="Volver" href="{{ route('bases.create') }}">
            <i class="bi bi-arrow-left-circle"></i></a>
            Producto Final</h1>
        </div>
        </div>
        <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <a href="{{ route('producto_final.create') }}" class="btn btn_crear">
                <i class="fas fa-plus"></i> Nuevo Producto
            </a>
        </div>

        <div class="col-md-6 d-flex justify-content-end">
            <form method="GET" action="{{ route('producto_final.index') }}">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="mostrar_inactivos" id="mostrar_inactivos" onchange="this.form.submit()"
                        {{ request()->has('mostrar_inactivos') ? 'checked' : '' }}>
                    <label class="form-check-label" for="mostrar_inactivos">
                        Mostrar productos inactivos
                    </label>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover" id="table_muestras">
            <thead class="table-dark">
                <tr>
                    <th>N°</th>
                    <th>Nombre</th>
                    <th>Clasificación</th>
                    <th>Volumen</th>
                    <th>Costo Producción</th>
                    <th>Costo Real</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $index => $producto)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $producto->nombre }}</td>
                        <td>{{ $producto->volumen->clasificacion->nombre_clasificacion ?? 'N/A' }}</td>
                        <td>{{ $producto->volumen->nombre ?? ' -  ' }}{{ $producto->volumen->clasificacion->unidadMedida->nombre_unidad_de_medida ?? 'N/A' }}</td>
                        <td>S/ {{ number_format($producto->costo_total_produccion, 2) }}</td>
                        <td>S/ {{ number_format($producto->costo_total_real, 2) }}</td>
                        <td>{{ $producto->stock }}</td>
                        <td>
                            @if($producto->estado === 'activo')
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <div class="w">
                                <a href="{{ route('producto_final.show', $producto->id) }}" class="btn btn-info btn-sm" style="background-color: #17a2b8; border-color: #17a2b8; color: white;"><i class="fa-regular fa-eye"></i>Ver</a>
                                <a href="{{ route('producto_final.edit', $producto->id) }}" class="btn btn-warning btn-sm" style="background-color: #ffc107; border-color: #ffc107; color: white;"><i class="fa-solid fa-pen"></i>Editar</a>
                                <form action="{{ route('producto_final.destroy', $producto->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" style="background-color: #dc3545; border-color: #dc3545;" title="Eliminar" onclick="return confirm('¿Estás seguro?')"><i class="fa-solid fa-trash"></i>Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">No hay productos finales registrados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<style>
    .btn-sm {
        font-size: 1rem; 
        padding: 8px 14px; 
        border-radius: 8px;
        display: flex; 
        align-items: center; 
    }
    .btn i {
        margin-right: 4px; /* Espaciado entre el icono y el texto */
    }
    .w {
        display: flex;
        justify-content: center;
        gap: 10px;
    }
    table thead th {
        background-color: #fe495f;
        color: white;
    }

    table tbody td {
        background-color: rgb(255, 249, 249);
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }

    .table-bordered {
        border-color: #fe495f;
    }
    table th, table td {
        text-align: center;
    }
    td {
        width: 1%;  
        white-space: nowrap; 
    }
</style>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#table_muestras').DataTable({
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
                            .attr('placeholder', 'Buscar por nombre del insumo') // <- aquí el placeholder
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

