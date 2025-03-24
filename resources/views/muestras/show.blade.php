@extends('layouts.app')

@section('title')
    | Detalles de la Muestra
@endsection

@section('content')
<h1 class="text-center">
   <a class="float-start" title="Volver" href="{{ route('muestras.index') }}">
      <i class="bi bi-arrow-left-circle"></i>
   </a>
   Datos de la muestra <hr>
</h1>

<ul class="list-group list-group-flush">
  <li class="list-group-item"> Nombre de la muestra: &nbsp; &nbsp; <strong> {{ $muestras->nombre_muestra }}</strong></li>
  
  <!-- Verificación de si la clasificación está disponible -->
  <li class="list-group-item">
    Clasificación: &nbsp; &nbsp; 
    <strong>
      {{ $muestras->clasificacion ? $muestras->clasificacion->nombre_clasificacion : 'No disponible' }}
    </strong>
  </li>

  <li class="list-group-item"> Unidad de medida: &nbsp; &nbsp; <strong> {{ $muestras->unidadDeMedida->nombre_unidad_de_medida }}</strong></li>
  <li class="list-group-item"> Cantidad: &nbsp; &nbsp; <strong> {{ $muestras->cantidad_de_muestra }}</strong></li>
  <li class="list-group-item"> Tipo de muestra: &nbsp; &nbsp; <strong> {{ $muestras->tipo_muestra }}</strong></li> <!-- Mostrar tipo de muestra -->
  <li class="list-group-item"> Observaciones: &nbsp; &nbsp; <strong> {{ $muestras->observacion }}</strong></li>
</ul>


@endsection
