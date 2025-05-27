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
            $table->string('nombre');
            $table->enum('tipo', ['prebase', 'final'])->default('final'); // Nueva columna discriminadora
            $table->decimal('precio', 8, 2); // Precio total de la base sumando insumos + costo humano
            $table->foreignId('volumen_id')->constrained('volumenes')->onDelete('cascade');
            $table->decimal('cantidad', 8, 2); // Cantidad de base en stock!!!
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('base');
    }
};
