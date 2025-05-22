@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container">
    <div class="row mb-4">
        <div class="form-check mb-6">
            <h1 class="text-center"><a class="float-start" title="Volver" href="{{ route('bases.create') }}">
            <i class="bi bi-arrow-left-circle"></i></a>
            Producto Final</h1>
        </div>
        <div class="col-md-3 text-end">
            <a href="{{ route('producto_final.create') }}" class="btn btn_crear">
                <i class="fas fa-plus"></i> Nuevo Producto
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Clasificación</th>
                            <th>Unidad de Medida</th>
                            <th>Costo Producción</th>
                            <th>Costo Real</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productos as $producto)
                            <tr>
                                <td>{{ $producto->id }}</td>
                                <td>{{ $producto->nombre }}</td>
                                <td>{{ $producto->clasificacion->nombre_clasificacion ?? 'N/A' }}</td>
                                <td>{{ $producto->unidadDeMedida->nombre_unidad_de_medida ?? 'N/A' }}</td>
                                <td>S/ {{ number_format($producto->costo_total_produccion, 2) }}</td>
                                <td>S/ {{ number_format($producto->costo_total_real, 2) }}</td>
                                <td>{{ $producto->stock }}</td>
                                <td>
                                    <span class="badge bg-{{ $producto->estado == 'activo' ? 'success' : 'danger' }}">
                                        {{ ucfirst($producto->estado) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="w">
                                        <a href="{{ route('producto_final.show', $producto->id) }}" class="btn btn-info btn-sm" style="background-color: #17a2b8; border-color: #17a2b8; color: white;"><i class="fa-regular fa-eye"></i>Ver</a>
                                        <a href="{{ route('producto_final.edit', $producto->id) }}" class="btn btn-warning btn-sm" style="background-color: #ffc107; border-color: #ffc107; color: white;"><i class="fa-solid fa-pen"></i>Editar</a>
                                        <form action="{{ route('producto_final.destroy', $producto->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" style="background-color: #dc3545; border-color: #dc3545;" title="Eliminar" onclick="return confirm('¿Estás seguro?')"><i class="fa-solid fa-trash"></i>Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No hay productos finales registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($productos->hasPages())
                <div class="mt-3">
                    {{ $productos->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
<style>
    .btn-sm {
        font-size: 1rem; 
        padding: 8px 14px; 
        border-radius: 8px;
        display: flex; 
        align-items: center; 
    }
    .btn i {
        margin-right: 4px; /* Espaciado entre el icono y el texto */
    }
    .w {
        display: flex;
        justify-content: center;
        gap: 10px;
    }
    table thead th {
        background-color: #fe495f;
        color: white;
    }

    table tbody td {
        background-color: rgb(255, 249, 249);
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }

    .table-bordered {
        border-color: #fe495f;
    }
    table th, table td {
        text-align: center;
    }
    td {
        width: 1%;  
        white-space: nowrap; 
    }
</style>
@endsection

