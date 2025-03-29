<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Muestra</title>
    <link rel="shortcut icon" href="{{ asset('imgs/favicon.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/muestras/home.css') }}">
</head>

<body>
    <div class="container py-4">
        <h1 class="text-center mb-4">
            <a class="float-start text-decoration-none" title="Volver" href="{{ route('muestras.index') }}">
                <i class="bi bi-arrow-left-circle fs-1 text-primary"></i>
            </a>
            Detalles de la Muestra
            <hr class="mt-3">
        </h1>

        <div class="card shadow-sm">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center"> 
                    <span class="text-muted">Nombre de la muestra:</span>
                    <strong class="text-end">{{ $muestra->nombre_muestra }}</strong>
                </li>
                
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span class="text-muted">Clasificación:</span>
                    <strong class="text-end">
                        {{ $muestra->clasificacion ? $muestra->clasificacion->nombre_clasificacion : 'No disponible' }}
                    </strong>
                </li>

                <li class="list-group-item d-flex justify-content-between align-items-center"> 
                    <span class="text-muted">Unidad de medida:</span>
                    <strong class="text-end">
                        @if($muestra->clasificacion && $muestra->clasificacion->unidadMedida)
                            {{ $muestra->clasificacion->unidadMedida->nombre_unidad_de_medida }}
                        @else
                            No disponible
                        @endif
                    </strong>
                </li>
                
                <li class="list-group-item d-flex justify-content-between align-items-center"> 
                    <span class="text-muted">Cantidad:</span>
                    <strong class="text-end">{{ $muestra->cantidad_de_muestra }}</strong>
                </li>
                
                <li class="list-group-item d-flex justify-content-between align-items-center"> 
                    <span class="text-muted">Tipo de muestra:</span>
                    <strong class="text-end">{{ ucfirst($muestra->tipo_muestra) }}</strong>
                </li>
                
                <li class="list-group-item d-flex justify-content-between align-items-center"> 
                    <span class="text-muted">Observaciones:</span>
                    <strong class="text-end">{{ $muestra->observacion ?? 'Sin observaciones' }}</strong>
                </li>
                
                <li class="list-group-item d-flex justify-content-between align-items-center"> 
                    <span class="text-muted">Fecha de registro:</span>
                    <strong class="text-end">{{ $muestra->created_at->format('d/m/Y H:i') }}</strong>
                </li>
                
                <li class="list-group-item d-flex justify-content-between align-items-center"> 
                    <span class="text-muted">Última actualización:</span>
                    <strong class="text-end">{{ $muestra->updated_at->format('d/m/Y H:i') }}</strong>
                </li>
            </ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>