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
        'nombre' => 'required|string|max:255|unique:base,nombre',
        'volumen_id' => 'required|exists:volumenes,id',
        'tipo' => 'required|in:prebase,final',
    ], [ 
        'nombre.unique' => 'Ya existe una base con este nombre.'
    ]);

    $tipo = $request->input('tipo');
    $precioTotal = 0;

    // Validación condicional según el tipo
    if ($tipo === 'final') { 
        if (empty($request->insumos) || empty($request->prebases) || empty($request->empaques)) {
            return back()->withInput()->withErrors([
                'llenar' => 'Debe ingresar al menos un insumo, una prebase y un empaque para una Base Final.',
            ]);
        }
    } elseif ($tipo === 'prebase') {
        if (empty($request->insumos)) {
            return back()->withInput()->withErrors([
                'llenar' => 'Debe ingresar al menos un insumo para una Prebase.',
            ]);
        }
    }

    // unidad_de_medida_id desde la clasificación
    $clasificacion = Clasificacion::find($request->clasificacion_id);
    $unidadDeMedidaId = $clasificacion?->unidad_de_medida_id;

    // Calcular precio total de insumos
    $insumos = $request->input('insumos', []);
    foreach ($insumos as $insumo) {
        $modeloInsumo = Insumo::find($insumo['id']);
        if ($modeloInsumo) {
            $precioTotal += $modeloInsumo->precio * $insumo['cantidad'];
        }
    }

    // Si es base final, sumar también prebases y empaques
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
            $modeloEmpaque = Empaque::find($empaque['id']);
            if ($modeloEmpaque) {
                $precioTotal += $modeloEmpaque->precio * $empaque['cantidad'];
            }
        }
    }

    // Crear la base sin los campos eliminados
    $base = Base::create([
        'nombre' => $request->nombre,
        'clasificacion_id' => $request->clasificacion_id,
        'volumen_id' => $request->volumen_id,
        'unidad_de_medida_id' => $unidadDeMedidaId,
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
        
        // Excluir esta base de las prebases disponibles
        $prebases = Base::where('tipo', 'prebase')
                    ->where('id', '!=', $id) // ← Excluye la base actual
                    ->get();
        
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
        'tipo' => 'required|in:final,prebase',
        'insumos' => 'required|array',
        'insumos.*.cantidad' => 'required|numeric|min:0.01',
    ]);

    $base = Base::findOrFail($id);
    $tipo = $request->input('tipo');

    // Validar cambio de tipo si tiene dependencias
    if ($base->tipo == 'prebase' && $tipo == 'final') {
        $basesDependientes = Base::whereHas('prebases', function($query) use ($id) {
            $query->where('prebase_id', $id);
        })->get();

        if ($basesDependientes->isNotEmpty()) {
            $nombresBases = $basesDependientes->pluck('nombre')->implode(', ');
            return back()->withInput()->withErrors([
                'tipo' => 'No puedes cambiar esta prebase a base final porque es utilizada en las siguientes bases: '.$nombresBases.'. Primero debes eliminar estas dependencias.'
            ]);
        }
    }

    // Validaciones por tipo
    if ($tipo === 'final') {
        if (empty($request->insumos) || empty($request->prebases) || empty($request->empaques)) {
            return back()->withInput()->withErrors([
                'tipo' => 'Debe ingresar al menos un insumo, una prebase y un empaque para una Base Final.',
            ]);
        }
    } elseif ($tipo === 'prebase') {
        if (empty($request->insumos)) {
            return back()->withInput()->withErrors([
                'tipo' => 'Debe ingresar al menos un insumo para una Prebase.',
            ]);
        }
    }

    DB::beginTransaction();
    try {
        $clasificacion = Clasificacion::find($request->clasificacion_id);
        $unidadDeMedidaId = $clasificacion?->unidad_de_medida_id;

        $base->update([
            'nombre' => $request->nombre,
            'clasificacion_id' => $request->clasificacion_id,
            'volumen_id' => $request->volumen_id,
            'unidad_de_medida_id' => $unidadDeMedidaId,
            'tipo' => $tipo,
        ]);

        // Recalcular precio total
        $precioTotal = 0;

        $insumosSync = [];
        foreach ($request->insumos as $insumoId => $data) {
            $modeloInsumo = Insumo::find($insumoId);
            if ($modeloInsumo) {
                $precioTotal += $modeloInsumo->precio * $data['cantidad'];
            }
            $insumosSync[$insumoId] = ['cantidad' => $data['cantidad']];
        }
        $base->insumos()->sync($insumosSync);

        if ($tipo === 'final') {
            $request->validate([
                'prebases' => 'required|array',
                'prebases.*.cantidad' => 'required|numeric|min:0.01',
                'empaques' => 'required|array',
                'empaques.*.cantidad' => 'required|numeric|min:1',
            ]);

            if (array_key_exists($id, $request->prebases)) {
                return back()->withErrors([
                    'prebases' => 'No puedes incluir esta base como prebase de sí misma'
                ]);
            }

            $prebasesSync = [];
            foreach ($request->prebases as $prebaseId => $data) {
                $modeloPrebase = Base::find($prebaseId);
                if ($modeloPrebase) {
                    $precioTotal += $modeloPrebase->precio * $data['cantidad'];
                }
                $prebasesSync[$prebaseId] = ['cantidad' => $data['cantidad']];
            }
            $base->prebases()->sync($prebasesSync);

            $empaquesSync = [];
            foreach ($request->empaques as $empaqueId => $data) {
                $modeloEmpaque = Empaque::find($empaqueId);
                if ($modeloEmpaque) {
                    $precioTotal += $modeloEmpaque->precio * $data['cantidad'];
                }
                $empaquesSync[$empaqueId] = ['cantidad' => $data['cantidad']];
            }
            $base->empaques()->sync($empaquesSync);
        } else {
            $base->prebases()->detach();
            $base->empaques()->detach();
        }

        $base->update(['precio' => $precioTotal]);

        DB::commit();

        return redirect()->route('bases.index')->with('success', 'Base actualizada correctamente.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Error al actualizar la base: ' . $e->getMessage());
    }
}


    public function show($id)
    {
        // Cargar la base con todas sus relaciones
        $base = Base::with([
            'insumos',
            'prebases',
            'empaques',
            'clasificacion',
            'unidadDeMedida',
            'volumen'
        ])->findOrFail($id);

        return view('cotizador.laboratorio.base.show', compact('base'));
    }

    public function destroy($id)
    {
        $base = Base::findOrFail($id);

        // Verificar si es prebase y tiene dependencias
        if ($base->tipo == 'prebase') {
            $basesDependientes = Base::whereHas('prebases', function($query) use ($id) {
                $query->where('prebase_id', $id);
            })->pluck('nombre')->toArray();

            if (!empty($basesDependientes)) {
                $mensajeError = 'No se puede eliminar esta prebase porque es utilizada en las siguientes bases: '
                    . implode(', ', $basesDependientes) 
                    . '. Por favor, actualiza esas bases primero.';
                
                return back()->with('error', $mensajeError);
            }
        }

        // Eliminar relaciones primero
        $base->insumos()->detach();
        $base->prebases()->detach();
        $base->empaques()->detach();

        // Eliminar la base
        $base->delete();

        return redirect()->route('bases.index')
            ->with('error', 'Base eliminada correctamente.');
    }
}