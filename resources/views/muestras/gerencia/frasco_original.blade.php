<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Frasco Original</title>
   <link rel="stylesheet" href="{{ asset('css/muestras/Reporte.css') }}">
</head>
<body>
    <div class="container">
        <div class="cont-report">
            <h1>Reporte de Muestras - Frasco Original</h1>
            
            <div class="btn-container">
            <a href="{{ route('muestras.frasco.original.pdf', ['mes' => $mesSeleccionado]) }}" class="btn btn-exportar">
                <i class="fas fa-file-pdf"></i> Exportar a PDF
            </a>

                <form class="form-graf" method="get" action="{{ route('muestras.reporte.frasco-original') }}">
                    <label for="mes">Seleccionar mes:</label>
                    <input type="month" name="mes" id="mes" value="{{ $mesSeleccionado }}">
                    <button type="submit" class="btn-filtrar">Filtrar</button>
                </form>
            </div>

            <h3>Tabla de Muestras</h3>
            <div class="table-responsive">
                <table>
                    <thead> 
                        <tr>
                            <th>Nombre</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Precio Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($muestrasData as $data)
                            <tr>
                                <td>{{ $data['nombre_muestra'] }}</td>
                                <td>{{ $data['cantidad'] }}</td>
                                <td>S/ {{ number_format($data['precio_unidad'], 2) }}</td>
                                <td>S/ {{ number_format($data['precio_total'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="totales-container">
                <table class="tabla-totales">
                    <thead>
                        <tr>
                            <th colspan="2">Resumen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Total Muestras</td>
                            <td>{{ $totalCantidad }}</td>
                        </tr>
                        <tr>
                            <td>Total Precio</td>
                            <td>S/ {{ number_format($totalPrecio, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>