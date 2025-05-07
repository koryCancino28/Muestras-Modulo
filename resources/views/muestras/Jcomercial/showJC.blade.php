
<div class="container mt-2" style="background-color: #ffffff; padding: 20px; border-radius: 10px; box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);">
    <div class="d-flex align-items-center mb-3">
        <a class="btn me-3" title="Volver" href="{{ route('muestras.confirmar') }}" style="color:#6c757d; font-size: 2.3rem;">
            <i class="bi bi-arrow-left-circle"></i>
        </a>
        <h1 class="flex-grow-1 text-center" style="color: #fe495f; font-weight: bold; margin: 0;">
            Datos de la Muestra
        </h1>
    </div>

    <div class="row">
        <!-- Card Información General -->
        <div class="col-md-6 mb-4">
            <div class="card" style="border-radius: 10px;">
                <div class="card-header">
                    <h5><i class="bi bi-info-circle" style="margin-right: 6px;"></i> Información General</h5>
                </div>
                <div class="card-body">
                    <p><strong class="text-danger">Nombre de la muestra:</strong> {{ $muestra->nombre_muestra }}</p>
                    <p><strong class="text-danger">Clasificación:</strong> {{ $muestra->clasificacion ? $muestra->clasificacion->nombre_clasificacion : 'No disponible' }}</p>
                    <p><strong class="text-danger">Tipo de muestra:</strong> {{ $muestra->tipo_muestra }}</p>
                    <p><strong class="text-danger">Unidad de medida:</strong>
                        @if($muestra->clasificacion && $muestra->clasificacion->unidadMedida)
                            {{ $muestra->clasificacion->unidadMedida->nombre_unidad_de_medida }}
                        @else
                            No asignada
                        @endif
                    </p>
                    <p><strong class="text-danger">Cantidad:</strong> {{ $muestra->cantidad_de_muestra }}</p>
                    <p><strong class="text-danger">Observaciones:</strong> {{ $muestra->observacion }}</p>
                    <p><strong class="text-danger">Doctor:</strong> {{ $muestra->name_doctor }}</p>
                    <p><strong class="text-danger">Creado por:</strong> {{ $muestra->creator ? $muestra->creator->name : 'Desconocido' }}</p>
                    <p><strong class="text-danger">Comentario de Laboratorio:</strong></p>
                    <span>{{ $muestra->comentarios ?? 'No hay comentarios' }}</span>
                </div>
            </div>
        </div>

        <!-- Card Estado y Fechas -->
        <div class="col-md-6 mb-4">
            <div class="card" style="border-radius: 10px;">
                <div class="card-header">
                    <h5><i class="bi bi-clock-history" style="margin-right: 6px;"></i> Estado y Fechas</h5>
                </div>
                <div class="card-body">
                    <p><strong class="text-danger">Aprobado por Jefe Comercial:</strong>
                        <span class="badge"
                              style="background-color: {{ $muestra->aprobado_jefe_comercial ? 'green' : ($muestra->aprobado_coordinadora ? 'yellow' : 'red') }};
                                     color: {{ ($muestra->aprobado_jefe_comercial == false && $muestra->aprobado_coordinadora == false) || $muestra->aprobado_jefe_comercial ? 'white' : 'black' }};
                                     padding: 10px;">
                            {{ $muestra->aprobado_jefe_comercial ? 'Aprobado' : 'Pendiente' }}
                        </span>
                    </p>

                    <p><strong class="text-danger">Aprobado por Coordinadora:</strong>
                        <span class="badge"
                              style="background-color: {{ $muestra->aprobado_coordinadora ? 'green' : ($muestra->aprobado_jefe_comercial ? 'yellow' : 'red') }};
                                     color: {{ ($muestra->aprobado_coordinadora == false && $muestra->aprobado_jefe_comercial == false) || $muestra->aprobado_coordinadora ? 'white' : 'black' }};
                                     padding: 10px;">
                            {{ $muestra->aprobado_coordinadora ? 'Aprobado' : 'Pendiente' }}
                        </span>
                    </p>

                    <p><strong class="text-danger">Estado:</strong>
                        <span class="badge" style="background-color: {{ $muestra->estado == 'Pendiente' ? 'red' : 'green' }}; color: white; padding: 10px;">
                            {{ $muestra->estado }}
                        </span>
                    </p>

                    <p><strong class="text-danger">Fecha y Hora Recibida:</strong></p>
                    <input type="text" class="form-control mb-2"
                           value="{{ $muestra->updated_at ? \Carbon\Carbon::parse($muestra->updated_at)->format('Y-m-d H:i') : ($muestra->created_at ? \Carbon\Carbon::parse($muestra->created_at)->format('Y-m-d H:i') : 'No disponible') }}"
                           readonly style="background-color:rgb(251, 239, 252); color: #555; border: 2px solid #ccc; font-weight: bold;">
                </div>
            </div>
        </div>
    </div>
</div>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/muestras/home.css') }}">
    <style>
        .card-header {
            background-color: #fe495f;
            color: white;
            font-size: 1.2rem;
        }

        .card-body {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 10px;
        }

        .text-danger {
            color: rgb(224, 61, 80) !important;
        }

        .badge {
            font-size: 0.9rem;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

