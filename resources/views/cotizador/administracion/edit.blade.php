@extends('layouts.app')

@section('content')
<div class="container">
    <div class="form-check mb-3">
        <h1 class="text-center">
            <a class="float-start" title="Volver" href="{{ route('insumo_empaque.index') }}">
                <i class="bi bi-arrow-left-circle"></i>
            </a>
            Editar {{ ucfirst($tipo) }}
        </h1>
    </div>

    <form action="{{ route('insumo_empaque.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="tipo" value="{{ $tipo }}">

        {{-- Tipo (solo lectura) --}}
        <div class="form-group">
            <label for="tipo">Tipo</label>
            <select class="form-control" disabled>
                @foreach ($tipos as $key => $value)
                    <option value="{{ $key }}" {{ $tipo === $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Nombre</label>
            <input name="nombre" class="form-control" value="{{ old('nombre', $item->nombre) }}" required>
        </div>

        <div class="form-group">
            <label>Precio / Costo</label>
            <input name="precio" type="number" step="0.01" class="form-control"
                value="{{ old('precio', $item->precio ?? $item->precio) }}" required>
        </div>

        {{-- === CAMPOS PARA INSUMOS === --}}
       {{-- === CAMPOS PARA INSUMOS === --}}
@if ($tipo === 'insumo')
    <div class="form-group">
        <label>Unidad de Medida</label>
        <select name="unidad_de_medida_id" class="form-control" required>
            @foreach ($unidades as $id => $unidad)
                <option value="{{ $id }}" {{ $item->unidad_de_medida_id == $id ? 'selected' : '' }}>{{ $unidad }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <input type="hidden" name="es_caro" value="0">
        <label>
            <input type="checkbox" name="es_caro" value="1" {{ old('es_caro', $item->es_caro) ? 'checked' : '' }}>
            ¿Es caro?
        </label>
    </div>


@endif


        <button class="btn btn-primary">Actualizar</button>
        <a href="{{ route('insumo_empaque.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>


@endsection
