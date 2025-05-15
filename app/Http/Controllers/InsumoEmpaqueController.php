<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use App\Models\Empaque;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;

class InsumoEmpaqueController extends Controller
{
    public function index()
    {
        $insumos = Insumo::all();
        $empaques = Empaque::all();
        return view('cotizador.administracion.index', compact('insumos', 'empaques'));
    }

    public function create()
    {
        $tipos = ['insumo' => 'Insumo', 'material' => 'Material', 'envase' => 'Envase'];
        $unidades = UnidadMedida::pluck('nombre_unidad_de_medida', 'id');
        return view('cotizador.administracion.create', compact('tipos', 'unidades'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $tipo = $data['tipo'];

        if ($tipo === 'insumo') {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'precio' => 'required|numeric|min:0',
                'unidad_de_medida_id' => 'required|exists:unidad_de_medida,id',
                'stock' => 'required|integer|min:0',
                'es_caro' => 'nullable|boolean',
            ]);

            Insumo::create([
                'nombre' => $data['nombre'],
                'precio' => $data['precio'],
                'unidad_de_medida_id' => $data['unidad_de_medida_id'],
                'stock' => $data['stock'],
                'es_caro' => $request->has('es_caro'), 
                'created_by' => auth()->id(),
            ]);
        } elseif (in_array($tipo, ['material', 'envase'])) {
            $estado = isset($data['estado']) ? true : false;

            $rules = [
                'nombre' => 'required|string|max:255',
                'precio' => 'required|numeric|min:0',
                'descripcion' => 'nullable|string',
            ];

            if ($estado) {
                $rules['cantidad'] = 'required|integer|min:1';
            }

            $request->validate($rules);

            Empaque::create([
                'nombre' => $data['nombre'],
                'tipo' => $tipo,
                'costo' => $data['precio'],
                'cantidad' => $estado ? $data['cantidad'] : 0,
                'estado' => $estado,
                'created_by' => auth()->id(),
            ]);
        }

        return redirect()->route('insumo_empaque.index')->with('success', 'Registro creado exitosamente.');
    }

}
