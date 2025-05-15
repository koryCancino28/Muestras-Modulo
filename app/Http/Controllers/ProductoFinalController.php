<?php

namespace App\Http\Controllers;
use App\Models\UnidadMedida;
use App\Models\ProductoFinal;
use App\Models\Base;
use App\Models\Insumo;
use App\Models\Empaque;
use App\Models\Configuracion;
use App\Models\Clasificacion;
use Illuminate\Http\Request;

class ProductoFinalController extends Controller
{
    public function index()
    {
        $productos = ProductoFinal::with(['bases', 'insumos', 'empaques'])->get();
        return view('cotizador.counter.index', compact('productos'));
    }

    public function create()
    {
        $clasificaciones = Clasificacion::with('unidadMedida', 'volumenes')->get();
        $bases = Base::with('clasificacion')->get()->groupBy('clasificacion.nombre');
        $insumos = Insumo::all();
        $empaques = Empaque::all();
        $unidadMedida = UnidadMedida::all(); // Obtén las unidades de medida

        return view('cotizador.counter.counter', compact('clasificaciones', 'bases', 'insumos', 'empaques', 'unidadMedida'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'clasificacion_id' => 'required|exists:clasificaciones,id',
            'unidad_de_medida_id' => 'required|exists:unidad_de_medida,id',
            'bases' => 'array',
            'bases.*.id' => 'exists:bases,id',
            'bases.*.cantidad' => 'numeric|min:0.01',
            'insumos' => 'array',
            'insumos.*.id' => 'exists:insumos,id',
            'insumos.*.cantidad' => 'numeric|min:0.01',
            'empaques' => 'array',
            'empaques.*.id' => 'exists:empaques,id',
            'empaques.*.cantidad' => 'numeric|min:0.01',
        ]);

        $producto = ProductoFinal::create([
            'nombre' => $request->nombre,
            'clasificacion_id' => $request->clasificacion_id,
            'unidad_de_medida_id' => $request->unidad_de_medida_id,
            'costo_maquina_id' => Configuracion::where('nombre', 'costo_maquina')->first()->id,
            'costo_humano_id' => Configuracion::where('nombre', 'costo_humano')->first()->id,
            'costo_fijo_id' => Configuracion::where('nombre', 'costo_fijo')->first()->id,
            'estado' => 'activo',
            'created_by' => auth()->id(),
        ]);

        // Asociar bases
        foreach ($request->bases ?? [] as $base) {
            $producto->bases()->attach($base['id'], ['cantidad' => $base['cantidad']]);
        }

        // Asociar insumos
        foreach ($request->insumos ?? [] as $insumo) {
            $producto->insumos()->attach($insumo['id'], ['cantidad' => $insumo['cantidad']]);
        }

        // Asociar empaques
        foreach ($request->empaques ?? [] as $empaque) {
            $producto->empaques()->attach($empaque['id'], ['cantidad' => $empaque['cantidad']]);
        }

        // Calcular costos
        $producto->calcularCostoTotalProduccion();
        $producto->calcularCostoTotalReal();

        return redirect()->route('productos-finales.index')
            ->with('success', 'Producto final creado correctamente');
    }

    public function getBasesByClasificacion($clasificacionId)
    {
        $bases = Base::where('clasificacion_id', $clasificacionId)->get();
        return response()->json($bases);
    }
}