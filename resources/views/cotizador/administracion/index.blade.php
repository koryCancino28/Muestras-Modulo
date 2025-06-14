@extends('layouts.app')

@section('content')
    <div class="mb-3">
            <h1 class="text-center">Crear Insumos</h1>
    </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    <div class="row mb-3 align-items-center">
            <div class="col-md-6">
                <a href="{{ route('insumo_empaque.create') }}" class="btn btn_crear">
                    <i class="fas fa-plus"></i> Nuevo Insumo
                </a>
            </div> 
            <div class="col-md-6 text-end">
                <form method="GET" action="{{ route('insumo_empaque.index') }}" class="mb-0 d-inline-block" id="filterForm">
                    <div class="btn-group" role="group">
                        <a href="{{ route('insumo_empaque.index') }}" 
                        class="btn btn-sm {{ request()->estado != 'inactivo' ? 'btn_crear' : 'btn-outline-danger' }}">
                        Activos
                        </a>
                        <a href="{{ route('insumo_empaque.index', ['estado' => 'inactivo']) }}" 
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
                <th>Tipo</th>
                <th>Nombre</th>
                <th>Precio <br> Unitario</th>
                <th>Precio de <br>última compra</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($insumos as $item)
                <tr>
                    <td>Insumo</td>
                    <td class="observaciones">{{ $item->articulo->nombre }}</td>
                    <td><p>S/ {{ $item->precio }}</p>
                         @if ($item->es_caro)
                             <span class="badge bg-danger">Insumo caro</span>
                         @endif
                    </td>
                    <td> S/ {{ $item->ultimoLote?->precio ?? '--' }}</td>
                    <td>{{ $item->articulo->stock }}</td>
                     <td>
                            <div class="w">
                                <a href="{{ route('insumo_empaque.show', $item->id) }}?tipo=insumo" class="btn btn-info btn-sm" style="background-color: #17a2b8; border-color: #17a2b8; color: white;"><i class="fa-regular fa-eye"></i>Ver</a>
                                <a href="{{ route('insumo_empaque.edit', $item->id) }}?tipo=insumo" class="btn btn-warning btn-sm" style="background-color: #ffc107; border-color: #ffc107; color: white;"><i class="fa-solid fa-pen"></i>Editar</a>
                                <form action="{{ route('insumo_empaque.destroy', $item->id) }}?tipo=insumo" method="POST" onsubmit="return confirm('¿Estás seguro que deseas eliminar este ítem?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                            </div>
                        </td>
                </tr>
            @endforeach

            @foreach ($empaques as $item)
                <tr>
                    <td>{{ ucfirst($item->tipo) }}</td>
                    <td class="observaciones">{{ $item->articulo->nombre }}</td>
                    <td>S/ {{ $item->precio }}</td>
                    <td>S/ {{ $item->ultimoLote?->precio ?? '--' }}</td>
                    <td>{{ $item->articulo->stock }}</td>
                    <td>
                        <div class="w">
                            <a href="{{ route('insumo_empaque.show', $item->id) }}?tipo={{ $item->tipo }}" class="btn btn-info btn-sm" style="background-color: #17a2b8; border-color: #17a2b8; color: white;"><i class="fa-regular fa-eye"></i>Ver</a>
                            <a href="{{ route('insumo_empaque.edit', $item->id) }}?tipo={{ $item->tipo }}" class="btn btn-warning btn-sm" style="background-color: #ffc107; border-color: #ffc107; color: white;"><i class="fa-solid fa-pen"></i>Editar</a>
                            <form action="{{ route('insumo_empaque.destroy', $item->id) }}?tipo={{ $item->tipo }}" method="POST" onsubmit="return confirm('¿Estás seguro que deseas eliminar este ítem?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <style>
        .btn-sm {
        font-size: 1rem; 
        padding: 8px 14px; 
        border-radius: 8px;
        display: flex; 
        align-items: center; 
        }

        .btn-sm i {
            margin-right: 4px; 
        }
        .w {
            display: flex;
            justify-content: center;
            gap: 5px;
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
