<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Clasificación</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="{{ asset('css/muestras/Reporte.css') }}">
</head>
<body>
    <div class="container">
        <div class="cont-report">
            <h1>Reporte de Clasificaciones</h1>

            <!-- Filtro de Mes -->
            <form class="form-graf" method="get" action="{{ route('muestras.reporte') }}">
                <label for="mes">Seleccionar mes:</label>
                <input type="month" name="mes" id="mes" value="{{ $mesSeleccionado }}">
                <button type="submit">Filtrar</button>
            </form>

            <!-- Gráfico de Barras -->
            <div class="chart-container">
                <canvas id="graficoBarras"></canvas>
            </div>

            <!-- Tabla de Clasificaciones y Monto Total -->
            <h3>Clasificaciones y Monto Total</h3>
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
                                <td >{{ $data['nombre_clasificacion'] }}</td>
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
        // Configuración del gráfico con colores del tema
        const primaryColor = '#d6254d';
        const secondaryColor = '#ff5475';
        const accentColor = '#fdeba9';

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
                labels: clasificaciones,
                datasets: [{
                    label: 'Monto Total en Soles',
                    data: montosTotales,
                    backgroundColor: secondaryColor,
                    borderColor: primaryColor,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return 'S/ ' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                const index = tooltipItem.dataIndex;
                                const monto = tooltipItem.raw;
                                const cantidad = cantidadTotal[index];
                                return 'Cantidad: ' + cantidad + ' - Monto Total: S/ ' + monto.toLocaleString();
                            }
                        }
                    },
                    legend: {
                        labels: {
                            font: {
                                size: 14
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

        // Función para redimensionar el gráfico al cambiar el tamaño de la ventana
        window.addEventListener('resize', function() {
            graficoBarras.resize();
        });
    </script>
</body>
</html>