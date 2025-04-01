<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MuestrasController;
use App\Http\Controllers\coordinadoraController;
use App\Http\Controllers\JcomercialController;
use App\Http\Controllers\jefe_proyectosController;
use App\Http\Controllers\laboratorioController;
use App\Http\Controllers\gerenciaController;

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
Route::get('/get-unidades/{clasificacionId}', [MuestrasController::class, 'getUnidadesPorClasificacion']);

// Ruta para actualizar el precio de una muestra
// Ruta para la gestión de precios en la vista de jefe de proyectos
Route::get('/jefe-operaciones', [jefe_proyectosController::class, 'precio'])->name('muestras.precio');
Route::put('/muestras/{id}/actualizar-precio', [jefe_proyectosController::class, 'actualizarPrecio'])->name('muestras.actualizarPrecio');


//coordinadora 
//Aprobaciones
Route::get('/Coordinadora', [coordinadoraController::class, 'aprobacionCoordinadora'])->name('muestras.aprobacion.coordinadora');
Route::put('/muestras/{id}/actualizar-aprobacion', [coordinadoraController::class, 'actualizarAprobacion'])->name('muestras.actualizarAprobacion');
Route::put('/muestras/{id}/actualizar-fecha', [coordinadoraController::class, 'actualizarFechaEntrega'])->name('muestras.actualizarFechaEntrega');

//Jcomercial
Route::get('/jefe-comercial', [JcomercialController::class, 'confirmar'])->name('muestras.confirmar');


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

