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
        //dd($unidades);
        return view('cotizador.administracion.create', compact('tipos', 'unidades'));
    }

   public function store(Request $request)
{
    $data = $request->all();
    $tipo = $data['tipo'];

    if ($tipo === 'insumo') {
        $rules = [
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'unidad_de_medida_id' => 'required|exists:unidad_de_medida,id',
            'es_caro' => 'nullable|boolean',
        ];

        $request->validate($rules);

        Insumo::create([
            'nombre' => $data['nombre'],
            'precio' => $data['precio'],
            'unidad_de_medida_id' => $data['unidad_de_medida_id'],
            'es_caro' => $request->has('es_caro'),
        ]);

    } elseif (in_array($tipo, ['material', 'envase'])) {
        $rules = [
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
        ];

        $request->validate($rules);

        Empaque::create([
            'nombre' => $data['nombre'],
            'tipo' => $tipo,
            'precio' => $data['precio'],
        ]);
    }

    return redirect()->route('insumo_empaque.index')->with('success', 'Registro creado exitosamente.');
}


        public function show($id)
    {
        $tipo = request()->query('tipo'); // ← Obtenemos el tipo desde la URL

        if ($tipo === 'insumo') {
            $item = Insumo::find($id);
            if (!$item) {
                return redirect()->route('insumo_empaque.index')->with('error', 'Insumo no encontrado.');
            }
        } elseif (in_array($tipo, ['material', 'envase'])) {
            $item = Empaque::find($id);
            if (!$item) {
                return redirect()->route('insumo_empaque.index')->with('error', 'Empaque no encontrado.');
            }
        } else {
            return redirect()->route('insumo_empaque.index')->with('error', 'Tipo inválido.');
        }

        return view('cotizador.administracion.show', compact('item', 'tipo'));
    }


        public function edit($id)
    {
        $tipos = ['insumo' => 'Insumo', 'material' => 'Material', 'envase' => 'Envase'];
        $unidades = UnidadMedida::pluck('nombre_unidad_de_medida', 'id');
        $tipo = request()->query('tipo'); // ← Leemos el tipo desde la URL

        if ($tipo === 'insumo') {
            $insumo = Insumo::find($id);
            if ($insumo) {
                return view('cotizador.administracion.edit', [
                    'item' => $insumo,
                    'tipo' => 'insumo',
                    'tipos' => $tipos,
                    'unidades' => $unidades,
                ]);
            }
        } elseif (in_array($tipo, ['material', 'envase'])) {
            $empaque = Empaque::find($id);
            if ($empaque) {
                return view('cotizador.administracion.edit', [
                    'item' => $empaque,
                    'tipo' => $empaque->tipo,
                    'tipos' => $tipos,
                    'unidades' => null,
                ]);
            }
        }

        return redirect()->route('insumo_empaque.index')->with('error', 'Elemento no encontrado.');
    }

 public function update(Request $request, $id)
{
    $tipo = $request->input('tipo');

    if ($tipo === 'insumo') {
        $insumo = Insumo::findOrFail($id);

        $rules = [
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'unidad_de_medida_id' => 'required|exists:unidad_de_medida,id',
            'es_caro' => 'nullable|boolean',
        ];

        $request->validate($rules);

        $insumo->update([
            'nombre' => $request->input('nombre'),
            'precio' => $request->input('precio'),
            'unidad_de_medida_id' => $request->input('unidad_de_medida_id'),
            'es_caro' => $request->boolean('es_caro'),
        ]);

    } elseif (in_array($tipo, ['material', 'envase'])) {
        $empaque = Empaque::findOrFail($id);

        $rules = [
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
        ];

        $request->validate($rules);

        $empaque->update([
            'nombre' => $request->input('nombre'),
            'tipo' => $tipo,
            'precio' => $request->input('precio'),
        ]);
    }

    return redirect()->route('insumo_empaque.index')->with('success', 'Registro actualizado correctamente.');
}

        public function destroy($id)
    {
        $insumo = Insumo::find($id);

        if ($insumo) {
            $enProductoFinal = \DB::table('producto_final_insumo')
                ->where('insumo_id', $id)
                ->exists();

            $enBaseInsumo = \DB::table('base_insumo')
                ->where('insumo_id', $id)
                ->exists();

            if ($enProductoFinal || $enBaseInsumo) {
                return redirect()->route('insumo_empaque.index')
                    ->withErrors('No se puede eliminar este insumo porque está asociado a productos o bases. Para eliminarlo, primero elimina esas dependencias o, si no hay stock, edita el insumo y desactiva el checkbox de stock.');
            }

            $insumo->delete();
            return redirect()->route('insumo_empaque.index')->with('error', 'Insumo eliminado correctamente.');
        }
        $empaque = Empaque::find($id);
        if ($empaque) {
            $empaque->delete();
            return redirect()->route('insumo_empaque.index')->with('error', 'Empaque eliminado correctamente.');
        }

        return redirect()->route('insumo_empaque.index')->withErrors('Elemento no encontrado.');
    }

}
