<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muestras Registradas</title>
    <link rel="shortcut icon" href="{{ asset('imgs/favicon.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/muestras/home.css') }}">
</head>

<body>
<h1 class="text-center mt-5 mb-5 fw-bold">  </h1>

    <div class="container">
    @include('messages')
        <h1 class="text-center">Muestras Registradas <hr></h1>

        <div class="d-flex justify-content-center mb-3">
            <a href="{{ route('muestras.create') }}" class="btn btn-primary me-3">
                <i class="bi bi-plus-circle"></i> Agregar Muestra
            </a>
            <input type="text" id="searchInput" class="form-control w-75" placeholder="Buscar por las primeras 5 letras del nombre de la Muestra" onkeyup="filterTable()">
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

    <script>
        function filterTable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toUpperCase();
            const table = document.getElementById('table_muestras');
            const tr = table.getElementsByTagName('tr');
            
            for (let i = 1; i < tr.length; i++) {
                const td = tr[i].getElementsByTagName('td')[1]; // Nombre de la muestra
                if (td) {
                    const txtValue = td.textContent || td.innerText;
                    if (txtValue.substring(0, 5).toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
</body>
</html>