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

class laboratorioController extends Controller
{
        public function showLab($id)
    { 
       // Cargar la muestra con sus relaciones
       $muestra = Muestras::with(['clasificacion.unidadMedida'])->findOrFail($id);
        
       // Pasar la variable correctamente nombrada (en singular)
       return view('muestras.laboratorio.showlab', ['muestra' => $muestra]);
    }

        public function actualizarFechaEntrega(Request $request, $id)
    {
        // Validar la entrada para asegurarse de que la fecha y hora sean válidas
        $validated = $request->validate([
            'fecha_hora_entrega' => 'required|date',
        ]);
        // Usar DB para actualizar solo el campo fecha_hora_entrega sin modificar los timestamps
        DB::table('muestras')
            ->where('id', $id)
            ->update([
                'fecha_hora_entrega' => $request->fecha_hora_entrega,
            ]);
        // Redirigir a la ruta 'muestras.estado' con un mensaje de éxito
        return redirect()->route('muestras.estado')->with('success', 'Fecha de entrega actualizada correctamente.');
    }

        public function estado()
    {
        // Obtén todas las muestras
        $muestras = Muestras::all();
        return view('muestras.laboratorio.estado', compact('muestras'));
    }

    // Método para actualizar el estado
    public function actualizarEstado(Request $request, $id)
    {
        $validated = $request->validate([
            'estado' => 'required|string|in:Pendiente,Elaborado',
        ]);
        
        // Actualización directa sin afectar timestamps
        DB::table('muestras')
            ->where('id', $id)
            ->update(['estado' => $request->estado]);
        
        // Recuperar la muestra para el broadcast
        $muestra = Muestras::findOrFail($id);
        broadcast(new MuestraActualizada($muestra))->toOthers();
        
        return response()->json(['success' => true]);
    }
    
}
