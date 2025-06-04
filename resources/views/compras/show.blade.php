@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex mb-3 justify-content-between align-items-center">
            <h1 class="text-center flex-grow-1">Detalles de la Compra</h1>
            <a href="{{ route('compras.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al listado
            </a>
        </div>

        <!-- Detalles de la compra -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4>Información de la Compra</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>ID de Compra:</strong> {{ $compra->id }}</p>
                        <p><strong>Fecha de Emisión:</strong> {{ $compra->fecha_emision->format('d/m/Y') }}</p>
                        <p><strong>Proveedor:</strong> {{ $compra->proveedor->razon_social }}</p>
                        <p><strong>Moneda:</strong> {{ $compra->moneda->nombre }} ({{ $compra->moneda->codigo_iso }})</p>
                        <p><strong>Condición de Pago:</strong> {{ $compra->condicion_pago }}</p>
                        <p><strong>IGV:</strong> 
                            @if(!empty($compra->igv) && $compra->igv > 0)
                                <span class="badge text-bg-success">Sí</span>
                            @else
                                <span class="badge text-bg-secondary">No</span>
                            @endif
                        </p>
                        <p><strong>Total:</strong> {{ number_format($compra->precio_total, 2) }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Detalles de la Compra:</strong></p>
                        <!-- Tu tabla con un ID para DataTables -->
                        <table id="tablaCompras" class="table table-bordered table-striped table-hover">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>SubTotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($compra->detalles as $detalle)
                                    <tr>
                                        <td>{{ $detalle->lote->articulo->nombre ?? 'Sin nombre' }}</td>
                                        <td>{{ $detalle->cantidad }}</td>
                                        <td>{{ number_format($detalle->precio, 2) }}</td>
                                        <td>{{ number_format($detalle->cantidad * $detalle->precio, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#tablaCompras').DataTable({
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json',
                    },
                    ordering: false,
                    responsive: true,
                    // quitamos "l" del DOM para eliminar el selector de cantidad de registros
                    dom: '<"row"<"col-sm-12 col-md-12"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                    pageLength: 10,
                    initComplete: function() {
                        $('.dataTables_filter')
                            .addClass('mb-3')
                            .find('input')
                            .attr('placeholder', 'Buscar por nombre del insumo') // <- aquí el placeholder
                            .end()
                            .find('label')
                            .contents().filter(function() {
                                return this.nodeType === 3;
                            }).remove()
                            .end()
                            .prepend('Buscar:');
                    }
                });
            });
    </script>
@endsection
