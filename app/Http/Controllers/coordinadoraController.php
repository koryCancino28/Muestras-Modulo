<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Muestras;
use App\Models\UnidadMedida;
use App\Models\Clasificacion;
//formatear
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
//eventos
use App\Events\MuestraCreada;
use App\Events\MuestraActualizada;
//imprimir reportes
use PDF;


class coordinadoraController extends Controller
{
    public function actualizarAprobacion(Request $request, $id)
    {
        // Buscar la muestra en la base de datos
        $muestra = Muestras::find($id);
    
        // Verificar si la muestra existe
        if (!$muestra) {
            return response()->json(['success' => false, 'message' => 'Muestra no encontrada.']);
        }
    
        // Verificar el campo que se quiere actualizar
        if ($request->field == 'aprobado_jefe_comercial') {
            // Actualizar el campo 'aprobado_jefe_comercial'
            $muestra->aprobado_jefe_comercial = $request->value;
            
            // Si el jefe desaprueba, también desaprobamos la coordinadora
            if ($request->value == 0) {
                $muestra->aprobado_coordinadora = 0;
            }
        } elseif ($request->field == 'aprobado_coordinadora') {
            // Solo permitir aprobar si el jefe comercial ya aprobó
            if ($muestra->aprobado_jefe_comercial == 1) {
                $muestra->aprobado_coordinadora = $request->value;
            } else {
                return response()->json(['success' => false, 'message' => 'Primero debe aprobar el Jefe Comercial.']);
            }
        }
        
        $muestra->save();
        
        // Disparar evento de actualización
        event(new MuestraActualizada($muestra));
    
        // Respuesta de éxito
        return response()->json(['success' => true, 'message' => 'Aprobación actualizada exitosamente.']);
    }

        public function aprobacionCoordinadora()
    {
        $muestras = Muestras::with(['clasificacion', 'unidadDeMedida'])->get();
        return view('muestras.coordinadora.aprob', compact('muestras'));
    }
}
