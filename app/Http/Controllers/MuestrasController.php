<?php

namespace App\Http\Controllers;

use App\Models\Muestras;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;
use App\Models\Clasificacion;
//formatear
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
//eventos
use App\Events\MuestraCreada;
use App\Events\MuestraActualizada;
//imprimir reportes
use PDF;

use Illuminate\Support\Str;
class MuestrasController extends Controller
{
    //Visitadora medica (crud muestras)
        public function index()
        {
            //
            $muestras = Muestras::all();  // Obtener las muestras con su unidad de medida
            $unidadmedida = UnidadMedida::all();  // Obtener las muestras con su unidad de medida
            // dd($unidadmedida);
            $clasificaciones = Clasificacion::all(); // Obtener todas las clasificaciones
            return view('layouts.app', compact('muestras','unidadmedida','clasificaciones'));
            
        }

        public function create()
        {
            // Obtener todas las clasificaciones y unidades de medida
        $clasificaciones = Clasificacion::all(); // Obtener todas las clasificaciones
        $unidadmedida = UnidadMedida::all(); // Obtener todas las unidades de medida
        
        // Retornar la vista 'add.blade.php' y pasar los datos necesarios
        return view('muestras.visitadoraMedica.add', compact('clasificaciones', 'unidadmedida'));
        }

            public function store(Request $request)
        {
            // Validar los datos de entrada
            $request->validate([
                'nombre_muestra' => 'required|string|max:255',
                'clasificacion_id' => 'required|exists:clasificaciones,id',
                'unidad_de_medida' => 'required|exists:unidad_de_medida,nombre_unidad_de_medida', // Validar unidad de medida
                'cantidad_de_muestra' => 'required|numeric',
                'observacion' => 'nullable|string',
                'tipo_muestra' => 'required|in:frasco original,frasco muestra',
            ]);

            // Crear una nueva muestra
            $muestra = new Muestras();
            $muestra->nombre_muestra = $request->nombre_muestra;
            $muestra->clasificacion_id = $request->clasificacion_id;

            // Verificar que la unidad de medida existe antes de asignar
            $unidad_de_medida = UnidadMedida::where('nombre_unidad_de_medida', $request->unidad_de_medida)->first();
            if (!$unidad_de_medida) {
                return redirect()->back()->withErrors(['unidad_de_medida' => 'La unidad de medida no es válida.']);
            }

            $muestra->unidad_de_medida_id = $unidad_de_medida->id;
            $muestra->cantidad_de_muestra = $request->cantidad_de_muestra;
            $muestra->observacion = $request->observacion;
            $muestra->tipo_muestra = $request->tipo_muestra;

            // Guardamos la muestra
            $muestra->save();
               // Emitir el evento con la muestra creada
                 event(new MuestraCreada($muestra));
            // Redirigir con mensaje de éxito
            return redirect()->route('muestras.visitadoraMedica.index')->with('success', 'Muestra registrada exitosamente.');
        }

        public function show($id)
        {
            //
            $muestras = Muestras::with('clasificacion','unidadDeMedida')->findOrFail($id);
            return view('muestras.visitadoraMedica.show', compact('muestras'));
        }

        public function edit($id)
        {
            $muestra = Muestras::find($id);
            $unidadmedida = UnidadMedida::all(); 
            $clasificaciones = Clasificacion::all(); // Obtener todas las clasificaciones

            return view('muestras.visitadoraMedica.edit', compact('muestra', 'unidadmedida', 'clasificaciones'));
        }

        public function getUnidadesPorClasificacion($clasificacionId)
        {
            // Buscar la clasificación por ID
            $clasificacion = Clasificacion::with('unidadMedida')->findOrFail($clasificacionId);
        
            // Devolver la unidad de medida asociada a esa clasificación
            return response()->json($clasificacion->unidadMedida);
        }

        public function update(Request $request, $id)
        {
            // Validar los datos de entrada
            $request->validate([
                'nombre_muestra' => 'required|string|max:255',
                'clasificacion_id' => 'required|exists:clasificaciones,id',
                'unidad_de_medida_id' => 'required|exists:unidad_de_medida,id',
                'cantidad_de_muestra' => 'required|numeric',
                'observacion' => 'nullable|string',
                'tipo_muestra' => 'required|in:frasco original,frasco muestra', // Validar el tipo de muestra
            ]);
            
            $muestra = Muestras::findOrFail($id); // Usar findOrFail para asegurar que se encuentra

            // Actualizar los demás campos
            $muestra->nombre_muestra = $request->nombre_muestra;
            $muestra->clasificacion_id = $request->clasificacion_id;
            $muestra->unidad_de_medida_id = $request->unidad_de_medida_id; 
            $muestra->cantidad_de_muestra = $request->cantidad_de_muestra;
            $muestra->observacion = $request->observacion;
            $muestra->tipo_muestra = $request->tipo_muestra;
            $muestra->save();

            // Emitir el evento con la muestra actualizada
            event(new MuestraActualizada($muestra));
            
            // Redirigir a la vista de índice con un mensaje de éxito
            return redirect()->route('muestras.visitadoraMedica.index')->with('success', 'Muestra actualizada exitosamente.');
        }

        public function destroy($id)
        {
            // Eliminar la muestra
            $muestra = Muestras::find($id);
            $muestra->delete();

            return redirect()->route('muestras.visitadoraMedica.index')->with('success', 'Muestra eliminada exitosamente.');
        }
}
