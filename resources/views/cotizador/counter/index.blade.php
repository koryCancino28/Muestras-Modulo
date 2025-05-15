
<div class="container">
    <h1 class="mb-4">Productos Finales</h1>
    
    <div class="mb-3">
        <a href="{{ route('productos-finales.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Producto
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Clasificación</th>
                    <th>Unidad de Medida</th>
                    <th>Costo Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos as $producto)
                <tr>
                    <td>{{ $producto->id }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->clasificacion->nombre ?? 'N/A' }}</td>
                    <td>{{ $producto->unidadMedida->nombre ?? 'N/A' }}</td>
                    <td>${{ number_format($producto->costo_total_real, 2) }}</td>
                    <td>
                        <span class="badge badge-{{ $producto->estado == 'activo' ? 'success' : 'danger' }}">
                            {{ ucfirst($producto->estado) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('productos-finales.show', $producto->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('productos-finales.edit', $producto->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
