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
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->string('serie');
            $table->string('numero');
            $table->decimal('precio_total', 10, 4);
            $table->foreignId('proveedor_id')->constrained('proveedores')->onDelete('cascade'); 
            $table->date('fecha_emision');
            $table->enum('condicion_pago', ['Contado', 'Crédito']);
            $table->foreignId('moneda_id')->constrained('tipo_moneda')->onDelete('cascade'); 
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null'); 
            $table->decimal('igv', 5, 2); 
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
