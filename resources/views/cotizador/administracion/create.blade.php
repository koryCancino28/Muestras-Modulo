@extends('layouts.app')

@section('content')
    <div class="form-check mb-3">
        <h1 class="text-center">
            <a class="float-start" title="Volver" href="{{ route('insumo_empaque.index') }}">
                <i class="bi bi-arrow-left-circle"></i>
            </a>
            Crear Insumos
        </h1>
    </div>

    <form action="{{ route('insumo_empaque.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="tipo">Tipo</label>
            <select name="tipo" id="tipo" class="form-control" required>
                @foreach ($tipos as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Nombre</label>
            <input name="nombre" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Precio / Costo</label>
            <input name="precio" type="number" step="0.01" class="form-control" required>
        </div>

        {{-- INSUMO --}}
        <div class="form-group insumo-field d-none">
            <label>Unidad de Medida</label>
            <select name="unidad_de_medida_id" class="form-control">
                @foreach ($unidades as $id => $unidad)
                    <option value="{{ $id }}">{{ $unidad }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group insumo-field d-none">
            <label>
                <input type="checkbox" name="es_caro" value="1" {{ old('es_caro') ? 'checked' : '' }}>
                ¿Es caro?
            </label>
        </div>

        {{-- Checkbox Estado para TODOS --}}
        <div class="form-check estado-field d-none">
            <input class="form-check-input" type="checkbox" name="estado" id="estado" value="1">
            <label class="form-check-label" for="estado">
                ¿Tiene stock?
            </label>
        </div>

        {{-- Campo STOCK (solo insumo) --}}
        <div class="form-group insumo-field d-none cantidad-wrapper">
            <label>Stock</label>
            <input name="stock" id="stock" type="number" class="form-control" disabled>
        </div>

        {{-- Campo CANTIDAD (solo empaque) --}}
        <div class="form-group empaque-field d-none cantidad-wrapper">
            <label>Cantidad</label>
            <input name="cantidad" id="cantidad" type="number" class="form-control" disabled>
        </div>

        <button class="btn btn-primary">Guardar</button>
    </form>

    <script>
        const tipoSelect = document.getElementById('tipo');
        const estadoCheckbox = document.getElementById('estado');
        const stockInput = document.getElementById('stock');
        const cantidadInput = document.getElementById('cantidad');

        function toggleCampos() {
            const tipo = tipoSelect.value;
            const isEmpaque = (tipo === 'material' || tipo === 'envase');
            const isInsumo = (tipo === 'insumo');

            // Ocultar todos primero
            document.querySelectorAll('.insumo-field').forEach(el => el.classList.add('d-none'));
            document.querySelectorAll('.empaque-field').forEach(el => el.classList.add('d-none'));
            document.querySelector('.estado-field').classList.add('d-none');
            document.querySelectorAll('.cantidad-wrapper').forEach(el => el.classList.add('d-none'));

            if (isInsumo) {
                document.querySelectorAll('.insumo-field').forEach(el => el.classList.remove('d-none'));
                document.querySelector('.estado-field').classList.remove('d-none');
                document.querySelector('#stock').parentElement.classList.remove('d-none');
            } else if (isEmpaque) {
                document.querySelectorAll('.empaque-field').forEach(el => el.classList.remove('d-none'));
                document.querySelector('.estado-field').classList.remove('d-none');
                document.querySelector('#cantidad').parentElement.classList.remove('d-none');
            }

            toggleCantidadEditable();
        }

        function toggleCantidadEditable() {
            if (estadoCheckbox.checked) {
                stockInput?.removeAttribute('disabled');
                cantidadInput?.removeAttribute('disabled');
            } else {
                stockInput?.setAttribute('disabled', 'disabled');
                stockInput.value = '';
                cantidadInput?.setAttribute('disabled', 'disabled');
                cantidadInput.value = '';
            }
        }

        tipoSelect.addEventListener('change', toggleCampos);
        estadoCheckbox.addEventListener('change', toggleCantidadEditable);
        toggleCampos();
        
    </script>
@endsection
