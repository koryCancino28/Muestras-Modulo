<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Frasco Original - {{ $mesSeleccionado }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        
        h1, h3 {
            color: #d6254d;
            text-align: center;
            margin-bottom: 15px;
        }
        
        h1 {
            font-size: 1.8rem;
        }
        
        h3 {
            font-size: 1.4rem;
            margin-top: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        
        th {
            background-color: #d6254d;
            color: white;
            font-weight: 600;
            text-align: center;
        }
        
        td {
            text-align: center;
        }
        
        td:first-child {
            text-align: left;
        }
        
        tr:nth-child(even) {
            background-color: #fff1be;
        }
        
        .tabla-totales {
            width: 100%;
            max-width: 450px;
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
        
        .mes-reporte {
            text-align: center;
            font-size: 1.2rem;
            margin-bottom: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="mes-reporte">Reporte del mes: {{ \Carbon\Carbon::parse($mesSeleccionado)->format('m/Y') }}</div>
    
    <h3>Reporte Frasco Original</h3>
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
</body>
</html>