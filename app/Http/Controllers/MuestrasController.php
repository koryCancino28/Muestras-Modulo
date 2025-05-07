<?php

namespace App\Http\Controllers;

use App\Models\Muestras;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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
        $muestras = Muestras::with(['clasificacion.unidadMedida'])->orderBy('created_at', 'desc')->get();
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
        $validated = $request->validate([
            'nombre_muestra' => 'required|string|max:255',
            'clasificacion_id' => 'required|exists:clasificaciones,id',
            'cantidad_de_muestra' => 'required|numeric|min:1|max:10000',
            'observacion' => 'nullable|string',
            'tipo_muestra' => 'required|in:frasco original,frasco muestra',
            'name_doctor' => 'nullable|string|max:80',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // VALIDACIÓN DE IMAGEN
        ]);
        
        // Manejar la subida de la imagen si existe
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
    
    return redirect()->route('muestras.index')->with('success', 'Muestra actualizada exitosamente.');
}

    public function destroy($id)
    {
        $muestra = Muestras::findOrFail($id);
        $muestra->delete();

        return redirect()->route('muestras.index')->with('success', 'Muestra eliminada exitosamente.');
    }
}