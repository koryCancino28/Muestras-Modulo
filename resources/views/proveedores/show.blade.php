@extends('layouts.app')

@section('content')

    <div class="d-flex align-items-center mb-3">
        <a class="btn me-3" title="Volver" href="{{ route('proveedores.index') }}" style="color:#6c757d; font-size: 2.3rem;">
            <i class="bi bi-arrow-left-circle"></i>
        </a>
        <h1 class="flex-grow-1 text-center">Detalles del Proveedor</h1>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="border-radius: 10px;">
                <div class="card-header" style="background-color: #fe495f; color: white;">
                    <h5><i class="bi bi-person-badge-fill" style="margin-right: 6px;"></i> Información</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong style="color:rgb(224, 61, 80);">Razón Social:</strong> {{ $proveedor->razon_social }}</p>
                            <p><strong style="color:rgb(224, 61, 80);">RUC:</strong> {{ $proveedor->ruc }}</p>
                            <p><strong style="color:rgb(224, 61, 80);">Dirección:</strong> {{ $proveedor->direccion }}</p>
                            <p><strong style="color:rgb(224, 61, 80);">Correo:</strong> {{ $proveedor->correo ?? 'N/A' }}</p>
                            <p><strong style="color:rgb(224, 61, 80);">Correo CPE:</strong> {{ $proveedor->correo_cpe ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong style="color:rgb(224, 61, 80);">Teléfono 1:</strong> {{ $proveedor->telefono_1 }}</p>
                            <p><strong style="color:rgb(224, 61, 80);">Teléfono 2:</strong> {{ $proveedor->telefono_2 ?? 'N/A' }}</p>
                            <p><strong style="color:rgb(224, 61, 80);">Persona de Contacto:</strong> {{ $proveedor->persona_contacto ?? 'N/A' }}</p>
                            <p><strong style="color:rgb(224, 61, 80);">Estado:</strong> 
                                <span class="badge" style="background-color: {{ $proveedor->estado == 'activo' ? 'green' : 'gray' }}; color: white; padding: 10px;">
                                    {{ ucfirst($proveedor->estado) }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <p><strong style="color:rgb(224, 61, 80);">Observaciones:</strong></p>
                    <span>{{ $proveedor->observacion ?? 'Ninguna' }}</span>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('proveedores.edit', $proveedor->id) }}" class="btn btn_crear" style="color:rgb(224, 61, 80);">
                        <i class="bi bi-pencil-square"></i> Editar
                    </a>
                
                </div>
            </div>
        </div>
    </div>


<!-- Bootstrap & Icon links -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/muestras/labora.css') }}">

<style>
    .card-body {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 10px;
    }
    .card-header {
        font-size: 1.2rem;
    }
    .card-footer {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 0 0 10px 10px;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script> console.log('Detalles del proveedor cargados'); </script>
@endsection
