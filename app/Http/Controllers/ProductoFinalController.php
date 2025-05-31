<?php

namespace App\Http\Controllers;
use App\Models\UnidadMedida;
use App\Models\ProductoFinal;
use App\Models\Base;
use App\Models\Volumen;
use App\Models\Insumo;
use App\Models\Articulo;
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
        $query = ProductoFinal::query();
        if ($request->has('estado') && $request->estado == 'inactivo') {
            $query->whereHas('articulo', function ($query) {
                $query->where('estado', 'inactivo');
            });
        } else {
            $query->whereHas('articulo', function ($query) {
                $query->where('estado', 'activo');
            });
        }
        $productos = $query->get();
        $bases = Base::where('tipo', 'final')->get();
        $insumos = Insumo::all();

        return view('cotizador.laboratorio.producto_final.index', compact('productos', 'clasificaciones', 'bases', 'insumos'));
    }
    public function create()
    {
        $clasificaciones = Clasificacion::with('unidadMedida')->get();

        $bases = Base::where('tipo', 'final')
            ->whereHas('articulo', function ($query) {
                $query->where('estado', 'activo');
            })->get();

        $insumos = Insumo::whereHas('articulo', function ($query) {
            $query->where('estado', 'activo');
        })->get();

        $volumenesAgrupados = Volumen::all()->groupBy('clasificacion_id');

        return view('cotizador.laboratorio.producto_final.create', compact(
            'clasificaciones',
            'bases',
            'insumos',
            'volumenesAgrupados'
        ));
    }

        public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|unique:articulos,nombre', // Cambié esto para validar en la tabla 'articulos'
            'bases' => 'required|array|min:1',
            'insumos' => 'array',
            'costo_total_produccion' => 'required|numeric',
            'costo_total_real' => 'required|numeric',
            'volumen_id' => 'required|exists:volumenes,id',
            'cantidad' => 'nullable|numeric|min:0',
        ], [
            'nombre.unique' => 'El nombre de este Producto Final ya está en uso. Por favor, elige otro nombre.'
        ]);

        // Crear un nuevo artículo para el producto final
        $articulo = Articulo::create([
            'nombre' => $validated['nombre'],
            'tipo' => 'producto_final', 
            'descripcion' => $request->descripcion ?? null,  
            'stock' => 1,  
        ]);

        // Crear el producto final y asociar el articulo_id
        $producto = ProductoFinal::create([
            'articulo_id' => $articulo->id,  // Asignamos el artículo creado
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

        // Calcular los costos del producto final
        $producto->calcularCostos();

        return redirect()->route('producto_final.index')->with('success', 'Producto creado correctamente');
    }

        public function show($id)
    {
        $producto = ProductoFinal::with(['clasificacion', 'unidadDeMedida', 'bases', 'insumos', 'articulo'])->findOrFail($id);
        return view('cotizador.laboratorio.producto_final.show', compact('producto'));
    }

        public function edit($id)
    {
        $producto = ProductoFinal::with(['clasificacion.unidadMedida', 'insumos', 'bases', 'articulo'])->findOrFail($id);
        
        $clasificaciones = Clasificacion::with('unidadMedida')->get();
        $bases = Base::where('tipo', 'final')
            ->whereHas('articulo', function ($query) {
                $query->where('estado', 'activo');
            })->get();

        $insumos = Insumo::whereHas('articulo', function ($query) {
            $query->where('estado', 'activo');
        })->get();
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
        //dd($request->all());
        $validated = $request->validate([
            'nombre' => 'required|string|unique:articulos,nombre,' . $producto->articulo->id, 
            'clasificacion_id' => 'required',
            'unidad_de_medida_id' => 'required',
            'bases' => 'required|array|min:1',
            'insumos' => 'array',
            'costo_total_produccion' => 'required|numeric',
            'costo_total_real' => 'required|numeric',
            'volumen_id' => 'nullable|exists:volumenes,id',
            'estado' => 'nullable|in:activo,inactivo',
        ], [
            'nombre.unique' => 'El nombre de este Producto Final ya está en uso. Por favor, elige otro nombre.',
        ]);

        // Actualizar el artículo relacionado con el producto final
        $producto->articulo->update([
            'nombre' => $validated['nombre'],
            'estado' => $validated['estado'] ?? 'activo', 
        ]);

        // Actualizar el producto final
        $producto->update([
            'clasificacion_id' => $validated['clasificacion_id'],
            'unidad_de_medida_id' => $validated['unidad_de_medida_id'],
            'volumen_id' => $validated['volumen_id'] ?? null,
            'costo_total_produccion' => $validated['costo_total_produccion'],
            'costo_total_real' => $validated['costo_total_real'],
        ]);

        // Sincronizar las bases
        $producto->bases()->detach();
        foreach ($request->bases as $baseId => $baseData) {
            $producto->bases()->attach($baseId, ['cantidad' => $baseData['cantidad']]);
        }

        // Sincronizar los insumos
        $producto->insumos()->detach();
        if ($request->has('insumos')) {
            foreach ($request->insumos as $insumoId => $insumoData) {
                $producto->insumos()->attach($insumoId, ['cantidad' => $insumoData['cantidad']]);
            }
        }

        // Calcular los costos del producto final
        $producto->calcularCostos();

        // Redirigir con mensaje de éxito
        return redirect()->route('producto_final.index')->with('success', 'Producto actualizado correctamente');
    }

            public function destroy($id)
    {
        $producto = ProductoFinal::findOrFail($id);
        $nombre = $producto->articulo->nombre;

        if ($producto->articulo->estado === 'inactivo') {
            return redirect()->route('producto_final.index')
                ->with('error', "El producto final '{$nombre}' ya está inactivo. Para activarlo, por favor usa la sección de editar.");
        }

        $producto->articulo->update([
            'estado' => 'inactivo',
        ]);

        return redirect()->route('producto_final.index')
            ->with('error', "Producto final '{$nombre}' marcado como inactivo correctamente.");
    }
}

