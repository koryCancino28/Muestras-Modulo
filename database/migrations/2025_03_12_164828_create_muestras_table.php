<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('muestras', function (Blueprint $table) {
             $table->id();
            $table->string('nombre_muestra', 255); // Nombre del producto
            $table->text('observacion')->nullable(); // Observaciones del producto
            $table->integer('cantidad_de_muestra')->nullable(); // Cantidad de muestra
            $table->decimal('precio', 10, 2)->nullable(); // Precio del producto
            $table->string('estado', 50)->nullable(); // Estado del producto
            $table->enum('tipo_muestra', ['Frasco Original', 'Frasco Muestra'])->nullable(); // Tipo de muestra (valores corregidos)
            $table->foreignId('unidad_de_medida_id')->constrained('unidad_de_medida')->onDelete('cascade'); // Relación con unidad de medida
            $table->foreignId('clasificacion_id')->nullable()->constrained('clasificaciones')->onDelete('set null'); // Relación con clasificaciones
            $table->timestamp('fecha_hora_entrega')->nullable(); // Fecha y hora de entrega
            $table->boolean('aprobado_jefe_comercial')->default(false); // Aprobación jefe comercial
            $table->boolean('aprobado_coordinadora')->default(false); // Aprobación Ángela
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('muestras');
    }
};