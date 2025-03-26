<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <!-- Viewport optimizado para mobile-first -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Reporte Frasco Muestra</title>
    <style>:root {
    --primary: #d6254d;
    --secondary: #ff5475;
    --accent: #fff1be;
    --text-dark: #333;
    --text-light: #fff;
    --bg-light: #fff9f0;
    --border-radius: 8px;
    --box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    --transition: all 0.3s ease;
}

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    -webkit-tap-highlight-color: transparent;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: var(--bg-light);
    color: var(--text-dark);
    line-height: 1.6;
    padding: 10px;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

.container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 15px;
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    flex: 1;
}

h1, h3 {
    color: var(--primary);
    text-align: center;
    word-wrap: break-word;
}

h1 {
    margin-bottom: 15px;
    font-size: clamp(1.5rem, 5vw, 2.2rem);
}

h3 {
    margin: 15px 0;
    font-size: clamp(1.2rem, 4vw, 1.5rem);
}

.btn-container {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    justify-content: center;
    margin-bottom: 20px;
}

.btn-exportar, .btn-filtrar {
    padding: 10px 20px;
    border: none;
    border-radius: 30px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    font-size: clamp(0.9rem, 3vw, 1rem);
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 120px;
}

.btn-exportar {
    background-color: var(--primary);
    color: var(--text-light);
}

.btn-filtrar {
    background-color: var(--secondary);
    color: var(--text-light);
}

.btn-exportar:hover, .btn-filtrar:hover {
    transform: translateY(-5px);
    box-shadow: 0 7px 20px rgba(214, 37, 77, 0.4);
}

.btn-exportar:active, .btn-filtrar:active {
    transform: translateY(-2px);
}

.form-graf {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
    justify-content: center;
    width: 100%;
}

.form-graf label {
    font-size: clamp(0.9rem, 3vw, 1rem);
    flex: 1 1 100%;
    text-align: center;
}

.form-graf input[type="month"] {
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: clamp(0.9rem, 3vw, 1rem);
    width: 100%;
    max-width: 280px;
}

.table-responsive {
    width: 100%;
    overflow-x: auto;
    margin: 20px 0;
    -webkit-overflow-scrolling: touch;
    box-shadow: 0 0 5px rgba(0,0,0,0.05);
    border-radius: 8px;
}

table {
    width: 100%;
    min-width: 300px;
    border-collapse: collapse;
}

th, td {
    padding: 10px 8px;
    text-align: left;
    border-bottom: 1px solid #eee;
    font-size: clamp(0.85rem, 3vw, 1rem);
    text-align: center;
}

th {
    background-color: var(--primary);
    color: var(--text-light);
    font-weight: 600;
    text-transform: uppercase;
    position: sticky;
    top: 0;
}

td:first-child {
    text-align: left;
    word-break: break-word;
}

tr:nth-child(even) {
    background-color: var(--accent);
}

tr:hover {
    background-color: rgba(255, 84, 117, 0.1);
}

.totales-container {
    width: 100%;
    display: flex;
    justify-content: center;
    margin-top: 25px;
}

.tabla-totales {
    width: 100%;
    min-width: 300px;
    max-width: 500px;
    border-collapse: collapse;
    box-shadow: var(--box-shadow);
    border-radius: var(--border-radius);
    overflow: hidden;
}

.tabla-totales th {
    background-color: var(--secondary);
    padding: 12px;
    font-size: clamp(1rem, 4vw, 1.2rem);
    text-align: center;
}

.tabla-totales td {
    padding: 10px 12px;
    text-align: center;
    font-size: clamp(0.95rem, 3vw, 1.1rem);
}

.tabla-totales tr:last-child {
    background-color: var(--accent);
    font-weight: bold;
}

/* Media Queries mejoradas */
@media screen and (max-width: 768px) {
    body {
        padding: 8px;
    }
    
    .container {
        padding: 12px;
    }
    
    .btn-container {
        flex-direction: column;
        align-items: center;
    }
    
    .btn-exportar, .btn-filtrar {
        width: 100%;
        max-width: 100%;
    }
    
    .form-graf {
        flex-direction: column;
    }
    
    .form-graf input[type="month"] {
        max-width: 100%;
    }
    
    th, td {
        padding: 8px 6px;
    }
}

@media screen and (max-width: 480px) {
    h1 {
        font-size: 1.5rem;
    }
    
    h3 {
        font-size: 1.2rem;
    }
    
    th, td {
        padding: 6px 4px;
        font-size: 0.85rem;
    }
    
    .tabla-totales th, 
    .tabla-totales td {
        padding: 8px 6px;
    }
}</style>
</head>
<body>
    <div class="container">
        <div class="cont-report">
            <h1>Reporte de Muestras - Frasco Muestra</h1>
            
            <div class="btn-container">
                <form method="get" action="{{ route('muestras.exportarPDF') }}">
                    <button type="submit" class="btn-exportar">Exportar a PDF</button>
                </form>

                <!-- Filtro de Mes -->
                <form class="form-graf" method="get" action="{{ route('muestras.reporte.frasco-muestra') }}">
                    <label for="mes">Mes:</label>
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