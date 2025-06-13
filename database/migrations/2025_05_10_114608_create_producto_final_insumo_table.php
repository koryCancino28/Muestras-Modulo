<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('producto_final_insumo', function (Blueprint $table) {
             $table->id();
            $table->foreignId('producto_final_id')->constrained('producto_final')->onDelete('cascade'); // Relación con producto final
            $table->foreignId('insumo_id')->constrained('insumos')->onDelete('cascade'); // Relación con insumo
            $table->decimal('cantidad', 8, 4); // Cantidad del insumo en el producto final
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producto_final_insumo');
    }
};
