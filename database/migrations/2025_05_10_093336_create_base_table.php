<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('base', function (Blueprint $table) {
            $table->id();
            $table->foreignId('articulo_id')->constrained('articulos')->onDelete('cascade');
            $table->enum('tipo', ['prebase', 'final'])->default('final'); // Nueva columna discriminadora
            $table->decimal('precio', 8, 4); // Precio total de la base sumando insumos + costo humano
            $table->foreignId('volumen_id')->constrained('volumenes')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('base');
    }
};
