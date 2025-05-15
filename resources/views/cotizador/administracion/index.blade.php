@extends('layouts.app')

@section('content')
    <div class="mb-3">
        <a href="{{ route('insumo_empaque.create') }}" class="btn btn-success">Agregar Insumo o Empaque</a>
    </div>

   <table class="table">
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Nombre</th>
                <th>Precio/Costo</th>
                <th>Stock/Cantidad</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($insumos as $item)
                <tr>
                    <td>Insumo</td>
                    <td>{{ $item->nombre }}</td>
                    <td><p>{{ $item->precio }}</p>
                         @if ($item->es_caro)
                             <span class="badge bg-danger">Insumo caro</span>
                         @endif
                    </td>
                    <td>{{ $item->stock }}</td>
                    <td>{{ $item->stock > 0 ? 'Disponible' : 'Sin stock' }}</td>
                </tr>
            @endforeach

            @foreach ($empaques as $item)
                <tr>
                    <td>{{ ucfirst($item->tipo) }}</td>
                    <td>{{ $item->nombre }}</td>
                    <td>{{ $item->costo }}</td>
                    <td>{{ $item->estado ? $item->cantidad : 'Sin stock' }}</td>
                    <td>{{ $item->estado ? 'Disponible' : 'Sin stock' }}</td>
                </tr>
            @endforeach
        </tbody>
</table>

@endsection
