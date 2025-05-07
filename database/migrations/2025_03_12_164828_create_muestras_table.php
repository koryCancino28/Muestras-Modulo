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
            $table->string('nombre_muestra', 255); 
            $table->text('observacion')->nullable(); 
            $table->integer('cantidad_de_muestra')->nullable(); 
            $table->decimal('precio', 10, 2)->nullable(); 
            $table->string('estado', 50)->nullable(); 
            $table->text('comentarios')->nullable();
            $table->enum('tipo_muestra', ['Frasco Original', 'Frasco Muestra'])->nullable(); 
            $table->foreignId('clasificacion_id')->nullable()->constrained('clasificaciones')->onDelete('set null'); 
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null'); 
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null'); 
            $table->timestamp('fecha_hora_entrega')->nullable();
            $table->string('foto')->nullable();
            $table->boolean('aprobado_jefe_comercial')->default(false); 
            $table->boolean('aprobado_coordinadora')->default(false); 
            $table->string('name_doctor', 50)->nullable();
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