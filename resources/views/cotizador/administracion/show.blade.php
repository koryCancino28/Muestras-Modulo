@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Detalle de {{ ucfirst($tipo) }}</h4>
            <a href="{{ route('insumo_empaque.index') }}" class="btn btn-light btn-sm">Volver</a>
        </div>

        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-4">Nombre</dt>
                <dd class="col-sm-8">{{ $item->articulo->nombre }}</dd>

                @if ($tipo === 'insumo')
                    <dt class="col-sm-4">Precio</dt>
                    <dd class="col-sm-8">S/ {{ number_format($item->precio, 2) }}</dd>

                    <dt class="col-sm-4">Unidad de Medida</dt>
                    <dd class="col-sm-8">{{ $item->unidadMedida->nombre_unidad_de_medida ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Stock</dt>
                    <dd class="col-sm-8">{{ $item->articulo->stock }}</dd>

                    <dt class="col-sm-4">¿Es caro?</dt>
                    <dd class="col-sm-8">
                        <span class="badge bg-{{ $item->es_caro ? 'danger' : 'secondary' }}">
                            {{ $item->es_caro ? 'Sí' : 'No' }}
                        </span>
                    </dd>
                    <dt class="col-sm-4">Estado</dt>
                    <dd class="col-sm-8">
                        <span class="badge bg-{{ $item->articulo->estado ? 'success' : 'secondary' }}">
                            {{ $item->articulo->estado ? 'Activo' : 'Inactivo' }}
                        </span>
                    </dd>
                @else
                    <dt class="col-sm-4">Tipo</dt>
                    <dd class="col-sm-8 text-capitalize">{{ $item->tipo }}</dd>

                    <dt class="col-sm-4">Costo</dt>
                    <dd class="col-sm-8">S/ {{ number_format($item->precio, 2) }}</dd>

                    <dt class="col-sm-4">Descripción</dt>
                    <dd class="col-sm-8">{{ $item->descripcion ?? 'No hay descripción' }}</dd>

                    <dt class="col-sm-4">Stock</dt>
                    <dd class="col-sm-8">{{ $item->articulo->stock }}</dd>

                    <dt class="col-sm-4">Estado</dt>
                    <dd class="col-sm-8">
                        <p class="badge bg-{{ $item->articulo->estado === 'activo' ? 'success' : 'secondary' }}">
                        {{ $item->articulo->estado === 'activo' ? 'Activo' : 'Inactivo' }}</p>
                    </dd>
                @endif
            </dl>
        </div>
    </div>
</div>
@endsection
