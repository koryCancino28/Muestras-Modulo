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
        Schema::create('tipo_moneda', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50); // Ej: Sol, Dólar
            $table->string('codigo_iso', 3)->unique(); // Ej: USD, PEN
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_moneda');
    }
};
