<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use App\Models\Articulo;
use App\Models\Empaque;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;

class InsumoEmpaqueController extends Controller
{
         public function index()
    {
        $estado = request()->estado;

        if ($estado == 'inactivo') {
            $insumos = Insumo::with(['articulo', 'ultimoLote'])->whereHas('articulo', function ($query) {
                $query->where('estado', 'inactivo');
            })->orderBy('id', 'desc')->get();

            $empaques = Empaque::with(['articulo', 'ultimoLote'])->whereHas('articulo', function ($query) {
                $query->where('estado', 'inactivo');
            })->orderBy('id','desc')->get();
        } else {
            // Si no se filtra por inactivo, mostramos solo los activos por defecto
            $insumos = Insumo::with(['articulo', 'ultimoLote'])->whereHas('articulo', function ($query) {
                $query->where('estado', 'activo');
            })->orderBy('id', 'desc')->get();

            $empaques = Empaque::with(['articulo', 'ultimoLote'])->whereHas('articulo', function ($query) {
                $query->where('estado', 'activo');
            })->orderBy('id', 'desc')->get();
        }

        // Pasamos los datos a la vista
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
                'nombre' => 'required|string|max:255|unique:articulos,nombre,',
                'precio' => 'required|numeric|min:0',
                'unidad_de_medida_id' => 'required|exists:unidad_de_medida,id',
                'es_caro' => 'nullable|boolean',
            ];
            $mensaje = ['nombre.unique' => 'El nombre del insumo ya está en uso. Por favor, elige otro nombre.'];
            $request->validate($rules, $mensaje);
            // Crear el artículo para el insumo
            $articulo = Articulo::create([
                'nombre' => $data['nombre'],
                'descripcion' => $data['descripcion'] ?? null, 
                'tipo' => 'insumo', 
                'stock' => 1, 
            ]);

            // Crear el insumo y asociarlo al artículo
            Insumo::create([
                'nombre' => $data['nombre'],
                'precio' => $data['precio'],
                'unidad_de_medida_id' => $data['unidad_de_medida_id'],
                'es_caro' => $request->has('es_caro'),
                'articulo_id' => $articulo->id, // Asociamos el insumo con el artículo
            ]);

        } elseif (in_array($tipo, ['material', 'envase'])) {
            $rules = [
                'nombre' => 'required|string|max:255|unique:articulos,nombre,',
                'precio' => 'required|numeric|min:0',
            ];
            $mensaje = ['nombre.unique' => 'El nombre del material/envase ya está en uso. Por favor, elige otro nombre.'];

            $request->validate($rules, $mensaje);

            // Crear el artículo para 'material' o 'envase'
            $articulo = Articulo::create([
                'nombre' => $data['nombre'],
                'descripcion' => $data['descripcion'] ?? null, 
                'tipo' => $tipo, // Aquí usamos el tipo 'material' o 'envase' directamente
                'stock' => 1, 
            ]);

            Empaque::create([
                'nombre' => $data['nombre'],
                'tipo' => $tipo, // Puede ser 'material' o 'envase'
                'precio' => $data['precio'],
                'articulo_id' => $articulo->id, 
            ]);
        }

        return redirect()->route('insumo_empaque.index')->with('success', 'Registro creado exitosamente.');
    }

        public function show($id)
    {
        $tipo = request()->query('tipo'); 

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
                    'articulo' => $insumo->articulo, // Pasamos el artículo asociado
                ]);
            }
        } elseif (in_array($tipo, ['material', 'envase'])) {
            $empaque = Empaque::find($id);
            if ($empaque) {
                return view('cotizador.administracion.edit', [
                    'item' => $empaque,
                    'tipo' => $empaque->tipo,
                    'tipos' => $tipos,
                    'articulo' => $empaque->articulo, // Pasamos el artículo asociado
                ]);
            }
        }

        return redirect()->route('insumo_empaque.index')->with('error', 'Elemento no encontrado.');
    }

        public function update(Request $request, $id)
    {
        $tipo = $request->input('tipo');

        // Validación y actualización para insumo
        if ($tipo === 'insumo') {
            $insumo = Insumo::findOrFail($id);

            $rules = [
                'nombre' => 'required|string|max:255|unique:articulos,nombre,' . $insumo->articulo->id, // Aseguramos que el nombre sea único excepto para el mismo artículo
                'precio' => 'required|numeric|min:0',
                'unidad_de_medida_id' => 'required|exists:unidad_de_medida,id',
                'es_caro' => 'nullable|boolean',
            ];
            $mensaje = ['nombre.unique' => 'El nombre del insumo ya está en uso. Por favor, elige otro nombre.'];

            $request->validate($rules, $mensaje);

            // Actualizar el artículo asociado al insumo
            $articulo = $insumo->articulo;
            if ($articulo) {
                $articulo->update([
                    'nombre' => $request->input('nombre'), 
                    'estado' => $validated['estado'] ?? 'activo',
                ]);
            }

            // Actualizar el insumo
            $insumo->update([
                'nombre' => $request->input('nombre'),
                'precio' => $request->input('precio'),
                'unidad_de_medida_id' => $request->input('unidad_de_medida_id'),
                'es_caro' => $request->boolean('es_caro'),
            ]);

        } elseif (in_array($tipo, ['material', 'envase'])) {
            $empaque = Empaque::findOrFail($id);

            $rules = [
                'nombre' => 'required|string|max:255|unique:articulos,nombre,' . $empaque->articulo->id, // Aseguramos que el nombre sea único excepto para el mismo artículo
                'precio' => 'required|numeric|min:0',
            ];
            $mensaje = ['nombre.unique' => 'El nombre del material/envase ya está en uso. Por favor, elige otro nombre.'];

            $request->validate($rules, $mensaje);
            // Actualizar el artículo asociado al empaque (ahora material o envase)
            $articulo = $empaque->articulo;
            if ($articulo) {
                $articulo->update([
                    'nombre' => $request->input('nombre'), 
                    'estado' => $validated['estado'] ?? 'activo',
                    'tipo' => $tipo, 
                ]);
            }

            $empaque->update([
                'nombre' => $request->input('nombre'),
                'tipo' => $tipo, // Actualizamos el tipo a material o envase
                'precio' => $request->input('precio'),
            ]);
        }

        return redirect()->route('insumo_empaque.index')->with('success', 'Registro actualizado correctamente.');
    }

       public function destroy(Request $request, $id)
{
    $tipo = $request->query('tipo'); // Recoge el tipo desde la URL

    if ($tipo === 'insumo') {
        $item = Insumo::with('articulo')->find($id);
    } elseif (in_array($tipo, ['material', 'envase'])) {
        $item = Empaque::with('articulo')->find($id);
    } else {
        return redirect()->route('insumo_empaque.index')
            ->withErrors('Tipo no válido o no especificado.');
    }

    if ($item && $item->articulo) {
        $articulo = $item->articulo;
        $nombre = $articulo->nombre;

        if ($articulo->estado === 'inactivo') {
            return redirect()->route('insumo_empaque.index')
                ->with('error', ucfirst($tipo) . " '{$nombre}' ya está inactivo. Para activarlo, por favor use la sección de editar.");
        }

        $articulo->update(['estado' => 'inactivo']);

        return redirect()->route('insumo_empaque.index')
            ->with('error', ucfirst($tipo) . " '{$nombre}' inactivado correctamente.");
    }

    return redirect()->route('insumo_empaque.index')
        ->withErrors('Elemento no encontrado.');
}

}
