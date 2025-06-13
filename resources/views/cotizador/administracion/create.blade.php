@extends('layouts.app')

@section('content')
    <div class="form-check mb-3">
        <h1 class="text-center">
            <a class="float-start text-secondary" title="Volver" href="{{ route('insumo_empaque.index') }}">
                <i class="bi bi-arrow-left-circle"></i>
            </a>
            Crear Insumos
        </h1>
    </div>

    <form action="{{ route('insumo_empaque.store') }}" method="POST">
        @csrf

        <div class="row">
            <div  class="col-md-6 mb-3">
                <label for="tipo">Tipo</label>
                <select name="tipo" id="tipo" class="form-control" required>
                    @foreach ($tipos as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            {{-- SOLO PARA INSUMO --}}
            <div class="form-group insumo-field d-none col-md-6 mb-3">
                <label>Unidad de Medida</label>
                <select name="unidad_de_medida_id" class="form-control">
                    @foreach ($unidades as $id => $unidad)
                        <option value="{{ $id }}">{{ $unidad }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="row">
            <div  class="col-md-6 mb-3">
                <label>Nombre</label>
                <input name="nombre" class="form-control" required>
                @error('nombre')
                    <div class="text-success">
                        <i class="fa-solid fa-triangle-exclamation"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label>Precio</label>
                <input name="precio" type="number" min="0.50" step="0.0001" class="form-control" required>
            </div>
        </div>

        

        <div class="form-group insumo-field d-none col-md-6 mb-3">
            <label  style="display: flex; align-items: center; gap: 8px;">
                <input type="checkbox" name="es_caro" value="1" {{ old('es_caro') ? 'checked' : '' }}>
                ¿Es caro?
            </label>
        </div>

        <button class="btn btn_crear">Guardar</button>
    </form>

    <script>
        const tipoSelect = document.getElementById('tipo');

        function toggleCampos() {
            const tipo = tipoSelect.value;
            const isInsumo = (tipo === 'insumo');

            // Ocultar todos
            document.querySelectorAll('.insumo-field').forEach(el => el.classList.add('d-none'));

            // Mostrar solo si es insumo
            if (isInsumo) {
                document.querySelectorAll('.insumo-field').forEach(el => el.classList.remove('d-none'));
            }
        }

        tipoSelect.addEventListener('change', toggleCampos);
        toggleCampos(); // Ejecutar al cargar
    </script>
@endsection
