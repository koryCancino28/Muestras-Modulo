<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        public function up(): void
        {
            Schema::create('producto_final_base', function (Blueprint $table) {
                $table->id();
                $table->foreignId('producto_final_id')->constrained('producto_final')->onDelete('cascade');
                $table->foreignId('base_id')->constrained('base')->onDelete('cascade');
                $table->decimal('cantidad', 8, 4); // Cuánta base se usó
                $table->timestamps();
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('producto_final_base');
    }
};
