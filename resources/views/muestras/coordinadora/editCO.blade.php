
<div class="container">
<h1 class="flex-grow-1 text-center">
    <a class="float-start" title="Volver" href="{{ route('muestras.aprobacion.coordinadora') }}">
        <i class="bi bi-arrow-left-circle"></i>
    </a>
    Editar Muestra <hr>
</h1>

<form action="{{ route('muestras.updateCO', $muestra->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method("PUT")

        <!-- Campo para el nombre de la muestra -->
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nombre de la Muestra</label>
                <input type="text" name="nombre_muestra" class="form-control" required value="{{ $muestra->nombre_muestra }}" />
            </div>
        </div>

         <!--FOTO CON MODAL -->
         <div class="mb-3">
                    <label for="foto" class="form-label">Foto de la muestra (opcional)</label>
                    <div class="d-flex align-items-center">
                        <input type="file" name="foto" id="foto" class="form-control me-2" style="max-width: 80%;" accept="images/*">
                        @if($muestra->foto)
                            <button type="button" class="btn" style="background-color: #fe495f; color: white; border-radius: 5px;" data-bs-toggle="modal" data-bs-target="#fotoModal">
                                <i class="bi bi-eye"></i> Ver Foto
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Modal para mostrar la foto ampliada -->
                <div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="fotoModalLabel">Foto de la Muestra</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                <!-- Imagen ampliada -->
                                <img src="{{ asset($muestra->foto) }}" alt="Foto de la muestra" style="max-width: 100%; max-height: 500px; border-radius: 10px;">
                            </div>
                        </div>
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

                <!-- Campo para la unidad de medida (autocompletado desde la clasificación) -->
                <div class="mb-3">
                    <label class="form-label">Unidad de Medida</label>
                    <input type="text" name="unidad_de_medida" id="unidad_de_medida" class="form-control" readonly required
                        value="{{ $muestra->clasificacion->unidadMedida->nombre_unidad_de_medida ?? '' }}">
                </div>

            <div class="mb-3">
                <label for="name_doctor" class="form-label">Nombre del doctor</label>
                <input type="text" id="name_doctor" name="name_doctor" class="form-control" value="{{ $muestra->name_doctor }}" required />
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
            <input type="number" id="cantidad_de_muestra" name="cantidad_de_muestra" class="form-control" required value="{{ $muestra->cantidad_de_muestra }}" min="1" />
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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="{{ asset('css/muestras/home.css') }}">
    <style>
        /* Estilo adicional para el modal */
        .modal-content {
            border-radius: 15px;
        }

        .modal-header {
            background-color: #fe495f;
            color: white;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <script>
     document.addEventListener('DOMContentLoaded', function() {
            const clasificacionSelect = document.getElementById('clasificacion_id');
            const unidadMedidaInput = document.getElementById('unidad_de_medida');
            
            // Cargar las unidades de medida en las opciones del select
            const clasificaciones = {!! json_encode($clasificaciones->mapWithKeys(function ($item) {
                return [$item->id => $item->unidadMedida->nombre_unidad_de_medida ?? ''];
            })) !!};
            
            clasificacionSelect.addEventListener('change', function() {
                const clasificacionId = this.value;
                unidadMedidaInput.value = clasificaciones[clasificacionId] || '';
            });
        });
</script>
