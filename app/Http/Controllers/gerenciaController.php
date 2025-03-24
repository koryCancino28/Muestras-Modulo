<?php

namespace App\Http\Controllers;

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

class gerenciaController extends Controller
{
    
    // Método para mostrar el reporte con gráfico================
    public function mostrarReporte(Request $request)
    {
        // Obtener el mes filtrado, por defecto el mes actual
        $mesSeleccionado = $request->get('mes', Carbon::now()->format('Y-m')); // Formato YYYY-MM
    
        // Obtener todas las clasificaciones
        $clasificaciones = Clasificacion::all();
    
        // Crear arrays para los datos
        $clasificacionLabels = [];
        $montosTotales = [];
        $cantidadTotal = [];
        $muestrasData = [];
    
        foreach ($clasificaciones as $clasificacion) {
            // Obtener las muestras de la clasificación, filtradas por mes
            $muestras = Muestras::where('clasificacion_id', $clasificacion->id)
                ->whereMonth('created_at', Carbon::parse($mesSeleccionado)->month)
                ->whereYear('created_at', Carbon::parse($mesSeleccionado)->year)
                ->get();
    
            // Si no hay muestras, asignar valores predeterminados
            if ($muestras->isEmpty()) {
                $montoTotal = 0;
                $cantidad = 0;
            } else {
                // Calcular el monto total de las muestras de esta clasificación
                $montoTotal = $muestras->sum(function ($muestra) {
                    // Asegurarse de que el precio y la cantidad sean números válidos
                    $precio = is_numeric($muestra->precio) ? $muestra->precio : 0;
                    $cantidadMuestra = is_numeric($muestra->cantidad_de_muestra) ? $muestra->cantidad_de_muestra : 0;
    
                    return $precio * $cantidadMuestra;
                });
    
                // Calcular la cantidad total de muestras de esta clasificación
                $cantidad = $muestras->sum(function ($muestra) {
                    return is_numeric($muestra->cantidad_de_muestra) ? $muestra->cantidad_de_muestra : 0;
                });
            }
            // Almacenar los resultados
            $clasificacionLabels[] = $clasificacion->nombre_clasificacion;
            $montosTotales[] = $montoTotal;
            $cantidadTotal[] = $cantidad;
    
            $muestrasData[] = [
                'nombre_clasificacion' => $clasificacion->nombre_clasificacion,
                'cantidad' => $cantidad,
                'monto_total' => $montoTotal,
            ];
        }
        return view('muestras.gerencia.reporte', compact('clasificacionLabels', 'montosTotales', 'cantidadTotal', 'muestrasData', 'mesSeleccionado'));
    }
    

   // Método para mostrar el reporte Frasco Original
   public function mostrarReporteFrascoOriginal(Request $request)
   {
       // Obtener el mes filtrado, por defecto el mes actual
       $mesSeleccionado = $request->get('mes', Carbon::now()->format('Y-m')); 

       // Obtener las muestras de tipo 'Frasco Original'
       $muestras = Muestras::where('tipo_muestra', 'Frasco Original')
           ->whereMonth('created_at', Carbon::parse($mesSeleccionado)->month)
           ->whereYear('created_at', Carbon::parse($mesSeleccionado)->year)
           ->get();

       // Crear los datos para la tabla
       $muestrasData = [];
       $totalCantidad = 0;
       $totalPrecio = 0;

       foreach ($muestras as $muestra) {
           $montoTotal = $muestra->precio * $muestra->cantidad_de_muestra;
           $muestrasData[] = [
               'nombre_muestra' => $muestra->nombre_muestra,
               'cantidad' => $muestra->cantidad_de_muestra,
               'precio_unidad' => $muestra->precio,
               'precio_total' => $montoTotal,
           ];
           $totalCantidad += $muestra->cantidad_de_muestra;
           $totalPrecio += $montoTotal;
       }
        return view('muestras.gerencia.frasco_original', compact('muestrasData', 'mesSeleccionado', 'totalCantidad', 'totalPrecio'));
    }

    //Método para el reporte frasco Muestra
        public function mostrarReporteFrascoMuestra(Request $request)
    {
        $mesSeleccionado = $request->get('mes', Carbon::now()->format('Y-m'));

        // Obtener todas las muestras de tipo 'Frasco Muestra'
        $muestras = Muestras::where('tipo_muestra', 'Frasco Muestra')
                        ->whereMonth('created_at', Carbon::parse($mesSeleccionado)->month)
                        ->whereYear('created_at', Carbon::parse($mesSeleccionado)->year)
                        ->get();
        $muestrasData = [];
        $totalCantidad = 0;
        $totalPrecio = 0;

        foreach ($muestras as $muestra) {
        // cantidad y precio son numéricos
        $cantidad = is_numeric($muestra->cantidad_de_muestra) ? $muestra->cantidad_de_muestra : 0;
        $precioUnidad = is_numeric($muestra->precio) ? $muestra->precio : 0;
        $precioTotal = $cantidad * $precioUnidad;

        // Almacenar los resultados
        $muestrasData[] = [
            'nombre_muestra' => $muestra->nombre_muestra,
            'cantidad' => $cantidad,
            'precio_unidad' => $precioUnidad,
            'precio_total' => $precioTotal,
        ];
        $totalCantidad += $cantidad;
        $totalPrecio += $precioTotal;
        }
        return view('muestras.gerencia.frasco_muestra', compact('muestrasData', 'mesSeleccionado', 'totalCantidad', 'totalPrecio'));
    }

    //===================Imprimir reporte-frascoMuestra
    public function exportarPDF(Request $request)
    {
        $mesSeleccionado = $request->get('mes', Carbon::now()->format('Y-m')); 
        $muestras = Muestras::where('tipo_muestra', 'Frasco Muestra')
                            ->whereMonth('created_at', Carbon::parse($mesSeleccionado)->month)
                            ->whereYear('created_at', Carbon::parse($mesSeleccionado)->year)
                            ->get();  
        // Crear arrays para los datos
        $muestrasData = [];
        $totalCantidad = 0;
        $totalPrecio = 0;    
        foreach ($muestras as $muestra) {
            $cantidad = is_numeric($muestra->cantidad_de_muestra) ? $muestra->cantidad_de_muestra : 0;
            $precioUnidad = is_numeric($muestra->precio) ? $muestra->precio : 0;
            $precioTotal = $cantidad * $precioUnidad;
    
            $muestrasData[] = [
                'nombre_muestra' => $muestra->nombre_muestra,
                'cantidad' => $cantidad,
                'precio_unidad' => $precioUnidad,
                'precio_total' => $precioTotal,
            ];
    
            $totalCantidad += $cantidad;
            $totalPrecio += $precioTotal;
        }
        // Cargar la vista para el PDF
        $pdf = PDF::loadView('muestras.gerencia.frasco_muestra', compact('muestrasData', 'mesSeleccionado', 'totalCantidad', 'totalPrecio'));
    
        // Descargar el archivo PDF
        return $pdf->download('reporte_frasco_muestra.pdf');
    }
        public function exportarPDFFrascoOriginal(Request $request)
    {
        $mesSeleccionado = $request->get('mes', Carbon::now()->format('Y-m'));
        $muestras = Muestras::where('tipo_muestra', 'Frasco Original')
                            ->whereMonth('created_at', Carbon::parse($mesSeleccionado)->month)
                            ->whereYear('created_at', Carbon::parse($mesSeleccionado)->year)
                            ->get();
        $muestrasData = [];
        $totalCantidad = 0;
        $totalPrecio = 0;

        foreach ($muestras as $muestra) {
            $cantidad = is_numeric($muestra->cantidad_de_muestra) ? $muestra->cantidad_de_muestra : 0;
            $precioUnidad = is_numeric($muestra->precio) ? $muestra->precio : 0;
            $precioTotal = $cantidad * $precioUnidad;

            // Almacenar los resultados
            $muestrasData[] = [
                'nombre_muestra' => $muestra->nombre_muestra,
                'cantidad' => $cantidad,
                'precio_unidad' => $precioUnidad,
                'precio_total' => $precioTotal,
            ];

            $totalCantidad += $cantidad;
            $totalPrecio += $precioTotal;
        }
        $pdf = PDF::loadView('muestras.gerencia.frasco_original', compact('muestrasData', 'mesSeleccionado', 'totalCantidad', 'totalPrecio'));
        return $pdf->download('reporte_frasco_original.pdf');
    }
}
