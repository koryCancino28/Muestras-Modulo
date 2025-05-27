@extends('layouts.app')

@section('content')
<h1 class="mb-4 text-center">Tipos de Moneda</h1>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <a href="{{ route('tipo_cambio.create') }}" class="btn btn_crear btn-w" data-bs-toggle="modal" data-bs-target="#crearTipoCambioModal">
            <i class="fa-solid fa-square-plus"></i>Agregar tipo de cambio
        </a>
        @include('tipo_cambio.create', ['monedas' => $monedas ?? \App\Models\TipoMoneda::all()])
    </div>
    <div>
        <a href="{{ route('tipo_cambio.index') }}" class="btn btn-success btn-w"><i class="fa-solid fa-file-invoice-dollar"></i>Ver Cambios</a>
    </div>
</div>

<table class="table table-bordered table-responsive">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Código ISO</th>
            <th>Último Valor de Cambio</th>
            <th>Fecha del Cambio</th>
  
        </tr>
    </thead>
    <tbody>
        @foreach($monedas as $moneda)
            <tr>
                <td>{{ $moneda->id }}</td>
                <td>{{ $moneda->nombre }}</td>
                <td>{{ $moneda->codigo_iso }}</td>
                <td>
                    {{ $moneda->ultimoCambio?->valor_cambio ?? '—' }}
                </td>
                <td>
                    {{ $moneda->ultimoCambio?->fecha ?? '—' }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
 <style>
        .btn-w i {
            margin-right: 4px; /* Espaciado entre el icono y el texto */
        }
    
        table tbody td {
            background-color: rgb(255, 249, 249);
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }

        .table-bordered {
            border-color: #fe495f;
        }
        table th, table td {
            text-align: center;
        }
        td {
            width: 1%;  
            white-space: nowrap; 
        }
    </style>
@endsection
