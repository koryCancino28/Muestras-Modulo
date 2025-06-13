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
            $table->foreignId('articulo_id')->constrained('articulos')->onDelete('cascade');
            $table->decimal('costo_total_produccion', 10, 4); // Costo total de producción (sumatoria de insumos más basses)
            $table->decimal('costo_total_real', 10, 4); // Costo total con IGV (costo total de producción + igv)
            $table->decimal('costo_total_publicado', 10, 4); // Costo total real con el margen de ganancia (por defecto es margen publico 70.2%)
            $table->foreignId('volumen_id')->nullable()->constrained('volumenes')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producto_final');
    }
};
