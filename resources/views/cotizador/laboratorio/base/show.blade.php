@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalles de la Base: {{ $base->nombre }}</h1>

    <div class="card">
        <div class="card-header">
            Información General
        </div>
        <div class="card-body">
            <p><strong>Tipo:</strong> {{ ucfirst($base->tipo) }}</p>
            <p><strong>Clasificación:</strong> {{ $base->clasificacion->nombre_clasificacion ?? '—' }}</p>
            <p><strong>Volumen:</strong> {{ $base->volumen->nombre ?? '-' }} {{ $base->unidadDeMedida->nombre_unidad_de_medida ?? '—' }}</p>
            <p><strong>Precio:</strong> S/ {{ number_format($base->precio, 2) }}</p>
            <p><strong>Cantidad en stock:</strong> {{ $base->cantidad }}</p>
        </div>
    </div>

    @if($base->insumos->isNotEmpty())
    <div class="card mt-4">
        <div class="card-header">
            Insumos Utilizados
        </div>
        <div class="card-body">
            <table class="table">
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
            <table class="table">
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
            <table class="table">
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

    <div class="mt-4">
        <a href="{{ route('bases.index') }}" class="btn btn-secondary">Volver al listado</a>
    </div>
</div>
@endsection