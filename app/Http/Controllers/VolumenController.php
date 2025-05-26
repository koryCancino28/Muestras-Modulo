<?php

namespace App\Http\Controllers;
use App\Models\Volumen;
use App\Models\Clasificacion;
use Illuminate\Http\Request;

class VolumenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
        public function index()
    {
        $clasificaciones = Clasificacion::all();
        $volumenes = Volumen::with('clasificacion')->get();
        return view('cotizador.laboratorio.volumen.index', compact('volumenes', 'clasificaciones'));
    }


    /**
     * Show the form for creating a new resource.
     */
        public function create()
    {
        $clasificaciones = Clasificacion::all();
        return view('cotizador.laboratorio.volumen.create', compact('clasificaciones'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
            'nombre' => 'required|numeric|unique:volumenes,nombre',
            'clasificacion_id' => 'required|exists:clasificaciones,id'
        ], [
            'nombre.unique'=> 'El registro anterior no se realizó ya que existe ese volumen asociado a una clasificación',
        ]);

        Volumen::create($request->only('nombre', 'clasificacion_id'));

        return redirect()->route('volumen.index')->with('success', 'Volumen creado correctamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors();

            if($errors->has('nombre')){
                return redirect()->route('volumen.index')->with('error', $errors->first('nombre'));
            }
            return redirect()->route('volumen.index')->with('error', 'Error de validación');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $volumen = Volumen::findOrFail($id);
        $clasificaciones = Clasificacion::all();
        return view('cotizador.laboratorio.volumen.edit', compact('volumen','clasificaciones'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            $request->validate([
            'nombre'=> 'required|numeric|unique:volumenes,nombre',
            'clasificacion_id' => 'required|exists:clasificaciones,id'
            ], [
                'nombre.unique'=> 'La actualización anterior no se realizó ya que existe ese volumen asociado a una clasificación'
            ]);

            $volumen = Volumen::findOrFail($id);
            $volumen->update($request->only('nombre','clasificacion_id'));

            return redirect()->route('volumen.index')->with('success','Volumen actualizado correctamente');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors();

            if($errors->has('nombre')){
                return redirect()->route('volumen.index')->with('error', $errors->first('nombre')); 
            }
            return redirect()->route('volumen.index')->with('error', 'Error de validación');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
        public function destroy(string $id)
    {
        $volumen = Volumen::findOrFail($id);
        $volumen->delete();

        return redirect()->route('volumen.index')->with('error', 'Volumen eliminado correctamente.');
    }

}
