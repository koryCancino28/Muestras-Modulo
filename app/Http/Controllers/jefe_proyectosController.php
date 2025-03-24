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
class jefe_proyectosController extends Controller
{
    
         public function precio()
    {
        // Obtén todas las muestras
        $muestras = Muestras::all();
        
        return view('muestras.jefe_proyectos.precio', compact('muestras'));
    }


        public function actualizarPrecio(Request $request, $id)
    {
        // Validar que el precio es un número
        $request->validate([
            'precio' => 'required|numeric|min:0',
        ]);

        // Buscar la muestra en la base de datos
        $muestra = DB::table('muestras')->where('id', $id)->first();

        // Verificar si la muestra existe
        if (!$muestra) {
            return response()->json(['success' => false, 'message' => 'Muestra no encontrada.']);
        }

        // Actualizar el precio en la base de datos
        DB::table('muestras')
            ->where('id', $id)
            ->update(['precio' => $request->precio]);

        // Devolver respuesta JSON
        return response()->json([
            'success' => true,
            'message' => 'Precio actualizado exitosamente.',
            'precio' => $request->precio
        ]);
    }
}
