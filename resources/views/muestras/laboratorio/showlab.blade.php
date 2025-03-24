@extends('layouts.app')

@section('title')
    | Detalles de la Muestra
@endsection

@section('content')
<h1 class="text-center">
   <a class="float-start" title="Volver" href="{{ route('muestras.estado') }}">
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
        <li class="list-group-item"> Tipo de muestra: &nbsp; &nbsp; <strong> {{ $muestras->tipo_muestra }}</strong></li> <!-- Mostrar tipo de muestra -->
        <li class="list-group-item"> Unidad de medida: &nbsp; &nbsp; <strong> {{ $muestras->unidadDeMedida->nombre_unidad_de_medida }}</strong></li>
        <!-- Aprobación por Jefe Comercial -->
        <li class="list-group-item">
                Aprobado por Jefe Comercial: &nbsp; &nbsp;
                <span class="badge" 
                    style="background-color: {{ $muestras->aprobado_jefe_comercial ? 'green' : ($muestras->aprobado_coordinadora ? 'yellow' : 'red') }}; 
                    color: {{ ($muestras->aprobado_jefe_comercial == false && $muestras->aprobado_coordinadora == false) || $muestras->aprobado_jefe_comercial ? 'white' : 'black' }}; 
                    padding: 10px;">
                    {{ $muestras->aprobado_jefe_comercial ? 'Aprobado' : 'Pendiente' }}
                </span>
            </li>
        <!-- Aprobación por Coordinadora -->
        <li class="list-group-item">
            Aprobado por Coordinadora: &nbsp; &nbsp;
            <span class="badge" 
                style="background-color: {{ $muestras->aprobado_coordinadora ? 'green' : ($muestras->aprobado_jefe_comercial ? 'yellow' : 'red') }}; 
                color: {{ ($muestras->aprobado_coordinadora == false && $muestras->aprobado_jefe_comercial == false) || $muestras->aprobado_coordinadora ? 'white' : 'black' }}; 
                padding: 10px;">
                {{ $muestras->aprobado_coordinadora ? 'Aprobado' : 'Pendiente' }}
            </span>
        </li>
        <li class="list-group-item"> Cantidad: &nbsp; &nbsp; <strong> {{ $muestras->cantidad_de_muestra }}</strong></li>
        <li class="list-group-item"> Observaciones: &nbsp; &nbsp; <strong> {{ $muestras->observacion }}</strong></li>

        <!-- Campo de Estado con colores -->
        <li class="list-group-item">
            Estado: &nbsp; &nbsp;
            <span class="badge" 
                style="background-color: {{ $muestras->estado == 'Pendiente' ? 'red' : 'green' }}; color: white; padding: 10px;">
                {{ $muestras->estado }}
            </span>
        </li>
        <!-- Mostrar Fecha y Hora Recibida (updated_at) con color verde claro -->
        <li class="list-group-item">
            Fecha y Hora Recibida: &nbsp; &nbsp;
            <input type="text" class="form-control" 
                value="{{ $muestras->updated_at ? \Carbon\Carbon::parse($muestras->updated_at)->format('Y-m-d H:i') : ($muestras->created_at ? \Carbon\Carbon::parse($muestras->created_at)->format('Y-m-d H:i') : 'No disponible') }}" 
                readonly style="background-color:rgb(225, 255, 207); color: #555; border: 2px solid #ccc; font-weight: bold;">
        </li>

        <!-- Mostrar Fecha y Hora de Entrega en solo lectura -->
            <li class="list-group-item">
                Fecha y Hora de Entrega: &nbsp; &nbsp;
                <input type="text" class="form-control" 
                    value="{{ $muestras->fecha_hora_entrega ? \Carbon\Carbon::parse($muestras->fecha_hora_entrega)->format('Y-m-d H:i') : 'No disponible' }}" 
                    readonly style="background-color: #f4f4f4; color: #555; border: 2px solid #ccc; font-weight: bold;">
            </li>
        </ul>
@endsection
