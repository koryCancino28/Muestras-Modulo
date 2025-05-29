@extends('layouts.app')

@section('content')
<div class="container">
    <div class="form-check mb-3">
        <h1 class="text-center"><a class="float-start" title="Volver" href="{{ route('producto_final.index') }}">
        <i class="bi bi-arrow-left-circle"></i></a>
        Detalle: {{ $producto->nombre }}</h1>
    </div>

    <div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <!-- Primera columna -->
            <div class="col-md-6">
                <p><label>Clasificación:</label> {{ $producto->volumen->clasificacion->nombre_clasificacion ?? 'N/A' }}</p>
                <p><label>Volumen:</label> {{ $producto->volumen->nombre ?? ' - ' }}{{ $producto->volumen->clasificacion->unidadMedida->nombre_unidad_de_medida ?? 'N/A' }}</p>
            </div>

            <!-- Segunda columna -->
            <div class="col-md-6">
                <h5><label>Costos</label></h5>
                <ul>
                    <li><label>Costo total de producción:</label> S/ {{ number_format($producto->costo_total_produccion, 2) }}</li>
                    <li><label>Costo total real (con IGV):</label> S/ {{ number_format($producto->costo_total_real, 2) }}</li>
                    <li><label>Costo publicado:</label> S/ {{ number_format($producto->costo_total_publicado, 2) }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>


    <!-- Insumos -->
    <div class="card mb-4">
        <div class="card-header">Insumos utilizados</div>
        <div class="card-body">
            @if($producto->insumos->isEmpty())
                <p>No se usaron insumos.</p>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario (S/)</th>
                            <th>Total (S/)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($producto->insumos as $insumo)
                            <tr>
                                <td>{{ $insumo->nombre }}</td>
                                <td>{{ $insumo->pivot->cantidad }}</td>
                                <td>S/ {{ number_format($insumo->precio, 2) }}</td>
                                <td>S/ {{ number_format($insumo->precio * $insumo->pivot->cantidad, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- Bases -->
    <div class="card mb-4">
        <div class="card-header">Bases utilizadas</div>
        <div class="card-body">
            @if($producto->bases->isEmpty())
                <p>No se usaron bases.</p>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario (S/)</th>
                            <th>Total (S/)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($producto->bases as $base)
                            <tr>
                                <td>{{ $base->nombre }}</td>
                                <td>{{ $base->pivot->cantidad }}</td>
                                <td>S/ {{ number_format($base->precio, 2) }}</td>
                                <td>S/ {{ number_format($base->precio * $base->pivot->cantidad, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
