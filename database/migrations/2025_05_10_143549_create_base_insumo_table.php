<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('base_insumo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('base_id')->constrained('base')->onDelete('cascade');
            $table->foreignId('insumo_id')->constrained('insumos')->onDelete('cascade');
            $table->decimal('cantidad', 8, 4); // Cantidad del insumo usada en esta base
            $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('base_insumo');
    }
};
