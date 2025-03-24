<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Clasificación</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>

<body>
    <div class="container">
        <div class="cont-report">
            <h1>Reporte de Clasificaciones y Montos Totales</h1>

            <!-- Filtro de Mes -->
            <form class="form-graf" method="get" action="{{ route('muestras.reporte') }}">
                <label for="mes">Seleccionar mes:</label>
                <input type="month" name="mes" id="mes" value="{{ $mesSeleccionado }}">
                <button type="submit">Filtrar</button>
            </form>

            <!-- Gráfico de Barras -->
            <canvas id="graficoBarras" width="400" height="200"></canvas>

            <!-- Tabla de Clasificaciones y Monto Total -->
            <h3>Tabla de Clasificaciones y Monto Total</h3>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Clasificación</th>
                            <th>Cantidad</th>
                            <th>Monto Total (S/)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($muestrasData as $data)
                            <tr>
                                <td>{{ $data['nombre_clasificacion'] }}</td>
                                <td>{{ $data['cantidad'] }}</td>
                                <td>{{ number_format($data['monto_total'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Verificar si los datos están llegando correctamente al frontend
        console.log("Clasificaciones:", @json($clasificacionLabels));
        console.log("Montos Totales:", @json($montosTotales));
        console.log("Cantidad Total:", @json($cantidadTotal));

        // Obtener los datos pasados desde el controlador
        const clasificaciones = @json($clasificacionLabels);
        const montosTotales = @json($montosTotales);
        const cantidadTotal = @json($cantidadTotal);

        // Verificar si hay datos vacíos o nulos
        if (clasificaciones.length === 0 || montosTotales.length === 0 || cantidadTotal.length === 0) {
            alert('No se encontraron datos para mostrar.');
        }

        // Configuración del gráfico de barras
        const ctx = document.getElementById('graficoBarras').getContext('2d');
        const graficoBarras = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: clasificaciones, // Etiquetas en el eje X (clasificaciones)
                datasets: [{
                    label: 'Monto Total en Soles',
                    data: montosTotales, // Datos en el eje Y (monto total)
                    backgroundColor: '#3498db',
                    borderColor: '#2980b9',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return 'S/ ' + value.toLocaleString(); // Formato en soles
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            // Muestra el monto total y la cantidad encima de cada barra
                            label: function (tooltipItem) {
                                const index = tooltipItem.dataIndex;
                                const monto = tooltipItem.raw;
                                const cantidad = cantidadTotal[index];
                                return 'Cantidad: ' + cantidad + ' - Monto Total: S/ ' + monto.toLocaleString();
                            }
                        }
                    }
                },
                onClick: function (e) {
                    var activePoints = graficoBarras.getElementsAtEventForMode(e, 'nearest', { intersect: true }, true);
                    if (activePoints.length > 0) {
                        var firstPoint = activePoints[0];
                        var value = graficoBarras.data.datasets[firstPoint.datasetIndex].data[firstPoint.index];
                        alert('Monto Total: S/ ' + value.toLocaleString());
                    }
                }
            }
        });
    </script>
</body>

</html>
