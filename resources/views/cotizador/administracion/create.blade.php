@extends('layouts.app')

@section('content')
    <h3>Crear Insumo, Material o Envase</h3>
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

        <div class="form-group insumo-field">
            <label>Unidad de Medida</label>
            <select name="unidad_de_medida_id" class="form-control">
                @foreach ($unidades as $id => $unidad)
                    <option value="{{ $id }}">{{ $unidad }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group insumo-field">
            <label>
                <input type="checkbox" name="es_caro" value="1" {{ old('es_caro') ? 'checked' : '' }}>
                ¿Es caro?
            </label>
        </div>

        <div class="form-group insumo-field">
            <label>Stock</label>
            <input name="stock" type="number" class="form-control">
        </div>

        <div class="form-check empaque-field d-none">
            <input class="form-check-input" type="checkbox" name="estado" id="estado" value="1">
            <label class="form-check-label" for="estado">
                ¿Tiene stock?
            </label>
        </div>
                <div class="form-group empaque-field d-none">
                    <label>Cantidad</label>
                    <input name="cantidad" id="cantidad" type="number" class="form-control" disabled>
                </div>
        <button class="btn btn-primary">Guardar</button>
    </form>

<script>
    function toggleCampos() {
        const tipo = document.getElementById('tipo').value;
        const isEmpaque = (tipo === 'material' || tipo === 'envase');

        document.querySelectorAll('.insumo-field').forEach(el => el.classList.add('d-none'));
        document.querySelectorAll('.empaque-field').forEach(el => el.classList.add('d-none'));

        if (tipo === 'insumo') {
            document.querySelectorAll('.insumo-field').forEach(el => el.classList.remove('d-none'));
        } else {
            document.querySelectorAll('.empaque-field').forEach(el => el.classList.remove('d-none'));
        }

        toggleCantidadEditable();
    }

    function toggleCantidadEditable() {
        const estado = document.getElementById('estado');
        const cantidad = document.getElementById('cantidad');

        if (estado.checked) {
            cantidad.removeAttribute('disabled');
        } else {
            cantidad.setAttribute('disabled', 'disabled');
            cantidad.value = '';
        }
    }

    document.getElementById('tipo').addEventListener('change', toggleCampos);
    document.getElementById('estado').addEventListener('change', toggleCantidadEditable);

    // Ejecutar al cargar
    toggleCampos();
</script>

@endsection
