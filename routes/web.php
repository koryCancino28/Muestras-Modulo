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
Route::get('/muestras', [MuestrasController::class, 'index'])->name('muestras.visitadoraMedica.index');
// Mostrar el formulario para agregar una nueva muestra
Route::get('/muestras/create', [MuestrasController::class, 'create'])->name('muestras.create');
// Ruta para almacenar una nueva muestra (POST)
Route::post('/muestras/store', [MuestrasController::class, 'store'])->name('muestras.store');
Route::get('/muestras/show/{id}', [MuestrasController::class, 'show'])->name('muestras.show');
Route::delete('/muestras/delete/{id}', [MuestrasController::class, 'destroy'])->name('muestras.destroy');
Route::get('/muestras/edit/{id}', [MuestrasController::class, 'edit'])->name('muestras.edit');
Route::put('/muestras/update/{id}', [MuestrasController::class, 'update'])->name('muestras.update');


//laboratorio======================
Route::get('/muestras/laboratorio', [laboratorioController::class, 'estado'])->name('muestras.estado');
Route::put('/muestras/{id}/actualizar-estado', [laboratorioController::class, 'actualizarEstado'])
    ->name('muestras.actualizarEstado');
Route::get('/muestras/laboratorio/{id}', [laboratorioController::class, 'showLab'])->name('muestras.showLab');
Route::put('/muestras/{id}/actualizar-fecha', [laboratorioController::class, 'actualizarFechaEntrega'])->name('muestras.actualizarFechaEntrega');
Route::get('/get-unidades/{clasificacionId}', [MuestrasController::class, 'getUnidadesPorClasificacion']);

// Ruta para actualizar el precio de una muestra
// Ruta para la gestión de precios en la vista de jefe de proyectos
Route::get('/jefe-operaciones', [jefe_proyectosController::class, 'precio'])->name('muestras.precio');
Route::put('/muestras/{id}/actualizar-precio', [jefe_proyectosController::class, 'actualizarPrecio'])->name('muestras.actualizarPrecio');


//coordinadora 
//Aprobaciones
Route::get('/muestras/Coordinadora', [coordinadoraController::class, 'aprobacionCoordinadora'])->name('muestras.aprobacion.coordinadora');
Route::put('/muestras/{id}/actualizar-aprobacion', [coordinadoraController::class, 'actualizarAprobacion'])->name('muestras.actualizarAprobacion');


//Jcomercial
Route::get('/muestras/jefe-comercial', [JcomercialController::class, 'confirmar'])->name('muestras.confirmar');


//GERENCIACONTROLLER
//Reporte gerencia - Clasificaciones
Route::get('/reporte', [gerenciaController::class, 'mostrarReporte'])->name('muestras.reporte');
//Reporte Gerencia frasco original
Route::get('/reporte/frasco-original', [gerenciaController::class, 'mostrarReporteFrascoOriginal'])->name('muestras.reporte.frasco-original');
//Reporte Gerencia Frasco Muestra
Route::get('/reporte/frasco-muestra', [gerenciaController::class, 'mostrarReporteFrascoMuestra'])->name('muestras.reporte.frasco-muestra');
//exportar pdf en Reportes
Route::get('muestras/PDF-frascoMuestra', [gerenciaController::class, 'exportarPDF'])->name('muestras.exportarPDF');
Route::get('reporte/PDF-frascoOriginal', [gerenciaController::class, 'exportarPDFFrascoOriginal'])->name('muestras.frasco.original.pdf');

