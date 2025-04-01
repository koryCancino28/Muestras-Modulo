<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <!-- Viewport optimizado para mobile-first -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Reporte Frasco Muestra</title>
    <link rel="stylesheet" href="{{ asset('css/muestras/Reporte.css') }}">
</head>
<body>
    <div class="container">
        <div class="cont-report">
            <h1>Reporte de Muestras - Frasco Muestra</h1>
            
            <div class="btn-container">
            <a href="{{ route('muestras.exportarPDF', ['mes' => $mesSeleccionado]) }}" class="btn btn-exportar">
                <i class="fas fa-file-pdf mr-2"></i> Exportar a PDF
            </a>

                <!-- Filtro de Mes -->
                <form class="form-graf" method="get" action="{{ route('muestras.reporte.frasco-muestra') }}">
                    <label for="mes">Seleccionar Mes:</label>
                    <input type="month" name="mes" id="mes" value="{{ $mesSeleccionado }}">
                    <button type="submit" class="btn-filtrar">Filtrar</button>
                </form>
            </div>

            <!-- Tabla de Muestras -->
            <h3>Tabla de Muestras</h3>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Nombre de Muestra</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario (S/)</th>
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

            <!-- Tabla de Totales -->
            <div class="totales-container">
                <table class="tabla-totales">
                    <thead>
                        <tr>
                            <th colspan="2">Resumen de Totales</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Total de Muestras</td>
                            <td>{{ $totalCantidad }}</td>
                        </tr>
                        <tr>
                            <td>Total de Precio</td>
                            <td>S/ {{ number_format($totalPrecio, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>