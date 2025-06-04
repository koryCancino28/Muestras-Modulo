<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proveedores = Proveedor::orderBy('razon_social')->paginate(10);
        return view('proveedores.index', compact('proveedores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('proveedores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'razon_social' => 'required|string|max:255',
            'ruc' => 'required|string|size:11|unique:proveedores',
            'direccion' => 'required|string|max:255',
            'correo' => 'nullable|email|max:100',
            'correo_cpe' => 'nullable|email|max:100',
            'telefono_1' => 'required|string|max:20',
            'telefono_2' => 'nullable|string|max:20',
            'persona_contacto' => 'nullable|string|max:100',
            'observacion' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['estado'] = 'activo'; // Estado predeterminado

        Proveedor::create($data);

        return redirect()->route('proveedores.index')
                         ->with('success', 'Proveedor creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Proveedor $proveedor)
    {
        return view('proveedores.show', compact('proveedor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proveedor $proveedor)
    {
        return view('proveedores.edit', compact('proveedor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'razon_social' => 'required|string|max:255',
            'ruc' => 'required|string|size:11|unique:proveedores,ruc,'.$proveedor->id,
            'direccion' => 'required|string|max:255',
            'correo' => 'nullable|email|max:100',
            'correo_cpe' => 'nullable|email|max:100',
            'telefono_1' => 'required|string|max:20',
            'telefono_2' => 'nullable|string|max:20',
            'persona_contacto' => 'nullable|string|max:100',
            'observacion' => 'nullable|string',
            'estado' => 'required|in:activo,inactivo'
        ]);

        $proveedor->update($request->all());

        return redirect()->route('proveedores.index')
                         ->with('success', 'Proveedor actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();

        return redirect()->route('proveedores.index')
                         ->with('success', 'Proveedor eliminado exitosamente.');
    }
}