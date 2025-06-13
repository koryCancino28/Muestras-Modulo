<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insumos', function (Blueprint $table) {
           $table->id();
            $table->foreignId('articulo_id')->constrained('articulos')->onDelete('cascade');
            $table->decimal('precio', 8, 4);
            $table->foreignId('unidad_de_medida_id')->constrained('unidad_de_medida')->onDelete('cascade');
            $table->boolean('es_caro')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insumos');
    }
};