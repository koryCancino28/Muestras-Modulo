
<div class="container py-4">
    <h1 class="flex-grow-1 text-center">
        <a class="float-start text-decoration-none" title="Volver" href="{{ route('muestras.index') }}">
            <i class="bi bi-arrow-left-circle fs-1 text-primary"></i>
        </a>
        Detalles de la Muestra
        <hr class="mt-3">
    </h1>

    <div class="row">
        <!-- Card 1: Información general -->
        <div class="col-md-6 mb-4">
            <div class="card" style="border-radius: 10px;">
                <div class="card-header" style="background-color: #fe495f; color: white;">
                    <h5><i class="bi bi-info-circle me-2"></i> Información General</h5>
                </div>
                <div class="card-body">
                    <p><strong style="color:rgb(224, 61, 80);">Nombre de la muestra:</strong> {{ $muestra->nombre_muestra }}</p>
                    <p><strong style="color:rgb(224, 61, 80);">Clasificación:</strong> {{ $muestra->clasificacion ? $muestra->clasificacion->nombre_clasificacion : 'No disponible' }}</p>
                    <p><strong style="color:rgb(224, 61, 80);">Tipo de muestra:</strong> {{ ucfirst($muestra->tipo_muestra) }}</p>
                    <p><strong style="color:rgb(224, 61, 80);">Unidad de medida:</strong>
                        @if($muestra->clasificacion && $muestra->clasificacion->unidadMedida)
                            {{ $muestra->clasificacion->unidadMedida->nombre_unidad_de_medida }}
                        @else
                            No disponible
                        @endif
                    </p>
                    <p><strong style="color:rgb(224, 61, 80);">Cantidad:</strong> {{ $muestra->cantidad_de_muestra }}</p>
                    <p><strong style="color:rgb(224, 61, 80);">Observaciones:</strong> {{ $muestra->observacion ?? 'Sin observaciones' }}</p>
                    <p><strong style="color:rgb(224, 61, 80);">Doctor:</strong> {{ $muestra->name_doctor ?? 'No disponible' }}</p>
                    <p><strong style="color:rgb(224, 61, 80);">Creado por:</strong> {{ $muestra->creator ? $muestra->creator->name : 'Desconocido' }}</p>
                    <p><strong style="color:rgb(224, 61, 80);">Comentario de Laboratorio:</strong> {{ $muestra->comentarios ?? 'No hay comentarios' }}</p>
                </div>
            </div>
        </div>

        <!-- Card 2: Fechas + Foto -->
        <div class="col-md-6 mb-4">
            <div class="card" style="border-radius: 10px;">
                <div class="card-header" style="background-color: #fe495f; color: white;">
                    <h5><i class="bi bi-calendar-event me-2"></i> Fechas y Foto</h5>
                </div>
                <div class="card-body">
                    <p><strong style="color:rgb(224, 61, 80);">Fecha de registro:</strong></p>
                    <input type="text" class="form-control mb-2"
                        value="{{ $muestra->created_at->format('d/m/Y H:i') }}"
                        readonly style="background-color:rgb(251, 239, 252); color: #555; border: 2px solid #ccc; font-weight: bold;">

                    <p><strong style="color:rgb(224, 61, 80);">Última actualización:</strong></p>
                    <input type="text" class="form-control mb-4"
                        value="{{ $muestra->updated_at->format('d/m/Y H:i') }}"
                        readonly style="background-color:rgb(244, 232, 255); color: #555; border: 2px solid #ccc; font-weight: bold;">

                    <!-- Foto -->
                    <p><strong style="color:rgb(224, 61, 80);">Foto de la Receta:</strong></p>
                    @if($muestra->foto)
                        <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#fotoModal">
                            <i class="bi bi-eye"></i> Ver Foto
                        </button>
                    @else
                        <p class="text-muted">No hay foto disponible</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para mostrar la foto -->
    <div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4">
                <div class="modal-header" style="background-color: #fe495f; color: white;">
                    <h5 class="modal-title" id="fotoModalLabel">Foto de la Receta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ asset($muestra->foto) }}" class="img-fluid rounded" style="max-height: 500px;" alt="Foto de la muestra">
                </div>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/muestras/home.css') }}">
<style>
    .card-header {
        background-color: #fe495f;
        color: white;
        font-weight: bold;
        border-radius: 10px 10px 0 0;
    }

    .card {
        border-radius: 10px;
    }

    .modal-content {
        border-radius: 15px;
    }

    .btn-outline-danger:hover {
        background-color: #fe495f;
        color: white;
        transition: 0.3s ease;
    }

    .form-control[readonly] {
        background-color: #f8f9fa;
        font-weight: bold;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script> console.log('Vista cargada con foto integrada.'); </script>

