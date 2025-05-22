<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('producto_final', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('clasificacion_id')->nullable()->constrained('clasificaciones')->onDelete('set null'); 
            $table->foreignId('unidad_de_medida_id')->constrained('unidad_de_medida')->onDelete('cascade');
            $table->decimal('costo_total_produccion', 10, 2); // Costo total de producción (sumatoria de insumos más basses)
            $table->decimal('costo_total_real', 10, 2); // Costo total con IGV (costo total de producción + igv)
            $table->decimal('costo_total_publicado', 10, 2); // Costo total real con el margen de ganancia (por defecto es margen publico 70.2%)
            $table->string('estado')->default('activo'); 
            $table->foreignId('volumen_id')->nullable()->constrained('volumenes')->onDelete('set null');
            $table->integer('stock'); 
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producto_final');
    }
};
