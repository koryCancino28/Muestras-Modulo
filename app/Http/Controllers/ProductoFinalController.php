<?php

namespace App\Http\Controllers;
use App\Models\UnidadMedida;
use App\Models\ProductoFinal;
use App\Models\Base;
use App\Models\Volumen;
use App\Models\Insumo;
use App\Models\Empaque;
use App\Models\Configuracion;
use App\Models\Clasificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ProductoFinalController extends Controller
{
      public function index(Request $request)
{
    $clasificaciones = Clasificacion::with('unidadMedida')->get();

    $query = ProductoFinal::query(); // Ya no ordenamos por created_at

    $productos = $query->get();
    $bases = Base::where('tipo', 'final')->get();
    $insumos = Insumo::all();

    return view('cotizador.laboratorio.producto_final.index', compact('productos', 'clasificaciones', 'bases', 'insumos'));
}


    public function create()
    {
        $clasificaciones = Clasificacion::with('unidadMedida')->get();
        $bases = Base::where('tipo', 'final')->get();
        $insumos = Insumo::all();
        $volumenesAgrupados = Volumen::all()->groupBy('clasificacion_id');
        return view('cotizador.laboratorio.producto_final.create', compact('clasificaciones', 'bases', 'insumos','volumenesAgrupados'));
    }

        public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|unique:producto_final,nombre',
            'bases' => 'required|array|min:1',
            'insumos' => 'array',
            'costo_total_produccion' => 'required|numeric', 
            'costo_total_real' => 'required|numeric',
            'volumen_id' => 'required|exists:volumenes,id',
            'cantidad' => 'nullable|numeric|min:0',
        ], [ 
            'nombre.unique' => 'Ya existe un Producto Final con este nombre'
        ]);

        $producto = ProductoFinal::create([
            'nombre' => $validated['nombre'],
            'costo_total_produccion' => $request->costo_total_produccion,
            'costo_total_real' => $request->costo_total_real,
            'costo_total_publicado' => 0, 
            'volumen_id' => $validated['volumen_id'] ?? null,
            'cantidad' => $validated['cantidad'] ?? 0,
        ]);

        // Relacionar bases
        foreach ($request->bases as $baseId => $baseData) {
            $producto->bases()->attach($baseId, ['cantidad' => $baseData['cantidad']]);
        }

        // Relacionar insumos si existen
        if ($request->has('insumos')) {
            foreach ($request->insumos as $insumoId => $insumoData) {
                $producto->insumos()->attach($insumoId, ['cantidad' => $insumoData['cantidad']]);
            }
        }
        $producto->calcularCostos();
        return redirect()->route('producto_final.index')->with('success', 'Producto creado correctamente');
    }


    public function show($id)
    {
        $producto = ProductoFinal::with(['clasificacion', 'unidadDeMedida', 'bases', 'insumos'])->findOrFail($id);
        return view('cotizador.laboratorio.producto_final.show', compact('producto'));
    }

        public function edit($id)
    {
        $producto = ProductoFinal::with(['clasificacion.unidadMedida', 'insumos', 'bases'])->findOrFail($id);
        
        $clasificaciones = Clasificacion::with('unidadMedida')->get();
        $bases = Base::where('tipo', 'final')->get();
        $insumos = Insumo::all();
        $volumenesAgrupados = Volumen::all()->groupBy('clasificacion_id');

        return view('cotizador.laboratorio.producto_final.edit', compact(
            'producto',
            'clasificaciones',
            'bases',
            'insumos',
            'volumenesAgrupados'
        ));
    }

        public function update(Request $request, ProductoFinal $producto_final)
    {
        $producto = $producto_final;

        $validated = $request->validate([
            'nombre' => 'required|string|unique:producto_final,nombre,' . $producto->id,
            'clasificacion_id' => 'required',
            'unidad_de_medida_id' => 'required',
            'bases' => 'required|array|min:1',
            'insumos' => 'array',
            'costo_total_produccion' => 'required|numeric',
            'costo_total_real' => 'required|numeric',
            'volumen_id' => 'nullable|exists:volumenes,id',
        ], [
            'nombre.unique' => 'Ya existe un Producto Final con este nombre',
        ]);

        $producto->update([
            'nombre' => $validated['nombre'],
            'clasificacion_id' => $validated['clasificacion_id'],
            'unidad_de_medida_id' => $validated['unidad_de_medida_id'],
            'volumen_id' => $validated['volumen_id'] ?? null,
            'costo_total_produccion' => $validated['costo_total_produccion'],
            'costo_total_real' => $validated['costo_total_real'],
        ]);

        $producto = ProductoFinal::findOrFail($producto->id);

        $producto->bases()->detach();
        foreach ($request->bases as $baseId => $baseData) {
            $producto->bases()->attach($baseId, ['cantidad' => $baseData['cantidad']]);
        }

        $producto->insumos()->detach();
        if ($request->has('insumos')) {
            foreach ($request->insumos as $insumoId => $insumoData) {
                $producto->insumos()->attach($insumoId, ['cantidad' => $insumoData['cantidad']]);
            }
        }

        $producto->calcularCostos();

        return redirect()->route('producto_final.index')->with('success', 'Producto actualizado correctamente');
    }


        public function destroy($id)
    {
        $producto = ProductoFinal::findOrFail($id);
        $producto->bases()->detach();
        $producto->insumos()->detach();

        $producto->delete();

        return redirect()->route('producto_final.index')->with('error', 'Producto final eliminado correctamente');
    }

}

