@extends('layouts.app')

@section('content')
    <div class="container">
            <h1 class="text-center fw-bold">Formulaciones Registradas</h1>
                <div class="mb-3">
                    <a href="{{ route('bases.create') }}" class="btn btn_crear">+ Crear Nueva</a>
                </div>
            <table class="table table-bordered table-responsive" id="table_muestras">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Presentación<br> Farmaceutica</th>
                        <th>Volumen</th>
                        <th>Tipo</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
            <tbody>
                @foreach($bases as $base)
                <tr>
                    <td>{{ $base->nombre }}</td>
                    <td>{{ $base->clasificacion->nombre_clasificacion ?? '—' }}</td>
                    <td>{{ $base->volumen->nombre ?? '- ' }} {{ $base->unidadDeMedida->nombre_unidad_de_medida ?? '—' }}</td>
                    <td>{{ ucfirst($base->tipo) }}</td> 
                    <td>S/ {{ number_format($base->precio, 2) }}</td>
                    <td>{{ $base->cantidad }}</td>
                    <td>
                        <div class="w">
                            <a href="{{ route('bases.show', $base->id) }}" class="btn btn-info btn-sm" style="background-color: #17a2b8; border-color: #17a2b8; color: white;"><i class="fa-regular fa-eye"></i>Ver</a>
                            <a href="{{ route('bases.edit', $base->id) }}" class="btn btn-warning btn-sm" style="background-color: #ffc107; border-color: #ffc107; color: white;"><i class="fa-solid fa-pen"></i>Editar</a>
                            <form action="{{ route('bases.destroy', $base->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" style="background-color: #dc3545; border-color: #dc3545;"><i class="fa-solid fa-trash"></i>Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
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
            margin-right: 4px; /* Espaciado entre el icono y el texto */
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
