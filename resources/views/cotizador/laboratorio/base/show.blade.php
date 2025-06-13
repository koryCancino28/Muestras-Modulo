@extends('layouts.app')

@section('content')
<div class="container">
    <div class="form-check mb-3">
        <h1 class="text-center"><a class="float-start" title="Volver" href="{{ route('bases.index') }}">
        <i class="bi bi-arrow-left-circle"></i></a>
        Detalles: {{ $base->articulo->nombre }}</h1>
    </div>
     <div class="card mb-4" style="border-radius: 10px;border: 2px solid #fe495f;">
        <div class="card-body">
            <div class="row">
                <!-- Primera columna -->
                <div class="col-md-6">
                    <p><label>Tipo:</label> {{ ucfirst($base->tipo) }}</p>
                    <p><label>Clasificación:</label> {{ $base->volumen->clasificacion->nombre_clasificacion ?? '—' }}</p>
                    <p><label>Volumen:</label> {{ $base->volumen->nombre ?? '-' }} {{ $base->volumen->clasificacion->unidadMedida->nombre_unidad_de_medida ?? 'N/A' }}</p>
                </div>
                <!-- Segunda columna -->
                <div class="col-md-6">
                    <p><label>Precio:</label> S/ {{ number_format($base->precio, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    @if($base->insumos->isNotEmpty())
    <div class="card mt-4">
        <div class="card-header" style="background-color:rgb(254, 107, 124); color: white;"><i class="fa-solid fa-atom"></i>
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
                        <td>{{ $insumo->articulo->nombre }}</td>
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
        <div class="card-header" style="background-color:rgb(254, 107, 124); color: white;"><i class="fa-solid fa-flask"></i>
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
                        <td>{{ $prebase->articulo->nombre }}</td>
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
        <div class="card-header" style="background-color:rgb(254, 107, 124); color: white;"><i class="fa-solid fa-box"></i>
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
                        <td>{{ $empaque->articulo->nombre }}</td>
                        <td>{{ $empaque->pivot->cantidad }}</td>
                        <td>S/ {{ number_format($empaque->precio, 2) }}</td>
                        <td>S/ {{ number_format($empaque->precio * $empaque->pivot->cantidad, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
@endsection