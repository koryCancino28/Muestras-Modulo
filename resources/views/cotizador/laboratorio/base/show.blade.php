@extends('layouts.app')

@section('content')
<div class="container">
    <div class="form-check mb-3">
            <h1 class="text-center"><a class="float-start" title="Volver" href="{{ route('bases.index') }}">
            <i class="bi bi-arrow-left-circle"></i></a>
           Detalles: {{ $base->nombre }}</h1>
        </div>
     <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <!-- Primera columna -->
                <div class="col-md-6">
                    <p><label>Tipo:</label> {{ ucfirst($base->tipo) }}</p>
                    <p><label>Clasificación:</label> {{ $base->clasificacion->nombre_clasificacion ?? '—' }}</p>
                    <p><label>Volumen:</label> {{ $base->volumen->nombre ?? '-' }} {{ $base->unidadDeMedida->nombre_unidad_de_medida ?? '—' }}</p>
                </div>
                <!-- Segunda columna -->
                <div class="col-md-6">
                    <p><label>Precio:</label> S/ {{ number_format($base->precio, 2) }}</p>
                    <p><label>Cantidad en stock:</label> {{ $base->cantidad }}</p>
                </div>
            </div>
        </div>
    </div>

    @if($base->insumos->isNotEmpty())
    <div class="card mt-4">
        <div class="card-header">
            Insumos Utilizados
        </div>
        <div class="card-body">
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Cantidad</th>
                        <th>Costo Unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($base->insumos as $insumo)
                    <tr>
                        <td>{{ $insumo->nombre }}</td>
                        <td>{{ $insumo->pivot->cantidad }}</td>
                        <td>S/ {{ number_format($insumo->precio, 2) }}</td>
                        <td>S/ {{ number_format($insumo->precio * $insumo->pivot->cantidad, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if($base->tipo === 'final' && $base->prebases->isNotEmpty())
    <div class="card mt-4">
        <div class="card-header">
            Prebases Utilizadas
        </div>
        <div class="card-body">
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($base->prebases as $prebase)
                    <tr>
                        <td>{{ $prebase->nombre }}</td>
                        <td>{{ $prebase->pivot->cantidad }}</td>
                        <td>S/ {{ number_format($prebase->precio, 2) }}</td>
                        <td>S/ {{ number_format($prebase->precio * $prebase->pivot->cantidad, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if($base->tipo === 'final' && $base->empaques->isNotEmpty())
    <div class="card mt-4">
        <div class="card-header">
            Empaques Utilizados
        </div>
        <div class="card-body">
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Cantidad</th>
                        <th>Costo Unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($base->empaques as $empaque)
                    <tr>
                        <td>{{ $empaque->nombre }}</td>
                        <td>{{ $empaque->pivot->cantidad }}</td>
                        <td>S/ {{ number_format($empaque->costo, 2) }}</td>
                        <td>S/ {{ number_format($empaque->costo * $empaque->pivot->cantidad, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
@endsection