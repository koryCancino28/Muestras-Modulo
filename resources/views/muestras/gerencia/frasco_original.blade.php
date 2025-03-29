<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Frasco Original</title>
    <style>
    :root {
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
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    flex: 1;
}

h1, h3 {
    color: var(--primary);
    text-align: center;
    word-wrap: break-word;
    margin-bottom: 15px;
}

h1 {
    font-size: clamp(1.5rem, 5vw, 2rem);
}

h3 {
    font-size: clamp(1.2rem, 4vw, 1.5rem);
    margin-top: 20px;
}

.btn-container {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    justify-content: center;
    margin-bottom: 25px;
}

.btn-exportar, .btn-filtrar {
    padding: 12px 20px;
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
    min-width: 140px;
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
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(214, 37, 77, 0.3);
}

.btn-exportar:active, .btn-filtrar:active {
    transform: translateY(1px);
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
    border-radius: var(--border-radius);
    font-size: clamp(0.9rem, 3vw, 1rem);
    width: 100%;
    max-width: 280px;
    transition: var(--transition);
}

.form-graf input[type="month"]:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 2px rgba(214, 37, 77, 0.2);
}

.table-responsive {
    width: 100%;
    overflow-x: auto;
    margin: 25px 0;
    -webkit-overflow-scrolling: touch;
    box-shadow: 0 0 10px rgba(0,0,0,0.05);
    border-radius: var(--border-radius);
}

table {
    width: 100%;
    min-width: 320px;
    border-collapse: collapse;
}

th, td {
    padding: 12px 10px;
    text-align: left;
    border-bottom: 1px solid #eee;
    font-size: clamp(0.85rem, 3vw, 1rem);
    text-align: center;
}

th {
    background-color: var(--primary);
    color: var(--text-light);
    font-weight: 600;
    position: sticky;
    top: 0;
}

td {
    text-align: center;
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
    margin-top: 30px;
}

.tabla-totales {
    width: 100%;
    min-width: 300px;
    max-width: 450px;
    border-collapse: collapse;
    box-shadow: var(--box-shadow);
    border-radius: var(--border-radius);
    overflow: hidden;
}

.tabla-totales th {
    background-color: var(--secondary);
    padding: 15px;
    font-size: clamp(1rem, 4vw, 1.2rem);
    text-align: center;
}

.tabla-totales td {
    padding: 12px 15px;
    text-align: center;
    font-size: clamp(0.95rem, 3vw, 1.1rem);
}

.tabla-totales tr:last-child {
    background-color: var(--accent);
    font-weight: bold;
}

/* Media Queries mejoradas */
@media (max-width: 768px) {
    .container {
        padding: 12px;
    }
    
    .btn-container {
        flex-direction: column;
        align-items: center;
    }
    
    .btn-exportar, .btn-filtrar {
        width: 100%;
        max-width: 280px;
    }
    
    .form-graf input[type="month"] {
        max-width: 280px;
    }
    
    th, td {
        padding: 10px 8px;
    }
}

@media (max-width: 480px) {
    body {
        padding: 8px;
    }
    
    h1 {
        font-size: 1.5rem;
    }
    
    h3 {
        font-size: 1.2rem;
    }
    
    .btn-exportar, .btn-filtrar {
        min-width: 100%;
        padding: 10px 15px;
    }
    
    .form-graf input[type="month"] {
        max-width: 100%;
    }
    
    th, td {
        padding: 8px 6px;
        font-size: 0.85rem;
    }
    
    .tabla-totales th, 
    .tabla-totales td {
        padding: 10px 8px;
    }
}
</style>
</head>
<body>
    <div class="container">
        <div class="cont-report">
            <h1>Reporte de Muestras - Frasco Original</h1>
            
            <div class="btn-container">
                <form method="get" action="{{ route('muestras.frasco.original.pdf', ['mes' => $mesSeleccionado]) }}">
                    <button type="submit" class="btn-exportar">Exportar a PDF</button>
                </form>

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