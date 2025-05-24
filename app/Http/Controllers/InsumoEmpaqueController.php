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
        $estado = isset($data['estado']) ? true : false;

        $rules = [
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'unidad_de_medida_id' => 'required|exists:unidad_de_medida,id',
            'es_caro' => 'nullable|boolean',
        ];

        // Si se marcó "¿tiene stock?", validar que el stock sea obligatorio
        if ($estado) {
            $rules['stock'] = 'required|integer|min:0';
        } else {
            $data['stock'] = 0; // o null, según cómo lo manejes en tu BD
        }

        $request->validate($rules);

        Insumo::create([
            'nombre' => $data['nombre'],
            'precio' => $data['precio'],
            'unidad_de_medida_id' => $data['unidad_de_medida_id'],
            'stock' => $data['stock'] ?? 0,
            'estado' => $estado,
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
    public function show($id)
    {
        $item = Empaque::find($id);
        $tipo = null;

        if ($item) {
            $tipo = $item->tipo;
        } else {
            $item = Insumo::find($id);
            if ($item) {
                $tipo = 'insumo';
            } else {
                return redirect()->route('insumo_empaque.index')->with('error', 'Elemento no encontrado.');
            }
        }

        return view('cotizador.administracion.show', compact('item', 'tipo'));
    }

    public function edit($id)
    {
        $tipos = ['insumo' => 'Insumo', 'material' => 'Material', 'envase' => 'Envase'];
        $unidades = UnidadMedida::pluck('nombre_unidad_de_medida', 'id');

        // Buscar primero en Empaques
        $empaque = Empaque::find($id);
        if ($empaque) {
            return view('cotizador.administracion.edit', [
                'item' => $empaque,
                'tipo' => $empaque->tipo, // esto puede ser 'material' o 'envase'
                'tipos' => $tipos,
                'unidades' => null,
            ]);
        }

        // Luego buscar en Insumos
        $insumo = Insumo::find($id);
        if ($insumo) {
            return view('cotizador.administracion.edit', [
                'item' => $insumo,
                'tipo' => 'insumo',
                'tipos' => $tipos,
                'unidades' => $unidades,
            ]);
        }
        return redirect()->route('insumo_empaque.index')->with('error', 'Elemento no encontrado.');
    }


    public function update(Request $request, $id)
    {
        $tipo = $request->input('tipo');

        if ($tipo === 'insumo') {
            $insumo = Insumo::findOrFail($id);

           $estado = $request->has('estado'); // ← derivamos del checkbox

        $rules = [
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'unidad_de_medida_id' => 'required|exists:unidad_de_medida,id',
            'es_caro' => 'nullable|boolean',
        ];

        if ($estado) {
            $rules['stock'] = 'required|integer|min:0';
        }

        $request->validate($rules);

        $stock = $estado ? $request->input('stock') : 0;

        $insumo->update([
            'nombre' => $request->input('nombre'),
            'precio' => $request->input('precio'),
            'unidad_de_medida_id' => $request->input('unidad_de_medida_id'),
            'stock' => $stock,
            'estado' => $estado,
            'es_caro' => $request->boolean('es_caro'),
            'updated_by' => auth()->id(),
        ]);
    } elseif (in_array($tipo, ['material', 'envase'])) {
            $empaque = Empaque::findOrFail($id);
            $estado = $request->has('estado');

            $rules = [
                'nombre' => 'required|string|max:255',
                'precio' => 'required|numeric|min:0',
                'descripcion' => 'nullable|string',
            ];

            if ($estado) {
                $rules['cantidad'] = 'required|integer|min:1';
            }

            $request->validate($rules);

            $empaque->update([
                'nombre' => $request->input('nombre'),
                'tipo' => $tipo,
                'costo' => $request->input('precio'),
                'cantidad' => $estado ? $request->input('cantidad') : 0,
                'estado' => $estado,
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
