<?php

namespace App\Http\Controllers\muestras; // Namespace correcto para la carpeta "muestras"

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
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

        // Deshabilitar temporalmente la actualización de la columna 'updated_at'
        $muestra->timestamps = false; // Esto evitará que 'updated_at' se actualice.

        $muestra->save();

        // Restaurar el comportamiento por defecto de los timestamps (por si se usa en otros lugares)
        $muestra->timestamps = true;

        // Disparar evento de actualización
        event(new MuestraActualizada($muestra));

        // Respuesta de éxito
        return response()->json(['success' => true, 'message' => 'Aprobación actualizada exitosamente.']);
    }

    public function actualizarFechaEntrega(Request $request, $id)
    {
        // Buscar la muestra en la base de datos
        $muestra = Muestras::find($id);
        $validated = $request->validate([
            'fecha_hora_entrega' => 'required|date|after_or_equal:' . \Carbon\Carbon::now()->format('Y-m-d\TH:i'),
        ]);

        // Usar DB para actualizar solo el campo fecha_hora_entrega sin modificar los timestamps
        DB::table('muestras')
            ->where('id', $id)
            ->update([
                'fecha_hora_entrega' => $request->fecha_hora_entrega,
            ]);
            // Disparar evento de actualización
        event(new MuestraActualizada($muestra));
        // Redirigir a la ruta 'muestras.estado' con un mensaje de éxito
        return redirect()->route('muestras.aprobacion.coordinadora')->with('success', 'Fecha de entrega actualizada correctamente.');
    }

        public function aprobacionCoordinadora()
    {
        $muestras = Muestras::with(['clasificacion.unidadMedida'])->orderBy('created_at', 'desc')->paginate(10);
        return view('muestras.coordinadora.aprob', compact('muestras'));
    }

    public function showCo($id)
    {
        // Cargar la muestra con su clasificación y la unidad de medida asociada
        $muestra = Muestras::with(['clasificacion.unidadMedida'])->findOrFail($id);
        
        // Retornar la vista de "Detalles de Muestra" con los datos
        return view('muestras.coordinadora.showCo', compact('muestra'));
    }

    public function createCO()
    {
        $clasificaciones = Clasificacion::with('unidadMedida')->get();
        return view('muestras.coordinadora.addCO', compact('clasificaciones'));
    }
       // Método para almacenar una nueva muestra
        public function storeCO(Request $request)
    {
        $validated = $request->validate([
            'nombre_muestra' => 'required|string|max:255',
            'clasificacion_id' => 'required|exists:clasificaciones,id',
            'cantidad_de_muestra' => 'required|numeric|min:1|max:10000',
            'observacion' => 'nullable|string',
            'tipo_muestra' => 'required|in:frasco original,frasco muestra',
            'name_doctor' => 'nullable|string|max:80',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // VALIDACIÓN DE IMAGEN
        ]);

        // Manejar la subida de la imagen si existeq
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $timestamp = Carbon::now()->format('m-d_H-i');
            $filename = Str::slug($validated['nombre_muestra']) . "_$timestamp." . $file->getClientOriginalExtension();
            $relativePath = 'images/muestras_fotos';
            $fullPath = public_path($relativePath);
            if (!file_exists($fullPath)) {mkdir($fullPath, 0755, true);} //crea directorio si no existe
            $file->move($fullPath, $filename);
            $fotoPath = $relativePath.'/'.$filename;
        }

        $muestra = Muestras::create([
            'nombre_muestra' => $validated['nombre_muestra'],
            'clasificacion_id' => $validated['clasificacion_id'],
            'cantidad_de_muestra' => $validated['cantidad_de_muestra'],
            'observacion' => $validated['observacion'],
            'tipo_muestra' => $validated['tipo_muestra'],
            'name_doctor' => $validated['name_doctor'],
            'foto' => $fotoPath, 
            'created_by' => auth()->id(),
        ]);

        event(new MuestraCreada($muestra));
        return redirect()->route('muestras.aprobacion.coordinadora')->with('success', 'Muestra registrada exitosamente.');
    }

       public function editCO($id)
       {
           $muestra = Muestras::findOrFail($id);
           $clasificaciones = Clasificacion::with('unidadMedida')->get(); // Cargar clasificaciones
   
           return view('muestras.coordinadora.editCO', compact('muestra', 'clasificaciones'));
       }
    
        public function updateCO(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre_muestra' => 'required|string|max:255',
            'clasificacion_id' => 'required|exists:clasificaciones,id',
            'cantidad_de_muestra' => 'required|numeric|min:1|max:10000',
            'observacion' => 'nullable|string',
            'tipo_muestra' => 'required|in:frasco original,frasco muestra',
            'name_doctor' => 'nullable|string|max:80',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $muestra = Muestras::findOrFail($id);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $nombreMuestra = Str::slug($validated['nombre_muestra'], '_');
            $fecha = now()->format('m-d_H-i');
            $extension = $file->getClientOriginalExtension();
            $fileName = "{$nombreMuestra}-{$fecha}.{$extension}";
            $destinationPath = public_path('images/muestras_fotos');
            if (!File::exists($destinationPath)) {File::makeDirectory($destinationPath, 0755, true); }
            if (isset($muestra->foto) && $muestra->foto) {
                $oldFilePath = public_path($muestra->foto);
                if (File::exists($oldFilePath)) { File::delete($oldFilePath); }}
            $file->move($destinationPath, $fileName);
            $validated['foto'] = 'images/muestras_fotos/' . $fileName;}

        $muestra->update($validated);
        event(new MuestraActualizada($muestra));

        return redirect()->route('muestras.aprobacion.coordinadora')->with('success', 'Muestra actualizada exitosamente.');
    }

       public function destroyCO($id)
       {
           $muestra = Muestras::findOrFail($id);
           $muestra->delete(); // Eliminar la muestra

           return redirect()->route('muestras.aprobacion.coordinadora')->with('success', 'Muestra eliminada exitosamente.');
       }
}
