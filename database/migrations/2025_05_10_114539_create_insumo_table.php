<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insumos', function (Blueprint $table) {
           $table->id();
            $table->string('nombre');
            $table->decimal('precio', 8, 2);
            $table->foreignId('unidad_de_medida_id')->constrained('unidad_de_medida')->onDelete('cascade');
            $table->unsignedInteger('stock')->default(1); // 0 = sin stock, 1 a más = con stock
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('es_caro')->default(false);
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insumos');
    }
};
