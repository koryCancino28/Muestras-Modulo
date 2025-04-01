<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Frasco Muestra - {{ \Carbon\Carbon::parse($mesSeleccionado)->format('m/Y') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.5;
            padding: 20px;
        }
        
        .mes-reporte {
            text-align: center;
            font-size: 1.2rem;
            margin-bottom: 20px;
            font-weight: bold;
            color: #d6254d;
        }
        
        h3 {
            color: #d6254d;
            text-align: center;
            margin: 15px 0;
            font-size: 1.3rem;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        
        th {
            background-color: #d6254d;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        td:first-child {
            text-align: left;
        }
        
        tr:nth-child(even) {
            background-color: #fff1be;
        }
        
        .tabla-totales {
            width: 100%;
            max-width: 500px;
            margin: 30px auto 0;
            border-collapse: collapse;
        }
        
        .tabla-totales th {
            background-color: #ff5475;
            padding: 12px;
            font-size: 1.1rem;
        }
        
        .tabla-totales tr:last-child {
            background-color: #fff1be;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="mes-reporte">Reporte del mes: {{ \Carbon\Carbon::parse($mesSeleccionado)->format('m/Y') }}</div>
    
    <h3>Tabla de Muestras</h3>
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
</body>
</html>