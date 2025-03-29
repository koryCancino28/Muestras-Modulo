<?php

namespace App\Http\Controllers;

use App\Models\Muestras;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;
use App\Models\Clasificacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Events\MuestraCreada;
use App\Events\MuestraActualizada;
use PDF;
use Illuminate\Support\Str;

class MuestrasController extends Controller
{
    public function index()
    {
        // Cargamos las muestras con su clasificación y la unidad de medida asociada
        $muestras = Muestras::with(['clasificacion.unidadMedida'])->get();
        $clasificaciones = Clasificacion::all();
        
        return view('muestras.visitadoraMedica.index', compact('muestras', 'clasificaciones'));
    }

    public function create()
    {
        $clasificaciones = Clasificacion::with('unidadMedida')->get();
        return view('muestras.visitadoraMedica.add', compact('clasificaciones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_muestra' => 'required|string|max:255',
            'clasificacion_id' => 'required|exists:clasificaciones,id',
            'cantidad_de_muestra' => 'required|numeric',
            'observacion' => 'nullable|string',
            'tipo_muestra' => 'required|in:frasco original,frasco muestra',
        ]);
    
        $muestra = Muestras::create($request->only([
            'nombre_muestra',
            'clasificacion_id',
            'cantidad_de_muestra',
            'observacion',
            'tipo_muestra'
        ]));
    
        event(new MuestraCreada($muestra));
        return redirect()->route('muestras.index')->with('success', 'Muestra registrada exitosamente.');
    }

    public function show($id)
    {
        // Cargamos la muestra con su clasificación y la unidad de medida asociada
        $muestra = Muestras::with(['clasificacion.unidadMedida'])->findOrFail($id);
        return view('muestras.visitadoraMedica.show', compact('muestra'));
    }

    public function edit($id)
    {
        $muestra = Muestras::findOrFail($id);
        $clasificaciones = Clasificacion::with('unidadMedida')->get();
        
        return view('muestras.visitadoraMedica.edit', compact('muestra', 'clasificaciones'));
    }

    public function getUnidadesPorClasificacion($clasificacionId)
    {
        $clasificacion = Clasificacion::with('unidadMedida')->findOrFail($clasificacionId);
        return response()->json([
            'unidad_medida' => $clasificacion->unidadMedida
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_muestra' => 'required|string|max:255',
            'clasificacion_id' => 'required|exists:clasificaciones,id',
            'cantidad_de_muestra' => 'required|numeric',
            'observacion' => 'nullable|string',
            'tipo_muestra' => 'required|in:frasco original,frasco muestra',
        ]);
        
        $muestra = Muestras::findOrFail($id);
        $muestra->update($request->only([
            'nombre_muestra',
            'clasificacion_id',
            'cantidad_de_muestra',
            'observacion',
            'tipo_muestra'
        ]));
    
         event(new MuestraActualizada($muestra));
        
        return redirect()->route('muestras.index')->with('success', 'Muestra actualizada exitosamente.');
    }

    public function destroy($id)
    {
        $muestra = Muestras::findOrFail($id);
        $muestra->delete();

        return redirect()->route('muestras.index')->with('success', 'Muestra eliminada exitosamente.');
    }
}