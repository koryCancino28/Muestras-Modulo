@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center fw-bold">Listado de Bases</h1>
    <div class="mb-3">
        <a href="{{ route('bases.create') }}" class="btn btn_crear">+ Nueva Base</a>
    </div>

     <table class="table table-bordered">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Presentación<br> Farmaceutica</th>
            <th>Volumen</th>
            <th>Tipo</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bases as $base)
        <tr>
            <td>{{ $base->nombre }}</td>
            <td>{{ $base->clasificacion->nombre_clasificacion ?? '—' }}</td>
            <td>{{ $base->volumen->nombre ?? '-' }} {{ $base->unidadDeMedida->nombre_unidad_de_medida ?? '—' }}</td>
            <td>{{ ucfirst($base->tipo) }}</td> <!-- Mostramos tipo (prebase/final) -->
            <td>S/ {{ number_format($base->precio, 2) }}</td>
            <td>{{ $base->cantidad }}</td>
            <td>
                <a href="{{ route('bases.show', $base->id) }}" class="btn btn-info btn-sm">Ver</a>
                <a href="{{ route('bases.edit', $base->id) }}" class="btn btn-warning btn-sm">Editar</a>
                <form action="{{ route('bases.destroy', $base->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta base?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

    </div>
@endsection
