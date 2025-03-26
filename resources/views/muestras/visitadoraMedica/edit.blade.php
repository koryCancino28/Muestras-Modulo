@extends('layouts.app')

@section('title')
    | Editar Muestra
@endsection

@section('content')
<h1 class="text-center">
    <a class="float-start" title="Volver" href="{{ route('muestras.visitadoraMedica.index') }}">
        <i class="bi bi-arrow-left-circle"></i>
    </a>
    Editar Muestra <hr>
</h1>

<form action="{{ route('muestras.update', $muestra->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method("PUT")

    <!-- Campo para el nombre de la muestra -->
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Nombre de la Muestra</label>
            <input type="text" name="nombre_muestra" class="form-control" required value="{{ $muestra->nombre_muestra }}" />
        </div>
    </div>

    <!-- Campo para la clasificación (select) -->
    <div class="mb-3">
        <label class="form-label">Clasificación</label>
        <select name="clasificacion_id" id="clasificacion_id" class="form-select" required>
            <option value="">Seleccione una clasificación</option>
            @foreach ($clasificaciones as $clasificacion)
                <option value="{{ $clasificacion->id }}" 
                    {{ $clasificacion->id == $muestra->clasificacion_id ? 'selected' : '' }}>
                    {{ $clasificacion->nombre_clasificacion }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Campo para la unidad de medida (input de texto) -->
    <div class="mb-3">
        <label class="form-label">Unidad de Medida</label>
        <input type="text" name="unidad_de_medida" id="unidad_de_medida" class="form-control" readonly required value="{{ $muestra->unidadDeMedida->nombre_unidad_de_medida }}" />
        <input type="hidden" name="unidad_de_medida_id" id="unidad_de_medida_id" value="{{ $muestra->unidad_de_medida_id }}" />
    </div>

    <!-- Campo para el tipo de muestra (select) -->
    <div class="mb-3">
        <label class="form-label">Tipo de Muestra</label>
        <select name="tipo_muestra" class="form-select" required>
            <option value="frasco original" {{ $muestra->tipo_muestra == 'frasco original' ? 'selected' : '' }}>Frasco Original</option>
            <option value="frasco muestra" {{ $muestra->tipo_muestra == 'frasco muestra' ? 'selected' : '' }}>Frasco Muestra</option>
        </select>
    </div>

    <!-- Campo para la cantidad de muestras -->
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Cantidad de Muestras</label>
            <input type="number" id="cantidad_de_muestra" name="cantidad_de_muestra" class="form-control" required value="{{ $muestra->cantidad_de_muestra }}" oninput="calcularPrecioTotal()" />
        </div>
    </div>

    <!-- Campo para las observaciones -->
    <div class="row">
        <div class="col-md-12 mb-3">
            <label class="form-label">Observaciones</label>
            <textarea name="observacion" class="form-control" rows="3">{{ $muestra->observacion }}</textarea>
        </div>
    </div>

    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary btn_add">
            Actualizar Muestra
        </button>
    </div>
</form>

<script>
    // Función para cargar la unidad de medida cuando se selecciona una clasificación
    document.getElementById('clasificacion_id').addEventListener('change', function() {
        var clasificacionId = this.value;

        if (clasificacionId) {
            fetch(`/get-unidades/${clasificacionId}`)
                .then(response => response.json())
                .then(data => {
                    // Verificamos que la respuesta contenga la unidad de medida
                    if (data && data.nombre_unidad_de_medida) {
                        // Establecer la unidad de medida en el campo correspondiente
                        document.getElementById('unidad_de_medida').value = data.nombre_unidad_de_medida;
                        document.getElementById('unidad_de_medida_id').value = data.id; // También actualizamos el hidden input con el ID
                    } else {
                        document.getElementById('unidad_de_medida').value = '';
                        document.getElementById('unidad_de_medida_id').value = ''; // Limpiamos el ID si no hay unidad
                    }
                })
                .catch(error => console.error('Error:', error));
        } else {
            document.getElementById('unidad_de_medida').value = '';
            document.getElementById('unidad_de_medida_id').value = ''; // Limpiar el ID si no se selecciona ninguna clasificación
        }
    });
</script>
@endsection
