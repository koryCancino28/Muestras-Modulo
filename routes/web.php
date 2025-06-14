<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MuestrasController;
use App\Http\Controllers\coordinadoraController;
use App\Http\Controllers\JcomercialController;
use App\Http\Controllers\jefe_proyectosController;
use App\Http\Controllers\laboratorioController;
use App\Http\Controllers\gerenciaController;
//COTIZADOR GENERAL
use App\Http\Controllers\ProductoFinalController;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\InsumoEmpaqueController;
use App\Http\Controllers\VolumenController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\TipoCambioController;
use App\Http\Controllers\MerchandiseController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\UtilController;

Route::get('/', function () {
    return view('welcome');
});
// Ruta principal que muestra todas las muestras

Route::resource('muestras', MuestrasController::class);

//laboratorio======================
Route::get('/laboratorio', [laboratorioController::class, 'estado'])->name('muestras.estado');
Route::put('/laboratorio/{id}/actualizar-estado', [laboratorioController::class, 'actualizarEstado'])
    ->name('muestras.actualizarEstado');
Route::get('/laboratorio/{id}', [laboratorioController::class, 'showLab'])->name('muestras.showLab');
Route::put('/laboratorio/{id}/actualizar-fecha', [laboratorioController::class, 'actualizarFechaEntrega'])->name('muestras.actualizarFechaEntrega');
Route::get('/get-unidades/{clasificacionId}', [MuestrasController::class, 'getUnidadesPorClasificacion']);
Route::put('/muestras/{id}/comentario', [laboratorioController::class, 'actualizarComentario'])->name('muestras.actualizarComentario');

// Ruta para actualizar el precio de una muestra
// Ruta para la gestión de precios en la vista de jefe de proyectos
Route::get('/jefe-operaciones', [jefe_proyectosController::class, 'precio'])->name('muestras.precio');
Route::get('/jefe-operaciones/{id}', [jefe_proyectosController::class, 'showJO'])->name('muestras.showJO');
Route::put('/muestras/{id}/actualizar-precio', [jefe_proyectosController::class, 'actualizarPrecio'])->name('muestras.actualizarPrecio');


//coordinadora 
//Aprobaciones
Route::get('/Coordinadora', [coordinadoraController::class, 'aprobacionCoordinadora'])->name('muestras.aprobacion.coordinadora');
Route::put('/muestras/{id}/actualizar-fecha', [coordinadoraController::class, 'actualizarFechaEntrega'])->name('muestras.actualizarFechaEntrega');
//crud
Route::get('/Coordinadora/{id}', [coordinadoraController::class, 'showCo'])->name('muestras.showCo');
Route::get('/coordinadora/agregar', [coordinadoraController::class, 'createCO'])->name('muestras.createCO');
Route::post('/Coordinadora/agregar', [coordinadoraController::class, 'storeCO'])->name('muestras.storeCO');
Route::get('/Coordinadora/{id}/edit', [coordinadoraController::class, 'editCO'])->name('muestras.editCO');
Route::put('/Coordinadora/{id}/actualizar', [coordinadoraController::class, 'updateCO'])->name('muestras.updateCO');
Route::delete('/Coordinadora/elimi/{id}', [coordinadoraController::class, 'destroyCO'])->name('muestras.destroyCO');

Route::put('/muestras/{id}/actualizar-aprobacion', [coordinadoraController::class, 'actualizarAprobacion'])->name('muestras.actualizarAprobacion')->middleware(['checkRole:jefe-comercial,coordinador-lineas,admin']);
 
//Jcomercial
Route::get('/jefe-comercial', [JcomercialController::class, 'confirmar'])->name('muestras.confirmar')->middleware(['checkRole:jefe-comercial,admin']);
Route::get('/jefe-comercial/{id}', [JcomercialController::class, 'showJC'])->name('muestras.showJC');

//GERENCIACONTROLLER
//Reporte gerencia - Clasificaciones
Route::get('/reporte', [gerenciaController::class, 'mostrarReporte'])->name('muestras.reporte');
//Reporte Gerencia frasco original
Route::get('/reporte/frasco-original', [gerenciaController::class, 'mostrarReporteFrascoOriginal'])->name('muestras.reporte.frasco-original');
//Reporte Gerencia Frasco Muestra
Route::get('/reporte/frasco-muestra', [gerenciaController::class, 'mostrarReporteFrascoMuestra'])->name('muestras.reporte.frasco-muestra');
//exportar pdf en Reportes
Route::get('reporte/PDF-frascoMuestra', [gerenciaController::class, 'exportarPDF'])->name('muestras.exportarPDF');
Route::get('reporte/PDF-frascoOriginal', [gerenciaController::class, 'exportarPDFFrascoOriginal'])->name('muestras.frasco.original.pdf');

//COTIZADOR GENERAL----------


// Rutas estándar del CRUD
Route::resource('producto_final', ProductoFinalController::class);

//crud volumen
Route::resource('volumen', VolumenController::class);

//Laboratorio 
Route::resource('bases', BaseController::class);

//Administración
Route::resource('insumo_empaque', InsumoEmpaqueController::class);
//Crud proveedores
Route::resource('proveedores', ProveedorController::class)->parameters([
        'proveedores' => 'proveedor']); 
//Crud tipo de cambio- EL PRINCIPAL ES RESUMEN-TIPO-CAMBIO!!!
Route::resource('tipo_cambio', TipoCambioController::class);
Route::get('/resumen-tipo-cambio', [TipoCambioController::class, 'resumenTipoCambio'])->name('tipo_cambio.resumen');

//crud para merchandise
Route::resource('merchandise', MerchandiseController::class);


Route::resource('compras', CompraController::class);
// Rutas adicionales para AJAX
/* Route::get('articulos/por-tipo', [CompraController::class, 'getArticulosByTipo'])
    ->name('articulos.por-tipo');
 */

//Ruta para utiles
Route::resource('util', UtilController::class);
