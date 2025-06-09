@extends('layouts.app')

@section('content')
<div class="container">
    <div class="form-check mb-3">
        <h1 class="text-center">
            Listado de Útiles
        </h1>
    </div>
    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <button type="button" class="btn btn_crear" data-bs-toggle="modal" data-bs-target="#crearUtilModal">
                <i class="fa-solid fa-square-plus"></i>Crear Útil
            </button>
        </div>
            @include('cotizador.util.create')

        <div class="col-md-6 text-end">
            <form method="GET" action="{{ route('util.index') }}" class="mb-0 d-inline-block" id="filterForm">
                <div class="btn-group" role="group">
                    <a href="{{ route('util.index') }}" 
                    class="btn btn-sm {{ request()->estado != 'inactivo' ? 'btn_crear' : 'btn-outline-danger' }}">
                    Activos
                    </a>
                    <a href="{{ route('util.index', ['estado' => 'inactivo']) }}" 
                    class="btn btn-sm {{ request()->estado == 'inactivo' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                    Inactivos
                    </a>
                </div>
            </form>
        </div>
    </div>
    <table class="table table-bordered table-responsive table-hover" id="table_muestras">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio <br> Unitario</th>
                <th>Precio de <br> última compra</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($utiles as $index => $util)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="observaciones">{{ $util->articulo->nombre ?? 'Sin nombre' }}</td>
                    <td>{{ $util->precio ?? 'Sin precio' }}</td>
                    <td> S/ {{ $util->ultimoLote?->precio ?? '--' }}</td>
                    <td>{{ $util->articulo->stock }}</td>
                    <td>
                        <div class="w">
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditar{{ $util->articulo_id }}">
                                <i class="fa-solid fa-pen"></i> Editar
                            </button>
                            @include('cotizador.util.edit', ['item' => $util])

                            <form action="{{ route('util.destroy', $util->articulo_id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas marcarlo como inactivo?')" >
                                @csrf
                                @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" style="background-color: #dc3545; border-color: #dc3545;" title="Eliminar"><i class="fa-solid fa-trash"></i>Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
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