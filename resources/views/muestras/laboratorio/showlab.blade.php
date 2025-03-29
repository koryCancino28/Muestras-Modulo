<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muestras</title>
    <link rel="shortcut icon" href="{{ asset('imgs/favicon.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="{{ asset('css/muestras/home.css') }}">
</head>
<body>

<h1 class="text-center mt-5 mb-5 fw-bold">  </h1>
<div class="container">
<h1 class="text-center">
   <a class="float-start" title="Volver" href="{{ route('muestras.estado') }}">
      <i class="bi bi-arrow-left-circle"></i>
   </a>
   Datos de la muestra <hr>
</h1>

        <ul class="list-group list-group-flush">
        <li class="list-group-item"> 
            Nombre de la muestra: &nbsp; &nbsp; 
            <strong>{{ $muestra->nombre_muestra }}</strong>
        </li>
        
        <!-- Clasificación -->
        <li class="list-group-item">
            Clasificación: &nbsp; &nbsp; 
            <strong>
                {{ $muestra->clasificacion ? $muestra->clasificacion->nombre_clasificacion : 'No disponible' }}
            </strong>
        </li>
        
        <!-- Tipo de muestra -->
        <li class="list-group-item"> 
            Tipo de muestra: &nbsp; &nbsp; 
            <strong>{{ $muestra->tipo_muestra }}</strong>
        </li>
        
      <!-- Unidad de medida accediendo a través de la relación -->
      <li class="list-group-item">
                Unidad de medida: &nbsp; &nbsp;
                <strong>
                    @if($muestra->clasificacion && $muestra->clasificacion->unidadMedida)
                        {{ $muestra->clasificacion->unidadMedida->nombre_unidad_de_medida }}
                    @else
                        No asignada
                    @endif
                </strong>
            </li>
        <!-- Aprobación por Jefe Comercial -->
        <li class="list-group-item">
            Aprobado por Jefe Comercial: &nbsp; &nbsp;
            <span class="badge" 
                style="background-color: {{ $muestra->aprobado_jefe_comercial ? 'green' : ($muestra->aprobado_coordinadora ? 'yellow' : 'red') }}; 
                color: {{ ($muestra->aprobado_jefe_comercial == false && $muestra->aprobado_coordinadora == false) || $muestra->aprobado_jefe_comercial ? 'white' : 'black' }}; 
                padding: 10px;">
                {{ $muestra->aprobado_jefe_comercial ? 'Aprobado' : 'Pendiente' }}
            </span>
        </li>
        
        <!-- Aprobación por Coordinadora -->
        <li class="list-group-item">
            Aprobado por Coordinadora: &nbsp; &nbsp;
            <span class="badge" 
                style="background-color: {{ $muestra->aprobado_coordinadora ? 'green' : ($muestra->aprobado_jefe_comercial ? 'yellow' : 'red') }}; 
                color: {{ ($muestra->aprobado_coordinadora == false && $muestra->aprobado_jefe_comercial == false) || $muestra->aprobado_coordinadora ? 'white' : 'black' }}; 
                padding: 10px;">
                {{ $muestra->aprobado_coordinadora ? 'Aprobado' : 'Pendiente' }}
            </span>
        </li>
        
        <li class="list-group-item"> 
            Cantidad: &nbsp; &nbsp; 
            <strong>{{ $muestra->cantidad_de_muestra }}</strong>
        </li>
        
        <li class="list-group-item"> 
            Observaciones: &nbsp; &nbsp; 
            <strong>{{ $muestra->observacion }}</strong>
        </li>

        <!-- Estado -->
        <li class="list-group-item">
            Estado: &nbsp; &nbsp;
            <span class="badge" 
                style="background-color: {{ $muestra->estado == 'Pendiente' ? 'red' : 'green' }}; color: white; padding: 10px;">
                {{ $muestra->estado }}
            </span>
        </li>
        
        <!-- Fecha y Hora Recibida -->
        <li class="list-group-item">
            Fecha y Hora Recibida: &nbsp; &nbsp;
            <input type="text" class="form-control" 
                value="{{ $muestra->updated_at ? \Carbon\Carbon::parse($muestra->updated_at)->format('Y-m-d H:i') : ($muestra->created_at ? \Carbon\Carbon::parse($muestra->created_at)->format('Y-m-d H:i') : 'No disponible') }}" 
                readonly style="background-color:rgb(225, 255, 207); color: #555; border: 2px solid #ccc; font-weight: bold;">
        </li>

        <!-- Fecha y Hora de Entrega -->
        <li class="list-group-item">
            Fecha y Hora de Entrega: &nbsp; &nbsp;
            <input type="text" class="form-control" 
                value="{{ $muestra->fecha_hora_entrega ? \Carbon\Carbon::parse($muestra->fecha_hora_entrega)->format('Y-m-d H:i') : 'No disponible' }}" 
                readonly style="background-color: #f4f4f4; color: #555; border: 2px solid #ccc; font-weight: bold;">
        </li>
        </ul>
</div>

<h1 class="text-center mt-5 mb-5 fw-bold">  </h1>
</body>
</html>