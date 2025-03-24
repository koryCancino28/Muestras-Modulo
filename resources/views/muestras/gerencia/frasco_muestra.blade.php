<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Frasco Muestra</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
     <!-- Toastr CSS -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

</head>
</head>
<body>
    <h1 class="text-center mt-5 mb-5 fw-bold"></h1>
    
    <div class="container">
        <div class="cont-report">
            <h1>Reporte de Muestras - Frasco Muestra</h1>
            
            <form method="get" action="{{ route('muestras.exportarPDF') }}">
    <button type="submit">Exportar a PDF</button>
</form>

            <!-- Filtro de Mes -->
            <form class="form-graf" method="get" action="{{ route('muestras.reporte.frasco-muestra') }}">
                <label for="mes">Seleccionar mes:</label>
                <input type="month" name="mes" id="mes" value="{{ $mesSeleccionado }}">
                <button type="submit">Filtrar</button>
            </form>

            <!-- Tabla de Muestras - Frasco Muestra -->
            <h3>Tabla de Muestras - Frasco Muestra</h3>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Nombre de Muestra</th>
                            <th>Cantidad</th>
                            <th>Precio por Unidad (S/)</th>
                            <th>Precio Total (S/)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($muestrasData as $data)
                            <tr>
                                <td>{{ $data['nombre_muestra'] }}</td>
                                <td>{{ $data['cantidad'] }}</td>
                                <td>{{ number_format($data['precio_unidad'], 2) }}</td>
                                <td>{{ number_format($data['precio_total'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totales -->
            <div class="totales">
                <h4>Total de Cantidad: {{ $totalCantidad }}</h4>
                <h4>Total de Precio: S/ {{ number_format($totalPrecio, 2) }}</h4>
            </div>
        </div>
    </div>
</body>
</html>
