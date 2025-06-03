@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4>Listado de Compras</h4>
                <a href="{{ route('compras.create') }}" class="btn btn-light">
                    <i class="fas fa-plus"></i> Nueva Compra
                </a>
            </div>
            <div class="card-body">
                <!-- Filtros de búsqueda -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <form method="GET" action="{{ route('compras.index') }}" class="form-inline">
                            <div class="form-group mr-2">
                                <label for="proveedor" class="mr-2">Proveedor:</label>
                                <select name="proveedor_id" id="proveedor" class="form-control select2">
                                    <option value="">Todos</option>
                                    @foreach($proveedores as $proveedor)
                                        <option value="{{ $proveedor->id }}" {{ request('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                                            {{ $proveedor->razon_social }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mr-2">
                                <label for="fecha_inicio" class="mr-2">Desde:</label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control"
                                    value="{{ request('fecha_inicio') }}">
                            </div>

                            <div class="form-group mr-2">
                                <label for="fecha_fin" class="mr-2">Hasta:</label>
                                <input type="date" name="fecha_fin" id="fecha_fin" class="form-control"
                                    value="{{ request('fecha_fin') }}">
                            </div>

                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                            <a href="{{ route('compras.index') }}" class="btn btn-secondary">
                                <i class="fas fa-sync-alt"></i> Limpiar
                            </a>
                        </form>
                    </div>
                </div>

                <!-- Tabla de compras -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="bg-secondary text-white">
                            <tr>
                                <th width="5%">ID</th>
                                <th width="10%">Fecha</th>
                                <th>Proveedor</th>
                                <th width="10%">Moneda</th>
                                <th width="10%">Condición</th>
                                <th width="10%">IGV</th>
                                <th width="12%">Total</th>
                                <th width="10%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($compras as $compra)
                                <tr>
                                    <td>{{ $compra->id }}</td>
                                    <td>{{ $compra->fecha_emision->format('d/m/Y') }}</td>
                                    <td>{{ $compra->proveedor->razon_social }}</td>
                                    <td>{{ $compra->moneda->nombre }} - {{ $compra->moneda->codigo_iso}}</td>
                                    <td>{{ $compra->condicion_pago == 'con_tarjeta' ? 'Tarjeta' : 'Efectivo' }}</td>
                                    <td class="text-center">
                                        @if($compra->igv)
                                            <span class="badge text-bg-success">Sí</span>
                                        @else
                                            <span class="badge text-bg-secondary">No</span>
                                        @endif
                                    </td>
                                    <td class="text-right">{{ number_format($compra->precio_total, 2) }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('compras.show', $compra->id) }}" class="btn btn-sm btn-info"
                                            title="Ver detalle">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('compras.edit', $compra->id) }}" class="btn btn-sm btn-warning"
                                            title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('compras.destroy', $compra->id) }}" method="POST"
                                            style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Eliminar"
                                                onclick="return confirm('¿Está seguro?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No se encontraron compras registradas</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6" class="text-right"><strong>Total:</strong></td>
                                <td class="text-right">{{ number_format($compras->sum('precio_total'), 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        {{ $compras->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .table th {
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
        }

        .badge {
            font-size: 100%;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            // Inicializar select2
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        });
    </script>
@endpush