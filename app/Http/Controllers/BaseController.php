<?php

namespace App\Http\Controllers;

use App\Models\Base;
use App\Models\Articulo;
use App\Models\Clasificacion;
use App\Models\Empaque;
use App\Models\Insumo;
use App\Models\Volumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
        public function index(Request $request)
    {
        // Filtrar las bases según el estado del artículo relacionado
        $query = Base::with(['clasificacion', 'unidadDeMedida', 'volumen', 'articulo']);

        if ($request->has('estado') && $request->estado === 'inactivo') {
            $query->whereHas('articulo', function ($q) {
                $q->where('estado', 'inactivo');
            });
        } else {
            $query->whereHas('articulo', function ($q) {
                $q->where('estado', 'activo');
            });
        }

        $bases = $query->get();

        return view('cotizador.laboratorio.base.index', compact('bases'));
    }

        public function create()
    {
        $clasificaciones = Clasificacion::all();

        // Filtrar insumos con artículo activo
        $insumos = Insumo::whereHas('articulo', function ($q) {
            $q->where('estado', 'activo');
        })->get();

        // Filtrar empaques con artículo activo
        $empaques = Empaque::whereHas('articulo', function ($q) {
            $q->where('estado', 'activo');
        })->get();

        // Filtrar prebases (tipo = 'prebase') con artículo activo
        $prebases = Base::where('tipo', 'prebase')
            ->whereHas('articulo', function ($q) {
                $q->where('estado', 'activo');
            })->get();

        $volumenesAgrupados = Volumen::all()->groupBy('clasificacion_id');

        return view('cotizador.laboratorio.base.create', compact(
            'clasificaciones',
            'insumos',
            'empaques',
            'prebases',
            'volumenesAgrupados'
        ));
    }

        public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:articulos,nombre', // Validar el nombre del artículo
            'volumen_id' => 'required|exists:volumenes,id',
            'tipo' => 'required|in:prebase,final',
        ], [
            'nombre.unique' => 'El nombre de esta Base ya está en uso. Por favor, elige otro nombre.', 
        ]);

        $tipo = $request->input('tipo');
        $precioTotal = 0;

        // Validación condicional según el tipo
        if ($tipo === 'final') { 
            if (empty($request->insumos) || empty($request->prebases) || empty($request->empaques)) {
                return back()->withInput()->withErrors([
                    'llenar' => 'Debe ingresar al menos un insumo, una prebase y un empaque para una Base Final.',
                ]);
            }
        } elseif ($tipo === 'prebase') {
            if (empty($request->insumos)) {
                return back()->withInput()->withErrors([
                    'llenar' => 'Debe ingresar al menos un insumo para una Prebase.',
                ]);
            }
        }

        // Crear un nuevo artículo (registrar el nombre de la base en artículos)
        $articulo = Articulo::create([
            'nombre' => $request->nombre, 
            'descripcion' => $request->descripcion ?? null,
            'tipo' => $tipo === 'final' ? 'base' : 'prebase',  
            'stock' => 1,  
        ]);

        // unidad_de_medida_id desde la clasificación
        $clasificacion = Clasificacion::find($request->clasificacion_id);
        $unidadDeMedidaId = $clasificacion?->unidad_de_medida_id;

        // Calcular precio total de insumos
        $insumos = $request->input('insumos', []);
        foreach ($insumos as $insumo) {
            $modeloInsumo = Insumo::find($insumo['id']);
            if ($modeloInsumo) {
                $precioTotal += $modeloInsumo->precio * $insumo['cantidad'];
            }
        }

        // Si es base final, sumar también prebases y empaques
        if ($tipo === 'final') {
            $prebases = $request->input('prebases', []);
            foreach ($prebases as $prebase) {
                $modeloPrebase = Base::find($prebase['id']);
                if ($modeloPrebase) {
                    $precioTotal += $modeloPrebase->precio * $prebase['cantidad'];
                }
            }

            $empaques = $request->input('empaques', []);
            foreach ($empaques as $empaque) {
                $modeloEmpaque = Empaque::find($empaque['id']);
                if ($modeloEmpaque) {
                    $precioTotal += $modeloEmpaque->precio * $empaque['cantidad'];
                }
            }
        }

        // Crear la base y asociar el articulo_id
        $base = Base::create([
            'articulo_id' => $articulo->id,  // Asocias el artículo creado con la base
            'clasificacion_id' => $request->clasificacion_id,
            'volumen_id' => $request->volumen_id,
            'unidad_de_medida_id' => $unidadDeMedidaId,
            'tipo' => $tipo,
            'precio' => $precioTotal,
        ]);

        // Relacionar insumos
        if (!empty($insumos)) {
            foreach ($insumos as $insumo) {
                $base->insumos()->attach($insumo['id'], ['cantidad' => $insumo['cantidad']]);
            }
        }

        // Relacionar prebases y empaques si es final
        if ($tipo === 'final') {
            if (!empty($prebases)) {
                foreach ($prebases as $prebase) {
                    $base->prebases()->attach($prebase['id'], ['cantidad' => $prebase['cantidad']]);
                }
            }
            if (!empty($empaques)) {
                foreach ($empaques as $empaque) {
                    $base->empaques()->attach($empaque['id'], ['cantidad' => $empaque['cantidad']]);
                }
            }
        }

        return redirect()->route('bases.index')->with('success', 'Base creada exitosamente.');
    }

        public function edit($id)
    {
        $base = Base::with([
            'insumos',
            'prebases',
            'empaques',
            'clasificacion.unidadMedida',
            'articulo'
        ])->findOrFail($id);

        $clasificaciones = Clasificacion::all();
        $volumenes = Volumen::where('clasificacion_id', $base->clasificacion_id)->get();

        // Solo insumos activos
        $insumos = Insumo::whereHas('articulo', function ($q) {
            $q->where('estado', 'activo');
        })->get();

        // Solo prebases activas, excluyendo la base actual
        $prebases = Base::where('tipo', 'prebase')
            ->where('id', '!=', $id)
            ->whereHas('articulo', function ($q) {
                $q->where('estado', 'activo');
            })->get();

        // Solo empaques activos
        $empaques = Empaque::whereHas('articulo', function ($q) {
            $q->where('estado', 'activo');
        })->get();

        $volumenesAgrupados = Volumen::all()->groupBy('clasificacion_id');

        return view('cotizador.laboratorio.base.edit', compact(
            'base',
            'clasificaciones',
            'volumenes',
            'insumos',
            'prebases',
            'empaques',
            'volumenesAgrupados',
        ));
    }

    public function update(Request $request, $id)
    {
        $base = Base::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|unique:articulos,nombre,' . $base->articulo->id,
            'clasificacion_id' => 'required|exists:clasificaciones,id',
            'volumen_id' => 'required|exists:volumenes,id',
            'tipo' => 'required|in:final,prebase',
            'insumos' => 'required|array',
            'estado' => 'nullable|in:activo,inactivo',
            'insumos.*.cantidad' => 'required|numeric|min:0.01',
        ], [
            'nombre.unique' => 'El nombre de esta Base ya está en uso. Por favor, elige otro nombre.',
        ]);

        $tipo = $request->input('tipo');

        // Validar cambio de tipo si tiene dependencias
        if ($base->tipo == 'prebase' && $tipo == 'final') {
            $basesDependientes = Base::whereHas('prebases', function($query) use ($id) {
                $query->where('prebase_id', $id);
            })->get();

            if ($basesDependientes->isNotEmpty()) {
                $nombresBases = $basesDependientes->pluck('nombre')->implode(', ');
                return back()->withInput()->withErrors([
                    'tipo' => 'No puedes cambiar esta prebase a base final porque es utilizada en las siguientes bases: '.$nombresBases.'. Primero debes eliminar estas dependencias.'
                ]);
            }
        }

        // Validaciones por tipo
        if ($tipo === 'final') {
            if (empty($request->insumos) || empty($request->prebases) || empty($request->empaques)) {
                return back()->withInput()->withErrors([
                    'tipo' => 'Debe ingresar al menos un insumo, una prebase y un empaque para una Base Final.',
                ]);
            }
        } elseif ($tipo === 'prebase') {
            if (empty($request->insumos)) {
                return back()->withInput()->withErrors([
                    'tipo' => 'Debe ingresar al menos un insumo para una Prebase.',
                ]);
            }
        }

        DB::beginTransaction();
        try {
            // Actualizar el artículo asociado (nombre de la base)
            $articulo = $base->articulo;  // Accedemos al artículo asociado
            $articulo->update([
                'nombre' => $request->nombre,  // Actualizamos el nombre del artículo
                'tipo' => $tipo === 'final' ? 'base' : 'prebase',  // Actualizamos el tipo del artículo según el tipo de base
                'estado' => $validated['estado'] ?? 'activo',
            ]);

            $clasificacion = Clasificacion::find($request->clasificacion_id);
            $unidadDeMedidaId = $clasificacion?->unidad_de_medida_id;

            // Actualizar los campos de la base
            $base->update([
                'clasificacion_id' => $request->clasificacion_id,
                'volumen_id' => $request->volumen_id,
                'unidad_de_medida_id' => $unidadDeMedidaId,
                'tipo' => $tipo,
            ]);

            // Recalcular precio total
            $precioTotal = 0;

            // Sincronizar insumos
            $insumosSync = [];
            foreach ($request->insumos as $insumoId => $data) {
                $modeloInsumo = Insumo::find($insumoId);
                if ($modeloInsumo) {
                    $precioTotal += $modeloInsumo->precio * $data['cantidad'];
                }
                $insumosSync[$insumoId] = ['cantidad' => $data['cantidad']];
            }
            $base->insumos()->sync($insumosSync);

            if ($tipo === 'final') {
                $request->validate([
                    'prebases' => 'required|array',
                    'prebases.*.cantidad' => 'required|numeric|min:0.01',
                    'empaques' => 'required|array',
                    'empaques.*.cantidad' => 'required|numeric|min:1',
                ]);

                if (array_key_exists($id, $request->prebases)) {
                    return back()->withErrors([
                        'prebases' => 'No puedes incluir esta base como prebase de sí misma'
                    ]);
                }

                // Sincronizar prebases
                $prebasesSync = [];
                foreach ($request->prebases as $prebaseId => $data) {
                    $modeloPrebase = Base::find($prebaseId);
                    if ($modeloPrebase) {
                        $precioTotal += $modeloPrebase->precio * $data['cantidad'];
                    }
                    $prebasesSync[$prebaseId] = ['cantidad' => $data['cantidad']];
                }
                $base->prebases()->sync($prebasesSync);

                // Sincronizar empaques
                $empaquesSync = [];
                foreach ($request->empaques as $empaqueId => $data) {
                    $modeloEmpaque = Empaque::find($empaqueId);
                    if ($modeloEmpaque) {
                        $precioTotal += $modeloEmpaque->precio * $data['cantidad'];
                    }
                    $empaquesSync[$empaqueId] = ['cantidad' => $data['cantidad']];
                }
                $base->empaques()->sync($empaquesSync);
            } else {
                // Si es prebase, eliminar relaciones con prebases y empaques
                $base->prebases()->detach();
                $base->empaques()->detach();
            }

            // Actualizar el precio total
            $base->update(['precio' => $precioTotal]);

            DB::commit();

            return redirect()->route('bases.index')->with('success', 'Base actualizada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar la base: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        // Cargar la base con todas sus relaciones
        $base = Base::with([
            'insumos',
            'prebases',
            'empaques',
            'clasificacion',
            'unidadDeMedida',
            'volumen',
            'articulo'
        ])->findOrFail($id);

        return view('cotizador.laboratorio.base.show', compact('base'));
    }
        public function destroy($id)
    {
        $base = Base::with('articulo')->findOrFail($id);

        DB::beginTransaction();
        try {
            if ($base->articulo) {
                if ($base->articulo->estado === 'inactivo') {
                    DB::commit();
                    return redirect()->route('bases.index')
                        ->with('error', "La base '{$base->articulo->nombre}' ya está inactiva. Para activarla, edítala desde la opción de editar.");
                }

                $base->articulo->estado = 'inactivo';
                $base->articulo->save();

                DB::commit();
                return redirect()->route('bases.index')
                    ->with('error', "Base '{$base->articulo->nombre}' inactivada correctamente.");
            }

            DB::commit();
            return redirect()->route('bases.index')
                ->with('error', 'No se encontró el artículo relacionado para inactivar.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al inactivar la base: ' . $e->getMessage());
        }
    }

} 