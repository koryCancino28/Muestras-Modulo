<?php

namespace App\Http\Controllers;

use App\Models\TipoCambio;
use App\Models\TipoMoneda;
use Illuminate\Http\Request;

class TipoCambioController extends Controller
{
        public function index(Request $request)
    {
        $query = TipoCambio::with('tipoMoneda')->orderBy('fecha', 'desc')->orderBy('id', 'desc');

        if ($request->filled('filtro_monedas')) {
            $filtroMonedas = $request->get('filtro_monedas');
            $query->whereHas('tipoMoneda', function ($q) use ($filtroMonedas) {
                $q->whereIn('codigo_iso', $filtroMonedas);
            });
        }

        $tiposCambio = $query->get();
        $monedas = TipoMoneda::all();

        return view('tipo_cambio.index', compact('tiposCambio', 'monedas'));
    }

        public function resumenTipoCambio()
    {
        $monedas = TipoMoneda::with(['ultimoCambio' => function ($q) {
            $q->latest('fecha');
        }])->get();

        return view('tipo_cambio.resumen', compact('monedas'));
    }

    public function create()
    {
        $monedas = TipoMoneda::all();
        return view('tipo_cambio.create', compact('monedas'));
    }

        public function store(Request $request)
    {
        $request->validate([
            'tipo_moneda_id' => 'required|exists:tipo_moneda,id',
            'valor_cambio' => 'required|numeric|min:0',
            // Puedes dejar 'fecha' opcional si siempre quieres fecha actual
        ]);

        $data = $request->all();
        $data['fecha'] = date('Y-m-d');
        TipoCambio::create($data);

        return redirect()->route('tipo_cambio.resumen')->with('success', 'Tipo de cambio creado exitosamente.');
    }
}
