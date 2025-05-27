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
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('razon_social', 255)->nullable(false);
            $table->string('ruc', 11)->unique()->nullable(false);
            $table->string('direccion', 255);
            $table->string('correo', 100)->nullable();
            $table->string('correo_cpe', 100)->nullable()->comment('Correo para Comprobantes Electrónicos');
            $table->string('telefono_1', 20);
            $table->string('telefono_2', 20)->nullable();
            $table->string('persona_contacto', 100)->nullable();
            $table->text('observacion')->nullable();
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index('razon_social');
            $table->index('ruc');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};