<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cotizadores', function (Blueprint $table) {
            $table->id();
            $table->string('numero_cotizacion');
            $table->string('documento_cliente');
            $table->string('nombre_ruc');
            $table->string('nombre_doctor');
            $table->string('telefono');
            $table->string('direccion');
            $table->foreignId('producto_final_id')->constrained('producto_final')->onDelete('cascade'); // Relación con producto_final
            $table->decimal('cantidad', 8, 4);
            $table->decimal('subtotal', 10, 4);
            $table->decimal('total', 10, 4);
            $table->enum('estado', ['pendiente', 'confirmada', 'cancelada'])->default('pendiente');
            $table->enum('tipo_delivery', ['Delivery Punto de Encuentro', 'Delivery Provincia'])->nullable();
            $table->decimal('costo_delivery', 8, 4);
            $table->text('observacion')->nullable();
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('cotizadores');
    }
};
