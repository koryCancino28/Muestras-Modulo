 
    <div class="container">

    <h1 class="flex-grow-1 text-center">
   <a class="float-start" title="Volver" href="{{ route('muestras.aprobacion.coordinadora') }}">
      <i class="bi bi-arrow-left-circle"></i>
   </a>
   Registrar muestra <hr>
</h1>

        <form action="{{route('muestras.storeCO') }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            
            <!-- Campo para el nombre de la muestra -->
            <div class="mb-3">
                <label class="form-label">Nombre de la Muestra</label>
                <input type="text" name="nombre_muestra" class="form-control" required />
            </div>
            
            <div class="mb-3">
                <label for="foto" class="form-label">Foto de la muestra (opcional)</label>
                <input type="file" name="foto" id="foto" class="form-control" accept="images/*">
            </div>

            <!-- Campo para la clasificacion (select) -->
            <div class="mb-3">
                <label class="form-label">Clasificación</label>
                <select name="clasificacion_id" id="clasificacion_id" class="form-select" required>
                    <option value="">Seleccione una clasificación</option>
                    @foreach ($clasificaciones as $clasificacion)
                        <option value="{{ $clasificacion->id }}">{{ $clasificacion->nombre_clasificacion }}</option>
                    @endforeach
                </select>
            </div>

                            <!-- Campo para la unidad de medida -->
        <div class="mb-3">
            <label class="form-label">Unidad de Medida</label>
            <input type="text" name="unidad_de_medida" id="unidad_de_medida" class="form-control" readonly required>
        </div>
            <!-- Campo para el tipo de muestra (select) -->
            <div class="mb-3">
                <label class="form-label">Tipo de Muestra</label>
                <select name="tipo_muestra" class="form-select" required>
                    <option value="">Seleccione el tipo de muestra</option>
                    <option value="frasco original">Frasco Original</option>
                    <option value="frasco muestra">Frasco Muestra</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="name_doctor" class="form-label">Nombre del doctor</label>
                <input type="text" id="name_doctor" name="name_doctor" class="form-control" value="{{ old('name_doctor') }}" required />
            </div>

            <!-- Campo para la cantidad de muestras -->
            <div class="mb-3">
                <label class="form-label">Cantidad de Muestras</label>
                <input type="number" id="cantidad_de_muestra" name="cantidad_de_muestra" class="form-control" required min="1" />
            </div>

            <!-- Campo para las observaciones -->
            <div class="mb-3">
                <label class="form-label">Observaciones</label>
                <textarea name="observacion" class="form-control" rows="3" required></textarea>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn_add">
                    Registrar Muestra
                </button>  
            </div>    
        </form>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="{{ asset('css/muestras/home.css') }}">


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


