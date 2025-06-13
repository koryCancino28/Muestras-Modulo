<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empaques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('articulo_id')->constrained('articulos')->onDelete('cascade');
            $table->enum('tipo', ['material', 'envase']); // Columna discriminadora
            $table->decimal('precio', 10, 4);
            $table->index('tipo'); // índice para búsquedas SELECT * FROM empaques WHERE tipo = 'material';
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empaques');
    }
};
