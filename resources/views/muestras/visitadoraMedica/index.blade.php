<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muestras Registradas</title>
    <link rel="shortcut icon" href="{{ asset('imgs/favicon.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('css/muestras/home.css') }}">
    <style>
        /* Estilos para el contenedor de herramientas */
        .header-tools {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        /* Estilo para el buscador de DataTables */
        .dataTables_filter {
            display: flex;
            align-items: center;
        }
        
        .dataTables_filter label {
            margin-bottom: 0;
            margin-right: 10px;
        }
        
        .dataTables_filter input {
            width: 250px !important;
        }
      
    </style>
</head>

<body>
<h1 class="text-center mt-5 mb-5 fw-bold">  </h1>

    <div class="container">
        @include('messages')
        <h1 class="text-center">Muestras Registradas <hr></h1>

        <div class="header-tools">
            <a href="{{ route('muestras.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Agregar Muestra
            </a>
            <!-- el buscador de DataTables se colocará aqui -->
        </div>

        <div class="table-responsive">
            <table class="table table-hover" id="table_muestras">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre de la Muestra</th>
                        <th scope="col">Clasificación</th>
                        <th scope="col">Unidad de<br>Medida</th>
                        <th scope="col">Tipo de Muestra</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Observaciones</th>
                        <th scope="col">Fecha/hora<br>Registrada</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($muestras as $index => $muestra)
                        <tr id="muestra_{{ $muestra->id }}">
                            <td>{{ $index + 1 }}</td>
                            <td class="observaciones">{{ $muestra->nombre_muestra }}</td>
                            <td>{{ $muestra->clasificacion ? $muestra->clasificacion->nombre_clasificacion : 'Sin clasificación' }}</td>
                            <td>
                                @if($muestra->clasificacion && $muestra->clasificacion->unidadMedida)
                                    {{ $muestra->clasificacion->unidadMedida->nombre_unidad_de_medida }}
                                @else
                                    No asignada
                                @endif
                            </td>
                            <td>{{ ucfirst($muestra->tipo_muestra) ?? 'No asignado' }}</td>
                            <td>{{ $muestra->cantidad_de_muestra }}</td>
                            <td class="observaciones">{{ $muestra->observacion }}</td>
                            <td>
                                {{ $muestra->updated_at ? $muestra->updated_at->format('Y-m-d') : $muestra->created_at->format('Y-m-d') }}<br>
                                {{ $muestra->updated_at ? $muestra->updated_at->format('H:i:s') : $muestra->created_at->format('H:i:s') }}
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a title="Ver detalles" href="{{ route('muestras.show', $muestra->id) }}" class="btn btn-success btn-sm">
                                        <i class="bi bi-binoculars"></i>
                                    </a>
                                    <a href="{{ route('muestras.edit', $muestra->id) }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-pencil-square"></i>   
                                    </a>
                                    <form action="{{ route('muestras.destroy', $muestra->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Desea eliminar esta muestra?');">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
<h1 class="text-center mt-5 mb-5 fw-bold">  </h1>

    <!-- Scripts necesarios para DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        pageLength: 10,
        initComplete: function() {
            $('.dataTables_filter')
                .appendTo('.header-tools')
                .find('input')  // Selecciona el input de búsqueda
                .attr('placeholder', 'Buscar por nombre de la muestra')  // Agrega el placeholder
                .end()  // Vuelve al contenedor del filtro
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
</body>
</html>