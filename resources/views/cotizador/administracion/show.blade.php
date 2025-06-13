@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center mb-3">
        <a class="btn me-3" title="Volver" href="{{ route('insumo_empaque.index') }}" style="color:#6c757d; font-size: 2.3rem;">
            <i class="bi bi-arrow-left-circle"></i>
        </a>
        <h1 class="flex-grow-1 text-center">Detalles de {{ ucfirst($tipo) }}</h1>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="border-radius: 10px;">
                <div class="card-header" style="background-color: #fe495f; color: white;">
                    <h5><i class="bi bi-info-circle-fill" style="margin-right: 6px;"></i> Información</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong style="color:rgb(224, 61, 80);">Nombre:</strong> {{ $item->articulo->nombre }}</p>

                            @if ($tipo === 'insumo')
                                <p><strong style="color:rgb(224, 61, 80);">Precio:</strong> S/ {{ number_format($item->precio, 2) }}</p>
                                <p><strong style="color:rgb(224, 61, 80);">Unidad de Medida:</strong> {{ $item->unidadMedida->nombre_unidad_de_medida ?? 'N/A' }}</p>
                                <p><strong style="color:rgb(224, 61, 80);">Stock:</strong> {{ $item->articulo->stock }}</p>
                                <p><strong style="color:rgb(224, 61, 80);">¿Es caro?:</strong> 
                                    <span class="badge bg-{{ $item->es_caro ? 'danger' : 'secondary' }}">
                                        {{ $item->es_caro ? 'Sí' : 'No' }}
                                    </span>
                                </p>
                            @else
                                <p><strong style="color:rgb(224, 61, 80);">Tipo:</strong> {{ $item->tipo }}</p>
                                <p><strong style="color:rgb(224, 61, 80);">Costo:</strong> S/ {{ number_format($item->precio, 2) }}</p>
                                <p><strong style="color:rgb(224, 61, 80);">Descripción:</strong> {{ $item->descripcion ?? 'No hay descripción' }}</p>
                                <p><strong style="color:rgb(224, 61, 80);">Stock:</strong> {{ $item->articulo->stock }}</p>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <p><strong style="color:rgb(224, 61, 80);">Estado:</strong>
                                <span class="badge" style="background-color: {{ $item->articulo->estado ? 'green' : 'gray' }}; color: white; padding: 10px;">
                                    {{ $item->articulo->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </p>
                        </div>
                    </div>

                    @if ($tipo === 'insumo')
                        <p><strong style="color:rgb(224, 61, 80);">Observaciones:</strong> {{ $item->observaciones ?? 'Ninguna' }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap & Icon links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/muestras/labora.css') }}">

    <style>
        .card-body {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 10px;
        }
        .card-header {
            font-size: 1.2rem;
        }
        .card-footer {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 0 0 10px 10px;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script> console.log('Detalles del {{ ucfirst($tipo) }} cargados'); </script>
@endsection
