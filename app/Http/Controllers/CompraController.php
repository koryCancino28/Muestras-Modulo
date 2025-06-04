<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Articulo;
use App\Models\Proveedor;
use App\Models\TipoMoneda;
use App\Models\Lote;
use App\Models\DetalleLote;
use App\Models\Almacen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CompraController extends Controller
{
        public function index(Request $request)
    {
        // Inicializamos la consulta base
        $comprasQuery = Compra::with(['proveedor', 'moneda'])->orderBy('created_at', 'desc');

        // Filtrar por proveedor
        if ($request->has('proveedor_id') && $request->proveedor_id != '') {
            $comprasQuery->where('proveedor_id', $request->proveedor_id);
        }

        // Filtrar por fecha de emisión 'desde'
        if ($request->has('fecha_inicio') && $request->fecha_inicio != '') {
            $comprasQuery->whereDate('fecha_emision', '>=', $request->fecha_inicio);
        }

        // Filtrar por fecha de emisión 'hasta'
        if ($request->has('fecha_fin') && $request->fecha_fin != '') {
            $comprasQuery->whereDate('fecha_emision', '<=', $request->fecha_fin);
        }
        $compras = $comprasQuery->get();

        $proveedores = Proveedor::activos()->orderBy('razon_social')->get();

        return view('compras.index', compact('compras', 'proveedores'));
    }

    public function create()
    {
        $proveedores = Proveedor::activos()->orderBy('razon_social')->get();
        $monedas = TipoMoneda::orderBy('nombre')->get();
        $articulos = Articulo::activos()->orderBy('nombre')->get();
        $almacenes = Almacen::orderBy('nombre')->get();

        return view('compras.create', compact('proveedores', 'monedas', 'articulos', 'almacenes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'serie' => 'required|string|max:255',
            'numero' => 'required|string|max:255',
            'proveedor_id' => 'required|exists:proveedores,id',
            'condicion_pago' => 'required|in:Contado,Crédito',
            'moneda_id' => 'required|exists:tipo_moneda,id',
            'fecha_emision' => 'required|date',
            'fecha_vencimiento' => 'nullable|date|after_or_equal:fecha_emision',
            'igv' => 'required|boolean',
            'articulos' => 'required|array|min:1',
            'articulos.*' => 'exists:articulos,id', 
            'cantidades' => 'required|array',
            'cantidades.*' => 'required|integer|min:1',
            'precios' => 'required|array',
            'precios.*' => 'required|numeric|min:0',
            'lotes' => 'array',
            'vencimientos' => 'array'
        ]);

        try {
            DB::beginTransaction();

            // Calcular subtotal
            $subtotal = 0;
            for ($i = 0; $i < count($request->articulos); $i++) {
                $subtotal += $request->cantidades[$i] * $request->precios[$i];
            }

            // Calcular IGV
            $igv = $request->igv ? $subtotal * 0.18 : 0;
            $total = $subtotal + $igv;

            // Crear la compra
            $compra = Compra::create([
                'serie' => $request->serie,
                'numero' => $request->numero,
                'precio_total' => $total,
                'proveedor_id' => $request->proveedor_id,
                'fecha_emision' => $request->fecha_emision,
                'condicion_pago' => $request->condicion_pago,
                'moneda_id' => $request->moneda_id,
                'igv' => $igv,
                'referencia' => $request->referencia
            ]);

            // Crear los detalles de compra y actualizar stock
            for ($i = 0; $i < count($request->articulos); $i++) {
                $articuloId = $request->articulos[$i];
                $cantidad = $request->cantidades[$i];
                $precio = $request->precios[$i];
                $lote = $request->lotes[$i] ?? null;
                $vencimiento = $request->vencimientos[$i] ?? null;

                // Verificar si el lote ya existe o crear uno nuevo
                $loteModel = Lote::firstOrCreate(
                    ['articulo_id' => $articuloId, 'num_lote' => $lote],
                    ['fecha_vencimiento' => $vencimiento, 'precio' => $precio]
                );

                // Crear detalle de compra
                DetalleCompra::create([
                    'compra_id' => $compra->id,
                    'lote_id' => $loteModel->id, // Ahora usamos lote_id
                    'cantidad' => $cantidad,
                    'precio' => $precio
                ]);

                // Actualizar stock del artículo
                $articulo = Articulo::find($articuloId);
                $articulo->increment('stock', $cantidad);

                // Si se especifica lote, agregar stock en el almacén correspondiente
                if ($lote) {
                    $almacen = Almacen::first();
                    if ($almacen) {
                        $detalleLote = DetalleLote::firstOrNew([
                            'lote_id' => $loteModel->id,
                            'almacen_id' => $almacen->id
                        ]);

                        if ($detalleLote->exists) {
                            $detalleLote->increment('stock', $cantidad);
                        } else {
                            $detalleLote->stock = $cantidad;
                            $detalleLote->save();
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->route('compras.index', $compra)
                ->with('success', 'Compra registrada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Error al registrar la compra: ' . $e->getMessage());
        }
    }

    public function show(Compra $compra)
    {
        $compra->load(['proveedor', 'moneda', 'detalles.articulo', 'detalles.lote.articulo']);
        return view('compras.show', compact('compra'));
    }

    public function edit(Compra $compra)
    {
        $proveedores = Proveedor::activos()->orderBy('razon_social')->get();
        $monedas = TipoMoneda::orderBy('nombre')->get();
        $articulos = Articulo::activos()->orderBy('nombre')->get();
        $compra->load('detalles.articulo');

        return view('compras.edit', compact('compra', 'proveedores', 'monedas', 'articulos'));
    }

    public function update(Request $request, Compra $compra)
    {
        $request->validate([
            'serie' => 'required|string|max:255',
            'numero' => 'required|string|max:255',
            'proveedor_id' => 'required|exists:proveedores,id',
            'condicion_pago' => 'required|in:con_tarjeta,en_efectivo',
            'moneda_id' => 'required|exists:tipo_moneda,id',
            'fecha_emision' => 'required|date',
            'fecha_vencimiento' => 'nullable|date|after_or_equal:fecha_emision',
            'igv' => 'required|boolean'
        ]);

        try {
            DB::beginTransaction();

            $compra->update($request->only([
                'serie', 'numero', 'proveedor_id', 'condicion_pago',
                'moneda_id', 'fecha_emision', 'referencia'
            ]));

            DB::commit();

            return redirect()->route('compras.show', $compra)
                ->with('success', 'Compra actualizada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Error al actualizar la compra: ' . $e->getMessage());
        }
    }

    public function destroy(Compra $compra)
    {
        try {
            DB::beginTransaction();

            // Revertir stock de los artículos
            foreach ($compra->detalles as $detalle) {
                $articulo = $detalle->articulo;
                $articulo->decrement('stock', $detalle->cantidad);
            }

            // Eliminar detalles y compra
            $compra->detalles()->delete();
            $compra->delete();

            DB::commit();

            return redirect()->route('compras.index')
                ->with('success', 'Compra eliminada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al eliminar la compra: ' . $e->getMessage());
        }
    }

    // Métodos auxiliares para AJAX
    public function getArticulosByTipo(Request $request)
    {
        $tipo = $request->get('tipo');
        
        $query = Articulo::activos();
        
        if ($tipo) {
            $query->where('tipo', $tipo);
        }
        
        $articulos = $query->orderBy('nombre')->get();
        
        return response()->json($articulos);
    }
}