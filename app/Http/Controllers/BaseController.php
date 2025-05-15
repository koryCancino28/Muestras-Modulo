<?php

namespace App\Http\Controllers;

use App\Models\Base;
use App\Models\Clasificacion;
use App\Models\Empaque;
use App\Models\Insumo;
use App\Models\Volumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    public function create()
    {
        $clasificaciones = Clasificacion::all();
        $insumos = Insumo::all();
        $empaques = Empaque::all();
        $prebases = Base::where('tipo', 'prebase')->get();
        $volumenesAgrupados = Volumen::all()->groupBy('clasificacion_id');

        return view('cotizador.laboratorio.base.create', compact(
            'clasificaciones',
            'insumos',
            'empaques',
            'prebases',
            'volumenesAgrupados'
        ));
    }
    public function index()
    {
       $bases = Base::with(['clasificacion', 'unidadDeMedida', 'volumen'])->get();
        return view('cotizador.laboratorio.base.index', compact('bases'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'clasificacion_id' => 'required|exists:clasificaciones,id',
            'volumen_id' => 'required|exists:volumenes,id',
            'cantidad' => 'required|numeric|min:0',
            'tipo' => 'required|in:prebase,final',
        ]);

        $tipo = $request->input('tipo');
        $precioTotal = 0;

        // 🔹 Calcular unidad_de_medida_id desde la clasificación
        $clasificacion = Clasificacion::find($request->clasificacion_id);
        $unidadDeMedidaId = $clasificacion?->unidad_de_medida_id;

        // 🔹 Calcular el precio total de insumos
        $insumos = $request->input('insumos', []);
        foreach ($insumos as $insumo) {
            $modeloInsumo = Insumo::find($insumo['id']);
            if ($modeloInsumo) {
                $precioTotal += $modeloInsumo->precio * $insumo['cantidad'];
            }
        }

        // 🔹 Si es base final, sumar también prebases y empaques
        if ($tipo === 'final') {
            $prebases = $request->input('prebases', []);
            foreach ($prebases as $prebase) {
                $modeloPrebase = Base::find($prebase['id']);
                if ($modeloPrebase) {
                    $precioTotal += $modeloPrebase->precio * $prebase['cantidad'];
                }
            }

            $empaques = $request->input('empaques', []);
            foreach ($empaques as $empaque) {
                $modeloEmpaque = \App\Models\Empaque::find($empaque['id']);
                if ($modeloEmpaque) {
                    $precioTotal += $modeloEmpaque->costo * $empaque['cantidad'];
                }
            }
        }

        // 🔸 Crear la base con unidad_de_medida_id derivada de la clasificación
        $base = Base::create([
            'nombre' => $request->nombre,
            'clasificacion_id' => $request->clasificacion_id,
            'volumen_id' => $request->volumen_id,
            'unidad_de_medida_id' => $unidadDeMedidaId, // <- Aquí la asignamos
            'cantidad' => $request->cantidad,
            'tipo' => $tipo,
            'precio' => $precioTotal,
        ]);

        // Relacionar insumos
        if (!empty($insumos)) {
            foreach ($insumos as $insumo) {
                $base->insumos()->attach($insumo['id'], ['cantidad' => $insumo['cantidad']]);
            }
        }

        // Relacionar prebases y empaques si es final
        if ($tipo === 'final') {
            if (!empty($prebases)) {
                foreach ($prebases as $prebase) {
                    $base->prebases()->attach($prebase['id'], ['cantidad' => $prebase['cantidad']]);
                }
            }

            if (!empty($empaques)) {
                foreach ($empaques as $empaque) {
                    $base->empaques()->attach($empaque['id'], ['cantidad' => $empaque['cantidad']]);
                }
            }
        }

        return redirect()->route('bases.index')->with('success', 'Base creada exitosamente.');
    }
   public function edit($id)
{
    $base = Base::with(['insumos', 'prebases', 'empaques', 'clasificacion.unidadMedida'])->findOrFail($id);
    
    $clasificaciones = Clasificacion::all();
    $volumenes = Volumen::where('clasificacion_id', $base->clasificacion_id)->get();
    $insumos = Insumo::all();
    $prebases = Base::where('tipo', 'prebase')->get();
    $empaques = Empaque::all();
    $volumenesAgrupados = Volumen::all()->groupBy('clasificacion_id');
    return view('cotizador.laboratorio.base.edit', compact(
        'base', 
        'clasificaciones', 
        'volumenes', 
        'insumos', 
        'prebases', 
        'empaques',
        'volumenesAgrupados'
    ));
}

public function update(Request $request, $id)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
        'clasificacion_id' => 'required|exists:clasificaciones,id',
        'volumen_id' => 'required|exists:volumenes,id',
        'cantidad' => 'required|numeric|min:0',
        'tipo' => 'required|in:final,prebase',
        'insumos' => 'required|array',
        'insumos.*.cantidad' => 'required|numeric|min:0.01',
    ]);
    
    $base = Base::findOrFail($id);
    
    DB::beginTransaction();
    try {
        // Actualizar datos básicos
        $base->update([
            'nombre' => $request->nombre,
            'clasificacion_id' => $request->clasificacion_id,
            'volumen_id' => $request->volumen_id,
            'cantidad' => $request->cantidad,
            'tipo' => $request->tipo,
        ]);
        
        // Sincronizar insumos
        $insumosSync = [];
        foreach ($request->insumos as $insumoId => $data) {
            $insumosSync[$insumoId] = ['cantidad' => $data['cantidad']];
        }
        $base->insumos()->sync($insumosSync);
        
        // Para bases finales, sincronizar prebases y empaques
        if ($request->tipo == 'final') {
            $request->validate([
                'prebases' => 'required|array',
                'prebases.*.cantidad' => 'required|numeric|min:0.01',
                'empaques' => 'required|array',
                'empaques.*.cantidad' => 'required|numeric|min:1',
            ]);
            
            // Sincronizar prebases
            $prebasesSync = [];
            foreach ($request->prebases as $prebaseId => $data) {
                $prebasesSync[$prebaseId] = ['cantidad' => $data['cantidad']];
            }
            $base->prebases()->sync($prebasesSync);
            
            // Sincronizar empaques
            $empaquesSync = [];
            foreach ($request->empaques as $empaqueId => $data) {
                $empaquesSync[$empaqueId] = [
                    'cantidad' => $data['cantidad'],
                    'tipo' => $data['tipo']
                ];
            }
            $base->empaques()->sync($empaquesSync);
        } else {
            // Si es prebase, eliminar relaciones con prebases y empaques
            $base->prebases()->detach();
            $base->empaques()->detach();
        }
        
        // Calcular y actualizar precio
        $precio = $base->calcularPrecio();
        $base->update(['precio' => $precio]);
        
        DB::commit();
        
        return redirect()->route('bases.index')->with('success', 'Base actualizada correctamente.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Error al actualizar la base: ' . $e->getMessage());
    }
}
}