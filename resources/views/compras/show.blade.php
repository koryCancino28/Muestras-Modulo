@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="form-check mb-4">
            <h1 class="text-center"><a class="float-start" title="Volver" href="{{ route('compras.index') }}">
            <i class="bi bi-arrow-left-circle"></i></a>
            Detalles de la compra</h1>
        </div>

                <div class="row">
                    <div class="col-md-6 ps-5">
                        <div class="card" style="border-radius: 10px;">
                <div class="card-header" style="background-color: #fe495f; color: white;">
                    <h5><i class="bi bi-info-circle me-2"></i> Información General</h5>
                </div>
                <div class="card-body">
                        <p><strong style="color:rgb(224, 61, 80);">N° de Compra:</strong> {{ $compra->id }}</p>
                        <p><strong style="color:rgb(224, 61, 80);">Fecha de Emisión:</strong> {{ $compra->fecha_emision->format('d/m/Y') }}</p>
                        <p><strong style="color:rgb(224, 61, 80);">Proveedor:</strong> {{ $compra->proveedor->razon_social }}</p>
                        <p><strong style="color:rgb(224, 61, 80);">Moneda:</strong> {{ $compra->moneda->nombre }} ({{ $compra->moneda->codigo_iso }})</p>
                        <p><strong style="color:rgb(224, 61, 80);">Condición de Pago:</strong> {{ $compra->condicion_pago }}</p>
                        <p><strong style="color:rgb(224, 61, 80);">Referencia:</strong> {{ $compra->serie }} - {{ $compra->numero }}</p>
                        <p><strong style="color:rgb(224, 61, 80);">IGV:</strong> 
                            @if(!empty($compra->igv) && $compra->igv > 0)
                                <span class="badge text-bg-success">Sí</span>
                            @else
                                <span class="badge text-bg-secondary">No</span>
                            @endif
                        </p>
                        <p><strong style="color:rgb(224, 61, 80);">Total:</strong> {{ number_format($compra->precio_total, 2) }}</p>
                    </div>
                    </div>
                    </div>
                    <div class="col-md-6">
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
