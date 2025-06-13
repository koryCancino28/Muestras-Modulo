@extends('layouts.app')

@section('content')
<div class="container">
    <div class="form-check mb-3">
        <h1 class="text-center">
            <a class="float-start text-secondary" title="Volver" href="{{ route('insumo_empaque.index') }}">
                <i class="bi bi-arrow-left-circle"></i>
            </a>
            Editar {{ ucfirst($tipo) }}
        </h1>
    </div>

    <form action="{{ route('insumo_empaque.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="tipo" value="{{ $tipo }}">
        <div class="row">
            {{-- Tipo (solo lectura) --}}
            <div class="col-md-6 mb-3">
                <label for="tipo">Tipo</label>
                <select class="form-control" disabled>
                    @foreach ($tipos as $key => $value)
                        <option value="{{ $key }}" {{ $tipo === $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
                {{-- === CAMPOS PARA INSUMOS === --}}
            @if ($tipo === 'insumo')
                <div class="col-md-6 mb-3">
                    <label>Unidad de Medida</label>
                    <select name="unidad_de_medida_id" class="form-control" required>
                        @foreach ($unidades as $id => $unidad)
                            <option value="{{ $id }}" {{ $item->unidad_de_medida_id == $id ? 'selected' : '' }}>{{ $unidad }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <input type="hidden" name="es_caro" value="0">
                    <label>
                        <input type="checkbox" name="es_caro" value="1" {{ old('es_caro', $item->es_caro) ? 'checked' : '' }}>
                        ¿Es caro?
                    </label>
                </div>
            @endif
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Nombre</label>
                <input name="nombre" class="form-control" value="{{ old('nombre', $item->articulo->nombre) }}" required>
                @error('nombre')
                    <div class="text-success">
                        <i class="fa-solid fa-triangle-exclamation"></i>{{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label>Precio</label>
                <input name="precio" type="number" step="0.0001" class="form-control"
                    value="{{ old('precio', $item->precio ?? $item->precio) }}" required>
            </div>
        </div>

        @if($item->articulo->estado === 'inactivo')
        <div class="col-md-6 mb-3">
            <label for="estado" class="form-label">Estado del Insumo</label>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="estado" id="estado"
                    value="activo" {{ $item->articulo->estado === 'activo' ? 'checked' : '' }} required>
                <label class="form-check-label" for="estado">Activo</label>
            </div>
        </div><br>
    @endif

        <button class="btn btn_crear">Actualizar</button>
    </form>
</div>


@endsection
